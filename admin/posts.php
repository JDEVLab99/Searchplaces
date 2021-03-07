<?php
include("../classi/Sql.php");
session_start();
if(isset($_REQUEST['Elimina'])){
	require("./config_sql.php");
	if($sql->elimina_commento($_REQUEST['Id'])!=false)
		exit("cancellato");
	$sql->chiudi();

}else if(isset($_REQUEST['Guarda'])){
	require("./config_sql.php");
	if($sql->ritorna_commento($_REQUEST['Id'])!=false){
			exit("<div class='modal fade' id='addPage' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
		  <div class='modal-dialog' role='document'>
			<div class='modal-content'>
			  <form>
			  <div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='myModalLabel'>Commento di </h4>
			  </div>
			  <div class='modal-body'>
				<div class='form-group'>
				  <label>Nome luogo</label>
				  <input type='text' class='form-control' placeholder='Page Title'>
				</div>
				<div class='form-group'>
				  <label>Testo</label>
				  <textarea  readonly='readonly' class='form-control' >bellissimo</textarea>
				</div>
			  </div>
			  <div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>");
	}
	$sql->chiudi();
}
?>
<!DOCTYPE html>
<html lang="en">
  <?php require("./Templates/head.php");?>
  <body>
	  <script>
		<?php require('./requests_ajax.js');?> 
	  </script>
	  <script>
		function elimina_commento(event){
			
			if(event.type=="click"){
				var target = event.target;
				var id = target.id;
				$.ajax({
				  method: 'post',
				  url: "./return_data.php",
				  dataType: 'text',
				  data:{
					elimina: 1,
					Id: id
				  },
				  success:function(response){
					if(response=="true"){
					  $('.'+id).fadeOut("slow",function(){
						  $('.'+id).remove();
					  });
					  var count4 = $(".com").attr("id");
					  count4=count4-1;
					  //AGGIORNA IL NUMERO
					  $('.com').replaceWith("<span id='"+count4+"' class='badge com'>"+count4+"</span>");
					}
				  },
				  error:function(){
					alert("Chiamata fallita....");
				  }
				});
			}
		}
		  
	  </script>
	<?php
	if(isset($_SESSION['Loggato'])){
		require("./Templates/header.php");
		echo "
    <section id='main'>
      <div class='container'>
        <div class='row'>";
		  require("./Templates/barra_sinistra.php");
					echo "
          <div class='col-md-9'>
            <!-- Website Overview -->
            <div class='panel panel-default'>
              <div class='panel-heading main-color-bg'>
                <h3 class='panel-title'>Vedi commenti</h3>
				<span class='pull-right clickable'><i id='c' class='glyphicon glyphicon-chevron-down'></i></span>
              </div>
              <div class='panel-body'>
                <div class='row'>
                      <div class='col-md-12'>
                          <input class='form-control' id='dev-table-filter' data-action='filter' data-filters='#mytbl' type='text' placeholder='Filter Posts...'>
                      </div>
                </div>
                <br>
				<br>
				<table id='mytbl' class='table table-striped table-hover'>
					<tr>
                <th>Autore</th>
                <th>Data</th>
                <th>Ora</th>
						<th></th>
          		</tr><tbody>";
						require("./config_sql.php");
						$result=$sql->ritorna_commenti();
						if($result->num_rows>0){
							while($row = mysqli_fetch_array($result)){
								$id=$row['Id_commento'];
								echo "
								<tr class='$id' >
									<td>".$row['Username'] ."</td>
									<td>".$row['Data']."</td>
									<td>".$row['Ora']."</td>
									<td><a class='btn btn-default guarda' data-toggle='modal' id='$id' data-target='#$id'>Guarda</a> <a id='$id' onclick='elimina_commento(event);' class='elimina btn btn-danger' >Elimina</a></td>
								</tr>

								<div class='modal fade' id='$id' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
								  <div class='modal-dialog' role='document'>
									<div class='modal-content'>
									  <form>
									  <div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
										<h4 class='modal-title' id='myModalLabel'>Commento di ".$row['Username']." </h4>
									  </div>
									  <div class='modal-body'>
										<div class='form-group'>
										  <label>Nome luogo</label>
										  <input type='text' readonly='readonly' value='".$row['Nome']."' class='form-control' placeholder='Page Title'>
										</div>
										<div class='form-group'>
										  <label>Testo</label>
										  <textarea  readonly='readonly' class='form-control' >".$row['Testo']."</textarea>
										</div>
									  </div>
									  <div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
									  </div>
									</form>
									</div>
								  </div>
								</div>

								";
							}
						}
				echo "</tbody>
				</table>
              </div>
              </div>
          </div>
        </div>
      </div>
    </section>";
    require("./Templates/footer.php");
	}else{
		echo "<center><h1>Error pagina non disponibile</h1></center>";
	}
	  ?>
  </body>
</html>
