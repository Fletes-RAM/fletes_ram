@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')
  @if (Session::has('error'))

  	<div class="alert alert-danger alert-dismissable">
      <i class="fas fa-ban"></i>
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><i class="fas fa-times"></i></b> {{ Session::get('error') }}
  	</div>

  @endif

  {{ Form::open(['route'=>'proveedor.create','method'=>'GET']) }}
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Notas de Combustible Pendientes por Pagar <small>Seleccionar comprobantes a pagar</small> </h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table id="comprobantes" class="table table-hover">
            <thead>
              <tr>
                <th>Seleccionar</th>
                <th>Fecha</th>
                <th>Ticket</th>
                <th>Operador</th>
                <th>Gasolinera</th>
                <th>Litros</th>
                <th>Precio</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comprobantes as $comprobante)
                <tr>
                  <td class="text-center">
                    {{ Form::checkbox('comprobante_id[]',$comprobante->id) }}
                  </td>
                  <td nowrap>{{ $comprobante->fecha }}</td>
                  <td><a href="#" id="myImg{{ $comprobante->id }}">{{ $comprobante->ticket }}</a></td>
                  <td>
                    @if ($comprobante->user_id == null)
                      Sin Operador
                    @else
                      {{ $comprobante->users->first_name }} {{ $comprobante->users->last_name }}</td>
                    @endif
                  <td>
                    @if ($comprobante->gasolinera_id == 0)
                      Otra
                    @else
                      {{ $comprobante->gasolineras->gasolinera }}
                    @endif
                  </td>
                  <td nowrap>{{ number_format($comprobante->litros,2,'.',',') }}</td>
                  <td nowrap>$ {{ number_format($comprobante->precio,2,'.',',') }}</td>
                  <td nowrap>$ {{ number_format($comprobante->total,2,'.',',') }}</td>
                </tr>
                <!-- The Modal -->
                <div id="myModal{{ $comprobante->id }}" class="modal">

                  <!-- The Close Button -->
                  <span class="close" id="close{{ $comprobante->id }}"> <i class="fas fa-times fa-2x"></i> </span>

                  <!-- Modal Content (The Image) -->
                  <img class="modal-content" id="img01{{ $comprobante->id }}" width="1500px">

                  <!-- Modal Caption (Image Text) -->
                  <div id="caption"></div>
                </div>
                <script type="text/javascript">
                  // Get the modal
                  var modal = document.getElementById('myModal{{ $comprobante->id }}');

                  // Get the image and insert it inside the modal - use its "alt" text as a caption
                  var img = document.getElementById('myImg{{ $comprobante->id }}');
                  var modalImg = document.getElementById("img01{{ $comprobante->id }}");
                  img.onclick = function(){
                    modal.style.display = "block";
                    modalImg.src = '{{ asset($comprobante->foto_ticket) }}';
                  }

                  // Get the <span> element that closes the modal
                  var span = document.getElementById("close{{ $comprobante->id }}");

                  // When the user clicks on <span> (x), close the modal
                  span.onclick = function() {
                  modal.style.display = "none";
                  }
                </script>
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
          $('#comprobantes').dataTable({
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
              "sLengthMenu": "_MENU_ comprobantes por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ comprobantes",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 comprobantes",
              "sInfoFiltered": "(filtrado de un total de _MAX_ comprobantes)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 1, "desc" ]],
          });
      });
    </script>

@stop
