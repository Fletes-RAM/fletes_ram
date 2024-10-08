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
        <a href="{{ URL::route('llanta.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-truck-monster fa-5x"></i><br>Agregar <br> Llantas</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><i class="fas fa-truck-monster"></i> Inventario Llantas</h3>
      </div>
      <div class="box-body">
        <table id="llantas" class="table table-bordered table-striped table-responsive">
          <thead>
            <tr>
              <th>Clave</th>
              <th>Marca</th>
              <th>Medida</th>
              <th>Tipo</th>
              <th>Existencia</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($llantas as $llanta)
              <tr >
                <td>{{ $llanta->clave }}</td>
                <td>{{ $llanta->marca }}</td>
                <td class="text-right">{{ $llanta->medida }}</td>
                <td class="text-right">{{ $llanta->tipo }}</td>
                <td class="text-right">{{ number_format($llanta->existencia,2,'.',',') }}</td>
                <td><a href="{{ URL::route('llanta.show',$llanta->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Historico</a></td>
              </tr>
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
  <!-- page script -->
  <script type="text/javascript">
    $(function() {
      $('#llantas').dataTable({
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
					"sLengthMenu": "_MENU_ llantas por página",
					"sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ llantas",
					"sEmptyTable": "No se encontraron datos en la tabla",
					"sInfoEmpty": "Mostrando del 0 al 0 de 0 llantas",
					"sInfoFiltered": "(filtrado de un total de _MAX_ llantas)",
					"sLoadingRecords": "Cargando...",
					"sProcessing": "Procesando...",
					"sSearch": "Buscar:",
					"sZeroRecords": "No se encontraron registros con la búsqueda",
					"oPaginate": {
						"sNext": "Siguiente",
						"sPrevious": "Anterior",
					}
        }
      });
    });
  </script>

@stop
