@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box box-info">
        <div class="box-header">
          @if (isset($ruta))
            <h3 class="box-title"><i class="fas fa-road"></i> Editar Ruta</h3>
          @else
            <h3 class="box-title"><i class="fas fa-road"></i> Nueva Ruta</h3>
          @endif
        </div>
        <div class="box-body">
          {{ Form::open(['role'=>'form','id'=>'form']) }}
            <div class="row-fluid">
              <div class="col-md-7">
                <div class="row">
                  <div class="form-group">
                    {{ Form::label('', 'Nombre de la Ruta') }}
                    {{ Form::text('nombre', Input::old('nombre', isset($ruta)?$ruta->nombre:null), array('class'=>'form-control','placeholder'=>'Nombre de la Ruta','required')) }}
                    {{ $errors->first('nombre', '<p class="text-danger">:message</p>') }}
                  </div>
                </div>
                <div class="row" id="ruta">

                </div>
                <div class="row">
                  <div class="col-md-12">
                    <a href="#" class="btn btn-success" id="add-empty">Agregar Ruta</a>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  {{ Form::label('', 'Observaciones') }}
                  {{ Form::textarea('observaciones', isset($ruta)?$ruta->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
                  {{ $errors->first('observaciones', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
            </div>
            <div class="row">

            </div>
        </div>
        <div class="box-footer">
          {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')
  <script type="text/javascript">
  $("#form").validate();

    $( function() {
      CKEDITOR.replace('observaciones');
    } );
    //Date picker
    var i = 0;
  $('#add-empty').click(function(){
    //var i = $('#ruta i').length,
        html =  '<div id="ruta'+i+'"><div class="form-group col-md-2">'+
                  '<label>Estado Origen</label>'+
                  '<select id="estado'+i+'" name="estado[]" class="form-control required" onchange="cambios('+i+')">'+
                    '<option value>Selecciona uno</option>'+
                    '<option value="1">Aguascalientes</option>'+
                    '<option value="2">Baja California</option>'+
                    '<option value="3">Baja California Sur</option>'+
                    '<option value="4">Campeche</option>'+
                    '<option value="5">Coahuila de Zaragoza</option>'+
                    '<option value="6">Colima</option>'+
                    '<option value="7">Chiapas</option>'+
                    '<option value="8">Chihuahua</option>'+
                    '<option value="9">Ciudad de México</option>'+
                    '<option value="10">Durango</option>'+
                    '<option value="11">Guanajuato</option>'+
                    '<option value="12">Guerrero</option>'+
                    '<option value="13">Hidalgo</option>'+
                    '<option value="14">Jalisco</option>'+
                    '<option value="15">México</option>'+
                    '<option value="16">Michoacán de Ocampo</option>'+
                    '<option value="17">Morelos</option>'+
                    '<option value="18">Nayarit</option>'+
                    '<option value="19">Nuevo León</option>'+
                    '<option value="20">Oaxaca</option>'+
                    '<option value="21">Puebla</option>'+
                    '<option value="22">Querétaro</option>'+
                    '<option value="23">Quintana Roo</option>'+
                    '<option value="24">San Luis Potosí</option>'+
                    '<option value="25">Sinaloa</option>'+
                    '<option value="26">Sonora</option>'+
                    '<option value="27">Tabasco</option>'+
                    '<option value="28">Tamaulipas</option>'+
                    '<option value="29">Tlaxcala</option>'+
                    '<option value="30">Veracruz de Ignacio de la Llave</option>'+
                    '<option value="31">Yucatán</option>'+
                    '<option value="32">Zacatecas</option>'+
                  '</select>'+
                '</div>'+
                '<div class="form-group col-md-2">'+
                  '<label>Del/Mun Origen</label>'+
                  '<select id="del_mun'+i+'" name="del_mun[]" class="form-control required">'+
                    '<option value="">Selecciona uno</option>'+
                  '</select>'+
                '</div>'+
                '<div class="form-group col-md-2">'+
                  '<label>Estado Destino</label>'+
                  '<select id="estado_destino'+i+'" name="estado_destino[]" class="form-control required" onchange="cambios_destino('+i+')">'+
                    '<option value>Selecciona uno</option>'+
                    '<option value="1">Aguascalientes</option>'+
                    '<option value="2">Baja California</option>'+
                    '<option value="3">Baja California Sur</option>'+
                    '<option value="4">Campeche</option>'+
                    '<option value="5">Coahuila de Zaragoza</option>'+
                    '<option value="6">Colima</option>'+
                    '<option value="7">Chiapas</option>'+
                    '<option value="8">Chihuahua</option>'+
                    '<option value="9">Ciudad de México</option>'+
                    '<option value="10">Durango</option>'+
                    '<option value="11">Guanajuato</option>'+
                    '<option value="12">Guerrero</option>'+
                    '<option value="13">Hidalgo</option>'+
                    '<option value="14">Jalisco</option>'+
                    '<option value="15">México</option>'+
                    '<option value="16">Michoacán de Ocampo</option>'+
                    '<option value="17">Morelos</option>'+
                    '<option value="18">Nayarit</option>'+
                    '<option value="19">Nuevo León</option>'+
                    '<option value="20">Oaxaca</option>'+
                    '<option value="21">Puebla</option>'+
                    '<option value="22">Querétaro</option>'+
                    '<option value="23">Quintana Roo</option>'+
                    '<option value="24">San Luis Potosí</option>'+
                    '<option value="25">Sinaloa</option>'+
                    '<option value="26">Sonora</option>'+
                    '<option value="27">Tabasco</option>'+
                    '<option value="28">Tamaulipas</option>'+
                    '<option value="29">Tlaxcala</option>'+
                    '<option value="30">Veracruz de Ignacio de la Llave</option>'+
                    '<option value="31">Yucatán</option>'+
                    '<option value="32">Zacatecas</option>'+
                  '</select>'+
                '</div>'+
                '<div class="form-group col-md-3">'+
                  '<label>Del/Mun Origen</label>'+
                  '<select id="del_mun_destino'+i+'" name="del_mun_destino[]" class="form-control required">'+
                    '<option value="">Selecciona uno</option>'+
                  '</select>'+
                '</div>'+
                '<div class="form-group col-md-2">'+
                  '<label>Tot Km</label>'+
                  '<input id="total_km'+i+'" type="text" name="total_km[]" class="form-control required">'+
                '</div>'+
                '<div class="form-group col-md-1">'+
                  '<a href="#" class="btn btn-danger" onclick="borrar('+i+')"><i class="fas fa-trash fa-2x"></i></a>'+
                '</div></div>';
    $('#ruta').append(html);
    i = i+1;
  });

  function cambios(i){
    valor = document.getElementById("estado"+i).value;
    $.get("../api/dropdown",
    {
      estado:valor
    },
      function(data){
        var muni = $('#del_mun'+i);
        muni.empty();
        $.each(data, function(index, element){
          muni.append("<option value='"+ element.municipio +"'>" + element.municipio + "</option>");
        });
    });

  }

  function cambios_destino(i){
    valor = document.getElementById("estado_destino"+i).value;
    $.get("../api/dropdown",
    {
      estado:valor
    },
      function(data){
        var muni = $('#del_mun_destino'+i);
        muni.empty();
        $.each(data, function(index, element){
          muni.append("<option value='"+ element.municipio +"'>" + element.municipio + "</option>");
        });
    });

  }

  function borrar(i) {
    var elem = document.getElementById('ruta'+i);
    elem.parentNode.removeChild(elem);
    return false;
  }


  </script>
@stop
