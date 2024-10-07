@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="box box-solid box-primary">
        <div class="box-header text-center">
          <h3 class="box-title">Presupuesto General del Periodo: <b>{{ $periodo }}</b></h3>
        </div>
        <div class="box-body">
          <table class="table table-hover table-striped table-responsive">
            <thead>
              <tr>
                <th>Categoria</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $x = 0;
                $banco = Input::get('bancos_id');
                $total = 0;
              ?>
              @if (isset($categoriasi))
                @foreach ($categoriasi as $categoria)
                  <?php
                  if ($categoria->total>=0) {
                    $bgclass = "success";
                  }else{
                    $bgclass = "danger";
                  }
                  ?>
                  <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                    <td>SALDO INICIAL *</td>
                    <td class="text-right"><b>$ {{ number_format($categoria->total,2,'.',',') }}</b></td>
                  </tr>
                  <tr class="{{ $bgclass }}">
                    <td colspan="2">
                      <div class="collapse" id="accordion{{ $i }}">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Banco</th>
                              <th>Subtotal Banco</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($bancosi as $banco)
                              <tr>
                                <td>{{ $banco->banco->banco }}</td>
                                <td class="text-right">$ {{ number_format($banco->total,2,'.',',') }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                  <?php
                    $i++;
                    $total = $categoria->total + $total;
                  ?>
                @endforeach
              @endif
              @foreach ($categoriasi2 as $categoria2)
                <?php
                if ($categoria2->total>=0) {
                  $bgclass = "success";
                }else{
                  $bgclass = "danger";
                }
                ?>
                <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                  <td>{{ $categoria2->categoria->categoria }}</td>
                  <td class="text-right"><b>$ {{ number_format($categoria2->total,2,'.',',') }}</b></td>
                </tr>
                <tr class="{{ $bgclass }}">
                  <td colspan="2">
                    <div class="collapse" id="accordion{{ $i }}">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Banco</th>
                            <th>Subtotal Banco</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($bancosi2 as $banco)
                            <tr>
                              <td>{{ $banco->banco->banco }}</td>
                              <td class="text-right">$ {{ number_format($banco->total,2,'.',',') }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
                <?php
                  $i++;
                  $total = $categoria2->total + $total;
                ?>
              @endforeach
              @foreach ($categoriasp as $categoria3)
                <?php
                if ($categoria3->total>=0) {
                  $bgclass = "success";
                }else{
                  $bgclass = "danger";
                }
                ?>
                <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                  <td>{{ $categoria3->categoria->categoria }}</td>
                  <td class="text-right"><b>$ {{ number_format($categoria3->total,2,'.',',') }}</b></td>
                </tr>
                <tr class="{{ $bgclass }}">
                  <td colspan="2">
                    <div class="collapse" id="accordion{{ $i }}">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Subcategoria</th>
                            <th>Subtotal Subcategoria</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $subcategoriasp = BancoReportePresupuesto::select(DB::raw('subcategoria_id, sum(total) as total'))
                                                        ->where('periodo',$periodo)
                                                        ->where('bancos_id','!=',7)
                                                        ->where('bancos_id','!=',9)
                                                        ->where('total', '>=', 0)
                                                        ->where('categoria_id', $categoria3->categoria_id)
                                                        ->groupBy('subcategoria_id')
                                                        ->orderBy('subcategoria_id')->get();
                          ?>
                          @foreach ($subcategoriasp as $subcategoria)
                            <?php
                            if ($subcategoria->total>=0) {
                              $bgclass = "success";
                            }else{
                              $bgclass = "danger";
                            }
                            ?>
                            <tr data-toggle="collapse" data-target="#acc{{ $x }}" class="clickable {{ $bgclass }}">
                              <td>- {{ $subcategoria->subcategoria->subcategoria }}</td>
                              <td class="text-right">$ {{ number_format($subcategoria->total,2,'.',',') }}</td>
                            </tr>
                            <tr class="{{ $bgclass }}">
                              <td colspan="2">
                                <div id="acc{{ $x }}" class="collapse">
                                  <?php
                                    $detalles = BancoDetalle::where('periodo',$periodo)
                                                            ->where('total', '>=', 0)
                                                            ->where('bancos_id','!=',7)
                                                            ->where('bancos_id','!=',9)
                                                            ->where('categoria_id', '!=', 0)
                                                            ->where('categoria_id', '!=', 4)
                                                           ->where('subcategoria_id',$subcategoria->subcategoria_id)
                                                           ->orderBy('fecha')
                                                           ->get();
                                  ?>
                                  <table class="table table-striped  table-bordered">
                                    <thead>
                                      <tr>
                                        <th>Fecha</th>
                                        <th>Banco</th>
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
                                          <td>{{ $detalle->banco->banco }} | Cta. {{ $detalle->banco->no_cuenta }}</td>
                                          <td>{{ $detalle->folio }}</td>
                                          <td>{{ $detalle->movimiento }}</td>
                                          <td>{{ $detalle->observaciones }}</td>
                                          <td class="text-right" nowrap>$ {{ number_format($detalle->total,2,'.',',') }}</td>
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
                      </table>
                    </div>
                  </td>
                </tr>
                <?php
                  $i++;
                  $total = $categoria3->total + $total;
                ?>
              @endforeach
              @foreach ($categoriasn as $categoria4)
                <?php
                if ($categoria4->total>=0) {
                  $bgclass = "success";
                }else{
                  $bgclass = "danger";
                }
                ?>
                <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                  <td>{{ $categoria4->categoria->categoria }}</td>
                  <td class="text-right"><b>$ {{ number_format($categoria4->total,2,'.',',') }}</b></td>
                </tr>
                <tr class="{{ $bgclass }}">
                  <td colspan="2">
                    <div class="collapse" id="accordion{{ $i }}">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Subcategoria</th>
                            <th>Subtotal Subcategoria</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $subcategoriasp = BancoReportePresupuesto::select(DB::raw('subcategoria_id, sum(total) as total'))
                                                        ->where('periodo',$periodo)
                                                        ->where('bancos_id','!=',7)
                                                        ->where('bancos_id','!=',9)
                                                        ->where('total', '<', 0)
                                                        ->where('categoria_id', $categoria4->categoria_id)
                                                        ->groupBy('subcategoria_id')
                                                        ->orderBy('subcategoria_id')->get();
                          ?>
                          @foreach ($subcategoriasp as $subcategoria)
                            <?php
                            if ($subcategoria->total>=0) {
                              $bgclass = "success";
                            }else{
                              $bgclass = "danger";
                            }
                            ?>
                            <tr data-toggle="collapse" data-target="#acc{{ $x }}" class="clickable {{ $bgclass }}">
                              <td>- {{ $subcategoria->subcategoria->subcategoria }}</td>
                              <td class="text-right">$ {{ number_format($subcategoria->total,2,'.',',') }}</td>
                            </tr>
                            <tr class="{{ $bgclass }}">
                              <td colspan="2">
                                <div id="acc{{ $x }}" class="collapse">
                                  <?php
                                    $detalles = BancoDetalle::where('periodo',$periodo)
                                                            ->where('total', '<', 0)
                                                            ->where('bancos_id','!=',7)
                                                            ->where('bancos_id','!=',9)
                                                            ->where('categoria_id', '!=', 0)
                                                            ->where('categoria_id', '!=', 4)
                                                           ->where('subcategoria_id',$subcategoria->subcategoria_id)
                                                           ->orderBy('fecha')
                                                           ->get();
                                  ?>
                                  <table class="table table-striped  table-bordered">
                                    <thead>
                                      <tr>
                                        <th>Fecha</th>
                                        <th>Banco</th>
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
                                          <td>{{ $detalle->banco->banco }}</td>
                                          <td>{{ $detalle->folio }}</td>
                                          <td>{{ $detalle->movimiento }}</td>
                                          <td>{{ $detalle->observaciones }}</td>
                                          <td class="text-right" nowrap>$ {{ number_format($detalle->total,2,'.',',') }}</td>
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
                      </table>
                    </div>
                  </td>
                </tr>
                <?php
                  $i++;
                  $total = $categoria4->total + $total;
                ?>
              @endforeach
              @foreach ($categoriasf as $categoria5)
                <?php
                if ($categoria5->total>=0) {
                  $bgclass = "success";
                }else{
                  $bgclass = "danger";
                }
                ?>
                <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable {{ $bgclass }}">
                  <td>SALDO FINAL *</td>
                  <td class="text-right"><b>$ {{ number_format($categoria5->total,2,'.',',') }}</b></td>
                </tr>
                <tr class="{{ $bgclass }}">
                  <td colspan="2">
                    <div class="collapse" id="accordion{{ $i }}">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Banco</th>
                            <th>Subtotal Banco</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($bancosf as $banco)
                            <tr>
                              <td>{{ $banco->banco->banco }}</td>
                              <td class="text-right">$ {{ number_format($banco->total,2,'.',',') }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
                <?php
                  $i++;
                  $total = $categoria5->total + $total;
                ?>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="box-foot text-center">
          <h1>
            POR DEFECTO O POR EXCESO
            @if ($total < 0)
              <b style="color: red;">$ {{ number_format($total,2,'.',',') }}</b>
            @elseif ($total > 0)
              <b style="color: green;">$ {{ number_format($total,2,'.',',') }}</b>
            @elseif ($total == 0)
              <b>$ {{ number_format($total,2,'.',',') }}</b>
            @endif
          </h1>
        </div>
      </div>
    </div>
  </div>

@stop
