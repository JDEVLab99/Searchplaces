<?php
include("../classi/Sql.php");
if(isset($_REQUEST['barre'])){
	require("./config_sql.php");
	$result=$sql->ritorna_ricerche();

	if($result->num_rows>0){
		$rows = array();
		$table=array();


		$table['cols']=array(

			array(
				'label' => 'Mese', //imposta le colonne
				'type' => 'string'
			),

			array(
				'label' => 'Ricerche',
				'type' => 'number'
			)

		);

		while($row = mysqli_fetch_array($result)){
			$temp=array();

			$temp[]=array(
					"v"=> (string) $row['data']//concatena alla riga il mese
			);

			$temp[]=array(
					"v"=> (int) $row['ricerche']//concatena alla riga il numero ricerche
			);

			$rows[]=array(
				"c"=>$temp//imposta la riga
			);
		}

		$table['rows']=$rows;//metti le righe nella tabella
		$json=json_encode($table);
		echo $json;
	}
}else if(isset($_REQUEST['regioni'])){
	require("./config_sql.php");
	$result=$sql->torna_ric_place();

	if($result->num_rows>0){
		$rows = array();
		$table=array();

		$table['cols']=array(

			array(
				'label' => 'Citta', //imposta le colonne
				'type' => 'string'
			),

			array(
				'label' => 'Ricerche',
				'type' => 'number'
			)

		);

		while($row = mysqli_fetch_array($result)){
			$temp=array();

			$temp[]=array(
					"v"=> (string) $row['place']//concatena alla riga il mese
			);

			$temp[]=array(
					"v"=> (int) $row['ricerche']//concatena alla riga il numero ricerche
			);

			$rows[]=array(
				"c"=>$temp//imposta la riga
			);
		}

		$table['rows']=$rows;//metti le righe nella tabella
		$json=json_encode($table);
		echo $json;
	}
}
else if(isset($_REQUEST['Agg_com'])){
	require("./config_sql.php");
	$result=$sql->return_n_comments();
	$sql->chiudi();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		$total=$row['total'];
		if($total==$_REQUEST['count'])
			echo "false";
		else
			exit($total);
	}else
		exit("false");
}else if(isset($_REQUEST['Agg_user'])){
	require("./config_sql.php");
	$result=$sql->return_n_users();
	$sql->chiudi();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		if($row['total']==$_REQUEST['count'])
			echo "false";
		else{
			exit($row['total']);
		}
	}else
		exit("false");
}else if (isset($_REQUEST['com'])){
	require("./config_sql.php");
	$result=$sql->return_n_comments();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		$total=$row['total'];
		if($total==$_REQUEST['count'])
			echo "false";
		else{
			$result=$sql->ritorna_commenti();
			if($result->num_rows>0){
				echo "
					<table id='mytbl' class='table table-striped table-hover'>
						<tr>
					<th>Autore</th>
					<th>Data</th>
					<th>Ora</th>
							<th></th>
					</tr>
				";
				$sql->chiudi();
				while($row=$result->fetch_assoc()){
					$id=$row['Id_commento'];
					echo "
					<tr class='$id'>
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
							</div>";
				}
			}else
				exit("false");
		}
	}else
		exit("false");
}else if(isset($_REQUEST['us'])){
	require("./config_sql.php");
	$result=$sql->return_n_users();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		$total=$row['total'];
		if($total==$_REQUEST['count'])
			echo "false";
		else{
			$result=$sql->ritorna_utenti();
			if($result->num_rows>0){
				echo "
					<table id='tbl_users' class='table table-striped table-hover'>
						<tr>
						<th>Nome</th>
						<th>Email</th>
						<th>Users</th>
						<th></th>
					</tr>
				";
				$sql->chiudi();
				while($row=$result->fetch_assoc()){
					echo "
					<tr id='".$row['Username']."'>
						<td>".$row['Nome']." ".$row['Cognome']."</td>
						<td>".$row['Email']."</td>
						<td>".$row['Username']."</td>
					</tr>";
				}
				echo "</table>";
			}else
				exit("false");
		}
	}else
		exit("false");
}else if(isset($_REQUEST['n_vis'])){
	require("./config_sql.php");
	$result=$sql->return_n_visitors();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		$total=$row['total'];
		$sql->chiudi();
		if($total==$_REQUEST['count'])
			echo "false";
		else{
			exit($total);
		}
	}
}else if(isset($_REQUEST['vis'])){
	require("./config_sql.php");
	$result=$sql->return_n_visitors();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		$total=$row['total'];
		if($total==$_REQUEST['count'])
			echo "false";
		else{
			$result=$sql->ritorna_visitatori();
			if($result->num_rows>0){
				echo "
					<table id='tbl_visitors' class='table table-striped table-hover'>
						<tr>
						<th>Id_visitatore</th>
						<th>Indirizzo_ip</th>
						<th>Porta</th>
						<th>Host</th>
						<th>Info</th>
						<th>Data</th>
						<th>Ora</th>
					</tr>
				";
				$sql->chiudi();
				while($row=$result->fetch_assoc()){
					echo "
					<tr id='".$row['Id_visitatore']."'>
						<td>".$row['Indirizzo_ip']."</td>
						<td>".$row['Porta']."</td>
						<td>".$row['Host']."</td>
						<td>".$row['Info']."</td>
						<td>".$row['Data']."</td>
						<td>".$row['Ora']."</td>
					</tr>";
				}
				echo "</table>";
			}else
				exit("false");
		}
	}else
		exit("false");
}else if(isset($_REQUEST['not'])){
	require("./config_sql.php");
	$result=$sql->stampa_richieste();
	
	if($result->num_rows>0){
		$result1=$sql->n_richieste();
		$sql->chiudi();
		$row=$result1->fetch_assoc();
		if($row['total']!=$_REQUEST['count']){
			echo  "<div id='notifiche' class='notifiche panel-group'>";
			while($row=$result->fetch_assoc()){
				echo "
					<div id='".$row['Id_richiesta']."' class='panel panel-danger notifica'>
					  <div class='panel-heading'>
							<h5>".$row['Tipo']." di ".$row['Username']." in data ".$row['Data']." alle ore ".$row['Ora']."</h5>
					  </div>
					  <div class='panel-body'>
						  <h5>".$row['Testo']."</h5>
					  </div>
					  <div class='panel-footer'>
						  <h5>Rispondi:</h5>

						  <textarea style='width: 100%;' type='text' id='rispo' class='".$row['Id_richiesta']."' name='rispo'></textarea><br><br>

						  <button name='".$row['Username']."' id='".$row['Id_richiesta']."' type='button' onclick='invia_notifica(event);' class='noti btn btn-primary'>Invia</button>
						  <input id='".$row['Id_richiesta']."' value='Ignora' type='button' onclick='elimina_notifica(event);' name='ignora' class='ignora btn btn-danger'></input>

					   </div>
					</div>";
			}
			echo "</div>";
		}else
			exit("false");
	}else{
		exit("false");
	}
}else if(isset($_REQUEST['n_not'])){
	require("./config_sql.php");
	$result=$sql->n_richieste();
	$sql->chiudi();
	if($result->num_rows>0){
		$row=$result->fetch_assoc();
		if($row['total']==$_REQUEST['count'])
			echo "false";
		else{
			exit($row['total']);
		}
	}
}else if(isset($_REQUEST['manda'])){
	require("./config_sql.php");
	$t=$sql->inserisci_risposta($_REQUEST['user'],$_REQUEST['risposta']);
	$sql->chiudi();
	if($t==true)
		echo "true";
	else
		echo "false";
}else if(isset($_REQUEST['ignora'])){
	require("./config_sql.php");
	$t=$sql->elimina_richiesta($_REQUEST['Id']);
	$sql->chiudi();
	if($t==true)
		exit("true");
	else
		exit("false");
}else if(isset($_REQUEST['elimina'])){
	require("./config_sql.php");
	$t=$sql->elimina_commento($_REQUEST['Id']);
	$sql->chiudi();
	if($t==true)
		exit("true");
	else
		exit("false");
}
?>
