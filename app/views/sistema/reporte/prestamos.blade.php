@extends('dashboard.layouts.dashboard.master')

@section('content')
  <?php
  $total_egresos = 0;
  ?>
  <table id="prestamos" class="table table-bordered table-striped table-responsive nowrap">
    <caption style="text-align: center; font-size:20px;">
      Detalle de Prestamos de: <b>{{ $operador->user->first_name }} {{ $operador->user->last_name }}</b><br>
      Periodo: del <b>{{ Input::get('fecha1') }}</b> al <b>{{ Input::get('fecha2') }}</b> <br>
    </caption>
  	<thead>
  		<tr>
        <th>Fecha</th>
        <th>Folio</th>
        <th>Categoría</th>
        <th>Subcategoría</th>
  			<th>Movimiento</th>
  			<th>Prestamo</th>
  			<th>Observaciones</th>
  		</tr>
  	</thead>
  	<tbody>
  		@foreach ($prestamos as $prestamo)
  			<tr>
  				<td nowrap>{{ $prestamo->fecha }}</td>
  				<td>{{ $prestamo->folio }}</td>
          <td>{{ $prestamo->categoria->categoria }}</td>
          <td>{{ $prestamo->subcategoria->subcategoria }}</td>
  				<td>{{ $prestamo->movimiento }}</td>
  				<td style="color:red; text-align: right;">{{ '$ '.number_format(($prestamo->cantidad * $prestamo->tipo), 2, '.', ',') }}</td>
  				<td>{{ substr(strip_tags($prestamo->observaciones),0,60) }} ...</td>
  			</tr>
        <?php $total_egresos = $total_egresos + ($prestamo->cantidad * $prestamo->tipo); ?>
  		@endforeach
  	</tbody>
  	<tfoot>
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th style="color:red; text-align: right; font-size:20px;">Total Prestamo = <b>$ {{ number_format($total_egresos, 2, '.', ',') }}</b></th>
        <th></th>
      </tr>
  	</tfoot>
  </table>

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


  <!-- page script -->
  <script type="text/javascript">
      $(function() {
          $('#prestamos').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "aaSorting": [[ 0, "asc" ]],
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ prestamos por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ prestamos",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 prestamos",
              "sInfoFiltered": "(filtrado de un total de _MAX_ prestamos)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
          });
      });
    </script>

@stop
