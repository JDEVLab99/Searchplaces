<?php
class Cerca 
{
        private $paese;
        private $query;
        private $url;
        private $id;
        private $url_immagine;
		private $url_details;
        private $metodo;
        private $output = array();
		private $nomi = array();
		private $location;
		private $civico;
		private $phone;
		private $categorie = array();
		private $citta;
		private $stato;
		private $provincia;
		private $cap;
		private $regione;
		private $n_venues;
		private $photos = array();
		private $righe = array();
		private $lat = array();
		private $long = array();
		private $foto_venue = array();
		private $venues=array();
		private $valutazioni=array();
		private $reference;
		private $output_place_details;
		private $content_photo;
		private $orario;
		private $descrizione;
		private $chiave;
		
        function set_metodo($m){
            if(isset($m))
                $this->metodo=$m;
        }
		
        function set_data(){
            $this->data=date("Ymd");
        }
	
		function set_key($k){
			if(isset($k))
				$this->chiave=$k;
		}

        function set_paese($p){
        	if(isset($p))
				$this->paese = ucfirst($p);;
        }

        function set_query($q){
            if(isset($q))
				$this->query = strtolower($q);
        }
		
		//AIzaSyAW121HZee767g3JOEQ1MGMEGvUUjc04Xw
		//AIzaSyBGDSOx9gv_SMDpFlhUe7w43C7RG7423Tk 
	    //AIzaSyAJxs1-m54F4NiIFXUYl7Zggo0y0AETlfw 
		//AIzaSyDUhKCHh3_iQVo7Id2h5X14_bOG8NcXTfM
	    //AIzaSyDF0rzPbu4XlCY7lIYLdrYAZ4RBscUd49w
	    //AIzaSyCRbS1sDCCEaZLMyPXgYHOXB_Kh70f-0C8
		function set_url($u){
			if(isset($u)){
				$this->paese=str_replace(' ', '%20' ,$this->paese);
				$this->url=$u."query=".$this->query."+in+".$this->paese."&key=".$this->chiave;
			}
		}
		
        function set_id($i){
            if(isset($i)&&!empty($i))
                $this->id = $i;
        }
	
		function set_photo_reference($i){
            if(isset($i)&&!empty($i))
                $this->reference = $i;
        }
	
        function set_url_immagine($u){
            $this->url_immagine = $u.$this->reference."&key=".$this->chiave;
		}
	
		function set_details_place($u){
			$this->url_details = $u.$this->id."&key=".$this->chiave;
		}
	
        function richiesta_http(){
            $mex = null;
            $json = null;
            try{
                $json = file_get_contents($this->url);
                if($json!=null){
                    $response = json_decode($json, true);
					$this->output=$response;
                }
                else
                    $mex = "Non è stato trovato alcun risultato!";
            }catch(MalformedURLException $e){
                $mex = "Non è stato trovato alcun risultato!";
            }catch(IOException $e){
                $mex = "Non è stato trovato alcun risultato!";
            }
            return $mex;
        }
	
		function richiesta_http_place_details(){
			$mex = null;
            $json = null;
            try{
                $json = file_get_contents($this->url_details);
                if($json!=null){
                    $response = json_decode($json, true);
					$this->output_place_details = $response;
                }
                else
                    $mex = "Non è stato trovato alcun risultato!";
            }catch(MalformedURLException $e){
                $mex = "Non è stato trovato alcun risultato!";
            }catch(IOException $e){
                $mex = "Non è stato trovato alcun risultato!";
            }
            return $mex;
		}
		
		function set_info($risultati){
			if(!empty($risultati)){
				$this->venues=$risultati;
				$this->n_venues = count($this->venues);
				$temp = array();
			
				for($i=0;$i<$this->n_venues;$i++){

					if(!empty($this->venues[$i]['geometry']['location']['lat']))
						$this->lat[$i]=$this->venues[$i]['geometry']['location']['lat'];
					else
						$this->lat[$i]="Latitudine non disponibile";

					if(!empty($this->venues[$i]['geometry']['location']['lng']))
						$this->long[$i]=$this->venues[$i]['geometry']['location']['lng'];
					else
						$this->long[$i]="Longitudine non disponibile";

					if(!empty($this->venues[$i]['types'][0]))
						$this->categorie[$i]=$this->venues[$i]['types'][0];
					else
						$this->categorie[$i]="Non disponibile";

					if(!empty($this->venues[$i]['name']))
						$this->nomi[$i]=$this->venues[$i]['name'];
					else
						$this->nomi[$i]="Nome non disponibile";

					if(!empty($this->venues[$i]['rating']))
						$this->valutazioni[$i]=$this->venues[$i]['rating'];
					else
						$this->valutazioni[$i]="Valutazione non disponibile";

				}
			}
		}
	
