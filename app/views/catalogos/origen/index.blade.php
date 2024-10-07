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
					<a href="{{ URL::route('catalogos.origen.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-check fa-5x"></i><br>Agregar <br> Origen</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-check"></i> Origen</h3>
				</div>
				<div class="box-body">
					<table id="origenes" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Origen</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($origenes as $origen)
								<tr>
									<td>{{ $origen->origen }}</td>
									<td align="center">
                    <a class="btn btn-sm btn-info" href="{{ URL::route('catalogos.origen.edit', $origen->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Banco"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Banco" data-toggle="modal" data-target=".bs-example-modal-lg{{ $origen->id }}"><i class="fas fa-trash"></i> Borrar</a></td>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $origen->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar el Banco: <b>{{ $origen->banco }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['catalogos.origen.destroy',$origen->id],'id'=>'myForm'.$origen->id]) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $origen->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
								        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-x2"></i> Cancelar</b></button>
								      {{ Form::close() }}
								      </div>
								    </div>
								  </div>
								</div>
							@endforeach
						</tbody>
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
          $('#origenes').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ tipos de origen por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ tipos de origen",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 tipos de origen",
              "sInfoFiltered": "(filtrado de un total de _MAX_ tipos de origen)",
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
        $('#Menu1').addClass('active');
        $('#Menu1-1-1').addClass('active');
    };
    </script>

@stop
