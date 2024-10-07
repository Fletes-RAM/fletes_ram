@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Crear Factura Nueva</h3>
      </div>
      <div class="panel-body">
        
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Origen</th>
                <th>Folio</th>
                <th>Toneladas</th>
                <th>Tarifa</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php $grantotal = 0; ?>
              @foreach ($controles as $control)
                <tr>
                  <td>{{ $control->origenes->origen }}</td>
                  <td>{{ $control->control_vehicular }}</td>
                  <td>{{ number_format((float)$control->toneladas,2,'.',',') }}</td>
                  <td>$ {{ number_format((float)$control->tarifa,2,'.',',') }}</td>
                  <td class="text-right">$ {{ number_format((float)$control->cantidad,2,'.',',') }}</td>
                </tr>
                <?php $grantotal = $grantotal + (float)$control->cantidad; ?>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">TOTAL:</th>
                <th  class="text-right">$ {{ number_format($grantotal,2,'.',',') }}</th>
              </tr>
            </tfoot>
          </table>
        </div>

        <h1>
          
          <div class="row">
            <div class="col-md-6">
              Cliente: <br> 
              {{ Form::open(['id'=>'form','route'=>'control.update','autocomplete'=>'off','method'=>'PUT']) }}
              {{ Form::select('cliente_id', [null=>'Seleccione Uno']+$clientes_list, isset($cotizacion)?$cotizacion->cliente_id:6, ['class'=>'form-control required buscaCliente','onChange'=>'gastos();','id'=>'cliente_id','style'=>'width: 80%;']) }}
              {{ $errors->first('cliente_id', '<p class="text-danger">:message</p>') }}
            </div>
          </div>
        </h1>
        <div class="row">
          <div class="col-md-12">
                        
            @if ($grantotal >= 0 )
              <div class="bs-callout bs-callout-success">
                <h1><b>Utilidad</b></h1>
            @else
              <div class="bs-callout bs-callout-danger">
                <h1><b>Perdida</b></h1>
            @endif
                <h2> <b>$ {{ number_format($grantotal,2,'.',',') }}</b> </h2>
              </div>

            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Factura por Cobrar</h3>
              </div>
              <div class="panel-body">
                  @foreach ($controles as $control)
                    {{-- Form::hidden('ctrls[]', $control->id) --}}
                    <input type="hidden" name="ctrls[]" value="{{ $control->id }}">
                  @endforeach
                  {{-- Form::hidden('controles', $controles) --}}
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('factura') ? ' has-error' : '' }}">
                        {{ Form::label('factura', 'Factura RAM') }}
                        {{ Form::text('factura',null, ['class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('factura') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('subtotal') ? ' has-error' : '' }}">
                        {{ Form::label('subtotal', 'Sub Total') }}
                        {{ Form::text('subtotal',$grantotal, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('subtotal') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('maniobras') ? ' has-error' : '' }}">
                        {{ Form::label('maniobras', 'Maniobras') }}
                        {{ Form::text('maniobras',0, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('maniobras') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('otros') ? ' has-error' : '' }}">
                        {{ Form::label('otros', 'Otros Cargos') }}
                        {{ Form::text('otros',0, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('otros') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('iva') ? ' has-error' : '' }}">
                        {{ Form::label('iva', 'IVA') }}
                        {{ Form::text('iva',0, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('iva') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group{{ $errors->has('retencion') ? ' has-error' : '' }}">
                        {{ Form::label('retencion', 'Retención') }}
                        {{ Form::text('retencion',0, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('retencion') }}</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('total') ? ' has-error' : '' }}">
                        {{ Form::label('total', 'Total') }}
                        {{ Form::text('total',$control->propuesta, ['class' => 'form-control', 'required' => 'required']) }}
                        <small class="text-danger">{{ $errors->first('total') }}</small>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }}">
                      {{ Form::label('observaciones', 'Observaciones') }}
                      {{ Form::textarea('observaciones',null, ['class' => 'form-control']) }}
                      <small class="text-danger">{{ $errors->first('observaciones') }}</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    {{ Form::submit('Guardar Factura', ['class'=>'btn btn-primary']) }}
                  </div>
                {{ Form::close() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script type="text/javascript">
    $("#form").validate();
    $( function() {
      CKEDITOR.replace('observaciones');
    } );

    function calcula(){
      var subtotal = Number(document.getElementById("subtotal").value);
      var maniobras = Number(document.getElementById("maniobras").value);
      var otros = Number(document.getElementById("otros").value);
      var retencion = document.getElementById("retencion").value;
      var iva = ((subtotal + maniobras + otros) * (.16));
      var retencion = (subtotal * .04);
      

      document.getElementById('iva').value = iva.toFixed(2);
      document.getElementById('retencion').value = retencion.toFixed(2);
      document.getElementById('total').value = subtotal + maniobras + otros + iva - retencion;
    }
  </script>
@stop
