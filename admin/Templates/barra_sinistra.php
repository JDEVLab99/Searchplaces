<div class="col-md-3">
<div class="list-group">
  <a href="page.php" class="list-group-item active main-color-bg">
	<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Dashboard
  </a>
  <?php
	require("../config_sql.php");
	$result=$sql->return_n_comments();
	$row=$result->fetch_assoc();
	$n_com=$row['total'];
	$result=$sql->return_n_users();
	$row=$result->fetch_assoc();
	$n_users=$row['total'];
	$result=$sql->return_n_visitors();
	$row=$result->fetch_assoc();
	$visitors=$row['total'];
	$result=$sql->ritorna_visite();
	$row=$result->fetch_assoc();
	$visite=$row['total'];
	$result=$sql->n_richieste();
	$row=$result->fetch_assoc();
	$news=$row['total'];
	$result=$sql->stampa_richieste();
  ?>
  <div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Notifiche</h5>
		<span class="pull-right clickable"><i id='no' class="glyphicon glyphicon-chevron-down"></i></span>
	</div> 
	<div class="panel-body">
		<a href="page.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Notifiche <span id='<?php echo $news ?>' class="badge news"><?php echo $news ?></span></a>
	</div>  
  </div>
  <div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Commenti</h5>
		<span class="pull-right clickable"><i id='co' class="glyphicon glyphicon-chevron-down"></i></span>
	</div>
	<div class="panel-body">
		<a href="posts.php" class="list-group-item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Commenti <span id='<?php echo $n_com ?>' class="badge com"><?php echo $n_com ?></span></a>
	</div>
  </div>
  <div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Utenti</h5>
		<span class="pull-right clickable"><i id='us' class="glyphicon glyphicon-chevron-down"></i></span>
	</div> 
	<div class="panel-body">
		<a href="users.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Utenti <span id='<?php echo $n_users ?>' class="badge users"><?php echo $n_users ?></span></a>
	</div>  
  </div>
  <div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Visitatori</h5>
		<span class="pull-right clickable"><i id='vis' class="glyphicon glyphicon-chevron-down"></i></span>
	</div> 
	<div class="panel-body">
		<a href="users.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Visitatori <span id='<?php echo $visitors ?>' class="badge visitors"><?php echo $visitors ?></span></a>
	</div>  
  </div>
</div>
</div>
