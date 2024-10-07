@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i> Asignación de Rutas
          </h3>
        </div>
        <div class="panel-body">
          {{ Form::open(['id'=>'form']) }}
          <div class="form-group">
            {{ Form::label('', 'Cotización') }}
            {{ Form::select('cotizacion_id', [null => 'Selecciona uno','Folio | Cliente | Ruta'=>$cotizaciones_list], Input::old('cotizacion_id', isset($asignacion)?$asignacion->cotizacion_id:null), ['class' => 'form-control buscaCotizacion','required']) }}
            {{ $errors->first('cotizacion_id', '<p class="text-danger">:message</p>') }}
          </div>
          <div class="form-group">
            {{ Form::label('', 'Operador') }}
            {{ Form::select('user_id', [null => 'Selecciona uno']+$operadores_list, Input::old('user_id', isset($asignacion)?$asignacion->user_id:null), ['class' => 'form-control buscaCliente','required']) }}
            {{ $errors->first('user_id', '<p class="text-danger">:message</p>') }}
          </div>
          <div class="form-group">
            {{ Form::label('', 'Unidad') }}
            {{ Form::select('unidad_id', [null => 'Selecciona uno', 'Unidad | Placas'=>$unidades_list], Input::old('unidad_id', isset($asignacion)?$asignacion->unidad_id:null), ['class' => 'form-control buscaUnidad','required']) }}
            {{ $errors->first('unidad_id', '<p class="text-danger">:message</p>') }}
          </div>
        </div>
        <div class="panel-footer">
          <div class="form-group">
            {{ Form::submit('Guardar',['class'=>'btn btn-success btn-lg']) }}
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')


  <script type="text/javascript">

    $(document).ready(function() {
      $('.buscaCotizacion').select2({
        language: "es"
      });
      $('.buscaCliente').select2({
        language: "es"
      });
      $('.buscaUnidad').select2({
        language: "es"
      });
    });

    $("#form").validate();

  </script>

@stop
