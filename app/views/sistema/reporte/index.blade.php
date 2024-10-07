@extends('dashboard.layouts.dashboard.master')

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header">
          <h3 class="box-title">Presupuesto General</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="box-group" id="accordion">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
              <div class="box-header">
                <h4 class="box-title">
                  Seleccione un periodo
                </h4>
              </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      {{ Form::open(['method'=>'get','action'=>'ReporteController@presupuesto','id'=>'form']) }}
                      {{ Form::select('periodo', [null=>'Seleccione un Periodo']+$periodo, null, ['class'=>'form-control','required']) }}
                    </div>
                  </div>
                  <br>
                  <div class="text-center row">
                    {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
                    {{ Form::close() }}
                  </div>
                </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->

    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header">
          <h3 class="box-title">Presupuesto Detallado</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="box-group" id="accordion">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
              <div class="box-header">
                <h4 class="box-title">
                  Seleccione un periodo
                </h4>
              </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      {{ Form::open(['method'=>'get','action'=>'ReporteController@reporte','id'=>'form1']) }}
                      {{ Form::select('periodo', [null=>'Seleccione un Periodo']+$periodo, null, ['class'=>'form-control','required']) }}
                      <br><br>
                      {{ Form::select('bancos_id', [null=>'Seleccione una cuenta']+$bancos, null, ['class'=>'form-control','required']) }}
                    </div>
                  </div>
                  <br>
                  <div class="text-center row">
                    {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
                    {{ Form::close() }}
                  </div>
                </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header">
          <h3 class="box-title">Presupuesto Anual</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="box-group" id="accordion">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
              <div class="box-header">
                <h4 class="box-title">
                  Seleccione un periodo
                </h4>
              </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      {{ Form::open(['method'=>'get','action'=>'ReporteController@presupuestoYear','id'=>'form1']) }}
                      {{ Form::select('periodo', [null=>'Seleccione un Periodo']+$anno, null, ['class'=>'form-control','required']) }}
                      
                    </div>
                  </div>
                  <br>
                  <div class="text-center row">
                    {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
                    {{ Form::close() }}
                  </div>
                </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Prestamos Personales</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@prestamos','id'=>'form2']) }}
              <div class="form-group">
                {{ Form::label('', 'Operador') }}
                {{ Form::select('operador', [null=>'Selecciona Uno','Operador'=>$operadores], null, ['class'=>'form-control buscaOperador','required']) }}
              </div>
              <!-- Date range -->
              <div class="form-group">
                <label>Rango de Fechas:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation"/>
                  <input type="hidden" name="fecha1" id="fecha1" value="">
                  <input type="hidden" name="fecha2" id="fecha2" value="">
                </div><!-- /.input group -->
              </div><!-- /.form group -->
            </div>
          </div>
        </div>
        <div class="text-center panel-footer">
          {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Salario</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@sueldos','id'=>'form3']) }}
              <div class="form-group">
                {{ Form::label('', 'Operador') }}
                {{ Form::select('operador', [null=>'Selecciona Uno','Administrativo'=>$administradores,'Operador'=>$operadores], null, ['class'=>'form-control buscaOperador','required']) }}
              </div>
              <!-- Date range -->
              <div class="form-group">
                <label>Rango de Fechas:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation1"/>
                  <input type="hidden" name="fecha1" id="fecha1a" value="">
                  <input type="hidden" name="fecha2" id="fecha2a" value="">
                </div><!-- /.input group -->
              </div><!-- /.form group -->
            </div>
          </div>
        </div>
        <div class="text-center panel-footer">
          {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Consumo de Combustible</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@combustibles','id'=>'form4']) }}
              <!-- Date range -->
              <div class="form-group">
                <label>Rango de Fechas:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation2"/>
                  <input type="hidden" name="fecha1" id="fecha1b" value="">
                  <input type="hidden" name="fecha2" id="fecha2b" value="">
                </div><!-- /.input group -->
              </div><!-- /.form group -->
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
          {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Facturas de Proveedores</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@proveedores','id'=>'form5']) }}
              <!-- Date range -->
              <div class="form-group">
                <label>Rango de Fechas:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation3"/>
                  <input type="hidden" name="fecha1" id="fecha1c" value="">
                  <input type="hidden" name="fecha2" id="fecha2c" value="">
                </div><!-- /.input group -->
              </div><!-- /.form group -->
              {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
              {{ Form::close() }}
            </div>
            <div class="col-md-4">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@proveedoresticket','id'=>'form6']) }}
                <div class="form-group">
                  <label>Ticket Combustible</label>
                  <input name="ticket" type="text" class="form-control" id="ticket" placeholder="Ticket Combustible">
                </div>
              {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
              {{ Form::close() }}
            </div>
            <div class="col-md-4">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@proveedoresfactura','id'=>'form7']) }}
                <div class="form-group">
                  <label>Factura Proveedor</label>
                  <input name="factura" type="text" class="form-control" id="factura" placeholder="Factura Proveedor">
                </div>
              {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
              {{ Form::close() }}
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Facturas de Clientes</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@facturas','id'=>'form8']) }}
              <!-- Date range -->
              <div class="form-group">
                <label>Rango de Fechas:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation4"/>
                  <input type="hidden" name="fecha1" id="fecha1d" value="">
                  <input type="hidden" name="fecha2" id="fecha2d" value="">
                </div><!-- /.input group -->
              </div><!-- /.form group -->
              {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
              {{ Form::close() }}
            </div>
            <div class="col-md-4">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@facturasticket','id'=>'form9']) }}
                <div class="form-group">
                  <label>Factura</label>
                  <input name="ticket" type="text" class="form-control" id="ticket" placeholder="Factura">
                </div>
              {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
              {{ Form::close() }}
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Cuentas por Cobrar</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ URL::route('showDeudas') }}" class="btn btn-success">Ver Cuentas por Cobrar</a>
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Reporte Unidades/Operadores</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@unidadoperador','id'=>'form8']) }}
              <div class="form-group">
                <label>Totales por Operador</label>
                {{ Form::select('rango', [
                                            null=>'Selecciona un rango',
                                            '1'=>'Del 6 de Junio al 31 de Diciembre del 2019',
                                            '2'=>'Del 1 de Enero al 31 de Diciembre del 2020',
                                            '3'=>'Del 1 de Enero al 31 de Diciembre del 2021',
                                            '4'=>'Del 1 de Enero al 31 de Diciembre del 2022',
                                            '5'=>'Del 1 de Enero al 31 de Diciembre del 2023',
                                            '6'=>'Del 1 de Enero al 31 de Diciembre del 2024'
                                            ], null, ['class'=>'form-control','required']) }}
              </div>
              
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
          {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>

    @if ($currentUser->hasAccess('view-reporte-cliente'))
    <div class="col-md-6">
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title">Reporte Mensual Clientes</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(['method'=>'get','action'=>'ReporteController@clientesmensual','id'=>'form10']) }}
              <div class="form-group">
                <label>Totales por Cliente</label>
                {{ Form::select('rango', [
                                            null=>'Selecciona un rango',
                                            '1'=>'2018',
                                            '2'=>'2019',
                                            '3'=>'2020',
                                            '4'=>'2021',
                                            '5'=>'2022',
                                            '6'=>'2023',
                                            '7'=>'2024'
                                            ], null, ['class'=>'form-control','required']) }}
              </div>
            </div>
          </div>
        </div>
        <div class="text-center box-footer">
          {{ Form::submit('Enviar', ['class'=>'btn btn-success btn-bg text-center']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
    @endif


  </div>

@stop

@section('scripts')
  {{ HTML::script('js/locales/bootstrap-datepicker.es.js') }}

  <script type="text/javascript">
  $(document).ready(function() {
    $('.buscaOperador').select2({
      language: "es"
    });
  });

  $("#form").validate();
  $("#form1").validate();
  $("#form2").validate();
  $("#form3").validate();
  $("#form4").validate();
  $("#form5").validate();

  $(function(){
    $('#reservation').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2').value = end.format('YYYY-MM-DD');
    });

    $('#reservation1').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1a').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2a').value = end.format('YYYY-MM-DD');
    });

    $('#reservation2').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1b').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2b').value = end.format('YYYY-MM-DD');
    });

    $('#reservation3').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1c').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2c').value = end.format('YYYY-MM-DD');
    });

    $('#reservation4').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1d').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2d').value = end.format('YYYY-MM-DD');
    });

    $('#reservation5').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": true,
      "opens": "center",
      "drops": "up",
      "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Del",
        "toLabel": "Al",
        "customRangeLabel": "Personalizar",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Augosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      },
    },
      function(start, end) {
        console.log('Nuevo rango de fechas seleccionado: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        document.getElementById('fecha1e').value = start.format('YYYY-MM-DD');
        document.getElementById('fecha2e').value = end.format('YYYY-MM-DD');
    });


  });



  var myForm = document.getElementById('form6');
    myForm.onsubmit = function() {
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600,left = 312,top = 234');
    this.target = 'Popup_Window';
  };

  var myForm = document.getElementById('form7');
    myForm.onsubmit = function() {
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600,left = 312,top = 234');
    this.target = 'Popup_Window';
  };
</script>

@stop
