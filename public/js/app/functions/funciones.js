function validarClase(idCla) {
	// valida la clase
	if (typeof idCla === "undefined") {
		alert('Debe ingresar una Clase Correcta');
		$("#sI").focus();
		return false;
	}
	return true;
}
function TamañoImagen(ima) {
	// valida que el archivo sea de extension imagen
	
	if (ima>=1048576) {
		alert('El tamaño de la imagen debe ser menor a un Mb');
		// $("#sI").focus();
		return false;
	}
	return true;
}
function validarNumero(numero,input) {

	var RE =  /^\d*$/.test(numero);
	if ((!RE)|| numero=='') {
		$(input).focus();
		var dialog = $("<div>")
		.attr("title","Mensaje de sistema.")
		.attr("id","dialog")
		.html('Debe ingresar Numero'+ input)
		.dialog({
			modal: true,
			buttons: {				
				Aceptar : function() {					
					$(input).focus();
					$( this ).dialog( "close" );
				} 
			}
		});		
		return false;
	}
	$('#dialog').remove();
	return true;
}
function modalAceptar(mensaje){
	Swal.fire({
		  title: mensaje,
		  icon:'success',
		  confirmButtonText: "Aceptar",
	      
	});
	
	}
function modalError(mensaje){
	Swal.fire({
		  title: mensaje,
		  icon:'error',
		  confirmButtonText: "Aceptar",
	      
	});
	
	}
function modalConfirmar(mensaje){
	Swal.fire({
		  icon:'warning',
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
			  window.location.href = "app/models/salir.php";
		  } 
		})

	}
function validarTexto(texto,input,mensaje) {
	//var reg = /^([a-z ñáéíóú]{2,60})$/i;
	console.log('numero'+texto);
	if (texto=='') {
		$(input).focus();
		var dialog = $("<div>")
		.attr("title","Mensaje de sistema.")
		.attr("id","dialog")
		.html('Debe ingresar '+mensaje)
		.dialog({
			modal: true,
			buttons: {
				Aceptar : function() {
					console.log(input);
					$(input).focus();			    
			        $( this ).dialog( "close" );
				} 
			}
		});
		return false;
	}
	/*else{
		return false;
	/*if (!reg.test(texto)) {
				$(input).focus();
				var dialog = $("<div>")
				.attr("title","Mensaje de sistema.")
				.attr("id","dialog")
				.html('Debe ingresar '+mensaje)
				.dialog({
					modal: true,
					buttons: {				
						Aceptar : function() {					
							$(input).focus();
							$( this ).dialog( "close" );
						} 
					}
				});		
				return false;
	}*/
	//}
	$("#dialog").remove();
	return true;
}
function validarCuil(cuil,input) {
//valido formato texto
var RE = /\b(20|23|24|27|30|33|34)(D)?[0-9]{8}?[0-9]{1}$/;
		if (!RE.test(cuil)) {
			$(input).focus();
			var dialog = $("<div>")
			.attr("title","Mensaje de sistema.")
			.attr("id","dialog")
			.html('Debe ingresar un Numero CUIT/CUIL ')
			.dialog({
				modal: true,
				buttons: {
					Aceptar : function() {
						$(input).focus();
						$( this ).dialog( "close" );
					} 
				}
			});
		return false;
		}
		return true;
}
function validarImagen(ima) {
	// valida que el archivo sea de extension imagen
	var re = /(.jpg)$/.test(ima);
	if (typeof ima == "" || !re) {
		alert('Debe ingresar una Imagen Formato jpg');
		// $("#sI").focus();
		return false;
	}
	return true;
}
function ValidarDocumento(doc) {
	// valida que el archivo sea de extension imagen
	var re = /(.pdf)$/.test(doc);
	if (typeof ima == "" || !re) {
		alert('Debe ingresar una Documento formato PDF');
		// $("#sI").focus();
		return false;
	}
	return true;
}
function validarPrecio(precio,input) {
	// valido de el precio sea un numero o numero
	// decimal
	//alert(precio);
	var RE =  /^[0-9]+([,][0-9]+)?$/.test(precio);
	if ((!RE)) {
		alert('Debe ingresar un Precio');
		$(input).focus();
		return false;
	}
	return true;
}
function validarFechaMayor(fecha, fechaH,input) {
	// valido formato fecha
	var x = fecha.split("/");
	var z = fechaH.split("/");

	//Cambiamos el orden al formato americano, de esto dd/mm/yyyy a esto mm/dd/yyyy
	fecha1 = x[1] + "-" + x[0] + "-" + x[2];
	fecha2 = z[1] + "-" + z[0] + "-" + z[2];
	
	if (Date.parse(fecha1) > Date.parse(fecha2)) {
		var dialog = $("<div>")
		.attr("title","Mensaje de sistema.")
		.html('La Fecha ingresada desde debe ser anterior a la Fecha hasta')
		.dialog({
			modal: true,
			buttons: {
				
				Aceptar : function() {
					$(input).focus();
					$( this ).remove();
				} 
			}
		});
		return false;
	}
	return true;
}
function validarFecha(fecha,input) {
	// valido formato fecha
	var RE = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	if ((fecha == '') || (!RE.test(fecha))) {
		var dialog = $("<div>")
		.attr("title","Mensaje de sistema.")
		.html('Debe ingresar una Fecha')
		.dialog({
			modal: true,
			buttons: {
				
				Aceptar : function() {
					$(input).focus();
					$( this ).remove();
				} 
			}
		});
		
		return false;
	}
	return true;
}
function cargando(){
	var div = $("<div>").attr("id","cargando").attr("class","loader").attr("align","center");	
	 $('#cuerpo').prepend(div);
}
function finCargando(){
	var div = $("#cargando").remove();	
	
}
function loading(id){
	$(id).html($("<div>").append(
			$("<img>").attr({
				src:"imagen/transparent.gif"
			})
	));
}