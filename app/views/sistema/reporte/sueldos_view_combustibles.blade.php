<div class="row">
    <div class="col-md-4">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3>Facturas</h3></div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Factura</th>
                  <th>Fecha de Pago</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0; ?>
                @foreach ($facturas as $factura)
                  <tr>
                    <td>{{ $factura->factura }}</td>
                    <td>{{ $factura->fecha_pago }}</td>
                    <td class="text-right">$ {{ number_format($factura->total,2,'.',',') }}</td>
                  </tr>
                  <?php $total = $total + $factura->total; ?>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2">Total</th>
                  <th class="text-right">$ {{ number_format($total,2,'.',',') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-warning">
        <div class="panel-heading"><h3>Gasto Combustibles</h3></div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Factura</th>
                  <th>Ticket</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php $total_combustible = 0; ?>
                @foreach ($combustibles as $combustible)
                  <tr>
                    <td>{{ $combustible->factura }}</td>
                    <td>{{ $combustible->ticket }}</td>
                    <td class="text-right">$ {{ number_format($combustible->total_ticket,2,'.',',') }}</td>
                  </tr>
                  <?php $total_combustible = $total_combustible + $combustible->total_ticket; ?>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2">Total Gasto Combustible</th>
                  <th class="text-right">$ {{ number_format($total_combustible,2,'.',',') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-warning">
          <div class="panel-heading"><h3>Gastos Varios</h3></div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $total_gastos = 0; ?>
                  @foreach ($gastos as $gasto)
                    <tr>
                      <td>{{ $gasto->fecha }}</td>
                      <td>{{ $gasto->descripcion }}</td>
                      <td class="text-right">$ {{ number_format($gasto->total,2,'.',',') }}</td>
                    </tr>
                    <?php $total_gastos = $total_gastos + $gasto->total; ?>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2">Total Gastos Varios</th>
                    <th class="text-right">$ {{ number_format($total_gastos,2,'.',',') }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

  </div>

  <?php
    $tot = $total - $total_combustible - $total_gastos;
    $titulo = '';
    if($tot>0){
      $alert = 'alert-success';
      $titulo = 'Ganancia';
    } else {
      $alert = 'alert-danger';
      $titulo = 'Perdida';
    }
  ?>

  <div class="row">
    <div class="col-md-12">
        <div class="alert {{ $alert }} text-center"  role="alert">
          <h1>{{ $titulo }} $ {{ number_format($tot,2,'.',',') }}</h1>
          @if ($total != 0)
            <h2>Utilidad de {{ $titulo }} {{ $tot / $total }}</h2>
          @endif
        </div>
    </div>
  </div>