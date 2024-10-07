@extends('dashboard.layouts.dashboard.master')

@section('content')
    
  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-book"></i> Bitácora Unidad: {{$unidad->unidad }} Placas: {{ $unidad->placas }} </h3>
        </div>

        <div class="box-body">
          <a href=" {{ URL::route('bitacora.create', ['unidad_id'=>Input::get('unidad_id')] ) }} ">+ Nuevo Evento</a> <br><br>
          <!-- The time line -->
          <ul class="timeline">
            @foreach ($bitacoras as $bitacora)
              <!-- timeline time label -->
              <li class="time-label">
                <span class="bg-red">
                  {{ $bitacora->fecha }}
                </span>
              </li>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <li>
                  <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock"></i> </span>
                      <h3 class="timeline-header"><a href="#"> {{ $bitacora->titulo }} </a></h3>
                      <div class="timeline-body">


                          <h5>
                            <b>Proveedor:</b> {{ $bitacora->proveedor->proveedor }}
                          </h5>

                          <div class="col-md-3">
                            <div class="box box-info">
                              <div class="box-header">
                                <h3 class="box-title"><b>Llantas</b></h3>
                              </div>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-md-12">
                                    Marca: {{ $bitacora->llantas_marca }}
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje de Dir Izq: @if($bitacora->llanta_eje_direccion_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Inter Izq: @if($bitacora->llanta_eje_inter_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Motriz Izq: @if($bitacora->llanta_eje_matriz_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje de Dir Der: @if($bitacora->llanta_eje_direccion_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Inter Der: @if($bitacora->llanta_eje_inter_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Motriz Der: @if($bitacora->llanta_eje_matriz_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="box box-info">
                              <div class="box-header">
                                <h3 class="box-title"><b>Balatas</b></h3>
                              </div>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje de Dir Izq: @if($bitacora->balata_eje_direccion_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Inter Izq: @if($bitacora->balata_eje_inter_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Motriz Izq: @if($bitacora->balata_eje_matriz_izq) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje de Dir Der: @if($bitacora->balata_eje_direccion_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Inter Der: @if($bitacora->balata_eje_inter_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        Eje Motriz Der: @if($bitacora->balata_eje_matriz_der) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="box box-info">
                              <div class="box-header">
                                <h3 class="box-title"><b>Filtros</b></h3>
                              </div>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Aire: @if($bitacora->filtro_aire) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Aceite: @if($bitacora->filtro_aceite) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Diesel: @if($bitacora->filtro_diesel) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Diesel separador de Agua (cada 100,000 KM): @if($bitacora->filtro_diesel_separador_agua) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                {{-- <div class="row">
                                  <div class="col-md-12">
                                    Filtro Diesel separador de Condimento: @if($bitacora->filtro_diesel_separador_condimentos) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div> --}}
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro de Adblue (cada 100,000 KM): @if($bitacora->filtro_adblue) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Agua: @if($bitacora->filtro_agua) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Filtro Aceite Hidráulico: @if($bitacora->filtro_aceite_hidraulico) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                      Filtro Secador de Aire (Cada 120 KM): @if($bitacora->filtro_secador_aire) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="box box-info">
                              <div class="box-header">
                                <h3 class="box-title"><b>Aceite</b></h3>
                              </div>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-md-12">
                                    Aceite de Motor: @if($bitacora->aceite_motor) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Aceite de Diferencial: @if($bitacora->aceite_caja_diferencial) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Aceite Caja: @if($bitacora->aceite_caja) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Aceite Hidráulico: @if($bitacora->aceite_hidraulico) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Liquido de Frenos: @if($bitacora->aceite_liquido_frenos) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    Anticongelante: @if($bitacora->anticongelante) <i class="fas fa-check" style="color: green;"></i> @else <i class="fas fa-times" style="color: red;"></i> @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <br><br>
                          <div class="col-md-12">
                            <b>Reparación Especial:</b> <br>
                            {{ $bitacora->reparacion_especial }}
                          </div>
                          <br>

                          <div class="col-md-12">
                            <b>Observaciones:</b> <br>
                            {{ $bitacora->observaciones }}
                          </div>

                          <div class="row">
                            
                          </div>
                      </div>
                      <div class='timeline-footer'>
                          <b>Kilometraje al momento:</b> {{ number_format((float)$bitacora->kilometraje,0,'.',',') }} Kms <br>
                          <b>Costo:</b> ${{ number_format($bitacora->costo,2,'.',',') }}
                      </div>
                  </div>
              </li>
              <!-- END timeline item -->
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

@endsection