<?php
  //STAMPA COMMENTI
  echo "<p class='commenti' ><span id='" . count($commenti) . "' class='badge'>".count($commenti)."</span> Commenti:</p><br><div class='panel-group'>";
  for($i=0;$i<count($commenti);$i++){
	  //CONTROLLA SE E' L'UTENTE CHE SI E' LOGGATO OPPURE UN ALTRO
	  if($commenti[$i]['Username']==$_SESSION["User"])
		  $temp = "Commento effettuato da te <button id='" .$commenti[$i]['id'] . "' type='button' class='close' aria-hidden='true'>&times;</button>";
	  else
		  $temp = "Commento effettuato da: " . $commenti[$i]['Username'];

	  echo "
	  <div id='" . $commenti[$i]['id'] . "' class='panel panel-default'>
		  <div class='panel-heading'>".$temp."<p><strong> in data " . $commenti[$i]['Data']. " alle ore " . $commenti[$i]['Ora'] ."</strong></p></div>
		  <div class='panel-body'> " . $commenti[$i]['testo'] . "</div>
		  <div class='panel-footer'></div>
	  </div>";
  }
  echo "</div>";
?>