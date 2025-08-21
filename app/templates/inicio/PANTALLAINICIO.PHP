<div id="mensaje"></div>

<div class="card" >
<div class="card card-header text-white bg-info">
	<h4>Bienvenido</h4>
	<h4>Usuario:  <?php session_start(); print_r( $_SESSION['USUARIO']['usuario'])?> </h4>
</div>	
  <div class="card-body" height="100%">
    	<div id="imagen" class="widget">
    		<img src="public/images/inicio.jpg" class="img-fluid" alt="Responsive image" >
			</div>
	</div>	
</div>