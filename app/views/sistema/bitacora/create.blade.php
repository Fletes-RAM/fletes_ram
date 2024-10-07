@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header">
          @if (isset($bitacora))
            <h3 class="box-title"><i class="fa fa-book"></i> Editar Evento</h3>
            <?php
              $action = 'PUT';
              $route = ['bitacora.update',$bitacora->id];
            ?>
          @else
            <h3 class="box-title"><i class="fa fa-book"></i> Nuevo Evento</h3>
            <?php
              $action = 'POST';
              $route = 'bitacora.store';
            ?>
          @endif
        </div>
        
        {{ Form::open(['role'=>'form','id'=>'form','route'=>$route,'method'=>$action]) }}
        {{ Form::hidden('unidad_id', $unidad->id) }}
        <div class="box-body">
          <div class="row">
            <div class="form-group">
              <div class="col-md-4">
                {{ Form::label('fecha', 'Fecha') }}
                {{ Form::text('fecha', Input::old('fecha', isset($bitacora)?$bitacora->fecha:null), array('class'=>'form-control required date-picker','placeholder'=>'Fecha','id'=>'fecha')) }}
                {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="col-md-4">
                {{ Form::label('kilometraje', 'Kilometraje') }}
                {{ Form::text('kilometraje', Input::old('kilometraje', isset($bitacora)?$bitacora->kilometraje:null), array('class'=>'form-control required','placeholder'=>'Kilometraje')) }}
                {{ $errors->first('kilometraje', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="col-md-4">
                {{ Form::label('titulo', 'Título') }}
                {{ Form::text('titulo', Input::old('titulo', isset($bitacora)?$bitacora->titulo:null), array('class'=>'form-control required','placeholder'=>'Título')) }}
                {{ $errors->first('titulo', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="col-md-6">
                {{ Form::label('proveedor_id', 'Proveedor') }}
                {{ Form::select('proveedor_id', [null=>'Seleccione uno']+$proveedores, Input::old('proveedor_id', isset($bitacora)?$bitacora->proveedor_id:null), array('class'=>'form-control required','placeholder'=>'Proveedor')) }}
                {{ $errors->first('proveedor_id', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="col-md-6">
                {{ Form::label('costo', 'Costo') }}
                {{ Form::text('costo', Input::old('costo', isset($bitacora)?$bitacora->costo:null), array('class'=>'form-control required','placeholder'=>'Costo')) }}
                {{ $errors->first('costo', '<p class="text-danger">:message</p>') }}
              </div>
              <br><br><br>
              <div class="col-md-3">
                <div class="box box-info">
                  <div class="box-header">
                    <h3 class="box-title"><b>Llantas</b></h3>
                  </div>
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::label('llantas_marca', 'Marca') }}
                        {{ Form::text('llantas_marca', Input::old('llantas_marca', isset($bitacora)?$bitacora->llantas_marca:null), array('class'=>'form-control','placeholder'=>'Marca')) }}
                        {{ $errors->first('llantas_marca', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_direccion_izq', Input::old('llanta_eje_direccion_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_direccion_izq', 'Eje de Dir Izq') }}
                            {{ $errors->first('llanta_eje_direccion_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_inter_izq', Input::old('llanta_eje_inter_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_inter_izq', 'Eje Inter Izq') }}
                            {{ $errors->first('llanta_eje_inter_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_matriz_izq', Input::old('llanta_eje_matriz_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_matriz_izq', 'Eje Motriz Izq') }}
                            {{ $errors->first('llanta_eje_matriz_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_direccion_der', Input::old('llanta_eje_direccion_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_direccion_der', 'Eje de Dir Der') }}
                            {{ $errors->first('llanta_eje_direccion_der', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_inter_der', Input::old('llanta_eje_inter_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_inter_der', 'Eje Inter Der') }}
                            {{ $errors->first('llanta_eje_inter_der', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('llanta_eje_matriz_der', Input::old('llanta_eje_matriz_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('llanta_eje_matriz_der', 'Eje Motriz Der') }}
                            {{ $errors->first('llanta_eje_matriz_der', '<p class="text-danger">:message</p>') }}
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
                            {{ Form::checkbox('balata_eje_direccion_izq', Input::old('balata_eje_direccion_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_direccion_izq', 'Eje de Dir Izq') }}
                            {{ $errors->first('balata_eje_direccion_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('balata_eje_inter_izq', Input::old('balata_eje_inter_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_inter_izq', 'Eje Inter Izq') }}
                            {{ $errors->first('balata_eje_inter_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('balata_eje_matriz_izq', Input::old('balata_eje_matriz_izq', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_matriz_izq', 'Eje Motriz Izq') }}
                            {{ $errors->first('balata_eje_matriz_izq', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('balata_eje_direccion_der', Input::old('balata_eje_direccion_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_direccion_der', 'Eje de Dir Der') }}
                            {{ $errors->first('balata_eje_direccion_der', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('balata_eje_inter_der', Input::old('balata_eje_inter_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_inter_der', 'Eje Inter Der') }}
                            {{ $errors->first('balata_eje_inter_der', '<p class="text-danger">:message</p>') }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            {{ Form::checkbox('balata_eje_matriz_der', Input::old('balata_eje_matriz_der', null),null,['class'=>'form-control']) }}
                            {{ Form::label('balata_eje_matriz_der', 'Eje Motriz Der') }}
                            {{ $errors->first('balata_eje_matriz_der', '<p class="text-danger">:message</p>') }}
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
                        {{ Form::checkbox('filtro_aire', Input::old('filtro_aire', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_aire', 'Filtro Aire') }}
                        {{ $errors->first('filtro_aire', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_aceite', Input::old('filtro_aceite', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_aceite', 'Filtro Aceite') }}
                        {{ $errors->first('filtro_aceite', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_diesel', Input::old('filtro_diesel', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_diesel', 'Filtro Diesel') }}
                        {{ $errors->first('filtro_diesel', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_diesel_separador_agua', Input::old('filtro_diesel_separador_agua', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_diesel_separador_agua', 'Filtro Diesel separador de Agua (cada 100,000 KM)') }}
                        {{ $errors->first('filtro_diesel_separador_agua', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    {{-- <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_diesel_separador_condimentos', Input::old('filtro_diesel_separador_condimentos', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_diesel_separador_condimentos', 'Filtro Diesel separador de Condimento') }}
                        {{ $errors->first('filtro_diesel_separador_condimentos', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div> --}}
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_adblue', Input::old('filtro_adblue', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_adblue', 'Filtro de Adblue (cada 100,000 KM)') }}
                        {{ $errors->first('filtro_adblue', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_agua', Input::old('filtro_agua', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_agua', 'Filtro Agua') }}
                        {{ $errors->first('filtro_agua', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('filtro_aceite_hidraulico', Input::old('filtro_aceite_hidraulico', null),null,['class'=>'form-control']) }}
                        {{ Form::label('filtro_aceite_hidraulico', 'Filtro Aceite Hidráulico') }}
                        {{ $errors->first('filtro_aceite_hidraulico', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          {{ Form::checkbox('filtro_secador_aire', Input::old('filtro_secador_aire', null),null,['class'=>'form-control']) }}
                          {{ Form::label('filtro_secador_aire', 'Filtro Secador de Aire (Cada 120 Km)') }}
                          {{ $errors->first('filtro_secador_aire', '<p class="text-danger">:message</p>') }}
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
                        {{ Form::checkbox('aceite_motor', Input::old('aceite_motor', null),null,['class'=>'form-control']) }}
                        {{ Form::label('aceite_motor', 'Aceite de Motor') }}
                        {{ $errors->first('aceite_motor', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('aceite_caja_diferencial', Input::old('aceite_caja_diferencial', null),null,['class'=>'form-control']) }}
                        {{ Form::label('aceite_caja_diferencial', 'Aceite De Diferencial') }}
                        {{ $errors->first('aceite_caja_diferencial', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('aceite_caja', Input::old('aceite_caja', null),null,['class'=>'form-control']) }}
                        {{ Form::label('aceite_caja', 'Aceite Caja') }}
                        {{ $errors->first('aceite_caja', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('aceite_hidraulico', Input::old('aceite_hidraulico', null),null,['class'=>'form-control']) }}
                        {{ Form::label('aceite_hidraulico', 'Aceite Hidráulico') }}
                        {{ $errors->first('aceite_hidraulico', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('aceite_liquido_frenos', Input::old('aceite_liquido_frenos', null),null,['class'=>'form-control']) }}
                        {{ Form::label('aceite_liquido_frenos', 'Liquido de Frenos') }}
                        {{ $errors->first('aceite_liquido_frenos', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        {{ Form::checkbox('anticongelante', Input::old('anticongelante', null),null,['class'=>'form-control']) }}
                        {{ Form::label('anticongelante', 'Anticongelante') }}
                        {{ $errors->first('anticongelante', '<p class="text-danger">:message</p>') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br><br>
              <div class="col-md-12">
                {{ Form::label('reparacion_especial', 'Reparación Especial') }}
                {{ Form::textArea('reparacion_especial', Input::old('reparacion_especial', isset($bitacora)?$bitacora->reparacion_especial:null), array('class'=>'form-control required','placeholder'=>'Reparación Especial')) }}
                {{ $errors->first('reparacion_especial', '<p class="text-danger">:message</p>') }}
              </div>
              <br>
              <div class="col-md-12">
                {{ Form::label('observaciones', 'Observaciones') }}
                {{ Form::textArea('observaciones', Input::old('observaciones', isset($bitacora)?$bitacora->observaciones:null), array('class'=>'form-control required','placeholder'=>'Observaciones')) }}
                {{ $errors->first('observaciones', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>

@stop

@section('scripts')
  {{ HTML::script('packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}
  {{ HTML::script('packages/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}
  <script type="text/javascript">
  $("#form").validate();

    $( function() {
      CKEDITOR.replace('reparacion_especial');
      CKEDITOR.replace('observaciones');
    } );
    //Date picker
    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd',
      language: 'es',
      todayHighlight: true,
      todayBtn: "linked",
      autoclose: true
    });
  </script>
@stop
