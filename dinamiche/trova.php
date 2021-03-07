<?php
	session_start();
    include("../classi/Cerca.php");
    include("../classi/Sql.php");
    $cerca = new Cerca();
	$presenti=false;
	//$posto = false;
	$vir=false;
	$primo_caso=false;
	$t=false;
	$mex = null;
	//$sql = new Sql("jpinna.it.mysql","jpinna_it","MDA9Kt7Z","jpinna_it");
	require("../config_sql.php");

	//1 CASO
	if(isset($_REQUEST['citta'])&&$_REQUEST['query']==""&&isset($_REQUEST['cerca'])){
		//echo "<script>alert('1CASO');</script>";
		//CASO IN CUI VIENE RICERCATO SOLAMENTE IL POSTO 
		$temp=$_REQUEST['citta'];
		if(strstr($temp,',')!=false){
			$temp=explode(',',$temp);
			$posto=$temp[0];//PRENDE SOLAMENTE IL PRIMO PEZZO DELLA STRINGA PER RICERCALO NEL DATABASE
			$vir=true;
		}else
			$posto=$temp;//ALTRIMENTI RICERCA COSI COME è
		
		//UNA VOLTA CHE ABBIAMO RICAVATO IL POSTO CONTROLLIAMO NEL DATABASE
		$t=$sql->controlla_posto($posto);//SE IL POSTO E' PRESENTE ALLORA REINDIRIZZA ALLA PAGINA DEL POSTO
		if($t!=false)
			header("Location: luogo.php?id=".$t);
		else
			header("Location: errore.html?mex=NON PRESENTE NEL DATABASE");
	}else if(isset($_REQUEST['citta'])&&isset($_REQUEST['query'])&&isset($_REQUEST['cerca'])||isset($_REQUEST['cat'])){
		//echo "<script>alert('23CASO');</script>";
		$temp=$_REQUEST['citta'];
		if(strstr($temp,',')!=false){
			$temp=explode(',',$temp);
			$posto=$temp[0];//PRENDE SOLAMENTE IL PRIMO PEZZO DELLA STRINGA PER RICERCALO NEL DATABASE
		}else
			$posto=$temp;//ALTRIMENTI RICERCA COSI COME è
		
		$cerca->set_paese($posto);
		$cerca->set_query($_REQUEST['query']);
		$cerca->set_key("AIzaSyC7l86uRRmZ4hvmfrrQYZQnYukWuv8ynQw");
		
		//AIzaSyBGDSOx9gv_SMDpFlhUe7w43C7RG7423Tk
		//AIzaSyAW121HZee767g3JOEQ1MGMEGvUUjc04Xw
		//CONTROLLA SE SONO PRESENTI GLI ELEMENTI IN BASE AI PARAMETRI INSERITI DALL'UTENTE

		$presenti = $sql->controlla_elementi($cerca->get_paese(),$cerca->get_query());

		//SE I DATI NON SONO PRESENTI NEL DATABASE ALLORA RICHIAMA LE API
		if($presenti==false){
			$cerca->set_url("https://maps.googleapis.com/maps/api/place/textsearch/json?language=italian&");
			$cerca->set_metodo("GET");
			//EFFETTUA LA RICHIESTA
			$mex = $cerca->richiesta_http();//SETTA IL MESSAGGIO SE LA RICHIESTA FALLISCE
			//SE RICHIESTA FALLITA STAMPA MESSAGGIO E VAI ALLA PAGINA DI ERRORE
			if($mex != null)
				header("Location: errore.html?mex=RICHIESTA FALLITA");

			//SE RICHIESTA VA A BUON FINE
			if($mex==null){
				$response = $cerca->get_output();
				if($response['status']=='OK'){
					$cerca->set_info($response['results']);

					for($i=0;$i<$cerca->get_venues();$i++){
						//RICHIESTA DELLA PHOTO REFERENCE DEL POSTO E SETTA ID -----------------------------------------------
						$cerca->set_id($response['results'][$i]['place_id']);
						$cerca->set_photo_reference($response['results'][$i]['photos'][0]['photo_reference']);
						$cerca->set_url_immagine("https://maps.googleapis.com/maps/api/place/photo?maxwidth=330&maxheight=270&photoreference=");

						//RICHIESTA DEI DETTAGLI DEL POSTO ---------------------------------------------------------------
						$cerca->set_details_place("https://maps.googleapis.com/maps/api/place/details/json?placeid=");
						$cerca->richiesta_http_place_details();

						$r=$cerca->get_output_place_details();
						if($r['status']=='OK'){
							$cerca->set_info2($r['result']);
							//PRLEVA LE IMMAGINI PRESENTI NEL CORPO DEI DETTAGLI DEL VENUES
							$cerca->set_photos($r['result']);

							//SE E' LA PRIMA VOLTA CONTROLLA SE IL LUOGO E' GIA' PRESENTE NEL DATABASE ALTRIMENTI LO INSERISCE
							if($i==0){
								$ricercato=str_replace('%20', ' ' ,$cerca->get_paese());
								if($sql->controlla_luogo($ricercato,$cerca->get_citta())==false)
									$sql->inserisci_luogo($cerca->get_citta(),$cerca->get_provincia(),$cerca->get_regione(),$cerca->get_stato(),$cerca->get_cap(),$ricercato);
							}

							if($sql->controlla_categoria($cerca->get_categoria($i))==false)
								$sql->inserisci_categoria($cerca->get_categoria($i));

							$id=$sql->restituisci_id_cat($cerca->get_categoria($i));
							//INSERISCI IL DATO ALL'INTERNO DELLA TABELLA DATI DATABASE PASSANDO COME PARAMETRI LE INFORMAZIONI RELATIVE AL SINGOLO DATO
							$sql->inserisci_dato($cerca->get_nomi($i),$cerca->get_location(),$cerca->get_civico(),$cerca->get_lat($i),$cerca->get_long($i),$cerca->get_phone(),$id,$cerca->get_rating($i),$cerca->get_foto_venue(),$cerca->get_content_photo(),$cerca->get_orario(),$cerca->get_descrizione());

							//INSERISCI L'INFORMAZIONE CHE LEGA LE TABELLE DATI E LUOGHI NELLA TABELLA LUOGHIDATI
							$sql->inserisci_luoghidati($cerca->get_query(),$ricercato);

						}
					}
				 }
				 else if($response['status']=='ZERO_RESULTS')
					 $mex="NON SONO STATI TROVATI RISULTATI";

				 else if($response['status']=='OVER_QUERY_LIMIT')
					 $mex="RICHIESTE MASSIME RAGGIUNTE";

				else if($response['status']=='REQUEST_DENIED')
					$mex="RICHIESTA NEGATA";

				else if($response['status']=='INVALID_REQUEST')
					$mex="RICHIESTA INVALIDA";

				else if($response['status']=='UNKNOWN_ERROR')
					$mex="PROBLEMI CON IL SERVER";

			}
		}
		
		//SE RICHIESTA VA A BUON FINE
		if($mex==null){
		   //INSERISCI RICERCA CON DATA E ORARIO CORRISPONDENTE SE L'UTENTE è LOGGATO
		   if(isset($_SESSION['Loggato'])){
			   if(isset($_REQUEST['cat']))
				  $sql->cronologia($_SESSION['User'],$_REQUEST['query'],$cerca->get_paese());
			   else
				   $sql->cronologia($_SESSION['User'],$cerca->get_query(),$cerca->get_paese());
		   }
		   header("Location: dati.php?page=1&paese=".$cerca->get_paese()."&query=".$cerca->get_query());

		}else{
			header("Location: errore.html?mex=$mex");
		}
	}
?>
