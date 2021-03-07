
/*
$(document).ready(function() {
	$('#form4').validate({

			rules:{
				risposta:{
					required: true
				}
			},
			messages:{
				risposta: {
					required: "Inserisci la tua risposta!"
				}
			},

			submitHandler: function(){
				var risposta = $('#risposta').val();
				var user = $('.noti').attr('id');
				alert(risposta);
				alert(user);

				/*
				$.ajax({  
				  type: "POST",
				  url: "./dinamiche/controlla.php",  
				  data: {
					manda: 1,
					user: user,
					risposta: risposta
				  },
				  dataType: "text",
				  success: function(response){


					  $(':input', '#form4').val('');
					  alert(response);
				  },
				  error: function(){
					alert("Chiamata fallita, si prega di riprovare...");
				  }
			});*/
/*
			}
		});
});*/