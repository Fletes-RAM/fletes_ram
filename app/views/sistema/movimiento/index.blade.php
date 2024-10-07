@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <br><br>
	<div class="row">
		<div class="col-md-3">
			<div class="box box-success">
				<div class="box-header">
          <h3 class="box-title">Periodo Actual</h3>
				</div>
				<div class="box-body" align="center">
					<b><h2>{{ $periodo->periodo }}</h2></b>
				</div>
			</div>
		</div>
    <div class="col-md-3">
      <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body" align="center">
          <a href="{{ URL::route('newBancoMov') }}" class="btn btn-block btn-sq btn-success"><i class="fas fa-money-bill-alt fa-5x"></i><br>Agregar <br> Movimiento <br> Bancario</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
			<div class="box box-info">
				<div class="box-header">
				</div>
				<div class="box-body" align="center">
					<a href="{{ URL::route('newBancoPrest') }}" class="btn btn-block btn-sq btn-info"><i class="fas fa-hand-holding-usd fa-5x"></i><br>Agregar <br> Prestamo</a>
				</div>
			</div>
		</div>
	</div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"><i class="fas fa-money-bill-alt"></i> Movimientos Bancarios</h3>
        </div>
        <div class="box-body">
          <div class="row">
            @foreach ($efectivos as $efectivo)
              <div class="col-md-3">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>
                      <?php
                        $saldo = BancoSaldo::where('periodo',$periodo->periodo)->where('bancos_id',$efectivo->id)->first();
                        if (!isset($saldo)) {
                          $saldo_inicial = 0;
                        }else{
                          $saldo_inicial = $saldo->saldo_inicial;
                        }
                        //dump($saldo_inicial);

                        $movimientos = DB::table('bancos_movimientos_sum')
                                         ->select(DB::raw('periodo,bancos_id,sum(total) as total'))
                                         ->where('periodo',$periodo->periodo)
                                         ->where('bancos_id',$efectivo->id)->first();
                        if (!isset($movimientos)) {
                          $total = 0;
                        }else{
                          $total = $movimientos->total;
                        }
                        //dump($total);

                        $gran_total = $saldo_inicial + $total;
                      ?>
                      $ {{ number_format($gran_total,2,'.',',') }}
                    </h3>
                    <p>Saldo <b>{{ $efectivo->banco }}</b></p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-money-bill-alt"></i>
                  </div>
                  <a href="{{ URL::route('showBancoMov', $efectivo->id) }}"  class="small-box-footer">Detalle <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
            @endforeach
            @foreach ($bancos as $banco)
              @if ($banco->id == 7)
                @if ($currentUser->hasAccess('banco-especial'))
                  <div class="col-md-3">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h3>
                          <?php
                            $saldo = BancoSaldo::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                            if (!isset($saldo)) {
                              $saldo_inicial = 0;
                            }else{
                              $saldo_inicial = $saldo->saldo_inicial;
                            }
                            //dump($saldo_inicial);

                            $movimientos = BancoMovSum::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                            if (!isset($movimientos)) {
                              $total = 0;
                            }else{
                              $total = $movimientos->total;
                            }
                            //dump($total);

                            $gran_total = $saldo_inicial + $total;
                          ?>
                          $ {{ number_format($gran_total,2,'.',',') }}
                        </h3>
                        <p>Saldo <b>{{ $banco->banco }}</b> Cuenta: <b>{{ $banco->no_cuenta }}</b></p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-money-bill-alt"></i>
                      </div>
                      <a href="{{ URL::route('showBancoMov', $banco->id) }}"  class="small-box-footer">Detalle <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                @endif
              @elseif ($banco->id == 9)
                @if ($currentUser->hasAccess('banco-especial-2'))
                  <div class="col-md-3">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h3>
                          <?php
                            $saldo = BancoSaldo::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                            if (!isset($saldo)) {
                              $saldo_inicial = 0;
                            }else{
                              $saldo_inicial = $saldo->saldo_inicial;
                            }
                            //dump($saldo_inicial);

                            $movimientos = BancoMovSum::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                            if (!isset($movimientos)) {
                              $total = 0;
                            }else{
                              $total = $movimientos->total;
                            }
                            //dump($total);

                            $gran_total = $saldo_inicial + $total;
                          ?>
                          $ {{ number_format($gran_total,2,'.',',') }}
                        </h3>
                        <p>Saldo <b>{{ $banco->banco }}</b> Cuenta: <b>{{ $banco->no_cuenta }}</b></p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-money-bill-alt"></i>
                      </div>
                      <a href="{{ URL::route('showBancoMov', $banco->id) }}"  class="small-box-footer">Detalle <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                @endif
              @else
                <div class="col-md-3">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>
                        <?php
                          $saldo = BancoSaldo::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                          if (!isset($saldo)) {
                            $saldo_inicial = 0;
                          }else{
                            $saldo_inicial = $saldo->saldo_inicial;
                          }
                          //dump($saldo_inicial);

                          $movimientos = BancoMovSum::where('periodo',$periodo->periodo)->where('bancos_id',$banco->id)->first();
                          if (!isset($movimientos)) {
                            $total = 0;
                          }else{
                            $total = $movimientos->total;
                          }
                          //dump($total);

                          $gran_total = $saldo_inicial + $total;
                        ?>
                        $ {{ number_format($gran_total,2,'.',',') }}
                      </h3>
                      <p>Saldo <b>{{ $banco->banco }}</b> Cuenta: <b>{{ $banco->no_cuenta }}</b></p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-money-bill-alt"></i>
                    </div>
                    <a href="{{ URL::route('showBancoMov', $banco->id) }}"  class="small-box-footer">Detalle <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

@stop
