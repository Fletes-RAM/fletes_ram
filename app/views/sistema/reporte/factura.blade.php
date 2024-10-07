@extends('dashboard.layouts.dashboard.master')

@section('content')
  <h1 class="text-center">Facturas de Clientes del {{ Input::get('fecha1') }} al {{ Input::get('fecha2') }}</h1>
  <?php
    $fac = 'vacio';
    $colores = ['info','danger','default','primary','success','warning'];
  ?>

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      @foreach ($facturas as $factura)
        <?php
          $color = array_rand($colores, 1);
        ?>
        <div class="bs-callout bs-callout-{{ $colores[$color] }}">
          <h1>
            Cliente: {{ $factura->cotizacion->cliente->cliente }}
          </h1>
          <h4>
            Factura: {{ $factura->factura }} <br>
            Cotización: {{ $factura->cotizacion->folio }} <br>
            Creada: {{ $factura->created_at }}<br>
            Cobrada: {{ $factura->fecha_pago }} <br>
            Total: $ {{ number_format($factura->total,2,'.',',') }}
          </h4>
        </div>
      @endforeach
              </tbody>
            </table>
          </div>
        </div>
    </div>
  </div>



@stop
