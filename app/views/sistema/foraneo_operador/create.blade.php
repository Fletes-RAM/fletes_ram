@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header">
          @if (isset($operador))
            <h3 class="box-title"><i class="fa fa-book"></i> Editar Operador Foráneo</h3>
            <?php
              $action = 'PUT';
              $route = ['foraneo_operador.update',$operador->id];
            ?>
          @else
            <h3 class="box-title"><i class="fa fa-book"></i> Nuevo Operador Foráneo</h3>
            <?php
              $action = 'POST';
              $route = 'foraneo_operador.store';
            ?>
          @endif
        </div>
        
        {{ Form::open(['role'=>'form','id'=>'form','route'=>$route,'method'=>$action]) }}
        <div class="box-body">
          <div class="row">
            <div class="form-group">
              <div class="col-md-6">
                {{ Form::label('foraneo_operador', 'Operador Foráneo') }}
                {{ Form::text('foraneo_operador', Input::old('foraneo_operador', isset($operador)?$operador->foraneo_operador:null), array('class'=>'form-control required','placeholder'=>'Operador Foráneo','id'=>'foraneo_operador')) }}
                {{ $errors->first('foraneo_operador', '<p class="text-danger">:message</p>') }}
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