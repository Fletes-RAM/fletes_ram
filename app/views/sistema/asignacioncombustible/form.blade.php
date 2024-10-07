<div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
  {{ Form::label('', 'Fecha') }}
  {{ Form::text('fecha', Input::old('fecha', isset($asignacioncombustible)?$asignacioncombustible->fecha:null), ['class' => 'form-control','required','id'=>'fecha']) }}
  {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
</div>
<div class="form-group{{ $errors->has('foto2') ? ' has-error' : '' }}">
    {{ Form::label('foto2', 'Foto Tablero Antes Cargar Combustible') }}
    {{ Form::file('foto2', ['required' => 'required']) }}
    <p class="help-block">Foto del Tablero Antes Cargar Combustible</p>
    <small class="text-danger">{{ $errors->first('foto2') }}</small>
</div>
<div class="form-group{{ $errors->has('gasolinera_id') ? ' has-error' : '' }}">
  {{ Form::label('', 'Gasolinera') }}
  {{ Form::select('gasolinera_id', [null => 'Selecciona uno', 0 => 'Otra', 'Gasolinera'=>$gasolineras_list], Input::old('gasolinera_id', isset($asignacion)?$asignacion->gasolinera_id:null), ['class' => 'form-control buscaCotizacion','required']) }}
  {{ $errors->first('gasolinera_id', '<p class="text-danger">:message</p>') }}
</div>
<div class="form-group{{ $errors->has('ticket') ? ' has-error' : '' }}">
  {{ Form::label('', 'Ticket') }}
  {{ Form::text('ticket', Input::old('ticket', isset($asignacioncombustible)?$asignacioncombustible->ticket:null), ['class' => 'form-control','required','id'=>'ticket']) }}
  {{ $errors->first('ticket', '<p class="text-danger">:message</p>') }}
</div>
<div class="form-group{{ $errors->has('litros') ? ' has-error' : '' }}">
    {{ Form::label('litros', 'Litros Cargados') }}
    {{ Form::text('litros', Input::old('litros', isset($asignacioncombustible)?$asignacioncombustible->litros:null), ['class' => 'form-control', 'required' => 'required']) }}
    <small class="text-danger">{{ $errors->first('litros') }}</small>
</div>
<!--<div class="form-group{{ $errors->has('precio') ? ' has-error' : '' }}">
    {{ Form::label('precio', 'Precio') }}
    {{ Form::text('precio', Input::old('precio', isset($asignacioncombustible)?$asignacioncombustible->precio:null), ['class' => 'form-control', 'required' => 'required']) }}
    <small class="text-danger">{{ $errors->first('precio') }}</small>
</div>-->
<div class="form-group{{ $errors->has('foto4') ? ' has-error' : '' }}">
    {{ Form::label('foto4', 'Foto del Kilometraje') }}
    {{ Form::file('foto4', ['required' => 'required']) }}
    <p class="help-block">Foto del Kilometraje</p>
    <small class="text-danger">{{ $errors->first('foto4') }}</small>
</div>
<div class="form-group{{ $errors->has('kilometraje') ? ' has-error' : '' }}">
    {{ Form::label('kilometraje', 'Kilometraje Actual del Tablero') }}
    {{ Form::text('kilometraje', Input::old('kilometraje', isset($asignacioncombustible)?$asignacioncombustible->kilometraje:null), ['class' => 'form-control', 'required' => 'required']) }}
    <small class="text-danger">{{ $errors->first('kilometraje') }}</small>
</div>
<div class="form-group{{ $errors->has('foto') ? ' has-error' : '' }}">
    {{ Form::label('foto', 'Foto Ticket') }}
    {{ Form::file('foto', ['required' => 'required']) }}
    <p class="help-block">Foto del Ticket</p>
    <small class="text-danger">{{ $errors->first('foto') }}</small>
</div>
<div class="form-group{{ $errors->has('foto3') ? ' has-error' : '' }}">
    {{ Form::label('foto3', 'Foto Tablero Despues de Cargar Combustible') }}
    {{ Form::file('foto3', ['required' => 'required']) }}
    <p class="help-block">Foto Tablero Despues de Cargar Combustible</p>
    <small class="text-danger">{{ $errors->first('foto3') }}</small>
</div>
