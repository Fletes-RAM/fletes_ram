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
          <h3 class="box-title">Nuevo Inventario {{ $llanta->nombre }}</h3>
          <?php 
            $route = 'llantaEntrada.store';
            $method = 'POST';
          ?>
        @endif
      </div>
      {{ Form::open(['role'=>'form','id'=>'form', 'route'=>'llantaEntrada.store', 'method'=>'POST']) }}
      <div class="box-body">
        <div class="form-group col-md-4">
          <div class="row">
            {{ Form::label('', 'Cantidad') }}
            {{ Form::text('cantidad', Input::old('cantidad', isset($llanta)?$llanta->cantidad:null), array('class'=>'form-control required','placeholder'=>'Cantidad')) }}
            {{ $errors->first('cantidad', '<p class="text-danger">:message</p>') }}

          
            <input type="hidden" name="llanta_id" id="input" class="form-control" value="{{ $llanta->id }}">
          
          </div>
          {{-- <div class="row">
            {{ Form::label('', 'Precio') }}
            {{ Form::text('precio', Input::old('precio', isset($llanta)?$llanta->precio:null), array('class'=>'form-control','placeholder'=>'Precio','required')) }}
            {{ $errors->first('precio', '<p class="text-danger">:message</p>') }}
          </div> --}}
        </div>
        <div class="form-group col-md-8">
            {{ Form::label('', 'Observaciones') }}
            {{ Form::textarea('observaciones', isset($llanta)?$llanta->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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