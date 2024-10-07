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
					<a href="{{ URL::route('newGasolinera') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-gas-pump fa-5x"></i><br>Agregar <br> Gasolinera</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-gas-pump"></i> Gasolineras a Crédito</h3>
				</div>
				<div class="box-body">
					<table id="gasolinera" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Gasolinera</th>
								<th>Estación</th>
								<th>Contacto</th>
								<th>Correo Electrónico</th>
								<th>Teléfono</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($gasolineras as $gasolinera)
								<tr>
									<td>{{ $gasolinera->gasolinera }}</td>
									<td>{{ $gasolinera->estacion }}</td>
									<td>{{ $gasolinera->contacto }}</td>
									<td>{{ $gasolinera->email }}</td>
									<td>{{ $gasolinera->telefono }}</td>
									<td>{{ $gasolinera->observaciones }}</td>
									<td align="center">
                    <a class="btn btn-sm btn-info" href="{{ URL::route('putGasolinera', $gasolinera->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Gasolinera Autorizada"><i class="fas fa-pencil-alt"></i> Editar</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Gasolinera Autorizada" data-toggle="modal" data-target=".bs-example-modal-lg{{ $gasolinera->id }}"><i class="fas fa-trash"></i> Borrar</a></td>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $gasolinera->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar la Gasolinera Autorizada: <b>{{ $gasolinera->gasolinera }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['deleteGasolinera',$gasolinera->id],'id'=>'myForm'.$gasolinera->id]) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $gasolinera->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
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
								<th>Gasolinera</th>
								<th>Estación</th>
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
          $('#gasolinera').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ gasolineras por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ gasolineras",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 gasolineras",
              "sInfoFiltered": "(filtrado de un total de _MAX_ gasolineras)",
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
        $('#Menu1-1-3').addClass('active');
    };
    </script>

@stop
