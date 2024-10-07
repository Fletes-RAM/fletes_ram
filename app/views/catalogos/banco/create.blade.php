@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($banco))
  					<h3 class="box-title"><i class="fas fa-piggy-bank"></i> Editar Banco</h3>
  				@else
  					<h3 class="box-title"><i class="fas fa-piggy-bank"></i> Nuevo Banco</h3>
  				@endif
  			</div>
  			{{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
  			<div class="box-body">
  					<div class="form-group col-md-4">
  						<div class="row">
                {{ Form::label('', 'Banco') }}
    						{{ Form::text('banco', Input::old('banco', isset($banco)?$banco->banco:null), array('class'=>'form-control required','placeholder'=>'Banco')) }}
    						{{ $errors->first('banco', '<p class="text-danger">:message</p>') }}
              </div>
              <div class="row">
                {{ Form::label('', 'Cuenta') }}
                {{ Form::text('no_cuenta', Input::old('no_cuenta', isset($banco)?$banco->no_cuenta:null), array('class'=>'form-control required','placeholder'=>'Cuenta')) }}
                {{ $errors->first('no_cuenta', '<p class="text-danger">:message</p>') }}
              </div>
							<div class="row">
                {{ Form::label('', 'CLABE') }}
                {{ Form::text('clabe', Input::old('clabe', isset($banco)?$banco->clabe:null), array('class'=>'form-control required','placeholder'=>'CLABE')) }}
                {{ $errors->first('clabe', '<p class="text-danger">:message</p>') }}
              </div>
  					</div>
  					<div class="form-group col-md-8">
  						{{ Form::label('', 'Observaciones') }}
  						{{ Form::textarea('observaciones', isset($banco)?$banco->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}
  					</div>
  			</div>
  			<div class="box-footer">
				{{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
				</div>
					{{ Form::close() }}
  		</div>
  	</div>
  </div>

@endsection

@section('scripts')
	<script type="text/javascript">
	$("#form").validate();

		$( function() {
			CKEDITOR.replace('observaciones');
	 	} );


		//Date picker
  window.onload=function () {
    $('#Menu1').addClass('active');
    $('#Menu1-1-6').addClass('active');
  };
	</script>
@stop
