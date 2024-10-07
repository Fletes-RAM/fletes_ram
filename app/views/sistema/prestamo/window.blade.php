@extends('dashboard.layouts.dashboard.window')

@section('content')
  <div class="row">
    <div class="col-sm-8 col-md-offset-2">
      @include('sistema.prestamo.form')

    </div>
  </div>
@stop

@section('scripts')
	<script type="text/javascript">
	$("#form").validate();

	function cambios(){
		valor = document.getElementById("categoria_id").value;
    $.get("../../api/categorias",
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
				format: 'yyyy-mm-dd',
	    	todayHighlight: true,
	    	autoclose: true,
				language: 'es',
				startView: 2,
				minView:2
			});
	 	});


	</script>
@stop
