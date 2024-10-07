@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($tipodeunidad))
  					<h3 class="box-title">Editar Tipo de Unidad</h3>
  				@else
  					<h3 class="box-title">Nuevo Tipo de Unidad</h3>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
  			<div class="box-body">
  					<div class="form-group col-md-4">
  						<div class="row">
                {{ Form::label('', 'Tipo de Unidad') }}
    						{{ Form::text('tipo_de_unidad', Input::old('tipo_de_unidad', isset($tipodeunidad)?$tipodeunidad->tipo_de_unidad:null), array('class'=>'form-control required','placeholder'=>'Tipo de Unidad')) }}
    						{{ $errors->first('tipo_de_unidad', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="row">
                {{ Form::label('', 'Porcentaje de Salario') }}
                {{ Form::number('porcentaje', Input::old('porcentaje', isset($tipodeunidad)?$tipodeunidad->porcentaje:null), array('class'=>'form-control required','placeholder'=>'')) }}
                {{ $errors->first('porcentaje', '<p class="text-danger">:message</p>') }}
              </div>
  					</div>
  					<div class="form-group col-md-8">
  						{{ Form::label('', 'Observaciones') }}
  						{{ Form::textarea('observaciones', isset($tipodeunidad)?$tipodeunidad->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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
    $('#Menu1-1').addClass('active');
  };
	</script>
@stop
