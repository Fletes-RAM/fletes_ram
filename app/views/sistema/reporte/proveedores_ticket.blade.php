@extends('dashboard.layouts.dashboard.window')

@section('content')

  <h1 class="text-center">Ticket: {{ Input::get('ticket') }} </h1>
  <?php
    $fac = 'vacio';
    $colores = ['info','danger','default','primary','success','warning'];
  ?>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">


      @foreach ($tickets as $proveedor)
        <?php
          $factura = Proveedor::where('comprobante_id',$proveedor->id)->first();
          $color = array_rand($colores, 1);
          list($tipo,$i) = explode('-',$proveedor->id);
          if ($tipo == 'a') {
            $ticketa = AsignacionCombustible::find($i);
            $ticket = AsignacionCombustible::find($i);
          }
          if ($tipo == 'e') {
            $tickete = AsignacionEspecial::find($i);
            $ticket = AsignacionEspecial::find($i);
          }
        ?>
        @if ($factura != null)

          @if ($fac == 'vacio')
            <div class="bs-callout bs-callout-{{ $colores[$color] }}">
              <h4> Gasolinera:
                @if ($ticket->gasolinera_id == 0)
                  Otra
                @else
                  {{ $ticket->gasolinera->gasolinera }}
                @endif
                <br />Factura: {{ $factura->factura }}
                <br />Fecha: {{ $factura->fecha }}
                <br />Total Factura:$ {{ number_format($factura->valor_factura,2,'.',',') }}
              </h4>
              <?php $fac = $factura->factura; ?>
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
                  @elseif ($fac != $factura->factura)
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
                <br />Factura: {{ $factura->factura }}
                <br />Fecha: {{ $factura->fecha }}
                <br />Total Factura:$ {{ number_format($factura->valor_factura,2,'.',',') }}
              </h4>
              <?php $fac = $factura->factura; ?>
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
        @endif
      @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="row text-center">
          <a href="#" onclick="cerrar();" class="btn btn-lg btn-success">Cerrar ventana</a>
        </div>


    </div>
  </div>
@stop

@section('scripts')
  <script type="text/javascript">
    function cerrar() {
      window.close(); // or self.close();
    }
  </script>
@stop
