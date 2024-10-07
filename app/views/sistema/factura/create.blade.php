@extends('dashboard.layouts.dashboard.master')

@section('content')
  @include('notifications')
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Crear Factura Nueva</h3>
      </div>
      <div class="panel-body">
        <!--<h1>-->
          <div class="row">
            <div class="col-md-12">
              Ruta: <br> <b>{{ $cotizacion->ruta->nombre }}</b>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              Folio: <br> <b>{{ $cotizacion->folio }}</b>
            </div>
            <div class="col-md-4">
              Cliente: <br> <b>{{ $cotizacion->cliente->cliente }}</b>
            </div>
            <div class="col-md-4">
              Presupuesto: <br> <b>$ {{ number_format($cotizacion->propuesta,2,'.',',') }}</b>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              Gastos Administrativos: <br> <b>$ {{ number_format($cotizacion->gastos_admon,2,'.',',') }}</b>
            </div>
            <div class="col-md-4">
              Sueldo Operador: <br> <b>$ {{ number_format($cotizacion->sueldo_ope,2,'.',',') }}</b>
            </div>
            <div class="col-md-4">
              Utilidad: <br> <b>$ {{ number_format($cotizacion->utilidad,2,'.',',') }}</b>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              Otros Gastos: <br> <b>$ {{ number_format($cotizacion->otros_gastos,2,'.',',') }}</b>
            </div>
            <div class="col-md-4">
              Casetas: <br> <b>$ {{ number_format($cotizacion->caseta,2,'.',',') }}</b>
            </div>
            <div class="col-md-4">
              Combustible presupuestado: <br> <b>$ {{ number_format($cotizacion->combustible,2,'.',',') }}</b>
            </div>
          </div>
        <!--</h1>-->
        <div class="row">
          <div class="col-md-12">
            <?php
              if (isset($asignacion)){
                $asignaciones_combustibles = AsignacionCombustible::where('asignacion_id',$asignacion->id)->get();
              } else {
                $asignaciones_combustibles = array();
              }
              $total = 0;
              $grantotal = 0;
            ?>
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Combustible</h3>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                   <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Gasolinera</th>
                        <th>Ticket</th>
                        <th>Fecha</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($asignaciones_combustibles as $asignacion_combustible)
                        <tr>
                          <td>{{ isset($asignacion_combustible->gasolinera->gasolinera)?$asignacion_combustible->gasolinera->gasolinera:'Otra' }}</td>
                          <td>{{ $asignacion_combustible->ticket }}</td>
                          <td>{{ $asignacion_combustible->fecha }}</td>
                          <td class="text-right">$ {{ number_format($asignacion_combustible->total,2,'.',',') }}</td>
                          <?php $total = $asignacion_combustible->total + $total; ?>
                        </tr>
                      @endforeach
                    </tbody>
                   </table>
                </div>
              </div>
              <div class="panel-footer text-right">
                <b><h3>Total Combustible $ {{ number_format($total,2,'.',',') }}</h3></b>
              </div>
            </div>
            <?php
              $grantotal = $cotizacion->propuesta - $cotizacion->gastos_admon - $cotizacion->sueldo_ope - $cotizacion->utilidad - $cotizacion->otros_gastos - $cotizacion->caseta - $total;
            ?>
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
                {{ Form::open(['id'=>'form','route'=>'factura.store','autocomplete'=>'off']) }}
                  {{ Form::hidden('cotizacion_id', $cotizacion->id) }}
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
                        {{ Form::text('subtotal',$cotizacion->propuesta, ['onkeyup'=>'calcula();','class' => 'form-control', 'required' => 'required']) }}
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
                        {{ Form::text('total',$cotizacion->propuesta, ['class' => 'form-control', 'required' => 'required']) }}
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
      document.getElementById('total').value = (subtotal + maniobras + otros + iva - retencion).toFixed(2);
    }
  </script>
@stop
