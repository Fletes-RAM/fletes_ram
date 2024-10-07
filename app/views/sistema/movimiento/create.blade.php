@extends('dashboard.layouts.dashboard.master')



@section('content')



	<div class="row">

    @include('notifications')

  </div>



  <div class="row">

  	<div class="col-lg-12">

  		<div class="box box-info">

  			<div class="box-header">

  				@if (isset($banco_mov))

  					<h3 class="box-title"><i class="fas fa-piggy-bank"></i> Editar Movimiento Bancario</h3>

  				@else

  					<h3 class="box-title"><i class="fas fa-piggy-bank"></i> Nuevo Movimiento Bancario</h3>

  				@endif

  			</div>

  			{{ Form::open(['role'=>'form','id'=>'form', 'autocomplete'=>'off']) }}

  			<div class="box-body">

					<div class="row">

  					<div class="form-group col-md-4">

  						<div class="row">

                {{ Form::label('', 'Banco') }}

                {{ Form::select('bancos_id', [null=>'Seleccione uno']+$bancos, Input::old('bancos_id', isset($banco_mov)?$banco_mov->banco:null), array('class'=>'form-control required')) }}

    						{{ $errors->first('bancos_id', '<p class="text-danger">:message</p>') }}

              </div>

							<div class="row">

								{{ Form::label('', 'Categoría Bancario') }}

								{{ Form::select('categoria_id', [null=>'Seleccione Uno']+$categorias, isset($banco_mov)?$banco_mov->categoria_id:null, array('class'=>'form-control required','onchange'=>'cambios();','id'=>'categoria_id')) }}

								{{ $errors->first('categoria_id', '<p class="text-danger">:message</p>') }}

							</div>

							<div class="row">

								{{ Form::label('', 'Subcategoría Bancario') }}

								{{ Form::select('subcategoria_id', [null=>'Seleccione Uno'], isset($banco_mov)?$banco_mov->subcategoria_id:null, array('class'=>'form-control required','id'=>'subcategoria')) }}

								{{ $errors->first('subcategoria_id', '<p class="text-danger">:message</p>') }}

							</div>

              <div class="row">

                {{ Form::label('', 'Descripción') }}

                {{ Form::text('movimiento', Input::old('movimiento', isset($banco_mov)?$banco_mov->movimiento:null), array('class'=>'form-control required','placeholder'=>'Descripción')) }}

                {{ $errors->first('movimiento', '<p class="text-danger">:message</p>') }}

              </div>

							<div class="row">

                {{ Form::label('', 'Folio') }}

                {{ Form::text('folio', Input::old('folio', isset($banco_mov)?$banco_mov->folio:null), array('class'=>'form-control required','placeholder'=>'Folio')) }}

                {{ $errors->first('folio', '<p class="text-danger">:message</p>') }}

              </div>

							<div class="row">

                {{ Form::label('', 'Fecha') }}

                {{ Form::text('fecha', Input::old('fecha', isset($banco_mov)?$banco_mov->fecha:null), array('class'=>'form-control required','placeholder'=>'Fecha','id'=>'fecha')) }}

                {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}

              </div>

							<div class="row">

                {{ Form::label('', 'Tipo') }}

                {{ Form::select('tipo', [null=>'Seleccione uno','1'=>'Ingreso','-1'=>'Egreso'], Input::old('tipo', isset($banco_mov)?$banco_mov->tipo:null), array('class'=>'form-control required')) }}

                {{ $errors->first('tipo', '<p class="text-danger">:message</p>') }}

              </div>

              <div class="row">

                {{ Form::label('', 'Cantidad') }}

                {{ Form::number('cantidad', Input::old('cantidad', isset($banco_mov)?$banco_mov->cantidad:null), array('class'=>'form-control required','placeholder'=>'Cantidad')) }}

                {{ $errors->first('cantidad', '<p class="text-danger">:message</p>') }}

              </div>

  					</div>

  					<div class="form-group col-md-8">

  						{{ Form::label('', 'Observaciones') }}

  						{{ Form::textarea('observaciones', isset($banco_mov)?$banco_mov->observaciones:null, ['class'=>'form-control','id'=>'observaciones']) }}

  					</div>

					</div>

  			</div>

  			<div class="box-footer">

					<div class="row">

						{{ Form::hidden('periodo', $periodo->periodo) }}

						{{ Form::submit('Guardar', array('class'=>'btn btn-primary')) }}

					</div>

				</div>

					{{ Form::close() }}

  		</div>

  	</div>

  </div>



@endsection



@section('scripts')

	<script type="text/javascript">

	$("#form").validate();



	function cambios(){

		valor = document.getElementById("categoria_id").value;

    $.get("../api/categorias",

    {

      categoria_id:valor

    },

      function(data){

        var subcat = $('#subcategoria');

        subcat.empty();

        $.each(data, function(index, element){

          subcat.append("<option value='"+ element.id +"'>" + element.subcategoria + "</option>");

        });

    });

	}



		$( function() {

			CKEDITOR.replace('observaciones');

			$('#fecha').datetimepicker({

				showTimePicker:false,

				format: 'yyyy-mm-dd',

	    	todayHighlight: true,

	    	autoclose: true,

				language: 'es',

				startView: 2,

				minView:2

			});

	 	});





		//Date picker


	</script>

@stop

