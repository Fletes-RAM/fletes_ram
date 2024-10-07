@extends('dashboard.layouts.dashboard.master')

@section('content')
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fas fa-pallet"></i> Inventario Materiales</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-4">Nombre: <b>{{ $material->nombre }}</b></div>
              <div class="col-md-6">Descripcion: <b>{{ $material->descripcion }}</b></div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">Precio: <b>$ {{ number_format($material->precio,2,'.',',') }}</b></div>
              <div class="col-md-4">Existencia: <b>{{ $material->existencia }}</b></div>
              <div class="col-md-4">Valor Inventario: <b>$ {{ number_format($material->valor,2,'.',',') }}</b></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fas fa-pallet"></i> Inventario Materiales Entradas</h3>
            <div class="box-tools pull-right">
              <a href="{{ URL::route('materialEntrada.create','id='.$material->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Agregar Entrada</a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="entradas" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Observaciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($materialEntrada as $entrada)
                          <tr>
                            <td>{{ $entrada->created_at }}</td>
                            <td class="text-right">{{ $entrada->cantidad }}</td>
                            <td class="text-right">$ {{ number_format($entrada->precio,2,'.',',') }}</td>
                            <td>{{ $entrada->observaciones }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fas fa-pallet"></i> Inventario Materiales Salidas</h3>
            <div class="box-tools pull-right">
              <a href="{{ URL::route('materialSalida.create','id='.$material->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-minus"></i> Agregar Salida</a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="salidas" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Observaciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($materialSalida as $salida)
                          <tr>
                            <td>{{ $salida->created_at }}</td>
                            <td>{{ $salida->unidad->unidad }} | {{ $salida->unidad->placas }}</td>
                            <td class="text-right">{{ $salida->cantidad }}</td>
                            <td>{{ $salida->observaciones }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
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
      $('#entradas').dataTable({
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
					"sLengthMenu": "_MENU_ entradas por página",
					"sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ entradas",
					"sEmptyTable": "No se encontraron datos en la tabla",
					"sInfoEmpty": "Mostrando del 0 al 0 de 0 entradas",
					"sInfoFiltered": "(filtrado de un total de _MAX_ entradas)",
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

      $('#salidas').dataTable({
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
					"sLengthMenu": "_MENU_ salidas por página",
					"sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ salidas",
					"sEmptyTable": "No se encontraron datos en la tabla",
					"sInfoEmpty": "Mostrando del 0 al 0 de 0 salidas",
					"sInfoFiltered": "(filtrado de un total de _MAX_ salidas)",
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
