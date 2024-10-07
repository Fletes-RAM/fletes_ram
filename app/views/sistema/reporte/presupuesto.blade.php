@extends('dashboard.layouts.dashboard.master')

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header text-center">
          <h3 class="box-title "><b>PRESUPUESTO {{ Input::get('periodo') }} de la Cuenta: <u>{{ $ban->banco }}</u> No. Cta: <u>{{ $ban->no_cuenta }}</u></b></h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Categoría</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $x = 0;
                $periodo = Input::get('periodo');
                $banco = Input::get('bancos_id');
                $total = 0;
              ?>

              @foreach ($presupuestos as $presupuesto)
                <?php
                  if ($presupuesto->total>=0) {
                    $bgclass = "success";
                  }else{
                    $bgclass = "danger";
                  }
                ?>
                <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                  <td>{{ $presupuesto->categoria->categoria }}</td>
                  <td class="text-right"><b>$ {{ number_format($presupuesto->total,2,'.',',') }}</b></td>
                </tr>
                <tr class="{{ $bgclass }}">
                  <td colspan="2">
                    <div id="accordion{{ $i }}" class="collapse">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Subcategoría</th>
                            <th>Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $subdetalles = BancoPresupuestoDetalle::where('periodo',$periodo)
                                                        ->where('bancos_id',$banco)
                                                        ->where('categoria_id',$presupuesto->categoria_id)
                                                        ->orderBy('subcategoria_id')
                                                        ->get();
                                $subtotal = 0;
                          ?>
                          @foreach ($subdetalles as $subdetalle)
                            <tr data-toggle="collapse" data-target="#acc{{ $x }}" class="clickable {{ $bgclass }}">
                              <td>- {{ $subdetalle->subcategoria->subcategoria }}</td>
                              <td class="text-right">{{ number_format($subdetalle->total,2,'.',',') }}</td>
                              <?php $subtotal = $subtotal + $subdetalle->total; ?>
                            </tr>
                            <tr class="{{ $bgclass }}">
                              <td colspan="2">
                                <div id="acc{{ $x }}" class="collapse">
                                  <?php
                                    $detalles = BancoDetalle::where('periodo',$periodo)
                                                           ->where('bancos_id',$banco)
                                                           ->where('subcategoria_id',$subdetalle->subcategoria_id)
                                                           ->orderBy('fecha')
                                                           ->get();
                                  ?>
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Movimiento</th>
                                        <th>Observaciones</th>
                                        <th>Total</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($detalles as $detalle)
                                        <tr>
                                          <td>-- {{ $detalle->fecha }}</td>
                                          <td>{{ $detalle->folio }}</td>
                                          <td>{{ $detalle->movimiento }}</td>
                                          <td>{{ $detalle->observaciones }}</td>
                                          <td class="text-right">$ {{ number_format($detalle->total,2,'.',',') }}</td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>

                            <?php
                              $x++;
                            ?>
                          @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th class="text-right">Subtotal:</th>
                            <th class="text-right">$ {{ number_format($subtotal,2,'.',',') }}</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </td>
                </tr>
                <?php
                  $i++;
                  $total = $total + $presupuesto->total;
                ?>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th class="text-right"><h1>Total:</h1></th>
                <th class="text-right"><h1>$ {{ number_format($total,2,'.',',') }}</h1></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
@stop
