$(document).ready(function() {
    // Selezione form e definizione dei metodi di validazione
    $("#form1").validate({
        // Definiamo le nostre regole di validazione
        rules : {
            // login - nome del campo di input da validare
            nome : {
              // Definiamo il campo login come obbligatorio
              required : true
            },

            cognome : {
              required : true
            },

			email : {
                required : true,
                // Definiamo il campo email come un campo di tipo email
                email : true,
				remote:{
					url: "registra.php",
					type: "post"
				}
            },

			user : {
                required : true,
				remote:{
					url: "registra.php",
					type: "post"
				}
            },

            pass : {
                required : true,
                // Settiamo la lunghezza minima e massima per il campo password
                minlength : 5,
                maxlength : 16
            },

            ripeti : {
                required: true,
                minlength: 5,
				maxlength : 16,
                equalTo : "#pass"
            }
        },
        // Personalizzimao i mesasggi di errore
        messages: {
            nome: "Campo nome obbligatorio!",
            cognome: "Campo cognome obbligatorio!",
            email: {
				required: "Campo email obbligatorio!",
				remote: "L'email è già utilizzata da un altro utente!"
			},
            pass: {
                required: "Campo password obbligatorio!",
                minlength: "La password deve essere lunga minimo 5 caratteri",
                maxlength: "La password deve essere lunga al massimo 16 caratteri"
            },
            ripeti: {
                required: "Conferma password",
                minlength: "La password deve essere lunga minimo 5 caratteri",
                maxlength: "La password deve essere lunga al massimo 16 caratteri",
                equalTo: "La password non corrisponde a quella inserita"
            },
            user: {
				required: "Campo username obbligatorio!",
				remote: "L'username è già utilizzato da un altro utente!"
			}

        },
        // Settiamo il submit handler per la form
        submitHandler: function() {
			var nome = $("#nome").val();
			var cognome = $("#cognome").val();
			var email = $("#email").val();
			var user = $("#user").val();
			var password = $("#pass").val();

			$.ajax({
				method: "POST",
				url: "./registra.php",
				data: {
					inserisci: 1,
					nome: nome,
					cognome: cognome,
					email: email,
					user: user,
					password: password
				},
				datatype: 'text',
				success: function (response){
					alert(response);
					$(':input', '#form1').val('');
				},
				error: function()
				{
					alert("Chiamata fallita, si prega di riprovare...");
				},
			});

		}

    });

	$('#form3').validate({
		rules:{
			pas: {
				required: true,
				minlength: 5,
				maxlength: 16
			},

			ripe:{
				required: true,
				minlength: 5,
				maxlength : 16,
                equalTo : "#pas"
			}
		},
		messages:{
			pas: {
                required: "Campo password obbligatorio!",
                minlength: "La password deve essere lunga minimo 5 caratteri",
                maxlength: "La password deve essere lunga al massimo 16 caratteri"
            },
			ripe: {
                required: "Conferma password",
                minlength: "La password deve essere lunga minimo 5 caratteri",
                maxlength: "La password deve essere lunga al massimo 16 caratteri",
                equalTo: "La password non corrisponde a quella inserita"
            }
		},
		submitHandler: function() {
			var pass = $('#pas').val();
			$.ajax({
			  type: "POST",
			  url: "./dinamiche/controlla.php",
			  data: {
				update: 1,
				pass: pass
			  },
			  dataType: "text",
			  success: function(response){
				  $(':input', '#form3').val('');
				  alert(response);
			  },
			  error: function(){
				alert("Chiamata fallita, si prega di riprovare...");
			  }
			});
        }
	});

	$('#form2').validate({
		rules:{
			us:{
				required: true
			},

			testo:{
				required: true
			}
		},
		messages:{
			us: {
                required: "Campo username obbligatorio!"
            },

			testo: {
                required: "Inserisci del testo"
            }
		},
		submitHandler: function() {
			var tipo = $('input[name="tipo"]:checked').val();
      		var utente = $('#us').val();
			var testo = $('#testo').val();
			$.ajax({
			  type: "POST",
			  url: "./dinamiche/controlla.php",
			  data: {
				invia: 1,
				tipo: tipo,
				testo: testo
			  },
			  dataType: "text",
			  success: function(response){
          		  $(':input', '#testo').val('');
				  $(':input', '#us').val(utente);
				  //$(':input', '#form2').val('');
				  alert(response);
			  },
			  error: function(){
				alert("Chiamata fallita, si prega di riprovare...");
			  }
			});
        }
	});
});
