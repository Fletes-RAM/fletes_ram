@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')
  
  <?php
    $hoy = date('Y-m-d');
  ?>

  @if (Session::has('error'))

  	<div class="alert alert-danger alert-dismissable">
      <i class="fas fa-ban"></i>
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><i class="fas fa-times"></i></b> {{ Session::get('error') }}
  	</div>

  @endif

  <div class="row">
    <div class="col-md-3 col-md-offset-1">
      <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body" align="center">
          <a href="{{ URL::route('mantenimiento.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-toolbox fa-5x"></i><br>Agregar <br> Factura de MAntenimiento</a>
        </div>
      </div>
    </div>
  </div>

  {{ Form::open(['route'=>['mantenimiento.edit',1],'method'=>'GET']) }}
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Notas de Mantenimiento Pendientes por Pagar <small>Seleccionar mantenimientos a pagar</small> </h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table id="mantenimientos" class="table table-hover">
            <thead>
              <tr>
                <th>Seleccionar</th>
                <th>Factura</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Plazo</th>
                <th>Vence</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Diferencia</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($mantenimientos as $mantenimiento)

                <?php
                  $fecha1 = new DateTime($hoy);
                  $fecha2 = new DateTime($mantenimiento->fecha->addDay($mantenimiento->plazo));
                  $interval = $fecha1->diff($fecha2);
                  $days = $interval->format('%r%a');

                  if ($days >=15) {
                    $color = 'success';
                  } elseif ($days < 15 AND $days > 0) {
                    $color = 'warning';
                  } else {
                    $color = 'danger';
                  }
                ?>

                <tr class="{{ $color }}">
                  <td class="text-center">
                    {{ Form::checkbox('comprobante_id[]',$mantenimiento->id) }}
                  </td>
                  <td nowrap>{{ $mantenimiento->factura }}</td>
                  <td nowrap>{{ ($mantenimiento->proveedor_id != 0)?$mantenimiento->proveedor->proveedor:'Sin Proveedor Seleccionado' }}</td>
                  <td>{{ $mantenimiento->fecha }}</td>
                  <td>{{ $mantenimiento->plazo }} días</td>
                  <td>{{ $mantenimiento->fecha->addDay($mantenimiento->plazo) }}</td>
                  <td nowrap>$ {{ number_format($mantenimiento->cantidad,2,'.',',') }}</td>
                  <td>{{ $mantenimiento['unidad']->unidad }} | {{ $mantenimiento['unidad']->placas }}</td>
                  <td>{{ $interval->format('%r%a') }} días</td>
                </tr>
                
              @endforeach
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="panel-foot">
          {{ Form::submit('Guardar',['class'=>'btn btn-success btn-lg btn-block']) }}
        {{ Form::close() }}
      </div>
    </div>
  </div>


@stop

@section('scripts')

  <!-- page script -->
  {{ HTML::script('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js') }}
  {{ HTML::script('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.bootstrap.min.js') }}
  {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.colVis.min.js') }}

  <script type="text/javascript">
      $(function() {
          $('#mantenimientos').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ mantenimientos por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ mantenimientos",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 mantenimientos",
              "sInfoFiltered": "(filtrado de un total de _MAX_ mantenimientos)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 5, 'asc' ]],
          });
      });
    </script>

@stop
