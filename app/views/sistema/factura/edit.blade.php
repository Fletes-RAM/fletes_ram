@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')

  <div class="col-md-8 col-md-offset-2">
    <div class="bs-callout bs-callout-primary">
      <div class="row">
        <div class="col-md-3">
          <h4>Factura</h4>
          {{ $factura->factura }}
        </div>
        <div class="col-md-3">
          <h4>Cliente</h4>
          {{ $factura->cotizacion->cliente->cliente }}
        </div>
        <div class="col-md-3">
          <h4>Fecha</h4>
          {{ $factura->created_at }}
        </div>
        <div class="col-md-3">
          <h4>Total</h4>
          $ {{ number_format($factura->total,2,'.',',') }}
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h4>Observaciones</h4>
          {{ $factura->observaciones }}
        </div>
      </div>
    </div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Cobro de Factura {{ $factura->factura }}</h3>
      </div>
      <div class="panel-body">
        {{ Form::open(['id'=>'form','route'=>['factura.update',$factura->id],'autocomplete'=>'off','method'=>'PUT']) }}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('bancos_id') ? ' has-error' : '' }}">
                  {{ Form::label('bancos_id', 'Banco') }}
                  {{ Form::select('bancos_id', [null=>'Seleccione uno']+$bancos, null, array('class'=>'form-control required')) }}
                  <small class="text-danger">{{ $errors->first('bancos_id') }}</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('categoria_id') ? ' has-error' : '' }}">
                  {{ Form::label('', 'Categoría Bancario') }}
                {{ Form::select('categoria_id', [null=>'Seleccione Uno']+$categorias, isset($banco_mov)?$banco_mov->categoria_id:null, array('class'=>'form-control required','onchange'=>'cambios();','id'=>'categoria_id')) }}
                {{ $errors->first('categoria_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('subcategoria_id') ? ' has-error' : '' }}">
                  {{ Form::label('', 'Subcategoría Bancario') }}
                {{ Form::select('subcategoria_id', [null=>'Seleccione Uno'], isset($banco_mov)?$banco_mov->subcategoria_id:null, array('class'=>'form-control required','id'=>'subcategoria')) }}
                {{ $errors->first('subcategoria_id', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  {{ Form::label('', 'Fecha de Pago') }}
                  {{ Form::text('fecha', Input::old('fecha', isset($banco_mov)?$banco_mov->fecha:null), array('class'=>'form-control required','placeholder'=>'Fecha','id'=>'fecha')) }}
                {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }}">
                  {{ Form::label('observaciones', 'Observaciones') }}
                  {{ Form::textarea('observaciones',$factura->observaciones, ['class' => 'form-control', 'required' => 'required']) }}
                  <small class="text-danger">{{ $errors->first('observaciones') }}</small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              {{ Form::submit('Guardar', ['class'=>'btn btn-success']) }}
            </div>
          </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script type="text/javascript">
    $("#form").validate();
    $( function() {
      CKEDITOR.replace('observaciones');
      $('#fecha').datetimepicker({
        showTimePicker:false,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        language: 'es',
        startView: 2,
        minView:2
      });
    } );

    function cambios(){
    valor = document.getElementById("categoria_id").value;
    $.get("../../api/categorias",
    {
      categoria_id:valor
    },
      function(data){
        var subcat = $('#subcategoria');
        subcat.empty();
        $.each(data, function(index, element){
          subcat.append("<option value='"+ element.id +"'>" + element.subcategoria + "</option>");
        });
    });
  }
  </script>
@stop
