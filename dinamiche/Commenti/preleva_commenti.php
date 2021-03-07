<?php
	$commenti = array();
	$result=$sql->restituisci_commenti($_SESSION['id']);
	if($result->num_rows > 0){
		 while($row = $result->fetch_assoc())
			 $commenti[] = array("id" => $row['Id_commento'], "testo" => $row['Testo'], "Id_dato" => $row['Id_dato'], "Username" => $row['Username'], "Data" => $row['Data'], "Ora" => $row['Ora']);

	}
	$commenti = array_reverse($commenti);
?>