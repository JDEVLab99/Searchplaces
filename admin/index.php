<?php
if(isset($_REQUEST['invia'])){
	//APERTURA DEL FILE CSV CHE CONTIENE LA PASSWORD DELL'ADMIN
	$filepath='./SECRET.csv';
	 if (($handle = fopen($filepath, "r")) !== FALSE) {
	  $nn = 0;
	  $handle=fopen($filepath, "r");

	  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	  	$temp=explode(';',$data[0]);
		$nome=$temp[1];
		$cognome=$temp[2];
		$password=$temp[4];
	  }
	  fclose($handle);
	 } else {
	  echo "File non trovato";
	 }

	//CONFRONTO SE LA PASSWORD INSERITA CORRISPONDE CON QUELLA NEL FILE
	$pass=$_REQUEST['pass'];
	if(password_verify($pass, $password)){
		session_start();
		$_SESSION['Loggato']=1;
		$_SESSION['nome']=$nome;
		$_SESSION['cognome']=$cognome;
		header("Location: page.php");
	}else
		echo "<script>alert('PASSWORD ERRATA!');</script>";
	
}else if(isset($_REQUEST['logout'])){
	session_destroy();
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SearchPlaces | Admin area</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="./Templates/js/bootstrap.min.js"></script>
    <link href="./Templates/css/bootstrap.min.css" rel="stylesheet">
    <link href="./Templates/css/style.css" rel="stylesheet">
	<style>
	  .loader {
	  border: 16px solid #f3f3f3;
	  border-radius: 50%;
	  border-top: 16px solid white;
	  border-right: 16px solid #337AB7;
	  border-bottom: 16px solid white;
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
	</style>
  </head>
  <body>
	<script>
		$(window).on('load',function(){
			$('#loading_screen').fadeOut("fast",function(){
				$("#page").css("visibility","visible");
				$("#page").fadeIn("fast");
			});
		});
		
		$(document).ready(function(){
			$("#page").fadeOut("fast");
			$('#loading_screen').fadeIn("slow");
		});
	</script>
	<div style="background-color: #E74C3C; color: white;" id="loading_screen">
		<!--visibility: hidden-->
	  <center>
	  <h1>Attendi</h1>
	  <p>La pagina &egrave; in caricamento<br/>
	  Resta connesso e non cambiare sito!</p>
	  <div id="loader" class="loader"></div></center>
	</div>
	<div id="page">
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">AdminStrap</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <header id="header">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="text-center"> Admin Area <small>Account Login</small></h1>
          </div>
        </div>
      </div>
    </header>

    <section id="main">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <form id="login" method='POST' action='' class="well">
                  <div class="form-group">
                    <label>Amministratore</label>
                    <input type="text" value='admin' readonly="true" class="form-control" placeholder="Enter Email">
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" id='pass' name='pass' class="form-control" placeholder="Password" required>
                  </div>
                  <button type="submit" id="invia" name='invia' class="btn btn-default btn-block">Login</button>
             </form>
			 <div id="risposta"></div>
          </div>
        </div>
      </div>
    </section>
    <footer id="footer">
      <p>Copyright AdminStrap, &copy; 2017</p>
    </footer>
	</div>
  </body>
</html>
