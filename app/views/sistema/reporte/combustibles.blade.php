@extends('dashboard.layouts.dashboard.master')

@include('notifications')

@section('content')

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"> <i class="fas fa-gas-pump"></i> Consumo de Combustible</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>Unidad</th>
                  <th>Tipo</th>
                  <th>Placas</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                  $total = 0;
                ?>
                @foreach ($unidades as $unidad)
                  <tr data-toggle="collapse" data-target="#accordion{{ $i }}" class="clickable warning">
                    <td>{{ $unidad->unidad }}</td>
                    <td>{{ $unidad->tipounidad->tipo_de_unidad }}</td>
                    <td>{{ $unidad->placas }}</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="warning">
                      <div class="collapse" id="accordion{{ $i }}">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Fecha</th>
                              <th>Ticket</th>
                              <th>Kilometraje</th>
                              <th>Rendimiento</th>
                              <th>Litros</th>
                              <th>Precio</th>
                              <th>Total</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $combustibles = ComprobanteCombustibleVista::where('unidad_id',$unidad->id)->whereBetween('fecha', [$fecha1,$fecha2])->orderBy('fecha')->orderBy('kilometraje')->get();
                              $subtotal = 0;
                              $acum = 0;
                              $bgclass = "success";
                              $km = 0;
                              $r = 0;
                              $rend = 0;
                            ?>
                            @foreach ($combustibles as $combustible)
                              <?php
                                if ($km == 0) {
                                  $rend = 0;
                                } else {
                                  $rend = ($combustible->kilometraje - $km)/$combustible->litros;
                                }
                              ?>
                              <tr>
                                <td>{{ $combustible->fecha }}</td>
                                <td><a href="{{ asset($combustible->foto_ticket) }}" target="_blank" rel="noopener noreferrer">{{ $combustible->ticket }}</a></td>
                                <td>{{ $combustible->kilometraje }}</td>
                                <td>{{ number_format($rend,2,'.',',') }}</td>
                                <td>{{ $combustible->litros }}</td>
                                <td>{{ $combustible->precio }}</td>
                                <td class="text-right">$ {{ number_format($combustible->total,2,'.',',') }}</td>
                                <td class="text-center">
                                  <a class="btn btn-sm btn-info" href="{{ URL::route('combustible.edit', [$combustible->id,'fecha1'=>$fecha1,'fecha2'=>$fecha2]) }}" data-toggle="tooltip" data-placement="top" title="Editar Combustible"><i class="fas fa-pencil-alt"></i> Editar</a>
                                </td>
                              </tr>
                              <?php
                                $subtotal = $subtotal + $combustible->total;
                                $km = $combustible->kilometraje;
                                $acum = $rend + $acum;
                                $r = $r+1;
                              ?>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="6"><b>Rendimiento Total</b></td>
                              @if ($rend == 0 || $r == 0)
                                <td class="text-right"><h4>0</h4></td>
                              @else
                                <td class="text-right"><h4>{{ number_format(($acum / ($r-1)),2,'.',',') }}</h4></td>
                              @endif
                              <td></td>
                            </tr>
                            <tr class="{{ $bgclass }}">
                              <td colspan="6"><h3><b>Subtotal</b></h3></td>
                              <td class="text-right"><h3>$ {{ number_format($subtotal,2,'.',',') }}</h3></td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </td>
                  </tr>
                  <?php
                    $i++;
                    $total = $total + $subtotal;
                  ?>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="box-foot">
          <div class="row text-center">
            <h1>Total Combustible $ {{ number_format($total,2,'.',',') }}</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

@stop