		function set_info2($risultati){
			if(!empty($risultati['formatted_phone_number']))
				$this->phone=$risultati['formatted_phone_number'];
			else
				$this->phone="Non disponibile";
			
			$temp=array();
			$this->orario=null;
			if(!empty($risultati['opening_hours']['weekday_text'])){
				$temp=$risultati['opening_hours']['weekday_text'];
				for($i=0;$i<count($temp);$i++)
					$this->orario=$this->orario.$temp[$i].";";
			}else
				$this->orario="Non disponibile";
			
			if(!empty($risultati['address_components'][1]['short_name']))
				$this->location=$risultati['address_components'][1]['short_name'];
			else
				$this->location="Indirizzo non disponibile";
			
			if(!empty($risultati['address_components'][0]['short_name']))
				$this->civico=$risultati['address_components'][0]['short_name'];
			else
				$this->civico="ND";
			
			if(!empty($risultati['address_components'][4]['long_name']))
				$this->provincia=$risultati['address_components'][4]['long_name'];
			else
				$this->provincia="Non disponibile";
			
			if(!empty($risultati['address_components'][5]['short_name']))
				$this->regione=$risultati['address_components'][5]['short_name'];
			else
				$this->regione="Non disponibile";
			
			if(!empty($risultati['address_components'][6]['short_name']))
				$this->stato=$risultati['address_components'][6]['short_name'];
			else
				$this->stato="Non disponibile";
			
			if(!empty($risultati['address_components'][7]['short_name'])){
				$this->cap=$risultati['address_components'][7]['short_name'];
			}else
				$this->cap="ND";
			
			if(!empty($risultati['reviews'][0]['text']))
				$this->descrizione=$risultati['reviews'][0]['text'];
			else
				$this->descrizione="Non disponibile";
			
			if(!empty($risultati['address_components'][2]['short_name']))
				$this->citta=$risultati['address_components'][2]['short_name'];
			else
				$this->citta="Non disponibile";
		}
	
		function set_photos($risultati){
			$temp = array();
			$this->content_photo=null;
			if(!empty($risultati['photos'])){
				$temp = $risultati['photos'];
				for($i=0;$i<count($temp);$i++){
					$this->photos[$i]="https://maps.googleapis.com/maps/api/place/photo?maxwidth=847&maxheight=400&photoreference=".$temp[$i]['photo_reference']."&key=".$this->chiave. ";";
					$this->content_photo=$this->content_photo.$this->photos[$i];	
				}
			}else
				$this->content_photo="Foto non disponibili";
		}
	
	
		function get_nomi($i){
			return $this->nomi[$i];
		}
	
		function get_location(){
			return $this->location;
		}
	
        function get_output(){
            return $this->output;
        }
        
        function get_paese(){
            return $this->paese;
        }

        function get_query(){
            return $this->query;
        }
	
        function get_url(){
            return $this->url;
        }

        function get_id(){
            return $this->id;
        }

        function get_url_immagine(){
            return $this->url_immagine;
        }
	
		function get_venues(){
			return $this->n_venues;
		}
	
		function get_photos($i){
			return $this->photos[$i];
		}
		
		function get_lat($i){
		    return $this->lat[$i];
		}
		
		function get_long($i){
		    return $this->long[$i];
		}
	
		function get_numero($i){
		    return $this->phones[$i];
		}
	
		function get_categoria($i){
		    return $this->categorie[$i];
		}
	
		function get_citta(){
		    return $this->citta;
		}
	
		function get_stato(){
		    return $this->stato;
		}
	
		function get_url_details($i){
			return $this->url_details[$i];
		}
	
		function get_output_place_details(){
			return $this->output_place_details;
		}
	
		function get_rating($i){
			return $this->valutazioni[$i];
		}
	
		function get_phone(){
			return $this->phone;
		}
		
		function get_foto_venue(){
		    return $this->url_immagine;
		}
	
		function get_content_photo(){
			return $this->content_photo;
		}
	
		function get_orario(){
			return $this->orario;
		}
	
		function get_provincia(){
			return $this->provincia;
		}
	
		function get_cap(){
			return $this->cap;
		}
	
		function get_regione(){
			return $this->regione;
		}
		
		function get_descrizione(){
			return $this->descrizione;
		}
	
		function get_civico(){
			return $this->civico;
		}
		
}
?>
