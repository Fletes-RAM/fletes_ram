@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header">
          @if (isset($foraneo))
            <h3 class="box-title"><i class="fa fa-book"></i> Editar Movimiento Foráneo</h3>
            <?php
              $action = 'PUT';
              $route = ['foraneo.update',$foraneo->id];
            ?>
          @else
            <h3 class="box-title"><i class="fa fa-book"></i> Nuevo Movimiento Foráneo</h3>
            <?php
              $action = 'POST';
              $route = 'foraneo.store';
            ?>
          @endif
        </div>
        
        {{ Form::open(['role'=>'form','id'=>'form','route'=>$route,'method'=>$action]) }}
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('bancos_id', 'Banco') }}
                  {{ Form::select('bancos_id', [null=>'Seleccione uno']+$bancos, null, array('class'=>'form-control required')) }}
                {{ $errors->first('bancos_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Operador Foraneo') }}
                {{ Form::select('foraneo_operador_id', [null => 'Selecciona uno']+$operadores, Input::old('foraneo_operador_id', isset($foraneo)?$foraneo->foraneo_operador_id:null), ['class' => 'form-control','required']) }}
                {{ $errors->first('foraneo_operador_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('', 'Unidad') }}
                {{ Form::select('unidad_id', [null => 'Selecciona uno']+$unidades_list, Input::old('unidad_id', isset($foraneo)?$foraneo->unidad_id:null), ['class' => 'form-control','required']) }}
                {{ $errors->first('unidad_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                {{ Form::label('fecha', 'Fecha') }}
                {{ Form::text('fecha', Input::old('fecha', isset($foraneo)?$foraneo->fecha:null), ['class' => 'form-control','required','id'=>'fecha']) }}
                {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('concepto', 'Concepto') }}
                {{ Form::text('concepto', Input::old('concepto', isset($foraneo)?$foraneo->concepto:null), ['class' => 'form-control','required']) }}
                {{ $errors->first('concepto', '<p class="text-danger">:message</p>') }}
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Tipo') }}
                {{ 
                  Form::select('tipo', 
                    [
                      null                 => 'Selecciona uno',
                      'Pago de Fletes'     => 'Pago de Fletes',
                      'Transferencia'      => 'Transferencia',
                      'Pago a Proveedores' => 'Pago a Proveedores',
                      'Aportaciones'       => 'Aportaciones',
                      'Retiros'            => 'Retiros',
                      'Otros'              => 'Otros'
                    ], 
                    Input::old('tipo', isset($foraneo)?$foraneo->tipo:null), ['class' => 'form-control','required']) }}
                {{ $errors->first('tipo', '<p class="text-danger">:message</p>') }}
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('monto', 'Monto') }}
                {{ Form::text('monto', Input::old('monto', isset($foraneo)?$foraneo->monto:null), ['class' => 'form-control','required']) }}
                {{ $errors->first('monto', '<p class="text-danger">:message</p>') }}
              </div>
            </div>  
            <!--<div class="col-md-3">
              <div class="form-group">
                {{-- Form::label('saldo', 'Saldo') --}}
                {{-- Form::text('saldo', Input::old('saldo', isset($foraneo)?$foraneo->saldo:null), ['class' => 'form-control','required']) --}}
                {{-- $errors->first('saldo', '<p class="text-danger">:message</p>') --}}
              </div>-->
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

@section('scripts')
  <script type="text/javascript">
    $('#form').validate();

    $( function() {
      $('#fecha').datetimepicker({
        showTimePicker:false,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        language: 'es',
        startView: 2,
        minView:2
      });
    });
  </script>
@endsection