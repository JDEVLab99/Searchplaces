<?php

class Sql{
	private $host;
	private $user;
	private $pwd;
	private $dbname;
	private $connect;

	function __construct($h,$u,$p,$d){
		$this->set_host($h);
		$this->set_user($u);
		$this->set_pwd($p);
		$this->set_dbname($d);
		$this->connect = new mysqli($this->host,$this->user,$this->pwd,$this->dbname);
	}

	function set_host($h){
		if(isset($h))
			$this->host=$h;
	}

	function set_user($u){
		if(isset($u))
			$this->user=$u;
	}

	function set_pwd($p){
		$this->pwd=$p;
	}

	function set_dbname($d){
		if(isset($d))
			$this->dbname=$d;
	}

	function connessione(){
	    $this->connect = new mysqli($this->host,$this->user,$this->pwd,$this->dbname);
		if(mysqli_connect_errno())
			return "<p>ERRORE DI CONNESSIONE AL DATABASE</p>";
	}

	function crea_database(){
		$comando="CREATE DATABASE IF NOT EXISTS progetto_esame";
	    if($this->connect->query($comando)==TRUE)
			return true;
	}
	function crea_tbl_utenti(){
	    $comando="CREATE TABLE IF NOT EXISTS UTENTI(
		Username VARCHAR(20) NOT NULL,
	    Email VARCHAR(20) NOT NULL,
	    Nome VARCHAR(20) NOT NULL,
	    Cognome VARCHAR(20) NOT NULL,
	    Password VARCHAR(200) NOT NULL,
	    PRIMARY KEY(Username)
	    )";
	    if($this->connect->query($comando)==TRUE)
			return true;
	}

	function crea_tbl_cookie(){
		$comando="CREATE TABLE IF NOT EXISTS COOKIE(
		Username VARCHAR(20) NOT NULL,
		SessionId VARCHAR(20) NOT NULL,
		Token VARCHAR(20) NOT NULL,
		PRIMARY KEY(SessionId),
		CONSTRAINT Chiaveesterna15 FOREIGN KEY(Username) REFERENCES UTENTI(Username)
		)";
		if($this->connect->query($comando)==TRUE)
			return true;
	}

	function crea_tbl_richieste_user(){
		$comando="CREATE TABLE IF NOT EXISTS RICHIESTE_USER(
		Id_richiesta MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
		Tipo VARCHAR(30) NOT NULL,
		Testo TEXT NOT NULL,
		Username VARCHAR(25) NOT NULL,
		Data DATE NOT NULL,
		Ora TIME NOT NULL,
		PRIMARY KEY(Id_richiesta),
		CONSTRAINT Chiaveesterna18 FOREIGN KEY(Username) REFERENCES UTENTI(Username)
		)";
		if($this->connect->query($comando)==TRUE)
			return true;
	}

	function crea_tbl_risposte_user(){
		$comando="CREATE TABLE IF NOT EXISTS RISPOSTE_ADMIN(
		Id_risposta MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
		Username VARCHAR(25) NOT NULL,
		Testo TEXT NOT NULL,
		Data DATE NOT NULL,
		Ora TIME NOT NULL,
		PRIMARY KEY(Id_risposta),
		CONSTRAINT Chiaveesterna19 FOREIGN KEY(Username) REFERENCES UTENTI(Username)
		)";
		if($this->connect->query($comando)==TRUE)
			return true;
	}

	function crea_tbl_ricerca(){
	    $comando="CREATE TABLE IF NOT EXISTS RICERCA(
		Id_ricerca MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
	    Username VARCHAR(20) NOT NULL,
	    Data DATE NOT NULL,
		Ora TIME NOT NULL,
		Query VARCHAR(30) NOT NULL,
	    Id_luogo MEDIUMINT(5) NOT NULL,
		PRIMARY KEY(Id_ricerca),
	    CONSTRAINT Chiaveesterna FOREIGN KEY(Username) REFERENCES UTENTI(Username),
	    CONSTRAINT Chiaveesterna1 FOREIGN KEY(Id_luogo) REFERENCES LUOGHI(Id_luogo)
	    )";
	    if($this->connect->query($comando)==TRUE)
			return true;
	}

	function crea_tbl_luoghi(){
	    $comando="CREATE TABLE IF NOT EXISTS LUOGHI(
	    Id_luogo MEDIUMINT(5) AUTO_INCREMENT NOT NULL,
	    Luogo VARCHAR(30) NOT NULL,
		Provincia VARCHAR(20) NOT NULL,
		Cap VARCHAR(10) NOT NULL,
		Regione VARCHAR(30) NOT NULL,
		Stato VARCHAR(30) NOT NULL,
		Ricercato VARCHAR(30) NOT NULL,
	    PRIMARY KEY(Id_luogo)
	    )";
	    if($this->connect->query($comando)==true)
			return true;
	}

	function crea_tbl_dati(){
	    $comando="CREATE TABLE IF NOT EXISTS DATI(
	    Id_dato MEDIUMINT(5) AUTO_INCREMENT NOT NULL,
		Id_categoria MEDIUMINT(5) NOT NULL,
	    Nome VARCHAR(35) NOT NULL,
	    Indirizzo VARCHAR(35) NOT NULL,
		Civico VARCHAR(7) NOT NULL,
	    Latitudine VARCHAR(50) NOT NULL,
	    Longitudine VARCHAR(50) NOT NULL,
		Telefono VARCHAR(20) NOT NULL,
		Rating DECIMAL(8,2) NOT NULL,
	    Immagine TEXT NOT NULL,
	    Immagini TEXT NOT NULL,
		Orario TEXT NOT NULL,
		Descrizione TEXT NOT NULL,
	    PRIMARY KEY(Id_dato),
		CONSTRAINT Chiaveesterna10 FOREIGN KEY(Id_categoria) REFERENCES CATEGORIA(Id_categoria) ON DELETE CASCADE
		ON UPDATE CASCADE
	    )";
	    if($this->connect->query($comando)==true)
			return true;
	}

	function crea_tbl_categoria(){
		$comando="CREATE TABLE IF NOT EXISTS CATEGORIA(
		Id_categoria MEDIUMINT(5) AUTO_INCREMENT NOT NULL,
		Nome VARCHAR(20) NOT NULL,
		PRIMARY KEY(Id_categoria)
		)";
		if($this->connect->query($comando)==true)
			return true;
	}

	function crea_tbl_luoghidati(){
	    $comando="CREATE TABLE IF NOT EXISTS LUOGHIDATI(
	    Id_luogo MEDIUMINT(5) NOT NULL,
	    Query VARCHAR(20) NOT NULL,
	    Id_dato MEDIUMINT(5) NOT NULL UNIQUE,
	    CONSTRAINT Chiaveesterna2 FOREIGN KEY(Id_dato) REFERENCES DATI(Id_dato) ON DELETE CASCADE
		ON UPDATE CASCADE,
	    CONSTRAINT Chiaveesterna3 FOREIGN KEY(Id_luogo) REFERENCES LUOGHI(Id_luogo) ON DELETE CASCADE
		ON UPDATE CASCADE
	    )";
	    if($this->connect->query($comando)==true)
			return true;
	}

	function crea_tbl_commenti(){
		$comando="CREATE TABLE IF NOT EXISTS COMMENTI(
	    Id_commento MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
	    Testo TEXT NOT NULL,
		Data DATE NOT NULL,
		Ora TIME NOT NULL,
	    Id_dato MEDIUMINT(5) NOT NULL,
		Username VARCHAR(20) NOT NULL,
		PRIMARY KEY(Id_commento),
	    CONSTRAINT Chiaveesterna4 FOREIGN KEY(Id_dato) REFERENCES DATI(Id_dato),
		CONSTRAINT Chiaveesterna5 FOREIGN KEY(Username) REFERENCES UTENTI(Username)
	    )";
		if($this->connect->query($comando)==true)
			return true;
	}


	function crea_tbl_preferiti(){
		$comando="CREATE TABLE IF NOT EXISTS PREFERITI(
	    Id_preferito MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
	    Id_dato MEDIUMINT(5) NOT NULL,
		Username VARCHAR(20) NOT NULL,
		PRIMARY KEY(Id_preferito),
	    CONSTRAINT Chiaveesterna6 FOREIGN KEY(Id_dato) REFERENCES DATI(Id_dato),
		CONSTRAINT Chiaveesterna7 FOREIGN KEY(Username) REFERENCES UTENTI(Username)

	    )";
		if($this->connect->query($comando)==true)
			return true;
	}

	function crea_tbl_visitatori(){
		$comando="CREATE TABLE IF NOT EXISTS VISITATORI(
	    Id_visitatore MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
	    Indirizzo_ip VARCHAR(20) NOT NULL,
		Porta VARCHAR(8) NOT NULL,
		Host VARCHAR(30) NOT NULL,
		Info VARCHAR(150) NOT NULL,
		Data DATE NOT NULL,
		Ora TIME NOT NULL,
		PRIMARY KEY(Id_visitatore)
	    )";
		if($this->connect->query($comando)==true)
			return true;
	}

	function controlla_elementi($p,$q){
		mysqli_escape_string($this->connect,$p);
		mysqli_escape_string($this->connect,$q);
		$query=null;
		$comando = "SELECT Luogo, Query, Ricercato FROM LUOGHIDATI JOIN LUOGHI ON LUOGHI.Id_luogo=LUOGHIDATI.Id_luogo WHERE ((LUOGHI.Luogo LIKE '%".$p."%' OR LUOGHI.Provincia LIKE '%".$p."%' OR LUOGHI.Ricercato LIKE '%".$p."%') AND LUOGHIDATI.Query LIKE '%".$q."%')";
		$result=$this->connect->query($comando);
		if($result->num_rows > 0){
			 $row = $result->fetch_assoc();

			 return $row['Luogo'];
		}else{
			 return false;
		}
	}

	function controlla_luogo($luogo,$citta){
	   mysqli_escape_string($this->connect,$luogo);
	   mysqli_escape_string($this->connect,$citta);
	   $comando = "SELECT Luogo AS luogo FROM LUOGHI WHERE (Luogo LIKE '%".$luogo."%' OR Luogo LIKE '%".$citta."%') OR (Provincia LIKE '%".$luogo."%' OR Provincia LIKE '%".$citta."%') OR (Ricercato LIKE '%".$luogo."%' OR Ricercato LIKE '%".$citta."%')";
	   $result=$this->connect->query($comando);
		if($result->num_rows>0)
		  	return true;
	    else
		  	return false;
	}


	function restituisci_risultati($p,$q,$start,$tot){
		mysqli_escape_string($this->connect,$p);
		mysqli_escape_string($this->connect,$q);
		mysqli_escape_string($this->connect,$tot);
		mysqli_escape_string($this->connect,$start);
		$comando = "SELECT DISTINCT * FROM DATI JOIN LUOGHIDATI ON DATI.Id_dato=LUOGHIDATI.Id_dato JOIN LUOGHI ON LUOGHIDATI.Id_luogo=LUOGHI.Id_luogo WHERE LUOGHI.Ricercato LIKE '%".$p."%' AND LUOGHIDATI.Query='".$q."'  LIMIT ".($start-1). ",". $tot . "";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return mysqli_error($this->connect);
	}
	
	function inserisci_luogo($citta,$provincia,$regione,$stato,$cap,$ricercato){
		mysqli_escape_string($this->connect,$citta);
		mysqli_escape_string($this->connect,$provincia);
		mysqli_escape_string($this->connect,$regione);
		mysqli_escape_string($this->connect,$stato);
		mysqli_escape_string($this->connect,$cap);
		mysqli_escape_string($this->connect,$ricercato);
	    $comando = "INSERT INTO LUOGHI(Luogo,Provincia,Cap,Regione,Stato,Ricercato) VALUES ('".$citta."','".$provincia."','".$cap."','".$regione."','".$stato."','".$ricercato."')";
		if($this->connect->query($comando)==false)
			return "RECORD NON CREATO";
	}

	function inserisci_user($username,$Email,$Nome,$Cognome,$Password){
		mysqli_escape_string($this->connect,$username);
		mysqli_escape_string($this->connect,$Email);
		mysqli_escape_string($this->connect,$Nome);
		mysqli_escape_string($this->connect,$Cognome);
		mysqli_escape_string($this->connect,$Password);
		$pass = password_hash($Password, PASSWORD_BCRYPT);
	    $comando = "INSERT INTO UTENTI(Username,Email,Nome,Cognome,Password) VALUES ('".$username."','".$Email."','".$Nome."','".$Cognome."','".$pass."')";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	function preleva_cookie($id){
		mysqli_escape_string($this->connect,$id);
		$comando = " SELECT * FROM COOKIE WHERE SessionId = '".$id."'";
		$result = $this->connect->query($comando);
		if ($result->num_rows > 0)
			return true;
		else
			return false;
	}

	function ritorna_cookie($id){
		mysqli_escape_string($this->connect,$id);
		$comando = " SELECT * FROM COOKIE WHERE SessionId = '".$id."'";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
	}

	function inserisci_cookie($id,$token,$user){
		mysqli_escape_string($this->connect,$id);
		$comando = "INSERT INTO COOKIE (SessionId,Token,Username) VALUES ('".$id."','".$token."','".$user."')";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	function elimina_cookie($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM COOKIE WHERE SessionId='".$id."'";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
	}

	function ritorna_user($user){
		$comando = "SELECT Nome,Username,Password FROM UTENTI WHERE Username='".$user."'";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
	}

	function aggiorna_pass($pass,$user){
		mysqli_escape_string($this->connect,$pass);
		mysqli_escape_string($this->connect,$user);
		$pass = password_hash($pass, PASSWORD_BCRYPT);
		$comando1="SELECT * FROM UTENTI WHERE Username='".$user."' FOR UPDATE";
		$comando="UPDATE UTENTI SET Password='".$pass."' WHERE Username='".$user."'";
		mysqli_autocommit($this->connect,FALSE);
		mysqli_query($this->connect,"START TRANSACTION");
		$result = mysqli_query($this->connect,$comando1);
		if($result->num_rows>0){
			if(mysqli_query($this->connect,$comando)){
				mysqli_commit($this->connect);
				return true;
			}
			else{
				mysqli_rollback($this->connect);
				return false;
			}
		}else{
			mysqli_rollback($this->connect);
			return false;
		}
	}

	function controlla_user($username){
		mysqli_escape_string($this->connect,$username);
		$comando = "SELECT COUNT(*) AS num FROM UTENTI WHERE Username='" .$username. "'";
		$result = mysqli_query($this->connect,$comando) or die(mysqli_error($this->connect));
    	$row = $result->fetch_assoc();
		if($row['num']>=1)
			return true;
		else
			return false;
	}

	function controlla_email($email){
		mysqli_escape_string($this->connect,$email);
		$comando = "SELECT COUNT(*) AS num FROM UTENTI WHERE Email='" .$email. "'";
		$result = mysqli_query($this->connect,$comando) or die(mysqli_error($this->connect));
    	$row = $result->fetch_assoc();
		if($row['num']>=1)
			return true;
		else
			return false;
	}

	function inserisci_dato($nome,$indirizzo,$civico,$latitudine,$longitudine,$telefono,$id_cat,$rating,$immagine,$immagini,$orario,$descrizione){
		mysqli_escape_string($this->connect,$nome);
		mysqli_escape_string($this->connect,$indirizzo);
		mysqli_escape_string($this->connect,$civico);
		mysqli_escape_string($this->connect,$telefono);
		mysqli_escape_string($this->connect,$immagine);
		mysqli_escape_string($this->connect,$immagini);
		mysqli_escape_string($this->connect,$orario);
		mysqli_escape_string($this->connect,$descrizione);
	    $comando = "INSERT INTO DATI(Nome,Indirizzo,Civico,Latitudine,Longitudine,Telefono,Id_categoria,Rating,Immagine,Immagini,Orario,Descrizione) VALUES ('".$nome."','".$indirizzo."','".$civico."','".$latitudine."','".$longitudine."','".$telefono."','".$id_cat."','".$rating."','".$immagine."','".$immagini."','".$orario."','".$descrizione."')";

		if($this->connect->query($comando)==false)
			return mysqli_error($this->connect);
	}

	function controlla_categoria($nome){
	   mysqli_escape_string($this->connect,$nome);
	   $comando = "SELECT Nome FROM CATEGORIA WHERE Nome='".$nome."'";
	   $result=$this->connect->query($comando);
		if($result->num_rows > 0){
    	   $row = $result->fetch_assoc();
			if($row['Nome']==$nome)
				return true;
			else
				return false;
		}
		else
			return false;
	}

	function restituisci_id_cat($nome){
	   mysqli_escape_string($this->connect,$nome);
	   $comando = "SELECT Id_categoria, Nome FROM CATEGORIA WHERE Nome='".$nome."'";
	   $result=$this->connect->query($comando);
		if($result->num_rows > 0){
    	   $row = $result->fetch_assoc();
			if($row['Nome']==$nome)
				return $row['Id_categoria'];
			else
				return false;
		}
		else
			return false;
	}

	function inserisci_categoria($nome){
		mysqli_escape_string($this->connect,$nome);
		$comando="INSERT INTO CATEGORIA(Nome) VALUES ('".$nome."')";
		if($this->connect->query($comando)==false)
			return "RECORD NON CREATO";
	}

	function inserisci_luoghidati($query,$luogo){
		mysqli_escape_string($this->connect,$query);
		mysqli_escape_string($this->connect,$luogo);
	    $comando = "INSERT INTO LUOGHIDATI(Id_luogo,Query,Id_dato) VALUES ((SELECT Id_luogo FROM LUOGHI WHERE (LUOGHI.Ricercato LIKE '%".$luogo."%')),'".$query."',(SELECT MAX(Id_dato) FROM DATI))";
	    if($this->connect->query($comando)==false)
			return mysqli_error($this->connect);

	}

	function restituisci_venues($p,$q){
		mysqli_escape_string($this->connect,$p);
		mysqli_escape_string($this->connect,$q);
	    $comando = "SELECT DISTINCT * FROM DATI JOIN LUOGHIDATI ON DATI.Id_dato=LUOGHIDATI.Id_dato JOIN LUOGHI ON LUOGHIDATI.Id_luogo=LUOGHI.Id_luogo WHERE LUOGHI.Ricercato='".$p."' AND LUOGHIDATI.Query='".$q."' ";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
	}

	function restituisci_venue($id){
		mysqli_escape_string($this->connect,$id);
	    $comando = " SELECT DATI.Nome, DATI.Telefono, DATI.Latitudine, DATI.Longitudine, DATI.Immagini, DATI.Immagine, DATI.Indirizzo, DATI.Orario, DATI.Descrizione, DATI.Civico, CATEGORIA.Nome AS cate, LUOGHI.Luogo, LUOGHI.Stato, LUOGHI.Regione, LUOGHI.Provincia, LUOGHI.Cap FROM DATI JOIN CATEGORIA ON CATEGORIA.Id_categoria=DATI.Id_categoria JOIN LUOGHIDATI ON LUOGHIDATI.Id_dato=DATI.Id_dato JOIN LUOGHI ON LUOGHIDATI.Id_luogo=LUOGHI.Id_luogo WHERE DATI.Id_dato='".$id."'";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
	}

	function confronta($user,$password){
		mysqli_escape_string($this->connect,$user);
		mysqli_escape_string($this->connect,$password);
	    $comando = "SELECT Username,Password,Nome FROM UTENTI WHERE Username='".$user."' ";
	    $result=$this->connect->query($comando);
		if($result->num_rows > 0){
    	   $row = $result->fetch_assoc();

    	   if(($row['Username']==$user)&&(password_verify($password, $row['Password'])))
		        return $row['Nome'].";".$row['Username'];
		   else
		        return false;
		}
		else
		    return false;
	}

	function cronologia($user,$query,$luogo){
		mysqli_escape_string($this->connect,$user);
		mysqli_escape_string($this->connect,$query);
		mysqli_escape_string($this->connect,$luogo);
		$comando = "INSERT INTO RICERCA(Username,Data,Ora,Query,Id_luogo) VALUES ('".$user."',NOW(),NOW(),'".$query."',(SELECT Id_luogo FROM LUOGHI WHERE Luogo LIKE '%".$luogo."%' OR Provincia LIKE '%".$luogo."%' OR Ricercato LIKE '%".$luogo."%'))";
	    if($this->connect->query($comando)==false)
			return "RECORD NON CREATO";
	}

	function inserisci_commento($id,$user,$testo){
		mysqli_escape_string($this->connect,$user);
		mysqli_escape_string($this->connect,$testo);
		mysqli_escape_string($this->connect,$id);
		$comando = "INSERT INTO COMMENTI(Testo,Id_dato,Data,Ora,Username) VALUES ('".$testo."','".$id."',NOW(),NOW(),'".$user."')";
		if($this->connect->query($comando)==false)
			return false;
	}

	function inserisci_preferito($id,$user){
		mysqli_escape_string($this->connect,$user);
		mysqli_escape_string($this->connect,$id);
		$comando = "INSERT INTO PREFERITI(Id_dato,Username) VALUES ('".$id."','".$user."')";
		if($this->connect->query($comando)==false)
			return "RECORD NON CREATO";
	}

	function restituisci_commenti($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "SELECT * FROM COMMENTI WHERE Id_dato='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
		else
			return $this->connect->query($comando);
	}

	function elimina_commento($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM COMMENTI WHERE Id_commento='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
		else 
			return true;
	}

	function controlla_preferito($id,$user){
		mysqli_escape_string($this->connect,$id);
		mysqli_escape_string($this->connect,$user);
		$comando = "SELECT COUNT(*) AS num FROM PREFERITI WHERE Id_dato='".$id."' AND Username='".$user."'";
		$result = mysqli_query($this->connect,$comando) or die(mysqli_error($this->connect));
    	$row = $result->fetch_assoc();
		if($row['num']>=1)
			return true;
		else
			return false;
	}

	function stampa_ricerche($user){
		mysqli_escape_string($this->connect,$user);
		$comando = "SELECT * FROM RICERCA JOIN LUOGHI ON RICERCA.Id_luogo=LUOGHI.Id_luogo WHERE Username='".$user."' ORDER BY RICERCA.Data DESC, RICERCA.Ora DESC ";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function cancella_ricerca($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM RICERCA WHERE Id_ricerca='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
	}

	function cancella_preferito($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM PREFERITI WHERE Id_preferito='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
	}

	function stampa_preferiti($user){
		mysqli_escape_string($this->connect,$user);
		$comando = "SELECT * FROM PREFERITI JOIN DATI ON PREFERITI.Id_dato=DATI.Id_dato JOIN LUOGHIDATI ON LUOGHIDATI.Id_dato=DATI.Id_dato JOIN LUOGHI ON LUOGHI.Id_luogo=LUOGHIDATI.Id_luogo WHERE Username='".$user."' ORDER BY Id_preferito DESC";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function elimina_cronologia(){
		$comando = "DELETE FROM RICERCA";
		if($this->connect->query($comando)==false)
			return false;
	}

	function elimina_preferiti(){
		$comando = "DELETE FROM PREFERITI";
		if($this->connect->query($comando)==false)
			return false;
	}

	function controlla_posto($name){
		mysqli_escape_string($this->connect,$name);
		$comando = "SELECT Id_dato AS id FROM DATI WHERE Nome='" .$name. "'";
		$result = mysqli_query($this->connect,$comando) or die(mysqli_error($this->connect));
    	$row = $result->fetch_assoc();
		if($row['id']!=null)
			return $row['id'];
		else
			return false;
	}

	function chiudi(){
		$this->connect->close();
	}

	function inserisci_richiesta($tipo,$testo,$user){
		mysqli_escape_string($this->connect,$tipo);
		mysqli_escape_string($this->connect,$testo);
		mysqli_escape_string($this->connect,$user);
		$comando = "INSERT INTO RICHIESTE_USER(Tipo,Testo,Username,Data,Ora) VALUES ('$tipo','$testo','$user',NOW(),NOW())";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	//FUNZIONI LATO AMMINISTRATORE -----------------------------------------------------------------------------------------
	function ritorna_ricerche(){
		$comando="SELECT DATE_FORMAT(Data,'%M') AS data, COUNT(*) AS ricerche FROM RICERCA GROUP BY MONTH(Data)";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function torna_ric_place(){
		$comando="SELECT LUOGHI.Ricercato AS place, COUNT(DATI.Id_dato) AS ricerche FROM LUOGHI JOIN LUOGHIDATI ON LUOGHI.Id_luogo=LUOGHIDATI.Id_luogo JOIN DATI ON DATI.Id_dato=LUOGHIDATI.Id_dato GROUP BY LUOGHI.Luogo ";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function ritorna_utenti(){
		$comando="SELECT*FROM UTENTI";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function ritorna_commenti(){
		$comando="SELECT*FROM COMMENTI JOIN DATI ON COMMENTI.Id_dato=DATI.Id_dato ORDER BY Id_commento DESC";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function ritorna_num_comm($id){
		mysqli_escape_string($this->connect,$id);
		$comando="SELECT COUNT(*) AS num FROM COMMENTI WHERE Id_dato='".$id."'";
		if($this->connect->query($comando)==TRUE)
			return $this->connect->query($comando);
		else
			return false;
	}

	function inserisci_bloccato($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "INSERT INTO BLOCCATI(Username,Email,Nome,Cognome,Password) SELECT * FROM UTENTI WHERE Username='".$id."'";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	function return_n_comments(){
		$comando = "SELECT COUNT(*) AS total FROM COMMENTI";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function return_n_users(){
		$comando = "SELECT COUNT(*) AS total FROM UTENTI";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function inserisci_visitatore($ip,$port,$host,$info){
		mysqli_escape_string($this->connect,$ip);
		mysqli_escape_string($this->connect,$port);
		mysqli_escape_string($this->connect,$host);
		mysqli_escape_string($this->connect,$info);
		$comando = "INSERT INTO VISITATORI(Indirizzo_ip,Porta,Host,Info,Data,Ora) VALUES('$ip','$port','$host','$info',NOW(),NOW())";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	function return_n_visitors(){
		$comando = "SELECT COUNT(DISTINCT Indirizzo_ip) AS total FROM VISITATORI";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function ritorna_visitatori(){
		$comando = "SELECT * FROM VISITATORI GROUP BY Indirizzo_ip ORDER BY Id_visitatore DESC";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function ritorna_visite(){
		$comando = "SELECT COUNT(*) AS total FROM VISITATORI";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function n_richieste(){
		$comando = "SELECT COUNT(*) AS total FROM RICHIESTE_USER";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function stampa_richieste(){
		$comando = "SELECT*FROM RICHIESTE_USER ORDER BY Id_richiesta DESC";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}

	function inserisci_risposta($user,$testo){
		mysqli_escape_string($this->connect,$user);
		mysqli_escape_string($this->connect,$testo);
		$comando = "INSERT INTO RISPOSTE_ADMIN(Username,Testo,Data,Ora) VALUES('$user','$testo',NOW(),NOW())";
		if($this->connect->query($comando))
			return true;
		else
			return false;
	}

	function elimina_richiesta($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM RICHIESTE_USER WHERE Id_richiesta='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
		else
			return true;
	}
	
	function stampa_risposte($user){
		mysqli_escape_string($this->connect,$user);
		$comando = "SELECT*FROM RISPOSTE_ADMIN WHERE Username='$user' ORDER BY Id_risposta DESC";
		if($this->connect->query($comando))
			return $this->connect->query($comando);
		else
			return false;
	}
	
	function cancella_risposta($id){
		mysqli_escape_string($this->connect,$id);
		$comando = "DELETE FROM RISPOSTE_ADMIN WHERE Id_risposta='".$id."'";
		if($this->connect->query($comando)==false)
			return false;
		else
			return true;
		
	}
	
	function elimina_risposte(){
		$comando = "DELETE FROM RISPOSTE_ADMIN ";
		if($this->connect->query($comando)==false)
			return false;
		else
			return true;
	}
};
?>
