@extends('dashboard.layouts.dashboard.master')

@section('content')
	
	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-md-8 col-md-offset-2">
  		<div class="panel panel-primary">
  			<div class="panel-heading">
  				<h3 class="panel-title">
  					<i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-toolbox"></i> Mantenimiento de Unidades
  				</h3>
  			</div>
  			<div class="panel-body">
  				{{ Form::open(['id'=>'form','route'=>'mantenimiento.store']) }}
  				<div class="row">
  					<div class="form-group col-md-4">
	            {{ Form::label('', 'Unidad') }}
	            {{ Form::select('unidad_id', [null => 'Selecciona una','Unidad'=>$unidades_list], Input::old('unidad_id', isset($mantenimiento)?$mantenimiento->unidad_id:null), ['class' => 'form-control','required']) }}
	            {{ $errors->first('unidad_id', '<p class="text-danger">:message</p>') }}
	          </div>
	          <div class="form-group col-md-4">
	            {{ Form::label('', 'Factura') }}
	            {{ Form::text('factura', Input::old('factura', isset($mantenimiento)?$mantenimiento->factura:null), array('class'=>'form-control required','placeholder'=>'Factura')) }}
	  						{{ $errors->first('factura', '<p class="text-danger">:message</p>') }}
	          </div>
	          <div class="form-group col-md-4">
	            {{ Form::label('', 'Fecha') }}
	            {{ Form::text('fecha', Input::old('fecha', isset($mantenimiento)?$mantenimiento->fecha:null), array('class'=>'form-control required','placeholder'=>'Fecha','id'=>'fecha')) }}
	  						{{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
	          </div>
  				</div>
					<div class="row">
						<div class="form-group col-md-4">
	            {{ Form::label('', 'Plazo') }}
	            {{ Form::number('plazo', Input::old('plazo', isset($mantenimiento)?$mantenimiento->plazo:null), array('class'=>'form-control required','placeholder'=>'Plazo')) }}
	  						{{ $errors->first('plazo', '<p class="text-danger">:message</p>') }}
	          </div>
	          <div class="form-group col-md-4">
	            {{ Form::label('', 'Cantidad') }}
	            {{ Form::text('cantidad', Input::old('cantidad', isset($mantenimiento)?$mantenimiento->cantidad:null), array('class'=>'form-control required','placeholder'=>'Cantidad')) }}
	  						{{ $errors->first('cantidad', '<p class="text-danger">:message</p>') }}
	          </div>
	          <div class="form-group col-md-4">
	            {{ Form::label('', 'Proveedor') }}
	            {{ Form::select('proveedor_id', [null=>'Seleccione uno']+$proveedores, Input::old('proveedor_id',null), array('class'=>'form-control required')) }}
            	{{ $errors->first('proveedor_id', '<p class="text-danger">:message</p>') }}
	          </div>
	          <div class="form-group col-md-12">
	          	{{ Form::submit('Guardar', ['class'=>'btn btn-sm btn-success']) }}
	          </div>
					</div>
  			</div>
  		</div>
  	</div>
  </div>

@stop

@section('scripts')
	<script type="text/javascript">
    $('form').validate();
    $('#fecha').datetimepicker({
				showTimePicker:false,
				format: 'yyyy-mm-dd',
	    	todayHighlight: true,
	    	autoclose: true,
				language: 'es',
				startView: 2,
				minView:2
			});
  </script>
@stop