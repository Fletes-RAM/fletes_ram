@extends('dashboard.layouts.dashboard.master')

@section('content')
	@include('notifications')
	<br><br><br><br><br>
	<div class="row">
		<div class="col-md-12 text-center">
			<div class="box box-success">
				<div class="box-header">
					<h1>Mes</h1>
				</div>
				<div class="box-body" align="center">
					<h1><a href="#" id="periodo" name="periodo" data-type="date" data-pk="1" data-url="/catalogos/banco/periodo/edit" >{{ $periodo->periodo }}</a></h1>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{{ HTML::script('js/locales/bootstrap-datepicker.es.js') }}
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<script>
		$(document).ready(function() {
		  $('#periodo').editable({
		  	success: function(response, config) {
		      if(response.errors == 'errors') {
		      	config.error.call(this, data.errors); //msg will be shown in editable form
		    	}
		    	location.reload();
		    },
				placement: 'right',
				format: 'MM-yyyy',
        viewformat: 'MM-yyyy',
        datepicker: {
          weekStart: 1,
					language: 'es',
					minViewMode: 1
        },
		  });
		});
	</script>
@endsection
