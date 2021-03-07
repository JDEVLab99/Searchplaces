<?php
include("./classi/Sql.php");
session_start();
if(isset($_REQUEST['Elimina'])){
	require("./config_sql.php");
	if($sql->cancella_risposta($_REQUEST['Id'])!=false)
		exit("cancellato");
	$sql->chiudi();
}
else if(isset($_REQUEST['tutte'])){
	require("./config_sql.php");
	if($sql->elimina_risposte($_REQUEST['tutte'])!=false)
		exit("<div id='risposta' class='container'>
				<div class='row'>
					<div class='col-md-4'>
						</br>
						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					<div class='col-md-4'>
						<h1 class='display-1'>Nessuna notifica</h1>
					</div>
				</div></div>");
	$sql->chiudi();
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>SearchPlaces</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
</head>
<body>
	<script>
		$(document).ready(function(){
			//ELIMINA NOTIFICA
			$('.btn-warning').click(function(){
				var id = $(this).attr("id");
				$.ajax({  
				  method: 'POST',
				  url: "risposte.php",  
				  data: "Elimina=1" + "&Id=" + id,
				  dataType: "text",
				  success: function(risposta){
					  if(risposta.includes("cancellato")){
						  $('#'+ id).fadeOut("slow",function(){
							  $('#'+ id).remove();
						  });
					  }
				  },
				  error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				  }
				});
				return false;
			});
			
			$('#elimina').click(function(){
				$.ajax({  
				  method: 'POST',
				  url: "risposte.php",  
				  data: "tutte=1",
				  dataType: "html",
				  success: function(risposta){
					  $('#risposta').replaceWith(risposta);

				  },
				  error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				  }
				});
				return false;
			});
		});
	</script>
	<?php
		if(isset($_SESSION['User'])){
			$sql = new Sql("localhost","root","","progetto_esame");
			$result=$sql->stampa_risposte($_SESSION['User']);
			$sql->chiudi();
			if($result->num_rows>0){
				echo "<center><div id='risposta' class='container'>
				<div class='row'>
					<div class='col-md-4'>
						</br>
						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					
					<div class='col-md-4'>
						<h1 class='display-1'>Notifiche</h1>
					</div>
				</div>
				";
				echo "<hr>
				<button id=\"elimina\" name=\"elimina\" class=\"form-control btn btn-danger\">Elimina notifiche</button><br><hr>";
				echo "<div class='panel-group'>";
				while($row=$result->fetch_assoc()){
					echo "
					<div id='".$row['Id_risposta']."' class='risposta panel panel-primary'>
						<div class='panel-heading'>
							<h5>Risposta admin in data ".$row['Data']." alle ore ".$row['Ora']."</h5>
						</div>
						<div class='panel-body'>
							<h5>".$row['Testo']."</h5>
						</div>
						<div class='panel-footer'>
							<button name='letto' id='".$row['Id_risposta']."' class='btn btn-warning'>Letto</button>
						</div>
				  	</div>";
				}
			  echo "</div>";
			echo "</center></div>";
			}else{
				echo "<div class='container'>
					<div class='row'>
						<div class='col-md-4'>
							</br>

							<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
						</div>
						<div class='col-md-4'>
							<h1 class='display-1'>Nessuna notifica</h1>
						</div>
					</div>
				</div>";
			}
		}else{
			echo "<div class='container'>
				<div class='row'>
					<div class='col-md-4'>
						</br>

						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					<div class='col-md-4'>
						<h1 class='display-1'>Notifiche non disponibili</h1>
					</div>
				</div>
			</div>";
		}
	?>
</body>
</html>