<style>
.scroll{
	overflow-y: auto;
	overflow-x: hidden;
	height: 10px;
}
</style>
<?php
include("../classi/Sql.php");
include("../classi/PhpGoogleMap.php");
session_start();

if(isset($_REQUEST['invia'])){
	//SE INVIA E' SETTATO ALLORA ASSEGNA SOLO ID ELEMENTO
	$id_elemento = $_SESSION['id'];
}
else{
	//SE E' LA PRIMA VOLTA ASSEGNA ID ELEMENTO E LA SESSIONE
	$id_elemento = $_GET['id'];
	$_SESSION['id'] = $id_elemento;
}

$img_array=array();
$orario=array();
$i=0;

try{
	//$sql = new Sql("jpinna.it.mysql","jpinna_it","MDA9Kt7Z","jpinna_it");
	require("../config_sql.php");
	$r = $sql->restituisci_venue($id_elemento);
	$row = $r->fetch_assoc();
	$name=$row["Nome"];
	$immagine=$row['Immagine'];
	$add=$row["Indirizzo"];
	$lat=$row["Latitudine"];
	$long=$row["Longitudine"];
	$phone=$row["Telefono"];
	$city=$row["Luogo"];
	$regione=$row['Regione'];
	$state=$row['Stato'];
	$provincia=$row['Provincia'];
	$categoria=$row['cate'];
	$cap=$row['Cap'];
	$descrizione=$row['Descrizione'];
	$img_array = explode(";",$row["Immagini"]);
	$orario= explode(";",$row['Orario']);

	//PRELEVA I COMMENTI DAL DATABASE
	if(isset($_SESSION['Loggato'])){
		require("./Commenti/preleva_commenti.php");
		//CONTROLLA SE IL PREFERITO E' STATO SALVATO OPPURE NO
		$presente=$sql->controlla_preferito($_SESSION['id'],$_SESSION['User']);
	}
}catch(SQLException $e){
	$no_result = true;
}
$sql->chiudi();

