$(document).ready(function(){
		'use strict';
		
		$('#logout').click(function(){
		    $.post("page.php",
			{
			  logout: 1
			},
			function(response){
				window.location.href = 'index.php';
			});
		});
		
		var count=$(".com").attr("id");
		var count2 = $(".users").attr("id");
		var count3 = $(".visitors").attr("id");
		var count4 = $(".news").attr("id");

		$('.panel-heading span.clickable').click(function(){
			var $this = $(this);
			if(!$this.hasClass('panel-collapsed')) {
				$this.parents('.panel').find('.panel-body').slideUp();
				$this.addClass('panel-collapsed');
				$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');

			} else {
				$this.parents('.panel').find('.panel-body').slideDown();
				$this.removeClass('panel-collapsed');
				$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');

			}
		});
	
		(function(){
			var $ = jQuery;
			$.fn.extend({
				filterTable: function(){
					return this.each(function(){
						$(this).on('keyup', function(e){
							$('.filterTable_no_results').remove();
							var $this = $(this),
								search = $this.val().toLowerCase(),
								target = $this.attr('data-filters'),
								$target = $(target),
								$rows = $target.find('tbody tr');

							if(search == '') {
								$rows.show();
							} else {
								$rows.each(function(){
									var $this = $(this);
									$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
								})
								if($target.find('tbody tr:visible').length === 0) {
									var col_count = $target.find('tr').first().find('td').length;
									var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
									$target.find('tbody').append(no_results);
								}
							}
						});
					});
				}
			});
			$('[data-action="filter"]').filterTable();
		})(jQuery);

		$(function(){
			// attach table filter plugin to inputs
			$('[data-action="filter"]').filterTable();

			$('.container').on('click', '.panel-heading span.filter', function(e){
				var $this = $(this),
					$panel = $this.parents('.panel');

				$panel.find('.panel-body').slideToggle();
				if($this.css('display') != 'none') {
					$panel.find('.panel-body input').focus();
				}
			});
			$('[data-toggle="tooltip"]').tooltip();
		})
		
		//VERIFICA NUMERO COMMENTI
		$('#co').click(function(){
			count=$(".com").attr("id");
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'text',
			  data:{
				Agg_com: 1,
				count: count
			  },
			  success:function(response){
					if(response.includes('false')){
						
					}else{
						$('.com').replaceWith("<span id='"+response+"' class='badge com'>"+response+"</span>");
						$('.n_com').replaceWith("<h2 class='n_com'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>"+response+"</h2>");

						//AGGIORNA COMMENTI
						$.ajax({
						  method: 'post',
						  url: "./return_data.php",
						  dataType: 'html',
						  data:{
							com: 1,
							count: count
						  },
						  success:function(response){
							if(response!=="false"){
								$('#mytbl').replaceWith(response);
							}
						  },
						  error:function(){
							alert("Chiamata fallita....");
						  }
						});
					}
			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});
		});

		//VERIFICA NUMERO UTENTI
		$('#us').click(function(){
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'text',
			  data:{
				Agg_user: 1,
				count: count2
			  },
			  success:function(response){
				if(response!=="false"){
					$('.users').replaceWith("<span id='"+response+"' class='badge users'>"+response+"</span>");
					$('.n_users').replaceWith("<h2 class='n_users'><span class='glyphicon glyphicon-user' aria-hidden='true'></span>"+response+"</h2>");
					//AGGIORNA UTENTI
					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'html',
					  data:{
						us: 1,
						count: count2
					  },
					  success:function(response){
						if(response!=="false"){
							$('#tbl_users').replaceWith(response);
						}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}

			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});

		});

		//VERIFICA NUMERO VISITATORI
		$('#vis').click(function(){
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'text',
			  data:{
				n_vis: 1,
				count: count3
			  },
			  success:function(response){
				if(response!=="false"){
					$('.visitors').replaceWith("<span id='"+response+"' class='badge visitors'>"+response+"</span>");

					//AGGIORNA VISITATORI
					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'html',
					  data:{
						vis: 1,
						count: count3
					  },
					  success:function(response){
						if(response!=="false"){
							$('#tbl_visitors').replaceWith(response);
						}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}

			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});

		});

		//VERIFICA NUMERO NOTIFICHE
		$('#no').click(function(){
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'text',
			  data:{
				n_not: 1,
				count: count4
			  },
			  success:function(response){
				if(response!=="false"){
					$('.news').replaceWith("<span id='"+response+"' class='badge news'>"+response+"</span>");
					$('.ne').replaceWith("<h2 class='ne'><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>"+response+"</h2>");

					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'html',
					  data:{
						not: 1,
						count: count4
					  },
					  success:function(response){
						if(response!=="false"){
							$('#notifiche').replaceWith(response);
						} 
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}
			  },
			  error:function(){
				 alert("Chiamata fallita....");
			  }
			  });
		   });

		//AGGIORNA COMMENTI
		$('#c').click(function(){
			count=$(".com").attr("id");
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'html',
			  data:{
				com: 1,
				count: count
			  },
			  success:function(response){
				if(response!=="false"){
					$('#mytbl').replaceWith(response);

					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'text',
					  data:{
						Agg_com: 1,
						count: count
					  },
					  success:function(response){
							if(response!==false){
								$('.com').replaceWith("<span id='"+response+"' class='badge com'>"+response+"</span>");
							}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}
			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});
		});

		//AGGIORNA UTENTI
		$('#u').click(function(){
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'html',
			  data:{
				us: 1,
				count: count2
			  },
			  success:function(response){
				if(response!=="false"){
					$('#tbl_users').replaceWith(response);

					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'text',
					  data:{
						Agg_user: 1,
						count: count2
					  },
					  success:function(response){
						if(response!=="false"){
							$('.users').replaceWith("<span id='"+response+"' class='badge users'>"+response+"</span>");

						}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}
			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});
		});

		//AGGIORNA VISITATORI
		$('#vi').click(function(){
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'html',
			  data:{
				vis: 1,
				count: count3
			  },
			  success:function(response){
				if(response!=="false"){
					$('#tbl_visitors').replaceWith(response);

					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'text',
					  data:{
						n_vis: 1,
						count: count3
					  },
					  success:function(response){
						if(response!=="false"){
							$('.visitors').replaceWith("<span id='"+response+"' class='badge visitors'>"+response+"</span>");

						}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}
			  },
			  error:function(){
				alert("Chiamata fallita....");
			  }
			});
		});

		//AGGIORNA NOTIFICHE
		$('#not').click(function(){
			count4=$('.news').attr('id');
			$.ajax({
			  method: 'post',
			  url: "./return_data.php",
			  dataType: 'html',
			  data:{
				not: 1,
				count: count4
			  },
			  success:function(response){
				if(response!=="false"){
					$('#notifiche').replaceWith(response);
					
					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'text',
					  data:{
						n_not: 1,
						count: count4
					  },
					  success:function(response){
						if(response!=="false"){
							$('.news').replaceWith("<span id='"+response+"' class='badge news'>"+response+"</span>");
							$('.ne').replaceWith("<h2 class='ne'><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>"+response+"</h2>");
						}

					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				}
			  },
			  error:function(){
					  alert("Chiamata fallita....");
				   }
			   });
			});
				  
		
		});
	
		//INVIA NOTIFICA ALLO USER
		function invia_notifica(event){
			if(event.type=='click'){
				var target=event.target;
				var id=target.id;
				var user=target.name;
				var risposta=$('.'+id).val();
				
				if(risposta!==""){
					$.ajax({
					  method: 'post',
					  url: "./return_data.php",
					  dataType: 'text',
					  data:{
						manda: 1,
						user: user,
						risposta: risposta
					  },
					  success:function(response){
						if(response!=="false"){
						  alert("Risposta inviata");
						  //ELIMINA LA RICHIESTA UTENTE
						  $.ajax({
							  method: 'post',
							  url: "./return_data.php",
							  dataType: 'text',
							  data:{
								ignora: 1,
								Id: id
							  },
							  success:function(response){
								if(response!=="false"){
									alert('eliminata');
								  $('.notifiche #'+id).fadeOut("slow",function(){
						  			$('.notifiche #'+id).remove();  
					  			  })
								  var count4 = $(".news").attr("id");
								  count4=count4-1;
								  //AGGIORNA IL NUMERO
								  $('.news').replaceWith("<span id='"+count4+"' class='badge news'>"+count4+"</span>");
								  $('.ne').replaceWith("<h2 class='ne'><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>"+count4+"</h2>");
								}
							  },
							  error:function(){
								alert("Chiamata fallita....");
							  }
							});
						}
					  },
					  error:function(){
						alert("Chiamata fallita....");
					  }
					});
				
				}else{
					alert("Inserisci risposta!");
				}
			}
		}
		
		//ELIMINA NOTIFICA 
		function elimina_notifica(event){
			if(event.type=='click'){
				var target=event.target;
				var id=target.id;
				$.ajax({
				  method: 'post',
				  url: "./return_data.php",
				  dataType: 'text',
				  data:{
					ignora: 1,
					Id: id
				  },
				  success:function(response){
					if(response!=="false"){
					  /*
					  $('.notifiche #'+id).fadeOut("slow",function(){
						  $('.notifiche #'+id).remove();  
					  })*/
					  $('.notifiche #'+id).fadeOut("slow",function(){
						  $('.notifiche #'+id).remove();  
					  });
					  var count4 = $(".news").attr("id");
					  count4=count4-1;
					  //AGGIORNA IL NUMERO
					  $('.news').replaceWith("<span id='"+count4+"' class='badge news'>"+count4+"</span>");
					  $('.ne').replaceWith("<h2 class='ne'><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>"+count4+"</h2>");
					}
				  },
				  error:function(){
					alert("Chiamata fallita....");
				  }
				});
			}			
		}