<?php
include("./classi/Sql.php");
session_start();
if(isset($_REQUEST['Elimina'])){
	require("./config_sql.php");
	if($sql->cancella_ricerca($_REQUEST['Id'])!=false)
		exit("cancellato");
	$sql->chiudi();
	
}
else if(isset($_REQUEST['tutte'])){
	require("./config_sql.php");
	if($sql->elimina_cronologia($_REQUEST['tutte'])!=false)
		exit("<div id='risposta' class='container'>
				
				<div class='row'>
					<div class='col-md-4'>
						</br>
						
						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					<div class='col-md-4'>
						<h1 class='display-1'>Cronologia vuota</h1>
					</div>
					
				</div></div>");
	$sql->chiudi();
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>SearchPlaces</title>
<style>
	.filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}

@media (max-width: 767px){
	
	.table{
		font-size: 10px;
	}
	
	tr{
		font-size: 10px;
	}
	
	.filterable .filters input[disabled]::-webkit-input-placeholder {
		color: #333;
		font-size: 10px;
	}
	.filterable .filters input[disabled]::-moz-placeholder {
		color: #333;
		font-size: 10px;
	}
	.filterable .filters input[disabled]:-ms-input-placeholder {
		color: #333;
		font-size: 10px;
	}
	
	::-webkit-input-placeholder { /* WebKit browsers */
		color:    #555;
		font-size: 10px;
	}
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
		color:    #555;
		font-size: 10px;
	}
	::-moz-placeholder { /* Mozilla Firefox 19+ */
		color:    #555;
		font-size: 10px;
	}
	:-ms-input-placeholder { /* Internet Explorer 10+ */
		color:    #555;
		font-size: 10px;
	}
	
}
	
</style>
</head>
<body>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
	<script>
		
	$(document).ready(function(){
		var lungh=0;
		$('.filterable .btn-filter').click(function(){
			var $panel = $(this).parents('.filterable'),
			$filters = $panel.find('.filters input'),
			$tbody = $panel.find('.table tbody');
			if ($filters.prop('disabled') == true) {
				$filters.prop('disabled', false);
				$filters.first().focus();
			} else {
				$filters.val('').prop('disabled', true);
				$tbody.find('.no-result').remove();
				$tbody.find('tr').show();
			}
		});

		$('.filterable .filters input').keyup(function(e){
			/* Ignore tab key */
			var code = e.keyCode || e.which;
			if (code == '9') return;
			/* Useful DOM data and selectors */
			var $input = $(this),
			inputContent = $input.val().toLowerCase(),
			$panel = $input.parents('.filterable'),
			column = $panel.find('.filters th').index($input.parents('th')),
			$table = $panel.find('.table'),
			$rows = $table.find('tbody tr');
			/* Dirtiest filter function ever ;) */
			var $filteredRows = $rows.filter(function(){
				var value = $(this).find('td').eq(column).text().toLowerCase();
				return value.indexOf(inputContent) === -1;
			});
			/* Clean previous no-result if exist */
			$table.find('tbody .no-result').remove();
			/* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
			$rows.show();
			$filteredRows.hide();
			/* Prepend no-result row if all rows are filtered */
			if ($filteredRows.length === $rows.length) {
				$table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
			}
		});
		
		//ELIMINA COMMENTO // PRENDO LA CLASSE DEL PULSANTE CLOSE
		$('.btn-danger').click(function(){
			var id = $(this).attr("id");
			$.ajax({  
			  method: 'POST',
			  url: "cronologia.php",  
			  data: "Elimina=1" + "&Id=" + id,
			  dataType: "text",
			  success: function(risposta){
				  $('#'+ id).remove();
				  lungh = $('.table tr').length;
				  lungh=lungh-1;
				  if(lungh==0){
					  $('#risposta').replaceWith("<div class='container'><div class='row'><div class='col-md-4'></br><a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a></div><div class='col-md-4'><h1 class='display-1'>Cronologia vuota</h1></div></div></div> ");}
			  },
			  error: function(){
				alert("Chiamata fallita, si prega di riprovare...");
			  }
			});
			return false;
		});
		
		$('#elimina').click(function(){
			$.ajax({  
			  method: 'POST',
			  url: "cronologia.php",  
			  data: "tutte=1",
			  dataType: "html",
			  success: function(risposta){
				  $('#risposta').replaceWith(risposta);
				  
				  
			  },
			  error: function(){
				alert("Chiamata fallita, si prega di riprovare...");
			  }
			});
			return false;
		});
		
	});
	</script>
	<?php
	//$sql = new Sql("jpinna.it.mysql","jpinna_it","MDA9Kt7Z","jpinna_it");
	if(isset($_SESSION['User'])){
		require("./config_sql.php");
		$result = $sql->stampa_ricerche($_SESSION['User']);
		if($result->num_rows>0){
			
			echo "
			<div id='risposta' class='container'>
				
				<div class='row'>
					<div class='col-md-4'>
						</br>
						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					
					<div class='col-md-4'>
						<h1 class='display-1'>Cronologia</h1>
					</div>
				</div>
				<hr>
				
			<div class='row'>
				<button type='submit' id=\"elimina\" name=\"elimina\" class=\"form-control btn btn-primary\">Elimina cronologia</button><br><hr>
				<div class='panel panel-primary filterable'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Cronologia</h3>
						<div class='pull-right'>
							<button class='btn btn-default btn-xs btn-filter'><span class='glyphicon glyphicon-filter'></span> Filter</button>
						</div>
					</div>
					<table class='table table-hover responsive'>
						<thead>
							<tr class='filters'>
								<th align='center' ><em class='fa fa-cog'></em></th>
								<th><input type='number' class='form-control' placeholder='ID' disabled></th>
								<th><input type='date' class='form-control' placeholder='Data' disabled></th>
								<th><input type='time' class='form-control' placeholder='Ora' disabled></th>
								<th><input type='text' class='form-control' placeholder='Query' disabled></th>
								<th><input type='text' class='form-control' placeholder='Luogo' disabled></th>
							</tr>
						</thead>
						<tbody>";
						while($row = $result->fetch_assoc()){
							$id = $row['Id_ricerca'];
							$data = $row['Data'];
							$ora = $row['Ora'];
							$query = $row['Query'];
							$luogo = $row['Ricercato'];
							echo "
							<tr class='riga' id='$id'>
								 <td align='center'>
								   <a id='$id' class='btn btn-danger'><em class='fa fa-trash'></em></a>
								 </td>
								<td>$id</td>
								<td>$data</td>
								<td>$ora</td>
								<td>$query</td>
								<td>$luogo</td>
							</tr>";
						}
						echo "
						</tbody>
					</table>
				</div>
			</div></div>";
			$sql->chiudi();	
		}else{
			echo "<div class='container'>
				
				<div class='row'>
					<div class='col-md-4'>
						</br>
						
						<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
					</div>
					<div class='col-md-4'>
						<h1 class='display-1'>Cronologia vuota</h1>
					</div>
				</div>
			</div>";
		  }
	}else{
		echo "<div class='container'>
				
			<div class='row'>
				<div class='col-md-4'>
					</br>

					<a href='./index.php'><button name='home' class='btn btn-primary'>TORNA ALLA HOME</button></a>
				</div>
				<div class='col-md-4'>
					<h1 class='display-1'>Cronologia non disponibile</h1>
				</div>
			</div>
		</div>";
	}
	?>
</body>
</html>