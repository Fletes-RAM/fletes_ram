@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-address-book"></i> Administradores</h3>
				</div>
				<div class="box-body">
					<table id="operador" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Operador</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
                            
							@foreach ($administradores as $operador)
								<tr>
									<td>{{ $operador->last_name }} {{ $operador->first_name }}</td>
									<td align="center">
                    <a href="{{ URL::action('sueldoAdmin.show', [$operador->id]) }}" class="btn btn-sm btn-default"><i class="fas fa-money-bill-alt"></i> Sueldos</a>
									</td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Operador</th>
								<th>Acciones</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>



	<!-- Modal -->

@stop

@section('scripts')
  <!-- page script -->
  <script type="text/javascript">
      $(function() {
          $('#operador').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ operadores por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ operadores",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 operadores",
              "sInfoFiltered": "(filtrado de un total de _MAX_ operadores)",
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
        $('#Menu4').addClass('active');
    };
    </script>

@stop
