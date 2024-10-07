@extends('dashboard.layouts.dashboard.master')

@section('content')

  @include('notifications')

  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#sueldos" aria-controls="sueldos" role="tab" data-toggle="tab">Sueldos</a></li>
    <li role="presentation"><a href="#combustible" aria-controls="combustible" role="tab" data-toggle="tab">Gastos Combustible</a></li>
    <li role="presentation"><a href="#ganancia" aria-controls="ganancia" role="tab" data-toggle="tab">Perdida o Ganancia</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="sueldos">
      <!--=============================
      =            SUELDOS            =
      ==============================-->
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-responsive nowrap">
          <caption style="text-align: center; font-size:20px;">
            Detalle de Salario de: <b>{{ $operador->user->first_name }} {{ $operador->user->last_name }}</b><br>
            Periodo: del <b>{{ Input::get('fecha1') }}</b> al <b>{{ Input::get('fecha2') }}</b> <br>
          </caption>

          <tbody>
            <tr>
              <td>
                <table class="table table-striped table-bordered table-hover table-condensed">
                  <caption style="text-align: center; font-size:20px;">
                    Cotizaciones Asignadas
                  </caption>
                  <thead>
                    <tr>
                      <th>Folio Cotización</th>
                      <th>Ruta</th>
                      <th>Km</th>
                      <th>Fecha Termino</th>
                      <th>Pago</th>
                      <th>Total Cotización</th>
                    </tr>
                  </thead>
                  <?php 
                    $total_cotizaciones = 0;
                    $total_presupuestos = 0; 
                  ?>
                  <tbody>
                    @foreach ($asignaciones as $asignacion)
                      <tr>
                        <td>{{ $asignacion->cotizacion->folio }}</td>
                        <td>{{ $asignacion->cotizacion->ruta->nombre }}</td>
                        <td>{{ $asignacion->cotizacion->ruta->total_km }} Km</td>
                        <td>{{ $asignacion->updated_at }}</td>
                        <td class="text-right">$ {{ number_format($asignacion->cotizacion->sueldo_ope,2,'.',',') }}</td>
                        <td class="text-right">$ {{ number_format($asignacion->cotizacion->propuesta,2,'.',',') }}</td>
                      </tr>
                      <?php
                        $total_cotizaciones = $total_cotizaciones + $asignacion->cotizacion->sueldo_ope;
                        $total_presupuestos = $total_presupuestos + $asignacion->cotizacion->propuesta;
                      ?>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th style="color:green; text-align: right; font-size:20px;">Total Cotizaciones =</th>
                      <th style="color:green; text-align: right; font-size:20px;"> <b>$ {{ number_format($total_cotizaciones, 2, '.', ',') }}</b></th>
                      <th style="color:green; text-align: right; font-size:20px;"><b>$ {{ number_format($total_presupuestos, 2, '.', ',') }}</b></th>
                    </tr>
                  </tfoot>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table class="table table-striped table-bordered table-hover table-condensed">
                  <caption style="text-align: center; font-size:20px;">
                    Control Vehicular <br>
                    <a href="#" class="btn btn-primary" onclick="control();"> <i class="fas fa-plus"></i> Control Vehicular</a>
                  </caption>
                  <thead>
                    <tr>
                      @if ($currentUser->hasAccess('delete-button')) <th>Acciones</th>@endif
                      <th>Fecha</th>
                      <th>Control Vehicular</th>
                      <th>Origen</th>
                      <th>Toneladas</th>
                      <th>Cantidad Total</th>
                      <th>Comisión</th>
                      <th>IVA</th>
                      <th>Cantidad %</th>
                    </tr>
                  </thead>
                  <?php
                    $total_controles = 0;
                    $total_cantidad = 0;
                  ?>
                  <tbody>
                    @foreach ($controlesvehiculares as $controlvehicular)
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) 
                            <td>
                                <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Control Vehicular" data-toggle="modal" data-target=".bs-example-modal-lg{{ $controlvehicular->id }}"><i class="fas fa-trash"></i> Borrar</a></td>
                            </td>
                        @endif
                        <td>{{ $controlvehicular->fecha }}</td>
                        <td>{{ $controlvehicular->control_vehicular }}</td>
                        <td>{{ $controlvehicular->origenes->origen }}</td>
                        <td class="text-right">{{ number_format(intval($controlvehicular->toneladas),2,'.',',') }}</td>
                        <td class="text-right">$ {{ number_format((float)$controlvehicular->cantidad,2,'.',',') }}</td>
                        <td>% {{ $controlvehicular->porcentaje }}</td>
                        <td>% {{ $controlvehicular->iva * 100 }}</td>
                        <td class="text-right">$ {{ number_format((float)$controlvehicular->cantidad * ((float)$controlvehicular->porcentaje/100),2,'.',',') }}</td>
                      </tr>
                      <?php 
                        $total_controles = $total_controles + ((float)$controlvehicular->cantidad * ((float)$controlvehicular->porcentaje/100));
                        $total_cantidad = $total_cantidad + (float)$controlvehicular->cantidad;
                      ?>
                      <div class="modal fade bs-example-modal-lg{{ $controlvehicular->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <div class="modal-body">

                            <h1>¡Atención!</h1>
                            <h3>Se va a eliminar el Control Vehicular: <b>{{ $controlvehicular->control_vehicular }}</b>.</h3>
                            <h3>¿Está seguro?</h3>
                            {{ Form::open(['route'=>['control.destroy',$controlvehicular->id],'id'=>'myForm'.$controlvehicular->id,'method'=>'delete']) }}
                              <a href="#" onclick="document.getElementById('myForm{{ $controlvehicular->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
                              <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                            {{ Form::close() }}
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      @if ($currentUser->hasAccess('delete-button')) <th></th>@endif
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Total Control Vehicular = </th>
                      <th style="color:green; text-align: right; font-size:20px;"><b>$ {{ number_format($total_cantidad, 2, '.', ',') }}</b></th>
                      <th></th>
                      <th></th>
                      <th style="color:green; text-align: right; font-size:20px;"><b>$ {{ number_format($total_controles, 2, '.', ',') }}</b></th>
                    </tr>
                  </tfoot>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover table-condensed">
                    <caption style="text-align: center; font-size:20px;">
                      Comprobante de Combustible<br>
                      <a href="#" class="btn btn-primary" onclick="combustible();"> <i class="fas fa-plus"></i> Comprobante de Combustible</a>
                    </caption>
                    <thead>
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) <th>Acciones</th>@endif
                        <th>Fecha</th>
                        <th>Ticket</th>
                        <th>Litros</th>
                        <th>Gasolinera</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <?php $total_comprobantes = 0; ?>
                    <tbody>
                      @foreach ($comprobantesvista as $comprobante)
                        <tr>
                          @if ($currentUser->hasAccess('delete-button')) 
                            <td>
                                <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Comprobante de Combustible" data-toggle="modal" data-target=".bs-comp-combus-modal-lg{{ $comprobante->id }}"><i class="fas fa-trash"></i> Borrar</a>
                            </td>
                          @endif
                          <td>{{ $comprobante->fecha }}</td>
                          <td>{{ $comprobante->ticket }}</td>
                          <td>{{ $comprobante->litros }}</td>
                          <td class="text-right">{{ isset($comprobante->gasolineras->gasolinera)?$comprobante->gasolineras->gasolinera:"No Especificada" }}</td>
                          <td class="text-right">$ {{ number_format($comprobante->total,2,'.',',') }}</td>
                        </tr>
                        <?php $total_comprobantes = $total_comprobantes + $comprobante->total; ?>
                        <div class="modal fade bs-comp-combus-modal-lg{{ $comprobante->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                              <div class="modal-body">

                              <h1>¡Atención!</h1>
                              <h3>Se va a eliminar el Comprobante de Combustible: <b>{{ $comprobante->ticket }}</b>.</h3>
                              <h3>¿Está seguro?</h3>
                              {{ Form::open(['route'=>['comprobante_combustible.destroy',$comprobante->id],'id'=>'myForm'.$comprobante->id,'method'=>'delete']) }}
                                <a href="#" onclick="document.getElementById('myForm{{ $comprobante->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
                                <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                              {{ Form::close() }}
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) <th></th>@endif
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="color:green; text-align: right; font-size:20px;">Total Comprobantes de Combustible = </th>
                        <th style="color:green; text-align: right; font-size:20px;"><b>$ {{ number_format($total_comprobantes, 2, '.', ',') }}</b></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover table-condensed">
                    <caption style="text-align: center; font-size:20px;">
                      Comprobante de Gastos<br>
                      <a href="#" class="btn btn-primary" onclick="gastos();"> <i class="fas fa-plus"></i> Comprobante de Gastos</a>
                    </caption>
                    <thead>
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) <th>Acciones</th>@endif
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Factura</th>
                        <th>Observaciones</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <?php $total_gastos = 0; ?>
                    <tbody>
                      @foreach ($gastos as $gasto)
                        <tr>
                          @if ($currentUser->hasAccess('delete-button')) 
                            <td>
                                <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Comprobante de Gasto" data-toggle="modal" data-target=".bs-comp-gasto-modal-lg{{ $gasto->id }}"><i class="fas fa-trash"></i> Borrar</a>
                            </td>
                          @endif≥
                          <td>{{ $gasto->fecha }}</td>
                          <td>{{ $gasto->descripcion }}</td>
                          <td>{{ $gasto->factura }}</td>
                          <td>{{ substr(strip_tags($gasto->observaciones),0,50) }}</td>
                          <td class="text-right">$ {{ number_format($gasto->total,2,'.',',') }}</td>
                        </tr>
                        <?php $total_gastos = $total_gastos + $gasto->total; ?>
                        <div class="modal fade bs-comp-gasto-modal-lg{{ $gasto->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                              <div class="modal-body">

                              <h1>¡Atención!</h1>
                              <h3>Se va a eliminar el Comprobante de Gasto: <b>{{ $gasto->descripcion }}</b>.</h3>
                              <h3>¿Está seguro?</h3>
                              {{ Form::open(['route'=>['gasto.destroy',$gasto->id],'id'=>'myForm'.$gasto->id,'method'=>'delete']) }}
                                <a href="#" onclick="document.getElementById('myForm{{ $gasto->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
                                <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                              {{ Form::close() }}
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) <th></th>@endif
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="color:green; text-align: right; font-size:20px;">Total Comprobantes de Gastos = <b>$ {{ number_format($total_gastos, 2, '.', ',') }}</b></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <table class="table table-striped table-bordered table-hover table-condensed">
                  <caption style="text-align: center; font-size:20px;">
                    Préstamos y Cargos<br>
                    <a href="#" class="btn btn-primary" onclick="prestamos();"> <i class="fas fa-plus"></i> Préstamos y Cargos</a>
                  </caption>
                  <thead>
                    <tr>
                      @if ($currentUser->hasAccess('delete-button')) <th>Acciones</th>@endif
                      <th>Fecha</th>
                      <th>Folio</th>
                      <th>Categoría</th>
                      <th>Subcategoría</th>
                      <th>Movimiento</th>
                      <th>Préstamo</th>
                    </tr>
                  </thead>
                  <?php
                  $total_egresos = 0;
                  ?>
                  <tbody>
                    @foreach ($prestamos as $prestamo)
                      <tr>
                        @if ($currentUser->hasAccess('delete-button')) 
                            <td>
                                <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Prestamo" data-toggle="modal" data-target=".bs-prestamo-modal-lg{{ $prestamo->id }}"><i class="fas fa-trash"></i> Borrar</a>
                            </td>
                        @endif
                        <td nowrap>{{ $prestamo->fecha }}</td>
                        <td>{{ $prestamo->folio }}</td>
                        <td>{{ $prestamo->categoria->categoria }}</td>
                        <td>{{ $prestamo->subcategoria->subcategoria }}</td>
                        <td>{{ $prestamo->movimiento }}</td>
                        @if ($prestamo->subcategoria_id == 46)
                          <td style="color:green; text-align: right;">{{ '$ '.number_format(($prestamo->cantidad), 2, '.', ',') }}</td>
                          <?php $total_egresos = $total_egresos + ($prestamo->cantidad); ?>
                        @elseif ($prestamo->subcategoria_id == 47 OR $prestamo->subcategoria_id == 48 OR $prestamo->subcategoria_id == 49 OR $prestamo->subcategoria_id == 50 OR $prestamo->subcategoria_id == 19 OR $prestamo->subcategoria_id == 74)
                          <td style="color:red; text-align: right;">{{ '$ '.number_format(($prestamo->cantidad * -1), 2, '.', ',') }}</td>
                          <?php $total_egresos = $total_egresos + ($prestamo->cantidad * -1); ?>
                        @else
                          <td style="color:red; text-align: right;">{{ '$ '.number_format(($prestamo->cantidad * $prestamo->tipo), 2, '.', ',') }}</td>
                          <?php $total_egresos = $total_egresos + ($prestamo->cantidad * $prestamo->tipo); ?>
                      @endif
                      </tr>
                      <div class="modal fade bs-prestamo-modal-lg{{ $prestamo->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <div class="modal-body">

                            <h1>¡Atención!</h1>
                            <h3>Se va a eliminar el Prestamo: <b>{{ $prestamo->movimiento }}</b>.</h3>
                            <h3>¿Está seguro?</h3>
                            {{ Form::open(['route'=>['deleteBancoPrest',$prestamo->id],'id'=>'myForm'.$prestamo->id]) }}
                              <a href="#" onclick="document.getElementById('myForm{{ $prestamo->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
                              <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                            {{ Form::close() }}
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      @if ($currentUser->hasAccess('delete-button')) <th></th>@endif
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th style="color:red; text-align: right; font-size:20px;">Total Préstamo = <b>$ {{ number_format($total_egresos, 2, '.', ',') }}</b></th>
                    </tr>
                  </tfoot>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!--====  End of SUELDOS  ====-->
      
    </div>
    <div role="tabpanel" class="tab-pane" id="combustible">
      <!--==================================
      =            COMBUSTIBLES            =
      ===================================-->
      
      <div class="row">
    

    <div class="col-md-6">
      <div class="panel panel-warning">
        <div class="panel-heading"><h3>Gasto Combustibles</h3></div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Ticket</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php $total_combustible = 0; ?>
                @foreach ($combustibles as $combustible)
                  <tr>
                    <td>{{ $combustible->ticket }}</td>
                    <td class="text-right">$ {{ number_format($combustible->total,2,'.',',') }}</td>
                  </tr>
                  <?php $total_combustible = $total_combustible + $combustible->total; ?>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>Total Gasto Combustible</th>
                  <th class="text-right">$ {{ number_format($total_combustible,2,'.',',') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
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
                  @foreach ($gastos_no_maniobra as $gasto)
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
      
      <!--====  End of COMBUSTIBLES  ====-->
      
    </div>
    <div role="tabpanel" class="tab-pane" id="ganancia">

      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th width='50%' class="text-right" style="color:green;">Cotizaciones</th>
              <th width='50%' class="text-left">$ {{ number_format(($total_presupuestos),2,'.',',') }}</th>
            </tr>
            <tr>
              <th width='50%' class="text-right" style="color:green;">Control Vehicular</th>
              <th width='50%' class="text-left">$ {{ number_format(($total_cantidad),2,'.',',') }}</th>
            </tr>
            <tr>
              <th width='50%' class="text-right" style="color:red;">Gastos</th>
              <th width='50%' class="text-left">$ {{ number_format(($total_gastos*-1),2,'.',',') }}</th>
            </tr>
            <tr>
              <th width='50%' class="text-right" style="color:red;">Combustible</th>
              <th width='50%' class="text-left">$ {{ number_format(($total_combustible*-1),2,'.',',') }}</th>
            </tr>
            <tr>
              <?php $sueldo = ($total_cotizaciones+$total_controles); ?>
              <th width='50%' class="text-right" style="color:red;">Sueldo</th>
              <th width='50%' class="text-left">$ {{ number_format(($sueldo*-1),2,'.',',') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
        </table>

        <table class="table table-striped table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th width='50%' class="text-right" style="color:green;">Entradas</th>
              <th width='50%' class="text-left">$ {{ number_format($total_presupuestos+($total_cantidad),2,'.',',') }}</th>
            </tr>
            <tr>
              <th width='50%' class="text-right" style="color:red;">Gastos</th>
              <th width='50%' class="text-left">$ {{ number_format((($total_gastos)+$total_combustible+$sueldo),2,'.',',') }}</th>
            </tr>
            <tr>
              <th width='50%' class="text-right">TOTAL</th>
              <th width='50%' class="text-left" style="font-size:20px;">$ {{ number_format(($total_presupuestos+($total_cantidad))-(($total_gastos)+$total_combustible+$sueldo),2,'.',',') }}</th>
            </tr>

          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
        </table>
      </div>
      
      <?php
        $tot = ($total_presupuestos+($total_cantidad))-(($total_gastos)+$total_combustible+$sueldo);
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
                <h2>Utilidad de {{ $titulo }} % {{ number_format($tot / (($total_presupuestos+$total_cantidad)/100),2,'.',',') }}</h2>
            </div>
        </div>
      </div>

    </div>
  </div>
  
@stop

@section('scripts')
  <script type="text/javascript">
    function control() {
      window.open("control/create?operador={{ $operador->user_id }}", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=50%,left=50%,width=1000, height=600");
    }

    function combustible() {
      window.open("comprobante_combustible/create?operador={{ $operador->user_id }}&fecha1={{ $fecha1 }}&fecha2={{ $fecha2 }}", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=50%,left=50%,width=1000, height=600");
    }

    function prestamos() {
      window.open("prestamo/crear/salario?operador={{ $operador->user_id }}&fecha1={{ $fecha1 }}&fecha2={{ $fecha2 }}", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=50%,left=50%,width=1000, height=600");
    }

    function gastos() {
      window.open("gasto/create?operador={{ $operador->user_id }}&fecha1={{ $fecha1 }}&fecha2={{ $fecha2 }}", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=50%,left=50%,width=1000, height=600");
    }
  </script>
@stop
