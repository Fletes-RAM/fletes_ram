@extends('dashboard.layouts.dashboard.master')

@section('content')

	@include('sistema.prestamo.form')

@stop

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

@section('scripts')
<script>
	$(document).ready(function() {
		$('.operador').select2({
			language: "es"
		});
	});
</script>

@endsection