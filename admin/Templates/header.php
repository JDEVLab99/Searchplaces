<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="page.php">AdminStrap</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
		  <?php
			$resource=$_SERVER['REQUEST_URI'];
			$temp=explode("/",$resource);
			if($temp[3]=="page.php"){
				$flag="page";
				echo "
				<ul class='nav navbar-nav'>
				<li class='active'><a href='page.php'>Dashboard</a></li>
				<li><a href='posts.php'>Commenti</a></li>
				<li><a href='users.php'>Utenti</a></li>
          		</ul>";

			}else if($temp[3]=="posts.php"){
				$flag="post";
				echo "
				<ul class='nav navbar-nav'>
				<li><a href='page.php'>Dashboard</a></li>
				<li class='active'><a href='posts.php'>Commenti</a></li>
				<li><a href='users.php'>Utenti</a></li>
          		</ul>";


			}else if($temp[3]=="users.php"){
				$flag="users";
				echo "
				<ul class='nav navbar-nav'>
				<li><a href='page.php'>Dashboard</a></li>
				<li><a href='posts.php'>Commenti</a></li>
				<li class='active'><a href='users.php'>Utenti</a></li>
          		</ul>";
			}else{
				$flag="index";
				echo "
				<ul class='nav navbar-nav'>
				<li class='active'><a href='page.php'>Dashboard</a></li>
				<li><a href='posts.php'>Commenti</a></li>
				<li><a href='users.php'>Utenti</a></li>
          		</ul>";
			}
		  ?>
		  <form method='post'>
          <ul class="nav navbar-nav navbar-right">

			<li><a href="#"><?php echo "Benvenuto " . $_SESSION['nome'] . "," . $_SESSION['cognome'] ?></a></li>
			<li><a id='logout' type='submit' name='logout' href="">Logout</a></li>

          </ul>
		  </form>
        </div>
      </div>
</nav>

<header id="header">
  <div class="container">
	<div class="row">
	  <div class="col-md-10">
		<h1><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Dashboard <small>Manage Your Site</small></h1>
	  </div>
	</div>
  </div>
</header>

<section id="breadcrumb">
  <div class="container">
	<?php
	  if($flag=="page"){
		  echo "
		  <ol class='breadcrumb'>
	  		<li class='active'>Dashboard</li>
		  </ol>";
	  }else if($flag=="post"){
		  echo "
		  <ol class='breadcrumb'>
	  		<li class='active'>Commenti</li>
		  </ol>";

	  }else if($flag=="users"){
		  echo "
		  <ol class='breadcrumb'>
	  		<li class='active'>Utenti</li>
		  </ol>";
	  }else{
		  echo "
		  <ol class='breadcrumb'>
	  		<li class='active'>Dashboard</li>
		  </ol>";
	  }
	?>
  </div>
</section>
