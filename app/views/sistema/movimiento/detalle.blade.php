@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <!-- Solid boxes -->
	<br><br>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-success">
				<div class="box-header">
          <h3 class="box-title">
            Detalle de Movimientos: <b>{{ $banco->banco }}</b> <br>
            Número de Cuenta: <b>{{ $banco->no_cuenta }}</b> <br>
            CLABE: <b>{{ $banco->clabe }}</b> <br>
            Periodo: <b>{{ $periodo->periodo }}</b>
          </h3>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-money-check-alt"></i> Detalle</h3>
				</div>
				<div class="box-body">
					@include('sistema.movimiento.tabla')
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


  <!-- page script -->
  <script type="text/javascript">
      $(function() {
          $('#detalle').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "aaSorting": [[ 1, "asc" ]],
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ movimientos por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ movimientos",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 movimientos",
              "sInfoFiltered": "(filtrado de un total de _MAX_ movimientos)",
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
   	window.onload=function () {
        $('#Menu7').addClass('active');
    };
    </script>

@stop
