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
					<a href="{{ URL::route('foraneo_operador.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-id-card fa-5x"></i><br>Agregar <br> Operador Foraneo</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-address-book"></i> Operadores Foráneos</h3>
				</div>
				<div class="box-body">
					<table id="operador" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Operador Foráneo</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($operadores as $operador)
								<tr>
									<td>{{ $operador->foraneo_operador }}</td>
									<td align="center">
                    <a class="btn btn-sm btn-info" href="{{ URL::route('foraneo_operador.edit', $operador->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Operador"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Operador" data-toggle="modal" data-target=".bs-example-modal-lg{{ $operador->id }}"><i class="fas fa-trash"></i> Borrar</a>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $operador->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar al Operador: <b>{{ $operador->foraneo_operador }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['foraneo_operador.destroy',$operador->id],'id'=>'myForm'.$operador->id,'method'=>'DELETE']) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $operador->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
								        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
								      {{ Form::close() }}
								      </div>
								    </div>
								  </div>
								</div>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Operador Foráneo</th>
								<th>Acciones</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

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
