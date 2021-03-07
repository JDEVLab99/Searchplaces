<?php
include("./classi/Sql.php");
require("./dinamiche/prepara.php");
session_start();

if (!empty($_SERVER['HTTP_CLIENT_IP']))
  $ip=$_SERVER['HTTP_CLIENT_IP'];
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
else
  $ip=$_SERVER['REMOTE_ADDR'];

$port=$_SERVER['REMOTE_PORT'];
$info=$_SERVER['HTTP_USER_AGENT'];
$host=$_SERVER['HTTP_HOST'];

require("./config_sql.php");
$sql->inserisci_visitatore($ip,$port,$host,$info);
$sql->chiudi();

if(isset($_COOKIE['SID'])&&isset($_COOKIE['TOKEN'])){
	$sql = new Sql("localhost","root","","progetto_esame");
	$result=$sql->ritorna_cookie($_COOKIE['SID']);
	if($result->num_rows==1){
		$row = $result->fetch_assoc();
		if($row['Token']==$_COOKIE['TOKEN']){
			$result = $sql->ritorna_user($row['Username']);
			if($result->num_rows>0){
				$row = $result->fetch_assoc();
				$_SESSION['Loggato']=$row['Nome'];
				$_SESSION['User']=$row['Username'];
			}
		}
	}
	$sql->chiudi();
}
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>SearchPlaces</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="./immagini/logo2.jpg" type="image/jpg" />
	<script type="text/javascript" src="https://maps.google.it/maps/api/js?key=AIzaSyCRbS1sDCCEaZLMyPXgYHOXB_Kh70f-0C8&libraries=places"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="controlli.js"></script>

	<style>
		
	#form1 label.error {
	   color: #f33;
	   padding: 0;
	   margin: 2px 0 0 0;
	   font-size: 13px;
	   padding-left: 18px;
	   background-position: 0 0;
	   background-repeat: no-repeat;
	}

	#form2 label.error {
	   color: #f33;
	   padding: 0;
	   margin: 2px 0 0 0;
	   font-size: 13px;
	   padding-left: 18px;
	   background-position: 0 0;
	   background-repeat: no-repeat;
	}
		
	#form label.error {
	   color: #f33;
	   padding: 0;
	   margin: 2px 0 0 0;
	   font-size: 13px;
	   padding-left: 18px;
	   background-position: 0 0;
	   background-repeat: no-repeat;
	}

	#form label.error {
	   color: #f33;
	   padding: 0;
	   margin: 2px 0 0 0;
	   font-size: 13px;
	   padding-left: 18px;
	   background-position: 0 0;
	   background-repeat: no-repeat;
	}

	#mioform label.error {
	   color: #f33;
	   padding: 0;
	   margin: 2px 0 0 0;
	   font-size: 15px;
	   padding-left: 18px;
	   background-position: 0 0;
	   background-repeat: no-repeat;
	}

	/* BOTTONE MENU LATERALE */
	#sidebarCollapse{
		background-color: #337AB7;
		padding: 15px;
		margin-right: 20px;
		font-size: 15px;
		margin-top: 25px;
	}

	@media (max-width: 767px){

		#sidebarCollapse{
			background-color: #337AB7;
			padding: 9px;
			margin-left: 225px;
			font-size: 12px;
			margin-top: 50px;
		}
	}

	/* ---------------------------------------------------
		SIDEBAR STYLE
	----------------------------------------------------- */
	#sidebar {
		width: 250px;
		position: fixed;
		top: 0;
		right: -250px;
		height: 100vh;
		z-index: 999;
		background-color: rgb(170,170,170);
		color: #fff;
		transition: all 0.3s;
		overflow-y: scroll;
		box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
	}

	#sidebar.active {
		right: 0;
	}

	#dismiss {
		width: 35px;
		height: 35px;
		line-height: 35px;
		text-align: center;
		background-color: rgb(170,170,170);
		position: absolute;
		top: 10px;
		left: 10px;
		cursor: pointer;
		-webkit-transition: all 0.3s;
		-o-transition: all 0.3s;
		transition: all 0.3s;
	}
	#dismiss:hover {
		background: #fff;
		color: #7386D5;
	}

	#sidebar .sidebar-header {
		padding: 20px;
		background-color: rgb(170,170,170);
	}

	#sidebar ul.components {
		padding: 20px 0;
		border-bottom: 1px solid #47748b;
	}

	#sidebar ul p {
		color: #fff;
		padding: 10px;
	}

	#sidebar ul li a {
		padding: 10px;
		font-size: 1.1em;
		display: block;
	}
	#sidebar ul li a:hover {
		color: #7386D5;
		background: #fff;
	}

	#sidebar ul li.active > a, a[aria-expanded="true"] {
		color: #fff;
		background: #6d7fcc;
	}


	a[data-toggle="collapse"] {
		position: relative;
	}

	a[aria-expanded="false"]::before, a[aria-expanded="true"]::before {
		content: '\e259';
		display: block;
		position: absolute;
		right: 20px;
		font-family: 'Glyphicons Halflings';
		font-size: 0.6em;
	}
	a[aria-expanded="true"]::before {
		content: '\e260';
	}


	ul ul a {
		font-size: 0.9em !important;
		padding-left: 30px !important;
		background: #6d7fcc;
	}

	ul.CTAs {
		padding: 20px;
	}

	ul.CTAs a {
		text-align: center;
		font-size: 0.9em !important;
		display: block;
		border-radius: 5px;
		margin-bottom: 5px;
	}
	a.download {
		background: #fff;
		color: #7386D5;
	}
	a.article, a.article:hover {
		background: #6d7fcc !important;
		color: #fff !important;
	}


	/*STYLE DELLA SELECT PER RICHIESTA DI SBLOCCO*/
	ul.chec-radio {
		margin: 15px;
	}
	ul.chec-radio li.pz {
		display: inline;
	}
	.chec-radio label.radio-inline input[type="checkbox"] {
		display: none;
	}
	.chec-radio label.radio-inline input[type="checkbox"]:checked+div {
		color: #fff;
		background-color: #000;
	}
	.chec-radio .radio-inline .clab {
		cursor: pointer;
		background: #e7e7e7;
		padding: 3px 10px;
		text-align: center;
		text-transform: uppercase;
		color: #333;
		position: relative;
		height: 34px;
		float: left;
		margin: 0;
		margin-bottom: 5px;
	}
	.chec-radio label.radio-inline input[type="checkbox"]:checked+div:before {
		content: "\e013";
		margin-right: 5px;
		font-family: 'Glyphicons Halflings';
	}
	.chec-radio label.radio-inline input[type="radio"] {
		display: none;
	}
	.chec-radio label.radio-inline input[type="radio"]:checked+div {
		color: #fff;
		background-color: #000;
	}
	.chec-radio label.radio-inline input[type="radio"]:checked+div:before {
		content: "\e013";
		margin-right: 5px;
		font-family: 'Glyphicons Halflings';
	}

	.loader {
	  border: 16px solid #f3f3f3;
	  border-radius: 50%;
	  border-top: 16px solid rgb(100,100,100);
	  border-right: 16px solid #337AB7;
	  border-bottom: 16px solid rgb(100,100,100);
	  border-left: 16px solid #337AB7;
	  width: 120px;
	  height: 120px;
	  -webkit-animation: spin 1s linear infinite;
	  animation: spin 1s linear infinite;
	}

	@-webkit-keyframes spin {
	  0% { -webkit-transform: rotate(0deg); }
	  100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	  0% { transform: rotate(0deg); }
	  100% { transform: rotate(360deg); }
	}
	
	/* STILE DELLE CATEGORIE */
	.over{
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		height: 100%;
		width: 100%;
		opacity: 0;
		transition: .5s ease;
		background-color: #337AB7;
	}

	.cate:hover .over {
		opacity: 1;
	}

	.t{
		color: white;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  text-align: center;
	}
	</style>
