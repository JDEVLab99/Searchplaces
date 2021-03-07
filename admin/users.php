<?php
session_start();
include("../classi/Sql.php");
?>
<!DOCTYPE html>
<html lang="en">
  <?php require("./Templates/head.php");?>
  <body>
	<script>
	<?php require('./requests_ajax.js');?>
	</script>
    <?php require("./Templates/header.php");?>
    <section id="main">
      <div class="container">
        <div class="row">
          <?php require("./Templates/barra_sinistra.php");?>
          <div class="col-md-9">
            <!-- Website Overview -->
            <div class="panel panel-default">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Utenti iscritti</h3>
				<span class='pull-right clickable'><i id='u' class='glyphicon glyphicon-chevron-down'></i></span>
              </div>
              <div class="panel-body">
                <div class="row">
                      <div class="col-md-12">
                          <input class="form-control" type="text" id='dev-table-filter' data-action='filter' data-filters='#tbl_users' placeholder="Filter Users...">
                      </div>
                </div>
                <br>
				<table id="tbl_users" class="table table-striped table-hover">
					<tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>User</th>
                        <th></th>
                    </tr>
					<tbody>
					<?php
						require("./config_sql.php");
						$result=$sql->ritorna_utenti();
						if($result->num_rows>0){
							while($row = mysqli_fetch_array($result)){
								echo "
								<tr id='".$row['Username']."'>
									<td>".$row['Nome']." ".$row['Cognome']."</td>
									<td>".$row['Email']."</td>
									<td>".$row['Username']."</td>
								</tr>";
							}
						}
					?>
					</tbody>
				</table>
              </div>
              </div>
			  </br>
			  <div class="panel panel-default">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Visitatori</h3>
				<span class='pull-right clickable'><i id='vi' class='glyphicon glyphicon-chevron-down'></i></span>
              </div>
              <div class="panel-body">
                <div class="row">
                      <div class="col-md-12">
                          <input class="form-control" type="text" id='dev-table-filter' data-action='filter' data-filters='#tbl_visitors' placeholder="Filter Users...">
                      </div>
                </div>
                <br>
				<table id='tbl_visitors' class="tbl-visitors table table-striped table-hover">
					<tr>
						<th>Id_visitatore</th>
                        <th>Indirizzo_ip</th>
                        <th>Porta</th>
                        <th>Host</th>
						<th>Info</th>
						<th>Data</th>
                        <th>Ora</th>
                    </tr>
					<?php
						$result=$sql->ritorna_visitatori();
						if($result->num_rows>0){
							while($row = mysqli_fetch_array($result)){
								echo "
								<tr id='".$row['Id_visitatore']."'>
									<td>".$row['Id_visitatore']."</td>
									<td>".$row['Indirizzo_ip']."</td>
									<td>".$row['Porta']."</td>
									<td>".$row['Host']."</td>
									<td>".$row['Info']."</td>
									<td>".$row['Data']."</td>
									<td>".$row['Ora']."</td>
								</tr>";
							}
						}
						$sql->chiudi();
					?>
				</table>
              </div>
              </div>
          </div>
        </div>
      </div>
    </section>
    <?php require("./Templates/footer.php");?>
  </body>
</html>
