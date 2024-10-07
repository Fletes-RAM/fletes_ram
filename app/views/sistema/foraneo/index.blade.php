@extends('dashboard.layouts.dashboard.master')

@section('content')
	<div class="row">
    @include('notifications')
  </div>

  <!-- Solid boxes -->
	<br><br>
	<div class="row">
		<div class="col-md-3">
			<div class="box box-success">
				<div class="box-header">
				</div>
				<div class="box-body" align="center">
					<a href="{{ URL::route('foraneo.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-id-card fa-5x"></i><br>Agregar <br> Movimiento</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
				</div>
				<div class="box-body" align="center">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="operador">
							<thead>
								<tr>
									<th>Operador Foraneo</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($foraneos as $foraneo)
									<tr>
										<td>{{ $foraneo->foraneo_operador }}</td>
										<td class="text-right">${{ number_format($foraneo->saldo,2,'.',',') }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

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