</head>
<body>
	<div style="background-color: rgb(255, 163, 26); color: white;" id="loading_screen">
		<!--visibility: hidden-->
	  <center>
	  <h1>Attendi</h1>
	  <p>La pagina &egrave; in caricamento<br/>
	  Resta connesso e non cambiare sito!</p>
	  <div id="loader" class="loader"></div></center>
	</div>
	
	<!-- SCRIPT CHE SI OCCUPA DELL'AUTOCOMPLETE TRAMITE API DI GOOGLE MAPS -->
	<script>
		$(window).on('load',function(){
			$('#loading_screen').fadeOut("fast",function(){
				$("#page").css("visibility","visible");
				$("#page").fadeIn("fast");
			});
		});
		
		$(document).ready(function(){
			$("#page").fadeOut("fast");
			$('#loading_screen').fadeIn("fast");
			/*
			$('#scritta').removeClass('bounceInDown',function(){
				$('#scritta').addClass('infinite pulse');
			});*/
			//VALIDAZIONE DEL FORM CONTROLLO PARAMETRI SE SONO SETTATI
			$('#mioform').validate({
				submitHandler: function(form){
					var citta = $('#citta').val();
					var query = $('#query').val();
					if(citta!==""||query!==""){
						$("#page").fadeOut("fast");
						$("#loading_screen").fadeIn("fast");
						form.submit();
					}else
						alert("Ti mancano i parametri!");
				}
			});
			
			$('.cat').click(function(){
				var c = $('#citta').val();
				if(c!==""){
					$("#page").fadeOut("fast");
					$("#loading_screen").fadeIn("fast");
					var q = $(this).attr('id');
					var url = "./dinamiche/trova.php?cat=1&query=" + q + "&citta="+c;
					url = url.replace(/ /gi,"%20");
					window.location = url;
				}else{
					alert("Inserisci un luogo!");
				}
			});
			
			//FUNZIONE PER L'AUTOCOMPLETE NELLE QUERY DA CERCARE-->
			$( function() {
			var availableTags = [
			  "Ristoranti",
			  "Pizzerie",
			  "Bar",
			  "Musei",
			  "Shopping",
			  "Scuole",
			  "Divertimento",
			  "Svago",
			  "Supermercati",
			  "Pasticcerie",
			  "Pub",
			  "Bancomat",
			  "Edicole",
			  "Fruttivendoli",
			  "Oratori",
			  "Campi",
			  "Piscine",
			  "Laghi",
			  "Acquari",
			  "Parchi",
			  "Panetterie",
			  "Biblioteche"
			];
			function split( val ) {
			  return val.split( /\s/ );
			}
			function extractLast( term ) {
			  return split( term ).pop();
			}

			$("#query")
			  // don't navigate away from the field on tab when selecting an item
			  .on( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).autocomplete( "instance" ).menu.active ) {
				  event.preventDefault();
				}
			  })
			  .autocomplete({
				//minLength: 0,
				appendTo:'#result',
				source: function( request, response ) {
				  // delegate back to autocomplete, but extract the last term
				  response( $.ui.autocomplete.filter(
					availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
				  // prevent value inserted on focus
				  return false;
				},
				select: function( event, ui ) {
				  var terms = split( this.value );
				  // remove the current input
				  terms.pop();
				  // add the selected item
				  terms.push( ui.item.value );
				  // add placeholder to get the comma-and-space at the end
				  terms.push( "" );
				  this.value = terms.join( "" );
				  return false;
				}
			  });

		  });

		  //FUNZIONE CHE CONSENTE DI UTILIZZARE L'AUTOCOMPLETE
		  function init(){
			   var input = document.getElementById("citta");
			   var autocomplete = new google.maps.places.Autocomplete(input);
		  }
		  google.maps.event.addDomListener(window, 'load', init);
			
		});
	</script>
	<div id="page">
		<header>

			<nav id="mynav" class="navbar" role="navigation" >
					<div class="navbar-form navbar-right" name="mioform1">
						<!-- BOTTONE CHE MI RICHIAMA IL MODALE DELLA REGISTRAZIONE -->
						<button type="button" id="sidebarCollapse"  class="btn btn-info navbar-btn">
								<span>VEDI MENU</span>
                                <i class="glyphicon glyphicon-align-right"></i>
                        </button>
						<nav id="sidebar">
							<div id="dismiss">
								<i class="glyphicon glyphicon-arrow-right"></i>
							</div>

							<div class="sidebar-header">
								<!--SE LOGGATO VISUALIZZA IL NOME DELL'UTENTE CON MESSAGGIO BENVENUTO-->
								<br>
								<h3><?php if(isset($_SESSION['Loggato']))echo "Benvenuto ".$_SESSION['Loggato'];?></h3>
							</div>

							<ul class="list-unstyled components">

								<li>
									<!-- RICHIAMA MODALE REGISTRAZIONE -->
									<?php
									if(!isset($_SESSION['Loggato']))
										echo "<a data-toggle='modal' data-target=\"#myModal1\" href=\"\" >Registrati</a>";
									else
										echo "<a href=\"cronologia.php\" >Vedi cronologia</a>";
									?>
								</li>
								<li>
									<!-- RICHIAMA MODALE ACCEDI -->
									<?php
									if(!isset($_SESSION['Loggato']))
										echo "<a data-toggle='modal' data-target=\"#myModal\" href=\"\" >Accedi</a>";
									else
										echo "<a href=\"preferiti.php\" >Vedi preferiti</a>";
									?>
								</li>
								<li>
									<!-- CONTATTA AMMINISTRATORE, RICHIEDI INFORMAZIONI O INVIA SUGGERIMENTI PER MIGLIORARE L'APPLICAZIONE -->
									<?php
									if(isset($_SESSION['Loggato']))
										echo "<a data-toggle='modal' id=\"contatta\"  data-target='#myModal2' href=\"\" >Contatta admin</a>";

									?>
								</li>
								<li>
									<!-- VEDI NOTIFICHE -->
									<?php
									if(isset($_SESSION['Loggato']))
										echo "<a id=\"vedi_risposte\" href=\"./risposte.php\" >Vedi notifiche</a>";

									?>
								</li>
								<li>
									<!-- AGGIORNA PASSWORD UTENTE -->
									<?php
									if(isset($_SESSION['Loggato']))
										echo "<a data-toggle='modal' id=\"aggiorna\"  data-target='#myModal3' href=\"\" >Aggiorna password</a>";

									?>
								</li>
								<li>
									<!-- ESCI DALLA SESSIONE -->
									<?php
									if(isset($_SESSION['Loggato']))
										echo "<a id=\"esci\" data-toggle=\"modal\"  href=\"\" >Esci</a>";

									?>
								</li>
							</ul>
						</nav>
					</div>
					<!-- NAVBAR BRAND CHE CORRISPONDE AL TITOLO DELL'APPLICAZIONE -->
					<a id="titolo" class="animated fadeIn navbar-brand" href="">SearchPlaces</a>
			</nav>

			<!-- GESTIONE SLIDER -->
			<div id="mycarousel" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner" role="listbox">
					<div id="1" class="item active" style="background-image: url(./immagini/londra.jpg);">
						<div class="filter"></div>
					</div>
					<div id="2" class="item" style="background-image: url(./immagini/sydney.jpg);">
						<div class="filter"></div>
					</div>
					<div id="3" class="item" style="background-image: url(./immagini/new_york.jpg);">
						<div class="filter"></div>
					</div>
					<div id="4" class="item" style="background-image: url(./immagini/parigi.jpg);">
						<div class="filter"></div>
					</div>
					<div id="5" class="item" style="background-image: url(./immagini/berlino.jpg);">
						<div class="filter"></div>
					</div>
					<div id="6" class="item" style="background-image: url(./immagini/ponte.jpg);">
						<div class="filter"></div>
					</div>
					<div id="8" class="item" style="background-image: url(./immagini/madrid.jpg);">
						<div class="filter"></div>
					</div>
					<div id="9" class="item" style="background-image: url(./immagini/roma.jpg);">
						<div class="filter"></div>
					</div>
				</div>
			</div>

			<!-- GESTIONE CONTENITORE PER INSERIRE I PARAMETRI -->
			<div class="overlay"></div>
			<center>
			<div class="container1">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center">
						<div class="display-t">
							<div class="display-tc animate-box" data-animate-effect="fadeIn">
								<h1 class='animated bounceInDown' id="scritta">Prova a cercare dei posti che ti interessano</h1>
								<!-- FORM PER INSERIRE I PARAMETRI CITTA' E QUERY-->
								<div class="row">
									<form class="form-inline" id="mioform" name="mioform" method="post" action="./dinamiche/trova.php">
											<div class="form-group">
												<input type="text" class="search-query form-control" id="citta" name="citta" placeholder="Cerca posto" />
											</div>
											<div class="form-group">
													<input type="text" class="search-query form-control" id="query" name="query" placeholder="Cerca bar, pizzerie, ristoranti" />
													<div style="text-align: left;" id="result"> </div>
											</div>
											<button type="submit" id="cerca" name="cerca" class="btn btn-primary">Cerca</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</center>
		</header>

		<div  class="row" id="categories" >
			<div  style='margin-top: 5px; margin-bottom: 10px;' class="card-deck">
			  <div class="col-lg-3 col-md-3 col-sm-3 cate">
				  <center>
					  <a class="cat" href="#" id="food" >
					  <div class="animated bounceInDown card" >
						<img class="animated fadeIn card-img-top img-circle" src="./immagini/cibo.jpg" width="200px" height="140px"  alt="Card image cap">
						<div style='margin-top: -1px;' class="card-body" >
						  <h5 class="animated fadeIn card-title" style="background-color: #337AB7">Cibo</h5>
						</div>
					  </div>
						  <div class='over'><div class='t'><h4>SCEGLI CATEGORIA CIBO</h4><hr></div></div>
					</a>
				  </center>
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-3 cate">
				  <center>
				  <a class="cat" href="#" id="club" >
					  <div class="animated bounceInDown card" >
						<img class="animated fadeIn card-img-top img-circle" src="./immagini/svago.jpg" width="200px" height="140px"  alt="Card image cap">
						<div style='margin-top: -1px;' class="card-body">
						  <h5  class="animated fadeIn card-title" style="background-color: #337AB7">Divertimento</h5>
						</div>
					  </div>
					  <div class='over'><div class='t'><h4>SCEGLI CATEGORIA DIVERTIMENTO</h4><hr></div></div>
				  </a>
				  </center>
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-3 cate">
				  <center>
				  <a class="cat" href="#" id="library" >
					  <div class="animated bounceInDown card" >
						<img class="animated fadeIn card-img-top img-circle" src="./immagini/cultura.jpg" width="200px" height="140px"  alt="Card image cap">
						<div style='margin-top: -1px;' class="card-body" >
						  <h5  class="animated fadeIn card-title" style="background-color: #337AB7;">Cultura</h5>
						</div>
					  </div>
					  <div class='over'><div class='t'><h4>SCEGLI CATEGORIA CULTURA</h4><hr></div></div>
				  </a>
				  </center>
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-3 cate" style="background-color: white;">
				  <center>
			      <a class="cat" href="#" id="shopping" >
					  <div class="animated bounceInDown card" >
						<img class="animated fadeIn card-img-top img-circle" src="./immagini/shopping.jpg" width="200px" height="140px"  alt="Card image cap">
						<div style='margin-top: -1px;' class="card-body" >
						  <h5  class="animated fadeIn card-title" style="background-color: #337AB7;">Shopping</h5>
						</div>
					  </div>
					  <div class='over'><div class='t'><h4>SCEGLI CATEGORIA SHOPPING</h4><hr></div></div>
				  </a>
				  </center>
			   </div>
			   <hr>
			</div>
			
			<!-- GESTIONE DEL FOOTER -->
			<?php require("footer.php");?>
		</div>
	</div>

   <!-- FINESTRA MODALE PER L'INSERIMENTO DEI DATI DA PARTE DELL'UTENTE LOGIN -->
   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="text" >Accesso utente</h4>
			</div> <!-- /.modal-header -->

    			<div class="modal-body">
    			        <form name="form1">
        					<div class="form-group">
        						<div class="input-group">
											<input type='text' class='form-control' name='username' id='username' placeholder='Username'>
        							<label for="username" class="input-group-addon glyphicon glyphicon-user"></label>
        						</div> <!-- /.input-group -->
        					</div> <!-- /.form-group -->

        					<div class="form-group">
        					    <div class="input-group">
											<input type='password' class='form-control' name='password' id='password' placeholder='Password'>
        							<label for="password" class="input-group-addon glyphicon glyphicon-lock"></label>
        						</div> <!-- /.input-group -->
        					</div> <!-- /.form-group -->

    					<div class="checkbox">
    						<label>
    							<input name="ricordami" id="ricordami" type="checkbox">Ricordami
    						</label>
    					</div> <!-- /.checkbox -->
    				</form>
    			</div> <!-- /.modal-body -->

    			<div class="modal-footer">
    			    <button id="valida" name="valida" class="form-control btn btn-primary">Ok</button>
    				<div class="progress">
    					<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" style="width: 0%;">
    						<span class="sr-only">Avanzamento</span>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- FINESTRA MODALE PER L'INSERIMENTO DEI DATI DA PARTE DELL'UTENTE IN FASE DI REGISTRAZIONE -->
	<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<!-- INSERIMENTO DEL FORM DI REGISTRAZIONE-->
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 readonly='readonly' class="modal-title" id='myModalLabel1'>Registrazione</h4>
				</div> <!-- /.modal-header -->

					<div class="modal-body">
						<form method="post" id="form1" name="form1">

							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control" name="nome" id="nome" placeholder="Inserisci il tuo nome" >
									<label for="nome" class="input-group-addon glyphicon glyphicon-user"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->

							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control" name="cognome" id="cognome" placeholder="Inserisci il tuo cognome" >
									<label for="cognome" class="input-group-addon glyphicon glyphicon-user"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->

							<div class="form-group">
								<div class="input-group">
									<input type="email" class="form-control" name="email" id="email" placeholder="Inserisci la tua email" >
									<label for="email" class="input-group-addon glyphicon glyphicon-envelope"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->

							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control" name="user" id="user" placeholder="Inserisci uno username" >
									<label for="user" class="input-group-addon glyphicon glyphicon-user"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->

							<div class="form-group">
								<div class="input-group">
									<input type="password" class="form-control" name="pass" id="pass" placeholder="Inserisci una password" >
									<label for="pass" class="input-group-addon glyphicon glyphicon-lock"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->

							<div class="form-group">
								<div class="input-group">
									<input type="password" class="form-control" name="ripeti" id="ripeti" placeholder="Ripeti password" >
									<label for="ripeti" class="input-group-addon glyphicon glyphicon-lock"></label>
								</div> <!-- /.input-group -->
							</div> <!-- /.form-group -->
							<button type="submit" id="registrati" name="registrati" class="form-control btn btn-primary">Registrati</button>

						</form>
						<div id="risposta"></div>
					</div> <!-- /.modal-body -->

				</div>
    	</div>

	</div>
	<!-- FINESTRA MODALE PER CONTATTARE L'AMMINISTRATORE -->
	<div class='modal fade' id="myModal2" tabindex='-1' role='dialog' aria-labelledby='myModalLabel2' aria-hidden='true'>
		  <div class='modal-dialog' role='document'>
			<div class='modal-content'>
			  <form method="post" id="form2" name="form2">
				  <div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					  <h4 readonly='readonly' class='modal-title' id='myModalLabel1'>Contatta admin</h4><hr>
					<h4>Scegli per cosa vuoi contattarlo</h4>
					<ul class="chec-radio">
						<li class="pz">
							<label class="radio-inline">
								<input type="radio" value="Suggerimento" id="pro-chx-residential" name="tipo" id="tipo" class="pro-chx" value="constructed">
								<div class="clab">Invia suggerimento per migliorare l'app</div>
							</label>
						</li>
						<li class="pz">
							<label class="radio-inline">
								<input type="radio" value="Richiesta di informazioni" id="pro-chx-commercial" name="tipo" id="tipo" class="pro-chx" value="unconstructed" checked>
								<div class="clab">Contatta per informazioni</div>
							</label>
						</li>
				    </ul>
				  </div>
				  <div class='modal-body'>
					<div class='form-group'>
					  <label>Inserisci il tuo username</label>
					  <input name="us" id="us" type='text' readonly='readonly' value='<?php echo $_SESSION['User']?>' class='form-control' placeholder='Inserisci il tuo username'>
					</div>
					<div class='form-group'>
					  <label>Scrivi del testo</label>
					  <textarea name="testo" id="testo" class='form-control' placeholder='Scrivi del testo'></textarea>
					</div>
				  </div>
				  <div class='modal-footer'>
					<button name="invia" value='Invia' type='submit' class=' btn btn-primary'>Invia</button>
					<button name="close" value='Close' type='button' class=' btn btn-danger' data-dismiss='modal'>Close</button>
				  </div>
			   </form>
			</div>
		  </div>
	</div>
	<!-- FINESTRA MODALE PER AGGORNARE LA PASSWORD DELL'UTENTE -->
	<div class='modal fade' id="myModal3" tabindex='-1' role='dialog' aria-labelledby='myModalLabel3' aria-hidden='true'>
		  <div class='modal-dialog' role='document'>
			<div class='modal-content'>
			  <form method="post" id="form3" name="form3">
				  <div class='modal-header'>
					<button type='button' id='chiudi' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					  <h4 readonly='readonly' class='modal-title' id='myModalLabel3'>Aggiorna password</h4><hr>
				  </div>
				  <div class='modal-body'>
					<div class='form-group'>
					  <input type="password" id='pas' name='pas' class='form-control' placeholder='Inserisci nuova password'>
					</div>
					<div class='form-group'>
					  <input type="password" id='ripe' name='ripe' class='form-control' placeholder='Conferma password'></input>
					</div>
				  </div>
				  <div class='modal-footer'>
					<button id="conferma" name="conferma" value='conferma' type='submit' class=' btn btn-primary'>Conferma</button>
					<button id='clo' name="close" value='Close' type='button' class=' btn btn-danger' data-dismiss='modal'>Close</button>
				  </div>
			   </form>
		      <label id="rispo"></label>
			</div>
		  </div>
	</div>
	<!------------------------------------------------------------------------------------------------------->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="login/controlla.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
	<script type="text/javascript">
			//FUNZIONE CHE PERMETTE DI ATTIVARE E DISATTIVARE IL MENU LATERALE
            $(document).ready(function () {
                $("#sidebar").mCustomScrollbar({
                    theme: "minimal"
                });

                $('#dismiss, .overlay').on('click', function () {
                    $('#sidebar').removeClass('active');
                    $('.overlay').fadeOut();
                });

                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').addClass('active');
                    $('.overlay').fadeIn();
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });

            });
    </script>
</body>
</html>
