<?php
include("./classi/Sql.php");
//CONTROLLA REGISTRAZIONE

if(isset($_REQUEST['inserisci'])){
	require("./config_sql.php");
	if($sql->inserisci_user($_REQUEST['user'],$_REQUEST['email'],$_REQUEST['nome'],$_REQUEST['cognome'],$_REQUEST['password']))
		exit("Utente registrato correttamente!");
	else
		exit("Utente NON registrato!");
	$sql->chiudi();
}

function isset_username($username){
	require("./config_sql.php");
    $username = trim($username);
	if($sql->controlla_user($username)==true)//ritorna true nel momento in cui trova nel database uno user con lo stesso user
		return true;
	else
		return false;
	$sql->chiudi();
}

function isset_email($email){
	require("./config_sql.php");
	$email = trim($email);
	if($sql->controlla_email($email)==true)
		return true;//true se utente esiste
	else
		return false;
	$sql->chiudi();
}
		

if(isset($_REQUEST['email'])){
	if(!isset_email($_REQUEST['email']))
		echo 'true';//fai comparire il messaggio
	else
		echo 'false';
     	//$e=false;
}else if(isset($_REQUEST['user'])){
	if(!isset_username($_REQUEST['user']))
		echo 'true';//fai comparire il messaggio
	else
		echo 'false';
}

?>