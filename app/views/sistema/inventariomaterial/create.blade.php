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
          <h3 class="box-title">Editar Inventario</h3>
          <?php 
            $route = 'material.update';
            $method = 'PUT';
          ?>
        @else
          <h3 class="box-title">Nuevo Inventario</h3>
          <?php 
            $route = 'material.store';
            $method = 'POST';
          ?>
        @endif
      </div>
      {{ Form::open(['role'=>'form','id'=>'form', 'route'=>$route, 'method'=>$method]) }}
      <div class="box-body">
        <div class="form-group col-md-4">
          <div class="row">
            {{ Form::label('', 'Nombre') }}
            {{ Form::text('nombre', Input::old('nombre', isset($material)?$material->nombre:null), array('class'=>'form-control required','placeholder'=>'Nombre')) }}
            {{ $errors->first('nombre', '<p class="text-danger">:message</p>') }}
          </div>
          <div class="row">
            {{ Form::label('', 'Precio') }}
            {{ Form::text('precio', Input::old('precio', isset($material)?$material->precio:null), array('class'=>'form-control','placeholder'=>'Precio','required')) }}
            {{ $errors->first('precio', '<p class="text-danger">:message</p>') }}
          </div>
        </div>
        <div class="form-group col-md-8">
            {{ Form::label('', 'Descripción') }}
            {{ Form::textarea('descripcion', isset($material)?$material->descripcion:null, ['class'=>'form-control','id'=>'descripcion']) }}
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