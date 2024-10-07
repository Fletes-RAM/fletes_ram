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
					<a href="{{ URL::route('newBanco') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-piggy-bank fa-5x"></i><br>Agregar <br> Banco</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-piggy-bank"></i> Banco</h3>
				</div>
				<div class="box-body">
					<table id="bancos" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Banco</th>
								<th>Cuenta</th>
								<th>CLABE</th>
								<th>Observaciónes</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($bancos as $banco)
								<tr>
									<td>{{ $banco->banco }}</td>
									<td>{{ $banco->no_cuenta }}</td>
									<td>{{ $banco->clabe }}</td>
									<td>{{ substr(strip_tags($banco->observaciones),0,60) }}</td>
									<td align="center">
                    <a class="btn btn-sm btn-info" href="{{ URL::route('putBanco', $banco->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Banco"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Banco" data-toggle="modal" data-target=".bs-example-modal-lg{{ $banco->id }}"><i class="fas fa-trash"></i> Borrar</a></td>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $banco->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar el Banco: <b>{{ $banco->banco }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['deleteBanco',$banco->id],'id'=>'myForm'.$banco->id,'autocomplete'=>'off']) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $banco->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
								        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-x2"></i> Cancelar</b></button>
								      {{ Form::close() }}
								      </div>
								    </div>
								  </div>
								</div>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Banco</th>
								<th>Cuenta</th>
								<th>CLABE</th>
								<th>Observaciónes</th>
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
          $('#bancos').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ tipos de unidades por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ tipos de unidades",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 tipos de unidades",
              "sInfoFiltered": "(filtrado de un total de _MAX_ tipos de unidades)",
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
