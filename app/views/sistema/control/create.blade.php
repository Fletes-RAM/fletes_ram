@extends('dashboard.layouts.dashboard.window')

@section('content')
  @include('notifications')
  <div class="row" style="padding: 20px;">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Control Vehicular</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          {{ Form::open(['id'=>'form','role'=>'form','action'=>'ControlController@store','autocomplete'=>'off']) }}
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="porcentaje">Porcentaje</label>
                <input required name="porcentaje" type="text" class="form-control" id="porcentaje" placeholder="Porcentaje">
                {{ $errors->first('porcentaje', '<p class="text-danger">:message</p>') }}
                {{ Form::hidden('user_id', Input::get('operador')) }}
              </div>
            </div>
            <div class="col-sm-6"></div>
            <div class="col-sm-12">
              <a href="#" class="btn btn-success" id="add-empty">Agregar Control</a>
            </div>
          </div>
          <br><br>
          <div class="row" id="ctl"></div>
        </div>
        <div class="box-foot">
          <div class="row">
            <div class="col-sm-12 text-center">
              <button type="submit" class="btn btn-lg btn-default ">Guardar</button>
            </div>
          </div>

          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  {{ HTML::script('packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}
  {{ HTML::script('packages/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}
  <script type="text/javascript">


    $('#form').validate();
    var i = 0;
    $('#add-empty').unbind('click').click(function(){
      html = '<div id="control'+i+'">'+
                '<div class="row">'+
                  '<div class="form-group col-sm-2" id="date-picker">'+
                    '<label for="fecha">Fecha</label>'+
                    '<input required name="fecha[]" type="text" class="form-control date-picker" id="fecha" placeholder="Fecha">'+
                  '</div>'+
                  '<div class="form-group col-sm-2">'+
                    '<label for="control_vehicular">Control Vehicular</label>'+
                    '<input required name="control_vehicular[]" type="text" class="form-control" id="control_vehicular" placeholder="Control Vehicular">'+
                  '</div>'+
                  '<div class="form-group col-sm-2">'+
                    '<label for="origen">Origen</label>'+
                    '{{ Form::select("origen[]", [null=>"Selecciona Uno"]+$origenes, null,["class"=>"form-control required"]) }}'+
                  '</div>'+
                  '<div class="form-group col-sm-2">'+
                    '<label for="toneladas">Toneladas</label>'+
                    '<input required name="toneladas[]" type="text" class="form-control sumas" onblur="calcular('+i+');" id="toneladas'+i+'" placeholder="Toneladas">'+
                  '</div>'+
                  '<div class="form-group col-sm-1">'+
                    '<label for="tarifa">Tarifa</label>'+
                    '<input required name="tarifa[]" type="text" class="form-control sumas" onblur="calcular('+i+');" id="tarifa'+i+'" placeholder="Tarifa">'+
                  '</div>'+
                  '<div class="form-group col-sm-1">'+
                    '<label for="cantidad">Cantidad</label>'+
                    '<input required name="cantidad[]" type="text" class="form-control" id="cantidad'+i+'" placeholder="Cantidad">'+
                  '</div>'+
                  '<div class="form-group col-sm-2">'+
                    '<div class="form-group col-sm-6">'+
                    '<label for="iva">IVA</label> <br />'+
                    '<input type="checkbox" name="iva[]" value="1.16">'+
                    '</div>'+
                    '<div class="form-group col-sm-6">'+
                    '<a href="#" class="btn btn-danger" onclick="borrar('+i+')"><i class="fas fa-trash"></i></a>'+
                    '</div>'+
                    '<div class="row">'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</div>';
      $('#ctl').append(html);
      i = i+1;
    });

    function borrar(i) {
      var elem = document.getElementById('control'+i);
      elem.parentNode.removeChild(elem);
      return false;
    }

    $('#ctl').off().on('focus','.date-picker', function(){
      $(this).datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        todayHighlight: true,
        todayBtn: "linked",
        autoclose: true
      });
    });

    function calcular(i){
      var total = document.getElementById('toneladas'+i).value * document.getElementById('tarifa'+i).value;
      document.getElementById('cantidad'+i).value = total;
      return false;
    }


  </script>
@stop
