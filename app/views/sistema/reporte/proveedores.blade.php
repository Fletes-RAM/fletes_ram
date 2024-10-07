@extends('dashboard.layouts.dashboard.master')

@section('content')
  <h1 class="text-center">Facturas de Proveedores del {{ Input::get('fecha1') }} al {{ Input::get('fecha2') }}</h1>
  <?php
    $fac = 'vacio';
    $colores = ['info','danger','default','primary','success','warning'];
  ?>

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      @foreach ($proveedores as $proveedor)
        <?php
          $color = array_rand($colores, 1);
          list($tipo,$i) = explode('-',$proveedor->comprobante_id);
          if ($tipo == 'a') {
            $ticketa = AsignacionCombustible::find($i);
            $ticket = AsignacionCombustible::find($i);
          }
          if ($tipo == 'e') {
            $tickete = AsignacionEspecial::find($i);
            $ticket = AsignacionEspecial::find($i);
          }
        ?>
        @if ($fac == 'vacio')
          <div class="bs-callout bs-callout-{{ $colores[$color] }}">
            <h4> Gasolinera:
              @if ($ticket->gasolinera_id == 0)
                Otra
              @else
                {{ $ticket->gasolinera->gasolinera }}
              @endif
               <br />Factura: {{ $proveedor->factura }}
               <br />Fecha: {{ $proveedor->fecha }}
               <br />Total Factura:$ {{ number_format($proveedor->valor_factura,2,'.',',') }}
            </h4>
          <?php $fac = $proveedor->factura; ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Ticket</th>
                  <th>Operador</th>
                  <th>Litros</th>
                  <th>Precio</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
        @elseif ($fac != $proveedor->factura)
                </tbody>
              </table>
            </div>
          </div>
          <div class="bs-callout bs-callout-{{ $colores[$color] }}">
            <h4> Gasolinera:
              @if ($ticket->gasolinera_id == 0)
                Otra
              @else
                {{ $ticket->gasolinera->gasolinera }}
              @endif
               <br />Factura: {{ $proveedor->factura }}
               <br />Fecha: {{ $proveedor->fecha }}
               <br />Total Factura:$ {{ number_format($proveedor->valor_factura,2,'.',',') }}
            </h4>
          <?php $fac = $proveedor->factura; ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Ticket</th>
                  <th>Operador</th>
                  <th>Litros</th>
                  <th>Precio</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
        @endif
        <tr>
          <td>{{ $ticket->fecha }}</td>
          <td>{{ $ticket->ticket }}</td>
          @if ($tipo == 'a')
            @if (isset($ticket->asignacion->user_id))
              <td>{{ $ticket->asignacion->operador->first_name }} {{ $ticket->asignacion->operador->last_name }}</td>
            @else
              <td>Sin Operador</td>
            @endif
          @else
            <td>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</td>
          @endif
          <td class="text-right">{{ number_format($ticket->litros,2,'.',',') }}</td>
          <td class="text-right">$ {{ number_format($ticket->precio,2,'.',',') }}</td>
          <td class="text-right">$ {{ number_format($ticket->total,2,'.',',') }}</td>
        </tr>
      @endforeach
              </tbody>
            </table>
          </div>
        </div>
    </div>
  </div>



@stop
