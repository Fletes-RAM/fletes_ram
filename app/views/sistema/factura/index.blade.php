@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')
  <?php
    $hoy = date('Y-m-d');
  ?>

  <div class="col-md-8 col-md-offset-2">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Facturas por Cobrar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="facturas" class="table table-hover">
            <thead>
              <tr>
                <th>Cotización</th>
                <th>Cliente</th>
                <th>Factura</th>
                <th>Fecha Emisión</th>
                <th>Diferencia</th>
                <th>Total Factura</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($facturas as $factura)
                <?php
                $fecha1 = new DateTime($hoy);
                $fecha2 = new DateTime($factura->created_at);
                $interval = $fecha1->diff($fecha2);
                $days = $interval->format('%r%a');
                $days = $days * -1;

                if ($days >=15) {
                  $color = 'danger';
                } elseif ($days < 15 AND $days > 10) {
                  $color = 'warning';
                } else {
                  $color = 'success';
                }
                ?>
                <tr class="{{ $color }}" onclick="document.location='{{ URL::route('factura.edit', ['id'=>$factura->id]) }}';">
                  <td>{{ $factura->cotizacion->folio }}</td>
                  <td>{{ $factura->cotizacion->cliente->cliente }}</td>
                  <td>{{ $factura->factura }}</td>
                  <td>{{ $factura->created_at }}</td>
                  <td>{{ $days }} días</td>
                  <td class="text-right">$ {{ number_format($factura->total,2,'.',',') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-md-offset-2">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Facturas de Controles Vehiculares por Cobrar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="facturas_controles" class="table table-hover">
            <thead>
              <tr>
                <th>Control Vehicular</th>
                <th>Origen</th>
                <th>Cliente</th>
                <th>Factura</th>
                <th>Fecha Emisión</th>
                <th>Diferencia</th>
                <th>Total Factura</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($facturas_controles as $factura_control)
                <?php
                $fecha1 = new DateTime($hoy);
                $fecha2 = new DateTime($factura_control->created_at);
                $interval = $fecha1->diff($fecha2);
                $days = $interval->format('%r%a');
                $days = $days * -1;

                if ($days >=15) {
                  $color = 'danger';
                } elseif ($days < 15 AND $days > 10) {
                  $color = 'warning';
                } else {
                  $color = 'success';
                }
                ?>
                <tr class="{{ $color }}" onclick="document.location='{{ URL::route('facturacontrol.edit', ['id'=>$factura_control->id]) }}';">
                  <td>{{ isset($factura_control->control->control_vehicular)?$factura_control->control->control_vehicular:'Multi Control Vehicular' }}</td>
                  <td>{{ isset($factura_control->control->origenes->origen)?$factura_control->control->origenes->origen:'Multi Origen' }}</td>
                  <td>{{ $factura_control->cliente->cliente }}</td>
                  <td>{{ $factura_control->factura }}</td>
                  <td>{{ $factura_control->created_at }}</td>
                  <td>{{ $days }} días</td>
                  <td class="text-right">$ {{ number_format($factura_control->total,2,'.',',') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-md-offset-2">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Cotizaciones por Cobrar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="cotizaciones" class="table table-hover">
            <thead>
              <tr>
                <th>Cotización</th>
                <th>Cliente</th>
                <th>Ruta</th>
                <th>Presupuesto</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cotizaciones as $cotizacion)
                <tr onclick="document.location='{{ URL::route('factura.create', ['id'=>$cotizacion->id]) }}';">
                  <td>{{ $cotizacion->folio }}</td>
                  <td>{{ $cotizacion->cliente->cliente }}</td>
                  <td>{{ $cotizacion->ruta->nombre }}</td>
                  <td class="text-right">$ {{ number_format($cotizacion->propuesta,2,'.',',') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{ Form::open(['route'=>['control.edit',1],'method'=>'GET']) }}
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Controles Vehiculares por Cobrar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="controles" class="table table-hover">
            <thead>
              <tr>
                <th>Seleccionar</th>
                <th>Fecha</th>
                <th>Control Vehicular</th>
                <th>Origen</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($controles as $control)
                <tr>
                  <td class="text-center">
                    {{ Form::checkbox('comprobante_id[]',$control->id) }}
                  </td>
                  <td nowrap>{{ $control->fecha }}</td>
                  <td nowrap>{{ $control->control_vehicular }}</td>
                  <td nowrap>{{ $control->origenes->origen }}</td>
                  <td nowrap class="text-right">$ {{ number_format((float)$control->cantidad,2,'.',',') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-foot">
          {{ Form::submit('Guardar',['class'=>'btn btn-success btn-lg btn-block']) }}
        {{ Form::close() }}
      </div>
    </div>
  </div>
@stop

@section('scripts')

  {{ HTML::script('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js') }}
  {{ HTML::script('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.bootstrap.min.js') }}
  {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.colVis.min.js') }}

  <script type="text/javascript">
      $(function() {
          $('#cotizaciones').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ cotizaciones por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ cotizaciones",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 cotizaciones",
              "sInfoFiltered": "(filtrado de un total de _MAX_ cotizaciones)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 0, "desc" ]],
          });

          $('#facturas').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ facturas por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ facturas",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 facturas",
              "sInfoFiltered": "(filtrado de un total de _MAX_ facturas)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 3, "asc" ]],
          });

          $('#facturas_controles').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ facturas de controles vehiculares por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ facturas de controles vehiculares",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 facturas de controles vehiculares",
              "sInfoFiltered": "(filtrado de un total de _MAX_ facturas de controles vehiculares)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 4, "asc" ]],
          });

          $('#controles').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ controles por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ controles",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 controles",
              "sInfoFiltered": "(filtrado de un total de _MAX_ controles)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 1, "asc" ]],
          });
      });
    </script>

@stop
