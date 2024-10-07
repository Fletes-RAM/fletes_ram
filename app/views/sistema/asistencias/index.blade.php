@extends('dashboard.layouts.dashboard.master')

@section('content')

    <div class="row">
        @include('notifications')
    </div>

    <!-- Solid boxes -->
    <br><br>
    <div class="row">
        {{-- <div class="col-md-3">
            <div class="box box-success">
                <div class="box-header">
                </div>
                <div class="box-body" align="center">
                    <a href="{{ URL::route('asistencia.show',[0]) }}" class="btn btn-block btn-sq btn-success"><i
                                class="fas fa-check fa-5x"></i><br>Reporte de <br> Asistencias</a>
                </div>
            </div>
        </div> --}}
        <div class="col-md-3">
            <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">Reporte de Asistencias</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      {{ Form::open(['method'=>'get','action'=>['AsistenciaController@show',0],'id'=>'form4']) }}
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
    </div>
    {{ Form::open(['role'=>'form','id'=>'form','action'=>'AsistenciaController@store']) }}
    <!-- Solid boxes -->

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><i class="fas fa-address-book"></i> Clientes</h3>
                </div>
                <div class="box-body">
                    <table id="trabajadores" class="table table-bordered table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Asistencia</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($trabajadores as $trabajador)
                            @if(is_null($trabajador->deleted_at))
                                <tr>
                                    <td>{{ $trabajador->last_name }} {{ $trabajador->first_name }}</td>
                                    <td>
                                        {{ Form::hidden('trabajador[]', $trabajador->id) }}
                                        {{ Form::select('trabajador1[]', ['Asistencia'=>'Asistencia','Falta'=>'Falta','Vacaciones'=>'Vacaciones'], 'Asistencia', ['class' => 'form-control','required']) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Nombre</th>
                            <th>Asistencia</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header">
                </div>
                <div class="box-body" align="center">
                    {{ Form::submit('Guardar asistencia del día '. date('d-M-Y'), array('class'=>'btn btn-primary')) }}
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}

@stop

@section('scripts')
    <!-- page script -->
    
    <script type="text/javascript">
        
  $(function(){
    $('#reservation2').daterangepicker({
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 días': [moment().subtract(6, 'days'), moment()],
        '30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "alwaysShowCalendars": true,
      "showDropdowns": true,
      "showWeekNumbers": false,
      "opens": "center",
      "drops": "down",
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

  });

    </script>

@stop
