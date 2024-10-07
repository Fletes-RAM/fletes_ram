@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box box-info">
        <div class="box-header">
          @if (isset($operador))
            <h3 class="box-title"><i class="fas fa-id-card"></i> Editar Operador</h3>
          @else
            <h3 class="box-title"><i class="fas fa-id-card"></i> Nuevo Operador</h3>
          @endif
        </div>
        <div class="box-body">
          {{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
            <div class="row-fluid">
              <div class="col-md-4">
                <div class="form-group">
                  {{ Form::label('', trans('syntara::users.username')) }}
                  @if (isset($operador))
                    <br>
                    {{ $operador->user->username }}
                  @else
                    {{ Form::text('username', Input::old('username', isset($operador)?$operador->user->username:null), array('class'=>'form-control','placeholder'=>trans('syntara::users.username'),'required')) }}
                    {{ $errors->first('username', '<p class="text-danger">:message</p>') }}
                  @endif
                </div>
                <div class="form-group">
                  {{ Form::label('', trans('syntara::all.email')) }}
                  @if (isset($operador))
                    <br>
                    {{ $operador->user->email }}
                  @else
                    {{ Form::email('email', Input::old('email', isset($operador)?$operador->user->email:null), array('class'=>'form-control','placeholder'=>trans('syntara::all.email'),'required')) }}
                    {{ $errors->first('email', '<p class="text-danger">:message</p>') }}
                  @endif
                </div>
                <div class="form-group">
                  {{ Form::label('', trans('syntara::users.last-name')) }}
                  {{ Form::text('last_name', Input::old('last_name', isset($operador)?$operador->user->last_name:null), array('class'=>'form-control','placeholder'=>trans('syntara::users.last-name'),'required')) }}
                  {{ $errors->first('last_name', '<p class="text-danger">:message</p>') }}
                </div>
                <div class="form-group">
                  {{ Form::label('', trans('syntara::users.first-name')) }}
                  {{ Form::text('first_name', Input::old('first_name', isset($operador)?$operador->user->first_name:null), array('class'=>'form-control','placeholder'=>trans('syntara::users.first-name'),'required')) }}
                  {{ $errors->first('first_name', '<p class="text-danger">:message</p>') }}
                </div>
                <div class="form-group">
                  {{ Form::label('', 'NSS') }}
                  {{ Form::text('nss', Input::old('nss', isset($operador)?$operador->nss:null), array('class'=>'form-control','placeholder'=>'NSS','required')) }}
                  {{ $errors->first('nss', '<p class="text-danger">:message</p>') }}
                </div>
                <div class="form-group">
                  {{ Form::label('', 'Teléfono') }}
                  {{ Form::text('telefono', Input::old('telefono', isset($operador)?$operador->telefono:null), array('class'=>'form-control','placeholder'=>'Teléfono','required')) }}
                  {{ $errors->first('telefono', '<p class="text-danger">:message</p>') }}
                </div>
                <div class="form-group">
                  {{ Form::label('', 'Contacto de Emergencia') }}
                  {{ Form::text('contacto', Input::old('contacto', isset($operador)?$operador->contacto:null), array('class'=>'form-control','placeholder'=>'Contacto de Emergencia','required')) }}
                  {{ $errors->first('contacto', '<p class="text-danger">:message</p>') }}
                </div>
                <div class="form-group">
                  {{ Form::label('', 'Teléfono Contacto de Emergencia') }}
                  {{ Form::text('tel_contacto', Input::old('tel_contacto', isset($operador)?$operador->tel_contacto:null), array('class'=>'form-control','placeholder'=>'Teléfono Contacto de Emergencia','required')) }}
                  {{ $errors->first('tel_contacto', '<p class="text-danger">:message</p>') }}
                </div>
								<div class="form-group">
                  {{ Form::label('', 'Vigencia Licencia') }}
                  {{ Form::text('vigencia', Input::old('vigencia', isset($operador)?$operador->vigencia:null), array('id'=>'vigencia','class'=>'form-control','placeholder'=>'Vigencia de la Licencia','required')) }}
                  {{ $errors->first('vigencia', '<p class="text-danger">:message</p>') }}
                </div>
								<div class="form-group">
                  {{ Form::label('', 'Vigencia Medicina Preventiva') }}
                  {{ Form::text('medica', Input::old('medica', isset($operador)?$operador->medica:null), array('id'=>'medica','class'=>'form-control','placeholder'=>'Vigencia Medicina Preventiva','required')) }}
                  {{ $errors->first('medica', '<p class="text-danger">:message</p>') }}
                </div>
								<div class="form-group">
                  {{ Form::label('', 'Unidad Asignada') }}
                  {{ Form::select('unidad_id', [0 => 'Selecciona uno', 'Unidad | Placas'=>$unidades_list], Input::old('unidad_id', isset($operador)?$operador->unidad_id:null), ['class' => 'form-control buscaCotizacion','required']) }}
                  {{ $errors->first('unidad_id', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  {{ Form::label('', 'Observaciones') }}
                  {{ Form::textarea('observaciones', isset($operador)?$operador->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
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
    //Date picker
    $('#vigencia').datetimepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true,
      language: 'es',
      startView: 2,
      minView:2
    });
		$('#medica').datetimepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true,
      language: 'es',
      startView: 2,
      minView:2
    });
  </script>
@stop
