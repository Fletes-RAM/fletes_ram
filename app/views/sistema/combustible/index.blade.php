@extends('dashboard.layouts.dashboard.master')

@section('content')
	
	<div class="row">
    @include('notifications')
  </div>

  <!-- Solid boxes -->
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-gas-pump"></i> Costo del combustible</h3>
				</div>
				<div class="box-body">
					<!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-4 col-sm-12">
              <!-- small box -->
              <div class="small-box bg-black">
                <div class="inner">
                  <h4>
                    Diesel
                  </h4>
                  <h3>
                    $<a href="#" id="diesel" name="diesel" data-type="text" data-pk="1" data-url="/costo_combustible/{{ $diesel->id }}/editar" data-title="Costo Diesel">{{ $diesel->costo }}</a> lt.
                  </h3>
                  <h4>
                  	Fecha Última Actualización
                  </h4>
                  <h4>
                  	{{ Date::parse($diesel->updated_at)->format('d M Y H:i') }}
                  </h4>
                </div>
                <div class="icon">
                  <i class="fas fa-gas-pump"></i>
                </div>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-sm-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h4>
                    Magna
                  </h4>
                  <h3>
                    $<a href="#" id="magna" name="magna" data-type="text" data-pk="1" data-url="/costo_combustible/{{ $magna->id }}/editar" data-title="Costo Magna">{{ $magna->costo }}</a> lt.
                  </h3>
                  <h4>
                  	Fecha Última Actualización
                  </h4>
                  <h4>
                  	{{ Date::parse($magna->updated_at)->format('d M Y H:i') }}
                  </h4>
                </div>
                <div class="icon">
                    <i class="fas fa-gas-pump"></i>
                </div>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-sm-12">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h4>
                    Premium
                  </h4>
                  <h3>
                    $<a href="#" id="premium" name="premium" data-type="text" data-pk="1" data-url="/costo_combustible/{{ $premium->id }}/editar" data-title="Costo Premium">{{ $premium->costo }}</a> lt.
                  </h3>
                  <h4>
                  	Fecha Última Actualización
                  </h4>
                  <h4>
                  	{{ Date::parse($premium->updated_at)->format('d M Y H:i') }}
                  </h4>
                </div>
                <div class="icon">
                  <i class="fas fa-gas-pump"></i>
                </div>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
				</div>
			</div>
		</div>
	</div>

@stop

@section('scripts')
  <!-- page script -->
  <script type="text/javascript">
  	$(document).ready(function() {
		  $('#diesel').editable({
		  	success: function(response, config) {
		      if(response.errors == 'errors') {
		      	config.error.call(this, data.errors); //msg will be shown in editable form
		    	}
		    	location.reload();
		    },
		  	placement: 'bottom',
		  	mode: 'inline'
		  });
		  $('#magna').editable({
		  	success: function(response, config) {
		      if(response.errors == 'errors') {
		      	config.error.call(this, data.errors); //msg will be shown in editable form
		    	}
		    	location.reload();
		    },
		  	placement: 'bottom',
		  	mode: 'inline'
		  });
		  $('#premium').editable({
		  	success: function(response, config) {
		      if(response.errors == 'errors') {
		      	config.error.call(this, data.errors); //msg will be shown in editable form
		    	}
		    	location.reload();
		    },
		  	placement: 'bottom',
		  	mode: 'inline'
		  });
		});
   	window.onload=function () {
        $('#Menu3').addClass('active');
    };
    </script>

@stop