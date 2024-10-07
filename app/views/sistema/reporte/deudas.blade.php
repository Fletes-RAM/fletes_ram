@extends('dashboard.layouts.dashboard.master')

@section('content')
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box box-info">
				<div class="box-header">
          <h3 class="box-title"> <i class="fas fa-user-tie"></i> Cuentas por Cobrar</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
        	<div class="table-responsive">
        		<table id="deudas" class="table table-striped table-bordered table-hover table-condensed">
        			<caption style="text-align: center; font-size:20px;">
        				<?php
        					$hoy = date("Y-m-d H:i:s");
        				?>
						    Reporte de Cuentas por Cobrar emitido el día: {{ $hoy }}
						  </caption>
	        		<thead>
	        			<tr>
	        				<th>Cliente</th>
	        				<th>Adeudo</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<?php
                  $i = 0;
                  $total = 0;
                ?>
        				@foreach ($deudas as $deuda)
		        			<tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable warning">
	        					<td>{{ $deuda->cliente }}</td>
	        					<td class="text-right">$ {{ number_format($deuda->total,2,'.',',') }}</td>
		        			</tr>
		        			<?php
                    $i++;
                    $total = $total + $deuda->total;
                  ?>
        				@endforeach
	        		</tbody>
	        	</table>
        	</div>
        </div>
        <div class="box-foot">
          <div class="row text-center">
            <h1>Total Cuentas por Cobrar $ {{ number_format($total,2,'.',',') }}</h1>
          </div>
        </div>
			</div>
		</div>
	</div>


  <div class="col-md-8 col-md-offset-2">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Cotizaciones por Cobrar y sin Facturar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="cotizaciones" class="table table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Presupuesto</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $total = 0;
              ?>
              @foreach ($cotizaciones as $cotizacion)
                <tr>
                  <td>{{ $cotizacion->cliente->cliente }}</td>
                  <td class="text-right">$ {{ number_format($cotizacion->propuesta,2,'.',',') }}</td>
                </tr>
                <?php
                  $total = $total + $cotizacion->propuesta;
                ?>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-foot">
        <div class="row text-center">
          <h1>Total Cotizaciones por Cobrar y sin Facturar $ {{ number_format($total,2,'.',',') }}</h1>
        </div>
      </div>
    </div>
  </div>


  <div class="col-md-8 col-md-offset-2">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Controles Vehiculares por Cobrar y sin Facturar</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="controles" class="table table-hover">
            <thead>
              <tr>
                <th>Origen</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $total = 0;
              ?>
              @foreach ($controles as $control)
                <tr>
                  <td nowrap>{{ $control->origenes->origen }}</td>
                  <td nowrap class="text-right">$ {{ number_format((float)$control->cantidad,2,'.',',') }}</td>
                </tr>
                <?php
                  $total = $total + $control->cantidad;
                ?>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-foot">
        <div class="row text-center">
          <h1>Total Controles Vehiculares por Cobrar y sin Facturar $ {{ number_format($total,2,'.',',') }}</h1>
        </div>
      </div>
    </div>
  </div>


@stop

@section('scripts')

  <!-- page script -->
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
          $('#deudas').dataTable({
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
              "sLengthMenu": "_MENU_ clientes por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ clientes",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 clientes",
              "sInfoFiltered": "(filtrado de un total de _MAX_ clientes)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 1, 'desc' ]],
          });

          $('#deudas_fact').dataTable({
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
              "sLengthMenu": "_MENU_ clientes por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ clientes",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 clientes",
              "sInfoFiltered": "(filtrado de un total de _MAX_ clientes)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 1, 'desc' ]],
          });

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