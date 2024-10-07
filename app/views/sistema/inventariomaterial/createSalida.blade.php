@extends('dashboard.layouts.dashboard.master')

@section('content')
<div class="row">
  @include('notifications')
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="box box-info">
      <div class="box-header">
        @if (isset($material))
          <h3 class="box-title">Nuevo Inventario {{ $material->nombre }}</h3>
          <?php 
            $route = 'materialSalida.store';
            $method = 'POST';
          ?>
        @endif
      </div>
      {{ Form::open(['role'=>'form','id'=>'form', 'route'=>$route, 'method'=>$method]) }}
      <div class="box-body">
        <div class="form-group col-md-4">
          <div class="form-group">
            {{ Form::label('', 'Unidad') }}
            {{ Form::select('unidad_id', [0 => 'Selecciona uno', 'Unidad | Placas'=>$unidades_list], Input::old('unidad_id', isset($operador)?$operador->unidad_id:null), ['class' => 'form-control buscaCotizacion','required']) }}
            {{ $errors->first('unidad_id', '<p class="text-danger">:message</p>') }}
          </div>
          <div class="row">
            {{ Form::label('', 'Cantidad') }}
            {{ Form::text('cantidad', Input::old('cantidad', isset($material)?$material->cantidad:null), array('class'=>'form-control required','placeholder'=>'Cantidad')) }}
            {{ $errors->first('cantidad', '<p class="text-danger">:message</p>') }}

          
            <input type="hidden" name="inventariomaterial_id" id="input" class="form-control" value="{{ $material->id }}">
          
          </div>
        </div>
        <div class="form-group col-md-8">
            {{ Form::label('', 'Observaciones') }}
            {{ Form::textarea('observaciones', isset($material)?$material->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
        </div>
      </div>
      <div class="box-footer">
      {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
      </div>
        {{ Form::close() }}
    </div>
  </div>
</div>

@stop