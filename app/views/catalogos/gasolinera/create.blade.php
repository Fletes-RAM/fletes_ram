@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($gasolinera))
  					<h3 class="box-title">Editar Gasolinera a Crédito</h3>
  				@else
  					<h3 class="box-title">Nueva Gasolinera a Crédito</h3>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
  			<div class="box-body">
					<div class="form-group col-md-4">
						<div class="row">
              {{ Form::label('', 'Gasolinera') }}
              {{ Form::text('gasolinera', Input::old('gasolinera', isset($gasolinera)?$gasolinera->gasolinera:null), array('class'=>'form-control required','placeholder'=>'Gasolinera')) }}
  						{{ $errors->first('gasolinera', '<p class="text-danger">:message</p>') }}
            </div>
						<div class="row">
              {{ Form::label('', 'Estación') }}
              {{ Form::text('estacion', Input::old('estacion', isset($gasolinera)?$gasolinera->estacion:null), array('class'=>'form-control required','placeholder'=>'Estación')) }}
  						{{ $errors->first('estacion', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Contacto') }}
              {{ Form::text('contacto', Input::old('contacto', isset($gasolinera)?$gasolinera->contacto:null), array('class'=>'form-control','placeholder'=>'Contacto')) }}
              {{ $errors->first('contacto', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Correo Electrónico') }}
              {{ Form::email('email', Input::old('email', isset($gasolinera)?$gasolinera->email:null), array('class'=>'form-control email','placeholder'=>'Correo Electrónico')) }}
              {{ $errors->first('email', '<p class="text-danger">:message</p>') }}
            </div>
             <div class="row">
              {{ Form::label('', 'Teléfono') }}
              {{ Form::text('telefono', Input::old('telefono', isset($gasolinera)?$gasolinera->telefono:null), array('class'=>'form-control','placeholder'=>'Teléfono')) }}
              {{ $errors->first('telefono', '<p class="text-danger">:message</p>') }}
            </div>
					</div>
          <div class="form-group col-md-8">
              {{ Form::label('', 'Observaciones') }}
              {{ Form::textarea('observaciones', isset($gasolinera)?$gasolinera->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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
  window.onload=function () {
    $('#Menu1').addClass('active');
    $('#Menu1-1-3').addClass('active');
  };
	</script>
@stop
