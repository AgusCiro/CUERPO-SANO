class  Persona{
		constructor(){		
			
			this.urlVista      = 'app/templates/persona/';
			this.urlModal      = 'app/templates/';
		}
		
		agregarPersona(){ //alta y validacion de datos 
			const url=this.urlVista +"agregarPersona.php";
			var self=this;



			$.get(url, function(data) {
				$("#cuerpo").html(data);

				$('#datetimepicker4').datetimepicker({ //modifica el formato de la fecha para que se muestre en latino usando moments.js
					format: 'L',
					locale: 'es-es',
					format: 'DD/MM/YYYY',
					
	
				});
				$('#fechanac').datetimepicker({
					format: 'L',
					locale: 'es-es',
					format: 'DD/MM/YYYY'//,
					//maxDate: fecha 	
					
				});


				$('#form').validate({
					event: 'blur',
					rules: {
						  
						  nombre: {
							  required: true
						  },
						  apellido: {
							  required: true
						  },
						  edad: {
							  required: true
						  },
						  fechanac: {
							required: true
						},
						  nacionalidad: {
							required: true
						}
					  },
					  messages: {
						  'nombre': 'Por favor ingrese el nombre.',
						  'apellido': 'Por favor ingrese la apellido.',
						  'edad': 'Por favor ingrese una edad.',
						  'fechanac': 'Por favor ingrese la Fecha de nacimiento.',
						  'nacionalidad': 'Por favor ingrese una nacionalidad.',
				
					  },
						 debug: true,
				 });
				 $( "#guardar" ).click(function( event ) {			
					 if ($('#form').valid()) {
						 let nombre=$('#nombre').val();
						 let apellido=$('#apellido').val();
						 let edad=$('#edad').val();
						 let fechanac=$('#fechanac').val();
						 let nacionalidad=$('#nacionalidad').val();
						 let accion = 'guardar'
						 self.guardarPersona( accion, nombre, apellido, edad, fechanac, nacionalidad);
					 }     
					
				});
		});		
		}


	guardarPersona( accion, nombre, apellido ,edad, fechanac, nacionalidad){// /guarda un nuevo dato
		const self=this;
			//console.log("Datos enviados:", { accion, nombre, apellido, edad, fechanac, nacionalidad });

		
		cargando();
		$.post("app/controllers/appController.php",
		{ 
			accion: accion,
			nombre:nombre,
			apellido: apellido, 
			edad: edad,
			fechanac: fechanac,
			nacionalidad: nacionalidad
			
		},
				function(data)
				{

		
		finCargando();
				if ( data.res == 'ok' )
					{
						modalAceptar('Se guardaron los datos de la persona');
						self.listarPersona();		
					}
					else{							
						modalAceptar('No se guardaron los datos de la persona');
						self.listarPersona();				
					}
				}, 'json' );
		}

	
	
		listarPersona(){//genera tabla con el listado de datos
		
			const url1=this.urlVista +"listarpersona.php";
			let i=0; 
			let self=this;
			$.get(url1).done(function(data) {
			
			cargando();
			let tableHeader = data;
			   $.post('app/controllers/appcontroller.php',
			{
				accion: 'listar_persona'
			})
			.done(function( data ) {
				finCargando();
				let jsonData = JSON.parse(data);
	
				if(jsonData.res) {
					$('#cuerpo').html(tableHeader);
				}
				else {
					let tabla = $('<table>').attr('id_p', 'datatable');
					$('#cuerpo').html(tabla);
					
				}
				var table = $("#datatable").DataTable({
					data: jsonData.datos,
					columns: [
						{ data: 'id_p' },
						{ data: 'nombre' },
						{ data: 'apellido' },
						{ data: 'edad' },
						{ data: 'fechanac' },
						{ data: 'nacionalidad' }
						
					],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {  
								
								data =
								data ='<button id="ver" type="button" class="btn btn-primary btn-sm" name="ver"><span id="' 
                                  + i + '">Ver</button><button type="button" id="editar" class="btn btn-success btn-sm" name="editar"><span id="' 
                                + i + '">Editar</button> <button type="button" id="eliminar" class="btn btn-danger btn-sm" name="eliminar"><span id="' + i + '">Eliminar</button>';
								$("#cuerpo").data("id_p" + i ,row.id_p);
								$("#cuerpo").data("nombre" + i,row.nombre);
								$("#cuerpo").data("apellido" + i,row.apellido);
								$("#cuerpo").data("edad" + i,row.edad);
								$("#cuerpo").data("fechanac" + i,row.fechanac);
								$("#cuerpo").data("nacionalidad" + i,row.nacionalidad);
								i++;
								return data;
							},
							"targets": 6,
							"visible": true,
						},
						{
							"searchable": false,
							"targets": [6],
							"visible": true,
							
						},
						{
							"orderable": false,
							"targets": [6],
							"visible": true,
						}
					],        

					'language': {
						"sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ning&uacute;n dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":    "",
						"sSearch":         "Buscar:",
						"sUrl":            "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "&Uacute;ltimo",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}
					},
				});
			/*
			$('#datetimepicker4').datetimepicker({ //modifica el formato de la fecha para que se muestre en latino usando moments.js
				format: 'L',
				locale: 'es-es',
				format: 'DD/MM/YYYY',
				

			});
			$('#fechanac').datetimepicker({
				format: 'L',
				locale: 'es-es',
				format: 'DD/MM/YYYY'//,
				//maxDate: fecha 	
				
			});

*/
/*
			$.post(			
				'app/controllers/appcontroller.php',
				{
					accion: 'listar_persona'
				},
				function(jsonData){

					var table = $("#datatable").DataTable({
						data: jsonData.datos,
						columns: [
							{ data: 'id_p' },
							{ data: 'nombre' },
							{ data: 'apellido' },
	
							{ data: 'edad' },
							{ data: 'fechanac' },
							{ data: 'nacionalidad' }
							
						],
						"columnDefs": [
							{
								"render": function ( data, type, row ) {  
									
									data ='<button id="ver" type="button" class="btn btn-primary btn-sm" name="ver"><span id="' 
									  + i + '">Ver</button><button type="button" id="editar" class="btn btn-success btn-sm" name="editar"><span id="' 
									+ i + '">Editar</button> <button type="button" id="eliminar" class="btn btn-danger btn-sm" name="eliminar"><span id="' + i + '">Eliminar</button>';
									$("#cuerpo").data("id" + i ,row.id_p);
									$("#cuerpo").data("nombre" + i,row.nombre);
									$("#cuerpo").data("calle" + i,row.apellido);
									$("#cuerpo").data("altura" + i,row.edad);
									$("#cuerpo").data("piso" + i,row.fechanac);
									$("#cuerpo").data("dep" + i,row.nacionalidad);
									i++;
									return data;
								},
								"targets": 6,
								"visible": true,
							},
							{
								"searchable": false,
								"targets": [6],
								"visible": true,
								
							},
							{
								"orderable": false,
								"targets": [6],
								"visible": true,
							}
						],        
	
						'language': {
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ning&uacute;n dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Buscar:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "&Uacute;ltimo",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
					});

				},
				'json'
			);

		*/

			$('#datatable tbody').on('click', 'button.btn', function () {     
			let hijo = $(this).children().attr('id');
			let opcion = $(this).attr('id');
			var id_p = $('#cuerpo').data("id" + hijo);
				
			if (opcion === 'ver') {
						let id_p = $('#cuerpo').data("id_p" + hijo);
						let nombre = $('#cuerpo').data("nombre" + hijo);
						let apellido = $('#cuerpo').data("apellido" + hijo);
						let edad = $('#cuerpo').data("edad" + hijo);
						let fechanac = $('#cuerpo').data("fechanac" + hijo);
						let nacionalidad = $('#cuerpo').data("nacionalidad" + hijo);
						self.mostrarPersona(id_p, nombre, apellido, edad, fechanac, nacionalidad);

				
					} else if (opcion === 'editar') {
						let id_p = $('#cuerpo').data("id_p" + hijo);
						let nombre = $('#cuerpo').data("nombre" + hijo);
						let apellido = $('#cuerpo').data("apellido" + hijo);
						let edad = $('#cuerpo').data("edad" + hijo);
						let fechanac = $('#cuerpo').data("fechanac" + hijo);
						let nacionalidad = $('#cuerpo').data("nacionalidad" + hijo);
						self.editarPersona(id_p, nombre, apellido, edad, fechanac, nacionalidad);
					} else if (opcion === 'eliminar') {
					let id = $('#cuerpo').data("id_P" + hijo);
						self.eliminarPersona(id_p);
					}
				});
		
		
			});
			
			

			
		});
		}
	

	mostrarPersona(id_p, nombre, apellido, edad, fechanac, nacionalidad){
		const url1 = this.urlModal + "dialog_aceptar.php";
		const url = this.urlVista + "verPersona.php";
		let self=this;
		$.get(url1).done(function(data) {
			$("#modal").html(data);
			$.get(url).done(function(data) {
				$("#modalContenido").html(data);
				$("#modalTitulo").html('<h4>Dato</h4>')
				$('#id_p').val(id_p);
				$('#nombre').val(nombre);
				$('#apellido').val(apellido);
				$('#edad').val(edad);
				$('#fechanac').val(fechanac);
				$('#nacionalidad').val(nacionalidad);
				$("#myModal").modal();
				
			});
		});
		
	}

	editarPersona(id_p, nombre, apellido, edad, fechanac, nacionalidad ){//edita los valores del dato
		const url1 = this.urlModal + "dialog_modal.php";
		const url = this.urlVista + "editarPersona.php";
		let self = this;
		$.get(url1).done(function(data) {
			$("#modal").html(data);
			$.get(url).done(
				function(data) {
					$("#modalContenido").html(data);
					$("#modalTitulo").html('<h4>Dato</h4>')
					$('#id_p').val(id_p);
					$('#nombre').val(nombre);
					$('#apellido').val(apellido);
					$('#edad').val(edad);
					$('#fechanac').val(fechanac);
					$('#nacionalidad').val(nacionalidad);
					
					$("#myModal").modal();
					$('#form').validate(
					{
						event: 'blur',
						rules: {
							id_p: {
								required: true
							},
							nombre: {
								required: true
							},
							apellido: {
								required: true
							},
							edad: {
								required: true
								},
							fechanac: {
									required: true
								},
							nacionalidad: {
									required: true
								},
						},
						messages: {
							'nombre': 'Por favor ingrese el nombre.',
							'apellido': 'Por favor ingrese su apellido.',
							'fechanac': 'Por favor ingrese una fecha nacimiento.',
							'edad': 'Por favor ingrese el edad.',
							'nacionalidad': 'Por favor ingrese su nacionalidad.',
				
						},
					});
			    } 
		    );
 
				$("#guardar" ).click(function( event ) {
					
					 if ($('#form').valid()) {
						 $("#myModal").modal('hide') 
						let id_p=$('#id_p').val();
						let nombre=$('#nombre').val();
						let apellido=$('#apellido').val();
						let edad=$('#edad').val();
						let fechanac=$('#fechanac').val();
						let nacionalidad=$('#nacionalidad').val();
						
						$.post("app/controllers/appcontroller.php",
								{  	
									accion: 'editar_persona',
									id_p:id_p,
									nombre:nombre,
									apellido: apellido,
									edad:edad,
									fechanac:fechanac,
									nacionalidad:nacionalidad,
								
									//observacion:observacion
								},
										function(data)
										{
											
											finCargando();
											if ( data.res == 'ok' )
											{
												
												
												modalAceptar('Se Cambio el dato');
												self.listarPersona();
													
												
											}
											else{	
												
												modalAceptar('No se Cambio el dato');
												self.listarPersona();
													
												
												
											}
										} , 
										
								'json' );
					 }
						
				});
			});
		};
		
	

	eliminarPersona(id_p) {
		const self = this;
		let mensaje = "Realmente desea eliminar los datos de la persona?";
		const url1 = 'app/templates/' + "modalSmallConfirmar.php";
		console.log(mensaje);
	
		Swal.fire({
			icon: 'warning',
			title: mensaje,
			showCancelButton: true,
			confirmButtonText: 'Si',
			cancelButtonText: 'No',
			customClass: {
				actions: 'my-actions',
				cancelButton: 'order-1 right-gap',
				confirmButton: 'order-2'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				$.post("app/controllers/appcontroller.php",
					{
						accion: 'eliminar_persona',
						id_p: id_p
					},
					function (data) {
						finCargando();
						if (data.res == 'ok') {
							self.listarPersona();
						} else {
							modalAceptar('No se eliminó su dato');
							self.listarPersona();
						}
					},
					'json'
				);
			}
		});
	}
}