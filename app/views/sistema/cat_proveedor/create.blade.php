@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($proveedor))
            <h3 class="box-title">Editar Proveedor</h3>
            <?php
              $action = 'PUT';
              $route = ['cat_proveedor.update',$proveedor->id];
            ?>
  				@else
            <h3 class="box-title">Nuevo Proveedor</h3>
            <?php
              $action = 'POST';
              $route = 'cat_proveedor.store';
            ?>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form','route'=>$route,'method'=>$action]) }}
  			<div class="box-body">
					<div class="form-group col-md-4">
						<div class="row">
              {{ Form::label('', 'Proveedor') }}
              {{ Form::text('proveedor', Input::old('proveedor', isset($proveedor)?$proveedor->proveedor:null), array('class'=>'form-control required','placeholder'=>'Proveedor')) }}
  						{{ $errors->first('proveedor', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Contacto') }}
              {{ Form::text('nombre_contacto', Input::old('nombre_contacto', isset($proveedor)?$proveedor->nombre_contacto:null), array('class'=>'form-control','placeholder'=>'Contacto','required')) }}
              {{ $errors->first('nombre_contacto', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Correo Electrónico') }}
              {{ Form::email('email', Input::old('email', isset($proveedor)?$proveedor->email:null), array('class'=>'form-control email','placeholder'=>'Correo Electrónico','required')) }}
              {{ $errors->first('email', '<p class="text-danger">:message</p>') }}
            </div>
             <div class="row">
              {{ Form::label('', 'Teléfono') }}
              {{ Form::text('telefono', Input::old('telefono', isset($proveedor)?$proveedor->telefono:null), array('class'=>'form-control','placeholder'=>'Teléfono')) }}
              {{ $errors->first('telefono', '<p class="text-danger">:message</p>') }}
            </div>
					</div>
          <div class="form-group col-md-8">
              {{ Form::label('', 'Observaciones') }}
              {{ Form::textarea('observaciones', isset($proveedor)?$proveedor->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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
