<div id="mensaje"></div>

<div  class="card">
<div class="card card-header text-white bg-info">
	<h4>Bienvenido  al Sistema</h4>
	<h4>Usuario:  <?php session_start(); print_r( $_SESSION['USUARIO']['usuario'])?> </h4>
</div>	
<div class="alert alert-danger" role="alert">
<i class="fas fa-info-circle  fa-2x" style="color:red"></i><strong><FONT SIZE=5>  Se le esta realizando un mantenimiento al sistema.</p></FONT></strong>
</div>
  <div class="card-body">
    	<div id="imagen" class="widget">
    		<img src="public/images/error.gif" width="400" class="img-fluid" alt="Responsive image" >
		</div>
</div>
	
</div>