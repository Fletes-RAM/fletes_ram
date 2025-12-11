@extends('dashboard.layouts.dashboard.master')

@section('content')
  <style>
  .bg-success-soft {
      background-color: #d4edda !important;
  }

  .bg-warning-soft {
      background-color: #fff3cd !important;
  }

  .bg-danger-soft {
      background-color: #f8d7da !important;
  }
  </style>
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
        <h3 class="panel-title">
          Notas de Combustible Pendientes por Pagar
          <small>Seleccionar comprobantes a pagar</small>
        </h3>
      </div>
      <div class="panel-body">

        {{-- NUEVO: Botón para asignar factura / crédito --}}
        <div class="row" style="margin-bottom: 10px;">
          <div class="col-md-6">
            <button type="button"
                    id="btnAsignarFactura"
                    class="btn btn-primary"
                    disabled>
              Asignar factura / crédito a seleccionados
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table id="comprobantes" class="table table-hover">
            <thead>
              <tr>
                {{-- NUEVO: checkbox general --}}
                <th class="text-center">
                  <input type="checkbox" id="check_all">
                </th>
                <th>Fecha</th>
                <th>Ticket</th>
                <th>Operador</th>
                <th>Gasolinera</th>
                <th>Litros</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Factura</th>
                <th>Fecha límite</th>
                <th>Días restantes</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comprobantes as $comprobante)
                <?php
                    $fechaLimite = $comprobante->fecha_limite_pago
                        ? \Carbon\Carbon::parse($comprobante->fecha_limite_pago)
                        : null;
                    $hoy = \Carbon\Carbon::today();
                    $diasRestantes = $fechaLimite ? $hoy->diffInDays($fechaLimite, false) : null;

                    $rowClass = '';
                    if (!is_null($diasRestantes)) {
                        if ($diasRestantes > 5) {
                            $rowClass = 'bg-success-soft'; // verde suave
                        } elseif ($diasRestantes >= 0) {
                            $rowClass = 'bg-warning-soft'; // amarillo suave
                        } else {
                            $rowClass = 'bg-danger-soft';  // rojo suave
                        }
                    }
                ?>
                <tr class="{{ $rowClass }}">
                  <td class="text-center">
                    {{-- NUEVO: agregamos clase para identificarlos con JS --}}
                    {{ Form::checkbox('comprobante_id[]', $comprobante->id, false, ['class' => 'comprobante-check']) }}
                  </td>
                  <td nowrap>{{ $comprobante->fecha }}</td>
                  <td><a href="#" id="myImg{{ $comprobante->id }}">{{ $comprobante->ticket }}</a></td>
                  <td>
                    @if ($comprobante->user_id == null)
                      Sin Operador
                    @else
                      {{ $comprobante->users->first_name }} {{ $comprobante->users->last_name }}
                    @endif
                  </td>
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
                  <td>{{ $comprobante->factura_proveedor ?? '-' }}</td>
                
                  <td>
                      @if ($fechaLimite)
                          {{ $fechaLimite->format('Y-m-d') }}
                      @else
                          -
                      @endif
                  </td>

                  <td>
                      @if (!is_null($diasRestantes))
                              {{ $diasRestantes }} días
                      @else
                          -
                      @endif
                  </td>
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
      <div class="panel-foot" style="padding: 15px;">
        {{ Form::submit('Guardar',['class'=>'btn btn-success btn-lg btn-block']) }}
        {{ Form::close() }}
      </div>
    </div>
  </div>

  {{-- NUEVO: Modal para asignar factura y días de crédito --}}
  <div class="modal fade" id="modalAsignarFactura" tabindex="-1" role="dialog" aria-labelledby="modalAsignarFacturaLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modalAsignarFacturaLabel">
            Asignar factura / crédito a comprobantes seleccionados
          </h4>
        </div>

        {{ Form::open(['route' => 'proveedor.asignarFactura', 'method' => 'POST', 'id' => 'formAsignarFactura']) }}
        <div class="modal-body">

          {{-- IDs de comprobantes seleccionados (lleno por JS) --}}
          <input type="hidden" name="comprobantes_ids" id="comprobantes_ids">

          <div class="form-group">
            {{ Form::label('factura_proveedor', 'Número de factura del proveedor') }}
            {{ Form::text('factura_proveedor', null, ['class' => 'form-control', 'required' => true]) }}
          </div>

          <div class="form-group">
              {{ Form::label('fecha_limite_pago', 'Fecha límite de pago') }}
              {{ Form::text('fecha_limite_pago', null, ['class' => 'form-control date-picker', 'required' => true, 'readonly' => true]) }}
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
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
  {{ HTML::script('packages/datetimepicker/js/bootstrap-datetimepicker.min.js') }}
  {{ HTML::script('packages/datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

  <script type="text/javascript">

      function initDatePicker() {
          $('.date-picker').datetimepicker({
              format: 'yyyy-mm-dd',
              minView: 2,      // solo fecha (sin horas)
              autoclose: true,
              todayBtn: true,
              language: 'es'
          });
      }

      // Inicializa al cargar
      initDatePicker();

      // Y vuelve a inicializar cada vez que abres el modal (por si DataTables/redibujos)
      $('#modalAsignarFactura').on('shown.bs.modal', function () {
          initDatePicker();
      });

      $(function() {

          // Inicializa DataTables y guarda la instancia
          var table = $('#comprobantes').DataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                  "oPaginate": { "sNext": "Siguiente", "sPrevious": "Anterior" }
              },
              "aaSorting": [[ 1, "desc" ]]
          });

          var $btn       = $('#btnAsignarFactura');
          var $hiddenIds = $('#comprobantes_ids');
          var $checkAll  = $('#check_all');

          function actualizarBoton() {
              // IMPORTANTE: table.$() incluye checkboxes de todas las páginas
              var seleccionados = table.$('.comprobante-check:checked').length;
              $btn.prop('disabled', seleccionados === 0);
          }

          // ✅ Evento delegado: funciona aunque cambies de página
          $('#comprobantes').on('change', '.comprobante-check', function () {
              // Si desmarcas alguno visible, desmarcamos "select all"
              if (!$(this).is(':checked')) {
                  $checkAll.prop('checked', false);
              }
              actualizarBoton();
          });

          // ✅ Select all: aplica a TODAS las páginas usando table.$
          $checkAll.on('change', function () {
              var checked = $(this).is(':checked');
              table.$('.comprobante-check').prop('checked', checked);
              actualizarBoton();
          });

          // ✅ Si DataTables redibuja (paginate, search, sort), recalculamos estado del botón
          table.on('draw', function () {
              actualizarBoton();
          });

          // ✅ Click del botón: toma IDs seleccionados en TODAS las páginas
          $btn.on('click', function () {
              var ids = [];
              table.$('.comprobante-check:checked').each(function () {
                  ids.push($(this).val());
              });

              if (ids.length === 0) {
                  alert('Selecciona al menos un comprobante.');
                  return;
              }

              $hiddenIds.val(ids.join(','));
              $('#modalAsignarFactura').modal('show');
          });

          // Estado inicial
          actualizarBoton();
      });
  </script>

@stop
