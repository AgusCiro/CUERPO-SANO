
<div class="card card-header text-white bg-info">
		<h4>Ingrese información persona</h4>
	</div>
	
	<form id="form">
		<div class="form-group row">
			<label for="c" class="col-sm-2 col-form-label">Nombre</label>
			<div class="col-sm-10">
				<input name="nombre" placeholder="Nombre" class="form-control" id="nombre">
			</div>
		</div>

		<div class="form-group row">
			<label for="c" class="col-sm-2 col-form-label">Apellido</label>
			<div class="col-sm-10">
				<input name="apellido"placeholder="Apellido" class="form-control" id="apellido">
			</div>
		</div>
		<div class="form-group row">
			<label for="c" class="col-sm-2 col-form-label">Edad</label>
			<div class="col-sm-10">
				<input class="form-control" type= "number" name="edad" placeholder="edad" id="edad">
			</div>
		</div>
	

		<div class="form-group row">
   		<label class="col-sm-2 col-form-label">fechanac</label>
   		<div class="col-sm-10">
	
		 <div class="input-group date" id="datetimepicker4" data-target-input="nearest">		
                    <input type="text" id="fechanac" name="fechanac" class="form-control datetimepicker-input" data-target="#datetimepicker4"/>
                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
          </div>
   		</div>


		<div class="form-group row">
			<label for="c" class="col-sm-2 col-form-label">Nacionalidad</label>
			<div class="col-sm-10">
				<input class="form-control" name="Nacionalidad" placeholder="Nacionalidad" id="nacionalidad">
			</div>
		</div>

	</form>
	<div id='term' class="col v-center">
		<button type="button" id="guardar" class="btn btn-primary btn-lg">Guardar</button>

	</div>
