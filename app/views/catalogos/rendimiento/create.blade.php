@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($rendimiento))
  					<h3 class="box-title">Editar Rendimiento de Combustible</h3>
  				@else
  					<h3 class="box-title">Nuevo Rendimiento de Combustible</h3>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
  			<div class="box-body">
  					<div class="form-group col-md-12">
  						<div class="row">
                {{ Form::label('', 'Tipo de Unidad') }}
                {{ Form::select('tipo_de_unidad_id', array(null=>'Seleccione Uno')+$tipounidad_list, isset($rendimiento)?$rendimiento->tipo_de_unidad_id:null, array('class'=>'form-control required',Input::has('tipo_de_unidad_id')?'disabled':'')) }}
    						{{ $errors->first('tipo_de_unidad_id', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="row">
                {{ Form::label('', 'Rendimiento Litros/Kilometro') }}
                {{ Form::text('rendimiento', Input::old('rendimiento', isset($rendimiento)?$rendimiento->rendimiento:null), array('class'=>'form-control required','placeholder'=>'Rendimiento Litros/Kilometro')) }}
                {{ $errors->first('rendimiento', '<p class="text-danger">:message</p>') }}
              </div>
  					</div>
  			</div>
  			<div class="box-footer">
				{{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
				</div>
					{{ Form::close() }}
  		</div>
  	</div>
  </div>

@endsection

@section('scripts')
	<script type="text/javascript">
	$("#form").validate();


		//Date picker
  window.onload=function () {
    $('#Menu1').addClass('active');
    $('#Menu1-1-2').addClass('active');
  };
	</script>
@stop
