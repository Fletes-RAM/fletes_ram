@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"> <i class="fas fa-gas-pump"></i> Carga de Combustible</h3>
        </div>
        <div class="panel-body">
          {{ Form::open(['id'=>'form','files' => true,'autocomplete' => 'off']) }}
          @include('sistema.asignacioncombustible.form')
        </div>
        <div class="panel-footer">
          <div class="form-group">
            {{ Form::submit('Guardar', ['class'=>'btn btn-lg btn-success']) }}
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')

  <script type="text/javascript">
    $("#form").validate();
    $( function() {
			$('#fecha').datetimepicker({
				showTimePicker:false,
				format: 'yyyy-mm-dd',
	    	todayHighlight: true,
	    	autoclose: true,
				language: 'es',
				startView: 2,
				minView:2
			});
	 	});
  </script>

@stop
