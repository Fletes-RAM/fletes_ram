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
					<a href="{{ URL::route('newRuta') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-road fa-5x"></i><br>Agregar <br> Ruta</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-road"></i> Rutas</h3>
				</div>
				<div class="box-body">
					<table id="ruta" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Ruta</th>
								<th>Total Km</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($rutas as $ruta)
								<tr>
									<td>{{ $ruta->nombre }}</td>
									<td>{{ $ruta->total_km }}</td>
									<td>{{ substr(strip_tags($ruta->observaciones),0,60) }} ...</td>
									<td align="center">
                    <a href="#" class="btn btn-sm btn-primary" onclick="detalle({{ $ruta->id }});"><i class="fas fa-road"></i> Info</a>
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Ruta" data-toggle="modal" data-target=".bs-example-modal-lg{{ $ruta->id }}"><i class="fas fa-trash"></i> Borrar</a>
									</td>
								</tr>
								<div class="modal fade bs-example-modal-lg{{ $ruta->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">

								      <div class="modal-body">

								      <h1>¡Atención!</h1>
								      <h3>Se va a eliminar la Ruta: <b>{{ $ruta->nombre }}</b>.</h3>
								      <h3>¿Está seguro?</h3>
								      {{ Form::open(['route'=>['deleteRuta',$ruta->id],'id'=>'myForm'.$ruta->id]) }}
								        <a href="#" onclick="document.getElementById('myForm{{ $ruta->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
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
								<th>Ruta</th>
								<th>Total Km</th>
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
          $('#ruta').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ rutas por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ rutas",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 rutas",
              "sInfoFiltered": "(filtrado de un total de _MAX_ rutas)",
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
		<script type="text/javascript">
			function detalle(ruta) {
				newwindow=window.open('/ruta/'+ruta+'/show','Detalle de Rutas','height=600,width=800,toolbar=0');
       	if (window.focus) {newwindow.focus()}
       	return false;
			}
		</script>

@stop
