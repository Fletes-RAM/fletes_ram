@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-md-12">
    {{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
      <div class="box box-info">
        <div class="box-header">
          @if (isset($unidad))
            <h3 class="box-title"><i class="fas fa-truck"></i> Editar Unidad</h3>
          @else
            <h3 class="box-title"><i class="fas fa-truck"></i> Nueva Unidad</h3>
          @endif
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Unidad') }}
                {{ Form::text('unidad', Input::old('unidad', isset($unidad)?$unidad->unidad:null), array('class'=>'form-control','placeholder'=>'Unidad', 'required')) }}
                {{ $errors->first('unidad', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Tipo de Unidad') }}
               {{ Form::select('tipo_de_unidad_id', array(null=>'Seleccione Uno')+$tipounidad_list, isset($unidad)?$unidad->tipo_de_unidad_id:null, array('class'=>'form-control required',Input::has('tipo_de_unidad_id')?'disabled':'')) }}
                {{ $errors->first('tipo_de_unidad_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Placas') }}
                {{ Form::text('placas', Input::old('placas', isset($unidad)?$unidad->placas:null), array('class'=>'form-control','placeholder'=>'Placas', 'required')) }}
                {{ $errors->first('placas', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Número de Serie') }}
                {{ Form::text('serie', Input::old('serie', isset($unidad)?$unidad->serie:null), array('class'=>'form-control','placeholder'=>'Número de Serie', 'required')) }}
                {{ $errors->first('serie', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Póliza') }}
                {{ Form::text('poliza', Input::old('poliza', isset($unidad)?$unidad->poliza:null), array('class'=>'form-control','placeholder'=>'Póliza')) }}
                {{ $errors->first('poliza', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Aseguradora') }}
                {{ Form::text('aseguradora', Input::old('aseguradora', isset($unidad)?$unidad->aseguradora:null), array('class'=>'form-control','placeholder'=>'Aseguradora')) }}
                {{ $errors->first('aseguradora', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
						<div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Fecha de Vencimiento') }}
                {{ Form::text('vigencia', Input::old('vigencia', isset($unidad)?$unidad->vigencia:null), array('id'=>'vigencia','class'=>'form-control','placeholder'=>'Fecha de Vencimiento')) }}
                {{ $errors->first('vigencia', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
					</div>
					<div class="row">
						<div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Km Inicial en el Tablero') }}
                {{ Form::text('km_inicial', Input::old('km_inicial', isset($unidad)?$unidad->km_inicial:null), array('class'=>'form-control','placeholder'=>'Km Inicial en el Tablero')) }}
                {{ $errors->first('km_inicial', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label('', 'Observaciones') }}
                {{ Form::textarea('observaciones', isset($unidad)?$unidad->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
        </div>
      </div>
    {{ Form::close() }}
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
    $('#vigencia').datetimepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true,
      language: 'es',
      startView: 2,
      minView:2
    });
	</script>
@stop
