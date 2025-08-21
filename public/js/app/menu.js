class ControladorI {
	constructor(div) {
	  this.div = div;
	//  this.persona = new persona(); // Inicializar persona correctamente
	 // this.inc = new Inicio(); // Inicializar Inicio correctamente
	}
 

	acceso(){
		const inc = new Inicio();
		const dato = new Datos();
		const persona = new Persona();

		self=this;
		inc.inicioP();
		$('#alta_datos').on('click', function() {
			dato.agregarDato();
		});
		$('#datos').on('click', function() {
			dato.listarDatos();
		});
		$('#alta_persona').on ('click', function() {
			persona.agregarPersona();
		});
		
		$('#listar_persona').on('click', function() {
			persona.listarPersona();
		});
		
		$('#salir').on('click', function() {
			modalConfirmar('¿Realmente desea salir del sistema?');
		});	

	}
}



 /*
	acceso2() {
	  // No necesitas usar `self`, simplemente usa funciones de flecha para mantener el contexto de `this`
	  this.inc.inicioP();  // Llamar correctamente al método inicioP de la instancia de Inicio
  
	  // Usamos funciones de flecha para asegurar que `this` dentro de las funciones de callback se mantenga
	  $('#alta_datos').on('click', () => {
		this.persona.agregarPersona(); // Usar `this` para acceder a la instancia de `persona`
	  });
  
	  $('#datos').on('click', () => {
		this.persona.listarPersona(); // Usar `this` para acceder a la instancia de `persona`
	  });
  
	  $('#salir').on('click', () => {
		modalConfirmar('¿Realmente desea salir del sistema?');
	  });
	}
  }
*/
	$(function() {
		let c = new ControladorI();
		c.acceso();
	});

