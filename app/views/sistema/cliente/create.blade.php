@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($cliente))
  					<h3 class="box-title">Editar Cliente</h3>
  				@else
  					<h3 class="box-title">Nuevo Cliente</h3>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form']) }}
  			<div class="box-body">
					<div class="form-group col-md-4">
						<div class="row">
              {{ Form::label('', 'Cliente') }}
              {{ Form::text('cliente', Input::old('cliente', isset($cliente)?$cliente->cliente:null), array('class'=>'form-control required','placeholder'=>'Cliente')) }}
  						{{ $errors->first('cliente', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Contacto') }}
              {{ Form::text('nombre_contacto', Input::old('nombre_contacto', isset($cliente)?$cliente->nombre_contacto:null), array('class'=>'form-control','placeholder'=>'Contacto','required')) }}
              {{ $errors->first('nombre_contacto', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Correo Electrónico') }}
              {{ Form::email('email', Input::old('email', isset($cliente)?$cliente->email:null), array('class'=>'form-control email','placeholder'=>'Correo Electrónico','required')) }}
              {{ $errors->first('email', '<p class="text-danger">:message</p>') }}
            </div>
             <div class="row">
              {{ Form::label('', 'Teléfono') }}
              {{ Form::text('telefono', Input::old('telefono', isset($cliente)?$cliente->telefono:null), array('class'=>'form-control','placeholder'=>'Teléfono')) }}
              {{ $errors->first('telefono', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Porcentaje Gastos de Administración') }}
              {{ Form::text('gasto_admon', Input::old('gasto_admon', isset($cliente)?$cliente->gasto_admon:null), array('class'=>'form-control','placeholder'=>'Porcentaje Gastos de Administración')) }}
              {{ $errors->first('gasto_admon', '<p class="text-danger">:message</p>') }}
            </div>
					</div>
          <div class="form-group col-md-8">
              {{ Form::label('', 'Observaciones') }}
              {{ Form::textarea('observaciones', isset($cliente)?$cliente->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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

    $( function() {
      CKEDITOR.replace('observaciones');
    } );
		//Date picker
	</script>
@stop
