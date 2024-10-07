@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box box-info">
        <div class="box-header">
          @if (isset($cotizacion))
            <h3 class="box-title"><i class="fas fa-id-card"></i> Editar Cotización</h3>
          @else
            <h3 class="box-title"><i class="fas fa-id-card"></i> Nueva Cotización</h3>
          @endif
        </div>
        <div class="box-body">
          {{ Form::open(['role'=>'form','id'=>'form']) }}
            <div class="row">
              <div class="form-group col-md-4">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Cliente
                    </p>
                      {{ Form::select('cliente_id', [null=>'Seleccione Uno']+$clientes_list, isset($cotizacion)?$cotizacion->cliente_id:null, ['class'=>'form-control required buscaCliente','onChange'=>'gastos();','id'=>'cliente_id','style'=>'width: 80%;']) }}
                      {{ $errors->first('cliente_id', '<p class="text-danger">:message</p>') }}
                  </div>
                  <div class="icon">
                    <i class="fas fa-address-book"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Utilidad %
                    </p>
                    <h3>
                      {{ Form::text('utilidad', Input::old('utilidad', isset($cotizacion)?($cotizacion->utilidad/$cotizacion->propuesta)*100:25), ['class'=>'form-control','id'=>'utilidad','required','style'=>'width: 80%;']) }}
                      {{ $errors->first('utilidad', '<p class="text-danger">:message</p>') }}
                      <br>
                      $ <span id="ut">0.00</span>
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-percentage"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Gastos Administrativos %
                    </p>
                    <h3>
                      {{ Form::text('gastos_admon', Input::old('gastos_admon', isset($cotizacion)?($cotizacion->gastos_admon/$cotizacion->propuesta)*100:null), ['class'=>'form-control','id'=>'gastos_admon','required','style'=>'width: 80%;']) }}
                      {{ $errors->first('gastos_admon', '<p class="text-danger">:message</p>') }}
                      <br>
                      $ <span id="ga">0.00</span>
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-wallet"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Sueldo Operador %
                    </p>
                    <h3>
                      {{ Form::text('sueldo_ope', Input::old('sueldo_ope', isset($cotizacion)?($cotizacion->sueldo_ope/$cotizacion->propuesta)*100:null), ['class'=>'form-control','id'=>'sueldo_ope','required','style'=>'width: 80%;']) }}
                      {{ $errors->first('sueldo_ope', '<p class="text-danger">:message</p>') }}
                      <br>
                      $ <span id="so">0.00</span>
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-id-card"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Otros Gastos $
                    </p>
                    <h3>
                      {{ Form::text('otros_gastos', Input::old('otros_gastos', isset($cotizacion)?$cotizacion->otros_gastos:0), ['class'=>'form-control','id'=>'otros_gastos','required','style'=>'width: 80%;']) }}
                      {{ $errors->first('otros_gastos', '<p class="text-danger">:message</p>') }}
                      <br>
                      $ <span id="og">0.00</span>
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-edit"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Ruta
                    </p>

                      {{ Form::select('ruta_id', [null=>'Seleccione uno']+$ruta_list, isset($cotizacion)?$cotizacion->ruta_id:null, ['class'=>'form-control required busqueda','onChange'=>'ruta();','id'=>'ruta_id','style'=>'width: 80%; font-size: 12px !important;']) }}
                      {{ $errors->first('ruta_id', '<p class="text-danger">:message</p>') }}

                  </div>
                  <div class="icon">
                    <i class="fas fa-road"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Total Kilometros
                    </p>
                    <h3>
                      {{ Form::text('tot_km', Input::old('tot_km', isset($cotizacion)?$cotizacion->tot_km:0), ['class'=>'form-control','id'=>'tot_km','required','style'=>'width: 80%;','readonly']) }}
                      {{ $errors->first('tot_km', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-map-signs"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Tipo de Unidad
                    </p>
                    <h3>
                      {{ Form::select('tipo_de_unidad_id', [null=>'Seleccione uno']+$tipo_unidad_list, isset($cotizacion)?$cotizacion->tipo_de_unidad_id:null, ['class'=>'form-control required','onChange'=>'tipo_unidad();','id'=>'tipo_de_unidad_id','style'=>'width: 50%;']) }}
                      {{ $errors->first('tipo_de_unidad_id', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-truck"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Rendimiento de Combustible Lts x Km
                    </p>
                    <h3>
                      {{ Form::select('rendimiento_id', isset($cotizacion)?$rendimiento_list:[null=>'Seleccione uno'], isset($cotizacion)?$cotizacion->rendimiento_id:null, ['class'=>'form-control required','id'=>'rendimiento_id','style'=>'width: 50%;']) }}
                      {{ $errors->first('rendimiento_id', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-gas-pump"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-2">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Costo de Combustible x Lt.
                    </p>
                    <h3>
                      {{ Form::select('costo_combustible', [null=>'Seleccione uno']+$combustibles, isset($cotizacion)?$cotizacion->costo_combustible:null, ['class'=>'form-control required','id'=>'costo_combustible']) }}
                      {{ $errors->first('costo_combustible', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-gas-pump"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Total de Combustible
                    </p>
                    <h3>
                      {{ Form::text('combustible', Input::old('combustible', isset($cotizacion)?$cotizacion->combustible:0), ['class'=>'form-control','id'=>'combustible','required','readonly']) }}
                      {{ $errors->first('combustible', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-gas-pump"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <p>
                      Casetas de Cuota $
                    </p>
                    <h3>
                      {{ Form::text('caseta', Input::old('caseta', isset($cotizacion)?$cotizacion->caseta:0), ['class'=>'form-control','id'=>'caseta','required']) }}
                      {{ $errors->first('caseta', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <!-- small box -->
                <div class="small-box bg-green">
                  <div class="inner">
                    <p>
                      Propuesta
                    </p>
                    <h3>
                      {{ Form::text('propuesta', Input::old('propuesta', isset($cotizacion)?$cotizacion->propuesta:0), ['class'=>'form-control','id'=>'propuesta','required','onkeyup'=>'calcula();']) }}
                      {{ $errors->first('propuesta', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <!-- small box -->
                <div class="small-box bg-blue">
                  <div class="inner">
                    <p>
                      Observaciones
                    </p>
                    <h3>
                      {{ Form::textarea('observaciones', Input::old('observaciones', isset($cotizacion)?$cotizacion->observaciones:null), ['class'=>'form-control','id'=>'observaciones']) }}
                      {{ $errors->first('observaciones', '<p class="text-danger">:message</p>') }}
                    </h3>
                  </div>
                  <div class="icon">
                    <i class="fas fa-check"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-lg-6">
              <div id="calculobg" class="box box-solid">
                <div class="box-header">
                  <h1 class="text-center"><i class="fas fa-money-bill"></i> Total Cotización</h1>
                </div>
                <div class="box-body">
                  <h1>
                    $ <span id="calculo"></span>
                    {{ Form::hidden('calculo', null, ['id' => 'total']) }}
                  </h1>
                </div>
                <div class="box-footer">

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <a href="#" class="btn btn-warning btn-lg" onclick="calcula();"><i class="fas fa-calculator"></i> Calcular</a>
          {{ Form::submit('Guardar', array('class'=>'btn btn-primary btn-lg pull-right','id'=>'enviar')) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')
  <script type="text/javascript">
  $("#form").validate();

    $( function() {
      CKEDITOR.replace('observaciones');
    } );
    //Date picker

  function gastos() {
    valor = document.getElementById("cliente_id").value;
    if (valor=='') {
      document.getElementById("gastos_admon").value = '';
    }else{
      $.get("../api/gastos",
      {
        cliente:valor
      },
      function(data){
        document.getElementById("gastos_admon").value = data.gasto_admon;
      });
    }
  }

  function ruta() {
    valor = document.getElementById("ruta_id").value;
    if (valor=='') {
      document.getElementById("tot_km").value = '';
    }else{
      $.get("../api/rutas",
      {
        ruta:valor
      },
      function(data){
        document.getElementById("tot_km").value = data.total_km
      });
    }
  }

  function tipo_unidad() {
    valor = document.getElementById("tipo_de_unidad_id").value;
    if (valor=='') {
      document.getElementById("sueldo_ope").value = '';
    }else{
      $.get('../api/tipo_unidad',
      {
        tipo_unidad:valor
      },
      function(data){
        document.getElementById("sueldo_ope").value = data.porcentaje
      });
      $.get('../api/rendimiento',
      {
        tipo_unidad:valor
      },
      function(data){
        var rend = $('#rendimiento_id');
        rend.empty();
        $.each(data, function(index, element){
          rend.append("<option value='"+ element.id +"'>" + element.rendimiento + "</option>");
        });
      });
    }
  }

  function calcula() {
    var propuesta = document.getElementById("propuesta").value;
    var utilidad = propuesta * (document.getElementById("utilidad").value / 100);
    var sueldo_ope = propuesta * (document.getElementById("sueldo_ope").value / 100);
    var gastos_admon = propuesta * (document.getElementById("gastos_admon").value / 100);
    var otros_gastos = Number(document.getElementById("otros_gastos").value);
    var casetas = document.getElementById("caseta").value;
    var selector = document.getElementById("rendimiento_id");
    var rendimiento_id = selector[selector.selectedIndex].text;
    var selector = document.getElementById("costo_combustible");
    var costo_combustible = selector[selector.selectedIndex].value;
    var combustible = (document.getElementById("tot_km").value / rendimiento_id) * costo_combustible;
    document.getElementById("combustible").value = combustible.toFixed(2);
    var total = propuesta - utilidad - sueldo_ope - gastos_admon - otros_gastos - combustible - casetas;
    document.getElementById("ut").innerHTML = utilidad.toFixed(2);
    document.getElementById("ga").innerHTML = gastos_admon.toFixed(2);
    document.getElementById("so").innerHTML = sueldo_ope.toFixed(2);
    document.getElementById("og").innerHTML = otros_gastos.toFixed(2);
    document.getElementById("calculo").innerHTML = total.toFixed(2);
    document.getElementById("total").value = total.toFixed(2);
    if (Number(total) >= 0) {
      $('#calculobg').removeClass('bg-red');
      $('#calculobg').removeClass('box-red');
      $('#enviar').removeClass('disabled');
      $('#calculobg').addClass('bg-green');
      $('#calculobg').addClass('box-green');
    }
    if (Number(total < 0)) {
      $('#calculobg').removeClass('bg-green');
      $('#calculobg').removeClass('box-green');
      $('#calculobg').addClass('bg-red');
      $('#calculobg').addClass('box-red');
      $('#enviar').addClass('disabled');
    }
  }

	$(document).ready(function() {
    $('.busqueda').select2({
			language: "es"
		});
    $('.buscaCliente').select2({
			language: "es"
		});

	});
  </script>
@stop
