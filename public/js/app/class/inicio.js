class  Inicio{ 
	constructor(div){		
		this.div = div;
		
		this.urlVista      = 'app/templates/inicio/';
		this.urlModal      = 'app/templates/';
	}
	
	inicioP(){
		const url = this.urlVista + "PANTALLAINICIO.PHP";
		var self = this;
		
		$.get(url, function(data) {
			$("#cuerpo").html(data);
	});		
	}

	inicioV(){
		const url = this.urlVista + "PANTALLAINICIOVISITA.PHP";
		var self = this;
		
		$.get(url, function(data) {
			$("#cuerpo").html(data);
	});	
	}

	inicioE(){
		const url=this.urlVista +"PANTALLAINICIOERROR.PHP";
		var self=this;
		
		$.get(url, function(data) {
			$("#cuerpo").html(data);
	});	
	}
}