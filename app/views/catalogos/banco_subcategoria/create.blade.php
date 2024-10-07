@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
  	<div class="col-md-12">
    {{ Form::open(['role'=>'form','id'=>'form','autocomplete'=>'off']) }}
      <div class="box box-info">
        <div class="box-header">
          @if (isset($subcategoria))
            <h3 class="box-title"><i class="fas fa-list"></i> Editar Subcategoría</h3>
          @else
            <h3 class="box-title"><i class="fas fa-list"></i> Nueva Subcategoría</h3>
          @endif
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {{ Form::label('', 'Categoría') }}
								{{ Form::select('categoria_id', [null=>'Seleccione Uno']+$categorias, isset($subcategoria)?$subcategoria->categoria_id:null, array('class'=>'form-control required')) }}
                {{ $errors->first('categoria_id', '<p class="text-danger">:message</p>') }}
              </div>
							<div class="form-group">
                {{ Form::label('', 'Subcategoria') }}
                {{ Form::text('subcategoria', Input::old('subcategoria', isset($subcategoria)?$subcategoria->subcategoria:null), array('class'=>'form-control','placeholder'=>'Subcategoría', 'required')) }}
                {{ $errors->first('subcategoria', '<p class="text-danger">:message</p>') }}
              </div>
            </div>
          </div>


        </div>
        <div class="box-footer">
          {{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}
        </div>
      </div>
    {{ Form::close() }}
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
    $('#Menu1-1-4').addClass('active');
  };
	</script>
@stop
