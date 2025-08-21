class  Datos{
	constructor(){		
		
		this.urlVista      = 'app/templates/datos/';
		this.urlModal      = 'app/templates/';
	}
	
	agregarDato(){ //alta y validacion de datos 
		const url=this.urlVista +"agregarDato.php";
		var self=this;
		$.get(url, function(data) {
			$("#cuerpo").html(data);
			$('#form').validate({
                event: 'blur',
                rules: {
			          
                      nombre: {
                          required: true
                      },
                      calle: {
                          required: true
                      },
                      altura: {
                          required: true
                      },
					  piso: {
						required: true
					},
					  departamento: {
						required: true
					}
                  },
                  messages: {
                      'nombre': 'Por favor ingrese el nombre.',
                      'calle': 'Por favor ingrese la calle.',
                      'altura': 'Por favor ingrese una altura.',
					  'piso': 'Por favor ingrese el piso.',
                      'departamento': 'Por favor ingrese un departamento.',
            
                  },
	                 debug: true,
	         });
			 $( "#guardar" ).click(function( event ) {			
				 if ($('#form').valid()) {
					 let nombre=$('#nombre').val();
					 let calle=$('#calle').val();
					 let altura=$('#altura').val();
					 let piso=$('#piso').val();
					 let departamento=$('#departamento').val();
					 let accion = 'guardar_dato'
					 self.guardarDato( accion, nombre, calle, altura, piso, departamento);
				 }     
				
			});
	});		
	}

	guardarDato(accion,nombre,calle, altura, piso, departamento){// /guarda un nuevo dato
		const self=this;
		cargando();
		$.post("app/controllers/appController.php",
		{ 
			accion: accion,
			nombre:nombre,
			calle: calle, 
			altura: altura,
			piso: piso,
			departamento: departamento
		},
				function(data)
				{
		finCargando();
				if ( data.res == 'ok' )
					{
						modalAceptar('Se guardo su nuevo dato');
						self.listarDatos();		
					}
					else{							
						modalAceptar('No se guardo su nuevo dato');
						self.listarDatos();				
					}
				}, 'json' );
		}

		
	guardarpersona(accion,nombre,apellido, fechanac, nacionalidad ){// /guarda un nuevo dato
		const self=this;
		cargando();
		$.post("app/controllers/appController.php",
		{ 
			accion: accion,
			nombre:nombre,
			apellido: apellido, 
			fechanac: fechanac,
			nacionalidad: nacionalidad,
		},
				function(data)
				{
		finCargando();
				if ( data.res == 'ok' )
					{
						modalAceptar('Se guardaron los datos de su persona');
						self.listarDatos();		
					}
					else{							
						modalAceptar("Se guardaron los datos de su persona");
						self.listarDatos();				
					}
				}, 'json' );
		}

	
		listarDatos(){//genera tabla con el listado de datos
		
			const url1=this.urlVista +"listarDatos.php";
			let i=0; 
			let self=this;
			$.get(url1).done(function(data) {
			
			cargando();
			let tableHeader = data;
			   $.post('app/controllers/appcontroller.php',
			{
				accion: 'listar_datos'
			})
			.done(function( data ) {
				finCargando();
				let jsonData = JSON.parse(data);
	
				if(jsonData.res) {
					$('#cuerpo').html(tableHeader);
				}
				else {
					let tabla = $('<table>').attr('id', 'datatable');
					$('#cuerpo').html(tabla);
					
				}
				var table = $("#datatable").DataTable({
					data: jsonData.datos,
					columns: [
						{ data: 'id' },
						{ data: 'nombre' },
						{ data: 'calle' },
						{ data: 'altura' },
						{ data: 'piso' },
						{ data: 'dep' }
						
					],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {  
								
								data =
								data ='<button id="ver" type="button" class="btn btn-primary btn-sm" name="ver"><span id="' 
                                  + i + '">Ver</button><button type="button" id="editar" class="btn btn-success btn-sm" name="editar"><span id="' 
                                + i + '">Editar</button> <button type="button" id="eliminar" class="btn btn-danger btn-sm" name="eliminar"><span id="' + i + '">Eliminar</button>';
								$("#cuerpo").data("id" + i ,row.id);
								$("#cuerpo").data("nombre" + i,row.nombre);
								$("#cuerpo").data("calle" + i,row.calle);
								$("#cuerpo").data("altura" + i,row.altura);
								$("#cuerpo").data("piso" + i,row.piso);
								$("#cuerpo").data("dep" + i,row.dep);
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
			
				$('#datatable tbody').on('click', 'button.btn', function () {     
					let hijo = $(this).children().attr('id');
					let opcion = $(this).attr('id');
					var id = $('#cuerpo').data("id" + hijo);
				
					if (opcion === 'ver') {
						let id = $('#cuerpo').data("id" + hijo);
						let nombre = $('#cuerpo').data("nombre" + hijo);
						let calle = $('#cuerpo').data("calle" + hijo);
						let altura = $('#cuerpo').data("altura" + hijo);
						let piso = $('#cuerpo').data("piso" + hijo);
						let departamento = $('#cuerpo').data("dep" + hijo);
						self.mostrarDatos(id, nombre, calle, altura, piso, departamento);
					} else if (opcion === 'editar') {
						let id = $('#cuerpo').data("id" + hijo);
						let nombre = $('#cuerpo').data("nombre" + hijo);
						let calle = $('#cuerpo').data("calle" + hijo);
						let altura = $('#cuerpo').data("altura" + hijo);
						let piso = $('#cuerpo').data("piso" + hijo);
						let dep = $('#cuerpo').data("dep" + hijo);
						self.editarDatos(id, nombre, calle, altura, piso, dep);
					} else if (opcion === 'eliminar') {
					//	let id = $('#cuerpo').data("id" + hijo);
						self.eliminarDatos(id);
					}
				});
			
			});
			
		});
		}
	

	mostrarDatos(id,nombre, calle, altura, piso, dep){
		const url1 = this.urlModal + "dialog_aceptar.php";
		const url = this.urlVista + "verDatos.php";
		let self=this;
		$.get(url1).done(function(data) {
			$("#modal").html(data);
			$.get(url).done(function(data) {
				$("#modalContenido").html(data);
				$("#modalTitulo").html('<h4>Dato</h4>')
				$('#id').val(id);
				$('#nombre').val(nombre);
				$('#calle').val(calle);
				$('#altura').val(altura);
				$('#piso').val(piso);
				$('#departamento').val(dep);
				$("#myModal").modal();
				
			});
		});
		
	}

	editarDatos(id, nombre, calle, altura, piso, dep ){//edita los valores del dato
		const url1 = this.urlModal + "dialog_modal.php";
		const url = this.urlVista + "editarDatos.php";
		let self = this;
		$.get(url1).done(function(data) {
			$("#modal").html(data);
			$.get(url).done(
				function(data) {
					$("#modalContenido").html(data);
					$("#modalTitulo").html('<h4>Dato</h4>')
					$('#id').val(id);
					$('#nombre').val(nombre);
					$('#calle').val(calle);
					$('#altura').val(altura);
					$('#piso').val(piso);
					$('#dep').val(dep);
					
					$("#myModal").modal();
					$('#form').validate(
					{
						event: 'blur',
						rules: {
							id: {
								required: true
							},
							nombre: {
								required: true
							},
							calle: {
								required: true
							},
							altura: {
								required: true
								},
							piso: {
									required: true
								},
							dep: {
									required: true
								},
						},
						messages: {
							'nombre': 'Por favor ingrese el nombre.',
							'calle': 'Por favor ingrese la calle.',
							'altura': 'Por favor ingrese una altura.',
							'piso': 'Por favor ingrese el piso.',
							'departamento': 'Por favor ingrese un departamento.',
				
						},
					});
			    } 
		    );
 
				$("#guardar" ).click(function( event ) {
					
					 if ($('#form').valid()) {
						 $("#myModal").modal('hide') 
						let id=$('#id').val();
						let nombre=$('#nombre').val();
						let calle=$('#calle').val();
						let altura=$('#altura').val();
						let piso=$('#piso').val();
						let dep=$('#dep').val();
						
						$.post("app/controllers/appcontroller.php",
								{  	
									accion: 'editar_dato',
									id:id,
									nombre:nombre,
									calle: calle,
									altura:altura,
									piso:piso,
									dep:dep,
								
									//observacion:observacion
								},
										function(data)
										{
											
											finCargando();
											if ( data.res == 'ok' )
											{
												
												
												modalAceptar('Se Cambio el dato');
												self.listarDatos();
													
												
											}
											else{	
												
												modalAceptar('No se Cambio el dato');
												self.listarDatos();
												
												
												
											}
										} , 
										
								'json' );
					 }
						
				});
			});
		};
		
	

	eliminarDatos(id) {
		const self = this;
		let mensaje = "Realmente desea eliminar el Dato?";
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
						accion: 'eliminar_dato',
						id: id
					},
					function (data) {
						finCargando();
						if (data.res == 'ok') {
							self.listarDatos();
						} else {
							modalAceptar('No se eliminó su dato');
							self.listarDatos();
						}
					},
					'json'
				);
			}
		});
	}
}