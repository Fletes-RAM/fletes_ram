@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-lg-12">
  		<div class="box box-info">
  			<div class="box-header">
  				@if (isset($origen))
  					<h3 class="box-title"><i class="fas fa-check"></i> Editar Origen</h3>
            {{ Form::open(['role'=>'form','id'=>'form','route'=>['catalogos.origen.update',$origen->id],'method'=>'put','autocomplete'=>'off']) }}
  				@else
  					<h3 class="box-title"><i class="fas fa-check"></i> Nuevo Origen</h3>
            {{ Form::open(['role'=>'form','id'=>'form','action'=>'OrigenController@store','autocomplete'=>'off']) }}
  				@endif
  			</div>
  			<div class="box-body">
  					<div class="form-group col-md-12">
  						<div class="row">
                {{ Form::label('', 'Origen') }}
    						{{ Form::text('origen', Input::old('origen', isset($origen)?$origen->origen:null), array('class'=>'form-control required','placeholder'=>'Origen')) }}
    						{{ $errors->first('origen', '<p class="text-danger">:message</p>') }}
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
	</script>
@stop
