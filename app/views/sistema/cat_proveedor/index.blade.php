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
					<a href="{{ URL::route('cat_proveedor.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-user-tie fa-5x"></i><br>Agregar <br> Proveedor</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-user-tie"></i> Proveedores</h3>
				</div>
				<div class="box-body">
					<table id="proveedor" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Proveedor</th>
								<th>Contacto</th>
								<th>Correo Electrónico</th>
								<th>Teléfono</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($proveedores as $proveedor)
								<tr>
									<td>{{ $proveedor->proveedor }}</td>
									<td>{{ $proveedor->nombre_contacto }}</td>
									<td>{{ $proveedor->email }}</td>
									<td>{{ $proveedor->telefono }}</td>
									<td>{{ substr(strip_tags($proveedor->observaciones),0,60) }} ...</td>
									<td align="center">
                    <a class="btn btn-sm btn-info" href="{{ URL::route('cat_proveedor.edit', $proveedor->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Proveedor"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Proveedor" data-toggle="modal" data-target=".bs-example-modal-lg{{ $proveedor->id }}"><i class="fas fa-trash"></i> Borrar</a>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $proveedor->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar al Proveedor: <b>{{ $proveedor->proveedor }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['method'=>'DELETE','route'=>['cat_proveedor.destroy',$proveedor->id],'id'=>'myForm'.$proveedor->id]) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $proveedor->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash"></i> Borrar</b></a>
								        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up"></i> Cancelar</b></button>
								      {{ Form::close() }}
								      </div>
								    </div>
								  </div>
								</div>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Proveedor</th>
								<th>Contacto</th>
								<th>Correo Electrónico</th>
								<th>Teléfono</th>
								<th>Observaciones</th>
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
          $('#proveedor').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ proveedores por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ proveedores",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 proveedores",
              "sInfoFiltered": "(filtrado de un total de _MAX_ proveedores)",
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
