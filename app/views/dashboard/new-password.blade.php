<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


{{ HTML::script('js/new-pass.js') }}

<div class="row">
  @include('notifications')
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
		<h1>Cambio de contraseña</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<p class="text-center">Utilice el siguiente formulario para cambiar su contraseña.</p>
			{{ Form::open(['id'=>'passwordForm']) }}
				<input type="password" class="input-lg form-control" name="password" id="password" placeholder="Nueva Contraseña" autocomplete="off">
				<div class="row">
					<div class="col-sm-6">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 caracteres de largo<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 1 letra mayúscula
					</div>
					<div class="col-sm-6">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 1 letra minuscula<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 1 número
					</div>
				</div>
				<input type="password" class="input-lg form-control" name="password_confirmation" id="password_confirmation" placeholder="Repetir Contraseña" autocomplete="off">
				<div class="row">
					<div class="col-sm-12">
						<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Contraseñas coinciden
					</div>
				</div>
				<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Cambiando la Contraseña..." value="Cambiar contraseña">
			{{ Form::close() }}
		</div><!--/col-sm-6-->
	</div><!--/row-->
</div>