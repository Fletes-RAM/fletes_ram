@extends('dashboard.layouts.dashboard.master')

@section('content')

  @include('notifications')

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i> Carga de Combustible SIN Asignación de Rutas</a></h3>
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
                      <th>Fecha de Carga</th>
                      <th>Operador</th>
                      <th>Unidad</th>
                      <th>Total</th>
                      <th>Acciones</th>
                  </tr>
              </thead>

              <tbody>
                @foreach ($asignacionesepeciales as $asignacion)
                  <tr>
                    <td>{{ $asignacion->created_at }}</td>
                    <td>{{ $asignacion->user->first_name }} {{ $asignacion->user->last_name }}</td>
                    <td>{{ $asignacion->unidad->unidad }} Placas: {{ $asignacion->unidad->placas }}</td>
                    <td class="text-right">$ {{ number_format($asignacion->total,2,'.',',') }}</td>
                    <td  class="text-center">
                      <a class="btn btn-sm btn-primary" href="{{ URL::route('showAsignacionEsp', $asignacion->id) }}"><i class="fas fa-id-card"></i> Info</a>
                      <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Asignación de Ruta" data-toggle="modal" data-target=".bs-example-modal-lg{{ $asignacion->id }}"><i class="fas fa-trash"></i> Borrar</a>
                    </td>
                  </tr>
                  <div class="modal fade bs-example-modal-lg{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  								  <div class="modal-dialog modal-lg">
  								    <div class="modal-content">

  								      <div class="modal-body">

  								      <h1>¡Atención!</h1>
  								      <h3>Se va a eliminar la Carga de Combustible del Día: <b>{{ $asignacion->fecha }} </b>, <br> del la Unidad: <b>{{ $asignacion->unidad->unidad }}</b>, placas: <b>{{ $asignacion->unidad->placas }}</b> <br> por un total de: <b>{{ number_format($asignacion->total,2,'.',',') }}</b>  </h3>
  								      <h3>¿Está seguro?</h3>
  								      {{ Form::open(['route'=>['deleteAsignacionEsp',$asignacion->id],'id'=>'myForm'.$asignacion->id]) }}
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
              "sLengthMenu": "_MENU_ cargas por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ cargas",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 cargas",
              "sInfoFiltered": "(filtrado de un total de _MAX_ cargas)",
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
