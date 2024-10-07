@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')

  {{ Form::open(['id'=>'form','route'=>'proveedor.store','autocomplete'=>'off']) }}
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Relacion de Notas Vs. Factura</h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Ticket</th>
                  <th>Gasolinera</th>
                  <th>Litros</th>
                  <th>Precio</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $total = 0;
                  $gasolinera = "";
                  $date = date("Y-m-d");
                ?>
                @if ($ticketa != null)
                  @foreach ($ticketa as $key => $value)
                    {{ Form::hidden('id[]', 'a-'.$value->id) }}
                    <tr>
                      <td>{{ $value->fecha }}</td>
                      <td><a href="#" id="myImg{{ $value->id }}">{{ $value->ticket }}</a></td>
                      <td>
                        @if ($value->gasolinera_id != 0)
                          {{ $value->gasolinera->gasolinera }}
                        @else
                          Otra
                        @endif
                      </td>
                      <td>{{ $value->litros }}</td>
                      <td class="text-right">$ {{ number_format($value->precio,2,'.',',') }}</td>
                      <td class="text-right">$ {{ number_format($value->total,2,'.',',') }}</td>
                    </tr>
                    <?php
                      $total = $total + $value->total;
                      if ($value->gasolinera_id != 0){
                        $gasolinera = $value->gasolinera->gasolinera;
                      }else{
                        $gasolinera = "Otra";
                      }
                    ?>
                    <!-- The Modal -->
                    <div id="myModal{{ $value->id }}" class="modal">

                      <!-- The Close Button -->
                      <span class="close" id="close{{ $value->id }}"> <i class="fas fa-times fa-2x"></i> </span>

                      <!-- Modal Content (The Image) -->
                      <img class="modal-content" id="img01{{ $value->id }}" width="1500px">

                      <!-- Modal Caption (Image Text) -->
                      <div id="caption"></div>
                    </div>
                    <script type="text/javascript">
                      // Get the modal
                      var modal = document.getElementById('myModal{{ $value->id }}');

                      // Get the image and insert it inside the modal - use its "alt" text as a caption
                      var img = document.getElementById('myImg{{ $value->id }}');
                      var modalImg = document.getElementById("img01{{ $value->id }}");
                      img.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($value->foto_ticket) }}';
                      }

                      // Get the <span> element that closes the modal
                      var span = document.getElementById("close{{ $value->id }}");

                      // When the user clicks on <span> (x), close the modal
                      span.onclick = function() {
                      modal.style.display = "none";
                      }
                    </script>
                  @endforeach
                @endif
                @if ($tickete != null)
                  @foreach ($tickete as $key => $value)
                    {{ Form::hidden('id[]', 'e-'.$value->id) }}
                    <tr>
                      <td>{{ $value->fecha }}</td>
                      <td><a href="#" id="myImg{{ $value->id }}">{{ $value->ticket }}</a></td>
                      <td>
                        @if ($value->gasolinera_id != 0)
                          {{ $value->gasolinera->gasolinera }}
                        @else
                          Otra
                        @endif
                      </td>
                      <td>{{ $value->litros }}</td>
                      <td class="text-right">$ {{ number_format($value->precio,2,'.',',') }}</td>
                      <td class="text-right">$ {{ number_format($value->total,2,'.',',') }}</td>
                    </tr>
                    <?php
                      $total = $total + $value->total;
                      if ($value->gasolinera_id != 0){
                        $gasolinera = $value->gasolinera->gasolinera;
                      }else{
                        $gasolinera = "Otra";
                      }
                    ?>
                    <!-- The Modal -->
                    <div id="myModal{{ $value->id }}" class="modal">

                      <!-- The Close Button -->
                      <span class="close" id="close{{ $value->id }}"> <i class="fas fa-times fa-2x"></i> </span>

                      <!-- Modal Content (The Image) -->
                      <img class="modal-content" id="img01{{ $value->id }}" width="1500px">

                      <!-- Modal Caption (Image Text) -->
                      <div id="caption"></div>
                    </div>
                    <script type="text/javascript">
                      // Get the modal
                      var modal = document.getElementById('myModal{{ $value->id }}');

                      // Get the image and insert it inside the modal - use its "alt" text as a caption
                      var img = document.getElementById('myImg{{ $value->id }}');
                      var modalImg = document.getElementById("img01{{ $value->id }}");
                      img.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($value->foto_ticket) }}';
                      }

                      // Get the <span> element that closes the modal
                      var span = document.getElementById("close{{ $value->id }}");

                      // When the user clicks on <span> (x), close the modal
                      span.onclick = function() {
                      modal.style.display = "none";
                      }
                    </script>
                  @endforeach
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-right" colspan="5">Total:</td>
                  <td class="text-right">$ {{ number_format($total,2,'.',',') }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-md-12">
              <h1 class="text-center">Factura de {{ $gasolinera }}</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No. Factura</th>
                      <th>Fecha</th>
                      <th>Valor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        {{ Form::text('factura', null, ['class'=>'form-control required']) }}
                      </td>
                      <td>
                        {{ Form::text('fecha', $date, ['class'=>'form-control required', 'id'=>'fecha']) }}
                      </td>
                      <td>
                        {{ Form::text('valor_factura', $total, ['class'=>'form-control required']) }}
                      </td>
                    </tr>
                    <tr>
                      <th>Banco</th>
                      <th>Categoría Bancaria</th>
                      <th>Subcategoría Bancaria</th>
                    </tr>
                    <tr>
                      <td>
                        {{ Form::select('banco_id', [null=>'Seleccione uno']+$bancos, Input::old('banco_id', isset($banco_mov)?$banco_mov->banco:null), array('class'=>'form-control required')) }}
            						{{ $errors->first('banco_id', '<p class="text-danger">:message</p>') }}
                      </td>
                      <td>
                        {{ Form::select('categoria_id', [null=>'Seleccione Uno']+$categorias, isset($banco_mov)?$banco_mov->categoria_id:null, array('class'=>'form-control required','onchange'=>'cambios();','id'=>'categoria_id')) }}
        								{{ $errors->first('categoria_id', '<p class="text-danger">:message</p>') }}
                      </td>
                      <td>
                        {{ Form::select('subcategoria_id', [null=>'Seleccione Uno'], isset($banco_mov)?$banco_mov->subcategoria_id:null, array('class'=>'form-control required','id'=>'subcategoria')) }}
        								{{ $errors->first('subcategoria_id', '<p class="text-danger">:message</p>') }}
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="3">Observaciones</th>
                    </tr>
                    <tr>
                      <td colspan="3">{{ Form::textarea('observaciones', null, ['class'=>'form-control']) }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <a href="{{ URL::previous() }}" class="btn btn-danger btn-lg pull-right">Cancelar</a>
                  {{ Form::submit('Guardar', ['class'=>'btn btn-success btn-lg pull-left']) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{ Form::close() }}
@stop

@section('scripts')

  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(){
        $('#categoria_id').val('8');
        cambios();
    });
   
  </script>

  <script type="text/javascript">
    $("#form").validate();
    $( function() {
      CKEDITOR.replace('observaciones');
    } );
    $('#fecha').datetimepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true,
      language: 'es',
      startView: 2,
      minView:2
    });
    function cambios(){
  		valor = document.getElementById("categoria_id").value;
      $.get("../api/categorias",
      {
        categoria_id:valor
      },
        function(data){
          var subcat = $('#subcategoria');
          subcat.empty();
          $.each(data, function(index, element){
            subcat.append("<option value='"+ element.id +"'>" + element.subcategoria + "</option>");
          });
      });
  	}
  </script>
@stop
