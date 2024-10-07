@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($diesel))
  					<h3 class="box-title">Editar Diesel Autorizado</h3>
  				@else
  					<h3 class="box-title">Nuevo Diesel Autorizado</h3>
  				@endif
  			</div>
              <div class="box-body">
                  <div class="form-group col-md-4">
                    {{ Form::open(['role'=>'form','route'=>'dieselAutorizado.store','id'=>'form']) }}
                    <div class="row">
                        <div class="col-md-12">
                        {{ Form::label('', 'Tipo de Unidad') }}
                        {{ Form::select('tipo_de_unidad_id', [null=>'Seleccione uno']+$tipo_unidad_list, isset($diesel)?$diesel->tipo_de_unidad_id:null, ['class'=>'form-control required','id'=>'tipo_de_unidad_id']) }}
                        {{ $errors->first('tipo_de_unidad_id', '<p class="text-danger">:message</p>') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('', 'Origen') }}
                            {{ Form::text('origen', Input::old('origen', isset($diesel)?$diesel->origen:null), array('class'=>'form-control','placeholder'=>'Origen','required')) }}
                            {{ $errors->first('origen', '<p class="text-danger">:message</p>') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('', 'Destino') }}
                            {{ Form::text('destino', Input::old('destino', isset($diesel)?$diesel->destino:null), array('class'=>'form-control','placeholder'=>'Destino','required')) }}
                            {{ $errors->first('destino', '<p class="text-danger">:message</p>') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('', 'Litros Autorizados') }}
                            {{ Form::text('lts_aut', Input::old('lts_aut', isset($diesel)?$diesel->lts_aut:null), array('class'=>'form-control','placeholder'=>'Litros Autorizados','required')) }}
                            {{ $errors->first('lts_aut', '<p class="text-danger">:message</p>') }}
                        </div>
                    </div>
                    <div class="row">
                        {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            
  		</div>
  	</div>
  </div>

@endsection

@section('scripts')
	<script type="text/javascript">
	$("#form").validate();

	</script>
@stop