?>
<html>
<head>
	<meta charset="UTF-8">
	<title>SearchPlaces</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<link rel="stylesheet" href="../style.css">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		.table{
			font-size: 12px;
		}

		th{
			font-size: 13.5px;
		}

	</style>
	<script>
		(function(){
			'use strict';
			var $ = jQuery;
			$.fn.extend({
				filterTable: function(){
					return this.each(function(){
						$(this).on('keyup', function(e){
							$('.filterTable_no_results').remove();
							var $this = $(this),
								search = $this.val().toLowerCase(),
								target = $this.attr('data-filters'),
								$target = $(target),
								$rows = $target.find('tbody tr');

							if(search == '') {
								$rows.show();
							} else {
								$rows.each(function(){
									var $this = $(this);
									$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
								})
								if($target.find('tbody tr:visible').size() === 0) {
									var col_count = $target.find('tr').first().find('td').size();
									var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
									$target.find('tbody').append(no_results);
								}
							}
						});
					});
				}
			});
			$('[data-action="filter"]').filterTable();
		})(jQuery);

		$(function(){
			// attach table filter plugin to inputs
			$('[data-action="filter"]').filterTable();

			$('.container').on('click', '.panel-heading span.filter', function(e){
				var $this = $(this),
					$panel = $this.parents('.panel');

				$panel.find('.panel-body').slideToggle();
				if($this.css('display') != 'none') {
					$panel.find('.panel-body input').focus();
				}
			});
			$('[data-toggle="tooltip"]').tooltip();
		})

		function aggiorna(){
			var count = $('.badge').attr("id");
			$.ajax({
				method: 'POST',
				url: "./Commenti/aggiorna.php",
				dataType: 'html',
				data:{
					aggiorna: 1,
					count: count
				},
				success: function(risposta){
					if(risposta.includes("false")){

					}else{
						$('#risposta').html(risposta);
					}

					$('.panel-group .close').click(function(){
							var id = $(this).attr("id");
							$.ajax({
							  method: 'POST',
							  url: "controlla.php",
							  data: "Elimina=1" + "&Id=" + id,
							  dataType: "html",
							  success: function(risposta){
								  $('.panel-group #'+id).remove();
							  },
							  error: function(){
								alert("Chiamata fallita, si prega di riprovare...");
							  }
							});
					});
				},
				error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				},
			});
		}
		window.setInterval(aggiorna,1000);
	</script>
	<script type="text/javascript">
		$(function(){
			$('#scrollabile').css('height', $(window).height()+'px');
		});
		
		$(window).on('load',function(){
			$('#loading_screen').fadeOut("fast",function(){
				$(".container").css("visibility","visible");
				$(".container").fadeIn("fast");
			});
		});
		
		$(document).ready(function(){
			
			$(".container").fadeOut("fast");
			$('#loading_screen').fadeIn("fast");
			
			//INVIA COMMENTO
			$("#invia").click(function(){
				var testo = $('#commento').val();
				if(testo!==""){
					$.ajax({
					  method: 'POST',
					  url: "controlla.php",
					  data: "InviaPHP=1" + "&testoPHP=" + testo,
					  dataType: "html",
					  success: function(risposta){
							$('#risposta').html(risposta);
							//ELIMINA COMMENTO // PRENDO LA CLASSE DEL PULSANTE CLOSE
							$('.panel-group .close').click(function(){
								var id = $(this).attr("id");
								$.ajax({
								  method: 'POST',
								  url: "controlla.php",
								  data: "Elimina=1" + "&Id=" + id,
								  dataType: "html",
								  success: function(risposta){
									  var count = $('.badge').attr("id");
									  count=count-1;
									  $('#risposta .commenti .badge').replaceWith("<span id='"+count+"' class='badge'>"+count+"</span>");
									  $('.panel-group #'+id).remove();
								  },
								  error: function(){
									alert("Chiamata fallita, si prega di riprovare...");
								  }
								});
							});
					  },
					  error: function(){
						alert("Chiamata fallita, si prega di riprovare...");
					  }
					});
					return false;
				}else
					alert("Scrivi del testo!");
			});

			//ELIMINA COMMENTO // PRENDO LA CLASSE DEL PULSANTE CLOSE
			$('.panel-group .close').click(function(){
				var id = $(this).attr("id");
				var count = $('.badge').attr("id");
				count=count-1;
				$('#risposta .commenti .badge').replaceWith("<span id='"+count+"' class='badge'>"+count+"</span>");
				$.ajax({
				  method: 'POST',
				  url: "controlla.php",
				  data: "Elimina=1" + "&Id=" + id,
				  dataType: "html",
				  success: function(risposta){
					  $('.panel-group #'+id).fadeOut('slow',function(){
						  $('.panel-group #'+id).remove();
					  })
				  },
				  error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				  }
				});
			});

			$('#salva').click(function(){
				$.ajax({
				  method: 'POST',
				  url: "controlla.php",
				  data: "Salva=1",
				  dataType: "html",
				  success: function(risposta){
					  $('#preferiti').html(risposta);
				  },
				  error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				  }
				});
				return false;
			});
		});
		
	</script>
	<style>
		.panel{
			animation: fadein 1s;
			-moz-animation: fadein 1s; /* Firefox */
			-webkit-animation: fadein 1s; /* Safari and Chrome */
			-o-animation: fadein 1s; /* Opera */
			animation: fadeout 2s;
			-moz-animation: fadein 2s; /* Firefox */
    		-webkit-animation: fadein 2s; /* Safari and Chrome */
    		-o-animation: fadein 2s; /* Opera */
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
	<div class="container">
	  <div class="row content">
		<div class="col-sm-3 sidenav">
		  <h1><?php echo $name ?></h1>
		  <hr>
		  <?php echo "<img class='img-responsive' src='$immagine'>"?>
		  <h3>Indirizzo</h3><h5><?php echo $add ?></h5>
		  <hr>
		  <h3>Telefono</h3><h5><?php echo $phone ?></h5>
		  <hr>
		  <?php
				$days=array();
			    $params=array();
				echo "<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Orari di apertura</h3>
					</div>
					<div class='panel-body'>
						<input type='text' class='form-control' id='dev-table-filter' data-action='filter' data-filters='#dev-table' placeholder='Scegli un giorno' />
					</div>
					<table class='table table-hover' id='dev-table'>
						<thead>
							<tr>
								<th>Giorno</th>
								<th>Turno1</th>
								<th>Turno2</th>
							</tr>
						</thead>
						<tbody>";
			  				for($i=0;$i<count($orario);$i++){
								$days=explode(': ',$orario[$i]);
								if(isset($days[1]))
									$params=explode(',',$days[1]);

								echo "<tr>
									<td>".$days[0]."</td>
									";

								if(isset($params[0]))
									echo "<td>".$params[0]."</td>";
								else
									echo "<td>Closed</td>";

								if(isset($params[1]))
									echo "<td>".$params[1]."</td>";
								else
									echo "<td>Closed</td>";

								echo "</tr>";
							}
						echo "</tbody>
					</table>
				</div>
				";
			//}

		  ?>
		</div>
		<div class="col-sm-9">

			<div id="scrollabile" class="scroll">
				
			<?php
				
			  echo "
			  <h3>Categoria</h3><h5>$categoria</h5>
			  <hr>
			  <h3>Città</h3><h5>$city</h5>
			  <hr>
			  <h3>Provincia</h3><h5>$provincia</h5>
			  <hr>
			  <h3>Regione</h3><h5>$regione</h5>
			  <hr>
			  <h3>Stato</h3><h5>$state</h5>
			  <hr>
			  <h3>Cap</h3><h5><?php echo $cap ?></h5>
			  <hr>";
				
			?>
		  <h3>Descrizione</h3><h5><?php  echo $descrizione ?></h5>
		  <hr>
			
		  <!--<div class="container">-->
			  <!-- SLIDER CON LE IMMAGINI DEL POSTO -->
			  <h3>Immagini del posto</h3>
			   <div id="myCarousel1" class="carousel slide" data-ride="carousel">
			   <?php
					$i=0;
					//Indicators
					echo "<ol class=\"carousel-indicators\">";
						while($i<(count($img_array)-1)){
							if($i==0){
								echo "<li data-target=\"#myCarousel1\" data-slide-to=\"$i\" class=\"active\"></li>";
								$i++;
							}
							else{
								echo "<li data-target=\"#myCarousel1\" data-slide-to=\"$i\"></li>";
							}
							$i++;
						}
					echo "</ol>";

					$i=0;
					echo "<div id=\"inner\" class=\"carousel-inner\">";
						//SLIDE IMMAGINI DEL POSTO
						while($i<(count($img_array)-1)){
							if($i==0){
								echo "<div class=\"item active\"> <img style=\"width:100%; height: 100%;\" class=\"img-responsive\" src=".$img_array[$i]."></div>";
								$i++;
							}
							else{
								echo "<div class=\"item\"><img style=\"width:100%; height: 100%;\" class=\"img-responsive\" src=".$img_array[$i]."> </div>";
								$i++;
							}
						}
					echo "</div>";
				?>

				<!-- Left and right controls -->
				<a class="left carousel-control" href="#myCarousel1" data-slide="prev">
				  <span class="glyphicon glyphicon-chevron-left"></span>
				  <span class="fade sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#myCarousel1" data-slide="next">
				  <span class="glyphicon glyphicon-chevron-right"></span>
				  <span class="fade sr-only">Next</span>
				</a>
			  </div>
		  <!-- </div>-->
		  <hr>
			<!-- MAPPA -->
			<?php
				$map = new PhpGoogleMap("AIzaSyAcAg8U21nYuM4bWn7PyiV_ZrTUHWEZUbA");
				$map->set_indirizzo($add);
				$map->set_coordinate($lat,$long);
				$map->set_text($name);
				$map->set_map2();
				$map->renderHTML();
			?>
		  <hr>
		  <?php
			if(isset($_SESSION['Loggato'])){
			   echo "<form method='post' id='mioform' name=\"mioform\">";
				if($presente==false){
					echo "<fieldset id='preferiti' class=\"form-group\">
						  <legend class=\"col-form-label col-sm-6 pt-0\">Vuoi salvare questo posto nei tuoi preferiti?</legend>
						  <button type='submit' id=\"salva\" name=\"salva\" class=\"form-control btn btn-primary\">Salva</button>
					</fieldset><hr>";
				}
				else
					echo "<h4>Hai già inserito questo luogo nei tuoi preferiti!</h4></br><hr>";

			  echo "
			  <h4>Lascia un tuo commento</h4>
				<div class=\"form-group\">
				  <textarea id=\"commento\" name=\"commento\" class=\"form-control\" rows=\"3\" required></textarea>
				</div>
				<button type='submit' id=\"invia\" name=\"invia\" class=\"form-control btn btn-primary\">Invia</button>
			  </form>
			  <br><br>";
			  echo "<div id='risposta' >";
				require("./Commenti/stampa_commenti.php");
		  	  echo "</div>";
			}
		  ?>
		</div>
		</div>
	  </div>

	  <!--<div id="back_to_top"><span class="glyphicon glyphicon-arrow-up"></span></div>-->
	<!-- GESTIONE FOOTER -->
	<?php require("../footer.php");?>
	</div>
</body>
</html>
