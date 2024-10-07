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
					<a href="{{ URL::route('newAsignacion') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-user fa-5x" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i><br>Agregar <br> Asignación</a>
				</div>
			</div>
		</div>
	</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i> Asignación de Rutas</a></h3>
        </div>
        <div class="panel-body">
          <table id="asignaciones" class="table table-striped table-responsive"
              aria-labelled-by="" aria-described-by="">

              <!-- Optional Caption -->
              <caption></caption>

              <!-- Optional Colgroup -->
              <colgroup span="" width="">
                  <col span="" width="">
              </colgroup>

              <thead>
                  <tr>
                      <th>Cotización</th>
                      <th>Asignada a</th>
                      <th>Unidad</th>
                      <th>Terminada</th>
                      <th>Acciones</th>
                  </tr>
              </thead>

              <tbody>
                @foreach ($asignaciones as $asignacion)
                    <tr>
                      <td>{{ $asignacion->cotizacion->folio }}</td>
                      <td>{{ $asignacion->operador->first_name }} {{ $asignacion->operador->last_name }}</td>
                      <td>
                          @if($asignacion && $asignacion->unidad)
                            {{ $asignacion->unidad->unidad }} Placas: {{ $asignacion->unidad->placas }}
                          @else
                            Información no disponible
                          @endif
                      </td>
                      <td>{{ $asignacion->terminado }}</td>
                      <td  class="text-center">
                        <a  class="btn-sm btn btn-warning" href="{{ URL::route('putAsignacion', $asignacion->id) }}" data-placement="top" title="Terminar Ruta">
                          <i class="fas fa-warehouse"></i>
                          Terminar Ruta
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{ URL::route('showAsignacion', $asignacion->id) }}"><i class="fas fa-id-card"></i> Info</a>
                        <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Asignación de Ruta" data-toggle="modal" data-target=".bs-example-modal-lg{{ $asignacion->id }}"><i class="fas fa-trash"></i> Borrar</a>
                      </td>
                    </tr>

                    <div class="modal fade bs-example-modal-lg{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-body">

                            <h1>¡Atención!</h1>
                            <h3>Se va a eliminar la Asignación de Ruta con Folio: <b>{{-- $asignacion->cotizacion->folio --}} </b>.</h3>
                            <h3>¿Está seguro?</h3>
                            {{ Form::open(['route'=>['deleteAsignacion',$asignacion->id],'id'=>'myForm'.$asignacion->id]) }}
                            <a href="#" onclick="document.getElementById('myForm{{ $asignacion->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
                            <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fas fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                            {{ Form::close() }}
                          </div>
                        </div>
                      </div>
                    </div>
                @endforeach
              </tbody>

          </table>
        </div>
        <div class="panel-footer"></div>
      </div>
    </div>
  </div>

@stop

@section('scripts')

  <script type="text/javascript">
      $(function() {
          $('#asignaciones').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ asignaciones por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ asignaciones",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 asignaciones",
              "sInfoFiltered": "(filtrado de un total de _MAX_ asignaciones)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 0, "desc" ]],
          });
      });
    </script>

@stop
