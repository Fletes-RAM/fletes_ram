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
          <a href="{{ URL::route('dieselAutorizado.create') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-toolbox fa-5x"></i><br>Agregar <br> Diesel Autorizado</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Diesel Autorizado</h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table id="diesel" class="table table-hover">
            <thead>
              <tr>
                <th>Tipo de Unidad</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Litros Autorizados</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dieselautorizado as $diesel)

                <tr>
                  <td nowrap>{{ $diesel->tipo->tipo_de_unidad }}</td>
                  <td nowrap>{{ $diesel->origen }}</td>
                  <td nowrap>{{ $diesel->destino }}</td>
                  <td nowrap align="center">{{ $diesel->lts_aut }}</td>
                  <td align="center">
                    <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Cliente" data-toggle="modal" data-target=".bs-example-modal-lg{{ $diesel->id }}"><i class="fas fa-trash"></i> Borrar</a>
                  </td>
                </tr>
                <div class="modal fade bs-example-modal-lg{{ $diesel->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-body">

                        <h1>¡Atención!</h1>
                        <h3>Se va a eliminar al Cliente: <b>{{ $diesel->tipo->tipo_de_unidad }}</b>.</h3>
                        <h3>¿Está seguro?</h3>
                        {{ Form::open(['route'=>['dieselAutorizado.destroy',$diesel->id],'id'=>'myForm'.$diesel->id,'method'=>'DELETE']) }}
                          <a href="#" onclick="document.getElementById('myForm{{ $diesel->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
                          <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fas fa-thumbs-up fa-x2"></i> Cancelar</b></button>
                        {{ Form::close() }}
                        </div>
                      </div>
                    </div>
                </div>
                
              @endforeach
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="panel-foot">
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
        $('#diesel').dataTable({
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
            "sLengthMenu": "_MENU_ Autorizaciones por página",
            "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ Autorizaciones",
            "sEmptyTable": "No se encontraron datos en la tabla",
            "sInfoEmpty": "Mostrando del 0 al 0 de 0 Autorizaciones",
            "sInfoFiltered": "(filtrado de un total de _MAX_ Autorizaciones)",
            "sLoadingRecords": "Cargando...",
            "sProcessing": "Procesando...",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron registros con la búsqueda",
            "oPaginate": {
              "sNext": "Siguiente",
              "sPrevious": "Anterior",
            }
          },
          "aaSorting": [[ 0, 'asc' ]],
        });
    });
  </script>

@stop
