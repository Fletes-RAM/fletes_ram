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
					<a href="{{ URL::route('newOperador') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-id-card fa-5x"></i><br>Agregar <br> Operador</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-address-book"></i> Operadores</h3>
				</div>
				<div class="box-body">
					<table id="operador" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Operador</th>
								<th>Unidad Asignada</th>
								<th>NSS</th>
								<th>Teléfono</th>
								<th>Contacto de Emergencia</th>
								<th>Teléfono de Emergencia</th>
								<th>Vigencia de la Licencia</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($operadores as $operador)
								<tr>
									<td>{{ $operador->user->last_name }} {{ $operador->user->first_name }}</td>
									<td>
										@if ($operador->unidad_id == 0)
											Sin Unidad
										@else
											{{ $operador->unidad->unidad }} | {{ $operador->unidad->placas }}
										@endif
									</td>
									<td>{{ $operador->nss }}</td>
									<td>{{ $operador->telefono }}</td>
									<td>{{ $operador->contacto }}</td>
									<td>{{ $operador->tel_contacto }}</td>
									<td>{{ $operador->vigencia }}</td>
									<td>{{ substr(strip_tags($operador->observaciones),0,60) }} ...</td>
									<td align="center">
                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal{{ $operador->id }}"><i class="fas fa-id-card"></i> Info</a>
                    <a href="{{ URL::route('operSueldos', [$operador->user_id]) }}" class="btn btn-sm btn-default"><i class="fas fa-money-bill-alt"></i> Sueldos</a>
                    <a class="btn btn-sm btn-info" href="{{ URL::route('putOperador', $operador->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Operador"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Operador" data-toggle="modal" data-target=".bs-example-modal-lg{{ $operador->id }}"><i class="fas fa-trash"></i> Borrar</a>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $operador->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar al Operador: <b>{{ $operador->user->last_name }} {{ $operador->user->first_name }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['deleteOperador',$operador->id],'id'=>'myForm'.$operador->id]) }}
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
								<th>Operador</th>
								<th>NSS</th>
								<th>Teléfono</th>
								<th>Contacto de Emergencia</th>
								<th>Teléfono de Emergencia</th>
								<th>Vigencia de la Licencia</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>



	<!-- Modal -->
	@foreach ($operadores as $operador)
		<div class="modal fade" id="myModal{{ $operador->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Tarjeta del Operador</h4>
		      </div>
		      <div class="modal-operador">
		      	<div class="row">
						  <div class="col-sm-3" align="center">
						    <img src="{{Gravatar::src($operador->user->email, 215)}}" class="img-circle" alt="User Image" />
						  </div>
						  <div class="col-sm-9">
						    <h2 align="center">Información</h2>
						    <hr>
						    <div class="row">
						      <div class="col-xs-4 col-sm-4">
						        Usuario: <b>{{ $operador->user->username }}</b>
						      </div>
						      <div class="col-xs-4 col-sm-4">
						        Nombre: <b>{{ $operador->user->first_name }} {{ $operador->user->last_name }}</b>
						      </div>
						      <div class="col-xs-4 col-sm-4">
						        Email: <b>{{ $operador->user->email }}</b>
						      </div>
						    </div>
						    <br>
						    <div class="row">
						      <div class="col-xs-3 col-sm-3">
						        NSS: <b>{{ $operador->nss }}</b>
						      </div>
						      <div class="col-xs-3 col-sm-3">
						        Teléfono: <b>{{ $operador->telefono }}</b>
						      </div>
						      <div class="col-xs-3 col-sm-3">
						        Contacto: <b>{{ $operador->contacto }}</b>
						      </div>
						      <div class="col-xs-3 col-sm-3">
						        Teléfono Contacto: <b>{{ $operador->tel_contacto }}</b>
						      </div>
						    </div>
						    <br>
						    <div class="row">
						      <div class="col-xs-11 col-sm-11">
						        Observaciones: <br>
						        <div align="justify">
							        <b>{{ $operador->observaciones }}</b>
							      </div>
						      </div>
						    </div>
						  </div>
						</div>
						<div class="row">
						</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endforeach

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
