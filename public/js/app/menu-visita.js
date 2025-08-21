class  ControladorI{
	constructor(div){
		this.div = div;
	
		
	}
	acceso(){
		const inc = new Inicio();
		inc.inicioV();
		
		$('#acesso').on('click', function() {
			modalAceptar('<div class="alert alert-danger" role="alert">Usted no posee un Rol para este sistema, Contáctese con el administrador para solicitar el acceso al sistema SGAC.</div>');
		});
	
		$('#manual').on('click', function() {
			window.open('Manual/Manual Usuario SGAC.docx', '_blank');
		});
		$('#salir').on('click', function() {
			modalComfirmar('¿Realmente desea salir del sistema?');
			
		});
	}	
}
$(function() {
	let c= new ControladorI();
	c.acceso();
});

