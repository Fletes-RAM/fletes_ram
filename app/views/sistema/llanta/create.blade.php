@extends('dashboard.layouts.dashboard.master')

@section('content')
<div class="row">
  @include('notifications')
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="box box-info">
      <div class="box-header">
        @if (isset($llanta))
          <h3 class="box-title">Editar Inventario</h3>
          <?php 
            $route = 'llanta.update';
            $method = 'PUT';
          ?>
        @else
          <h3 class="box-title">Nuevo Inventario</h3>
          <?php 
            $route = 'llanta.store';
            $method = 'POST';
          ?>
        @endif
      </div>
      {{ Form::open(['role'=>'form','id'=>'form', 'route'=>$route, 'method'=>$method]) }}
      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <div class="row">
                {{ Form::label('', 'Clave') }}
                {{ Form::text('clave', Input::old('clave', isset($llanta)?$llanta->clave:null), array('class'=>'form-control required','placeholder'=>'Clave')) }}
                {{ $errors->first('clave', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="row">
                {{ Form::label('', 'Marca') }}
                {{ Form::text('marca', Input::old('marca', isset($llanta)?$llanta->marca:null), array('class'=>'form-control','placeholder'=>'Marca','required')) }}
                {{ $errors->first('marca', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <div class="row">
              {{ Form::label('', 'Medida') }}
              {{ Form::text('medida', Input::old('medida', isset($llanta)?$llanta->medida:null), array('class'=>'form-control','placeholder'=>'Medida','required')) }}
              {{ $errors->first('medida', '<p class="text-danger">:message</p>') }}
            </div>
            <div class="row">
              {{ Form::label('', 'Tipo') }}
              {{ Form::select('tipo', [null => 'Selecciona uno', 'Original' => 'Original', 'Vitalizada' => 'Vitalizada'], Input::old('tipo', isset($operador)?$operador->tipo:null), ['class' => 'form-control buscaCotizacion','required']) }}
              {{ $errors->first('tipo', '<p class="text-danger">:message</p>') }}
            </div>
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

@stop