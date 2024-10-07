@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')

  {{ Form::open(['id'=>'form','route'=>['mantenimiento.update',1],'autocomplete'=>'off','method'=>'PUT']) }}
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Relacion de Notas Vs. Factura</h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Unidad</th>
                  <th>Factura</th>
                  <th>Fecha</th>
                  <th>Plazo</th>
                  <th>Cantidad</th>
                  <th>Descuento</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $total = 0;
                  $subtotal = 0;
                  $factura = '';
                ?>
                @foreach ($mantenimientos as $key => $value)
                  {{ Form::hidden('id[]', 'a-'.$value->id) }}
                  <tr>
                    <td nowrap>{{ $value->unidad->unidad }} | {{ $value->unidad->placas }}</td>
                    <td nowrap>{{ $value->factura }}</td>
                    <td nowrap>{{ $value->fecha }}</td>
                    <td nowrap>{{ $value->plazo }}</td>
                    <td class="text-right">$ {{ number_format($value->cantidad,2,'.',',') }}</td>
                    {{ Form::hidden('sbt', $value->cantidad, ['id'=>'sbt-'.$value->id]) }}
                    <td class="text-right">{{ Form::number('descuento', $value->descuento, ['id'=>$value->id,'onchange'=>'calcula(this);']) }} %</td>
                    <?php
                      $descuento = ($value->descuento == '')? 0 : $value->descuento;
                      $desc = ($value->cantidad * $descuento) / 100;
                      $subtotal = $value->cantidad - $desc;
                    ?>
                    <td class="text-right">$ {{ Form::number('subtotal', $subtotal, ['id'=>'sub-'.$value->id]) }}</td>
                  </tr>
                  <?php
                    $total = $total + $subtotal;
                    $factura .= $value->factura.',';
                    $proveedor = $value->proveedor_id;
                  ?>
                @endforeach              
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-md-12">
              <h1 class="text-center">Factura de </h1>
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
                        {{ Form::text('factura', $factura, ['class'=>'form-control required']) }}
                      </td>
                      <td>
                        {{ Form::text('fecha', null, ['class'=>'form-control required', 'id'=>'fecha']) }}
                      </td>
                      <td>
                        {{ Form::text('valor_factura', $total, ['class'=>'form-control required','id'=>'valor_factura']) }}
                      </td>
                    </tr>
                    <tr>
                      <th>Banco</th>
                      <th>Categoría Bancaria</th>
                      <th>Subcategoría Bancaria</th>
                    </tr>
                    <tr>
                      <td>
                        {{ Form::select('banco_id', [null=>'Seleccione uno']+$bancos, Input::old('banco_id', isset($banco_mov)?$banco_mov->banco:null), array('class'=>'form-control required','id'=>'banco_id')) }}
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
                    <tr>
                      <th>Proveedor</th>
                    </tr>
                    <tr>
                      <td>
                        {{ Form::select('proveedor_id', [null=>'Seleccione uno']+$proveedores, Input::old('proveedor_id', isset($banco_mov)?$banco_mov->banco:$proveedor), array('class'=>'form-control required')) }}
            						{{ $errors->first('proveedor_id', '<p class="text-danger">:message</p>') }}
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
    function sleep(time)
    {
      return(new Promise(function(resolve, reject) {
        setTimeout(function() { resolve(); }, time);
      }));
    }

    document.addEventListener("DOMContentLoaded", function(){
        $('#banco_id').val('2');
        $('#categoria_id').val('18');
        cambios();
        sleep(1000).then(function() { 
          $('#subcategoria').val('20');
        });
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
    function calcula(valores) {
      val = document.getElementById("sbt-"+valores.id).value;
      sub = document.getElementById("sub-"+valores.id);
      desc = (val * valores.value) / 100;
      sub.value = val - desc;
      total = 0;
      @foreach ($mantenimientos as $key => $value)
        subtotal = document.getElementById("sub-"+{{ $value->id }});
        total = parseInt(total) + parseInt(subtotal.value);
      @endforeach
      document.getElementById("valor_factura").value = total;
    }
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
