@extends(Config::get('syntara::views.master'))

@section('content')

	@include('notifications')
		<?php
			$hoy = date('Y-m-d');
		?>


		@if ($user->inGroup($admin) OR $user->inGroup($usuarios))

			<div class="row">
			  <div class="col-md-6 col-md-offset-3">
			    <div class="panel panel-primary">
			      <div class="panel-heading">
			        <h3 class="panel-title">Control de Flotilla</h3>
			      </div>
			      <div class="panel-body">
			        <div class="table-responsive">
								<table id="operadores" class="table table-hover">
									<thead>
										<tr>
											<th>Operador</th>
											<th>Cliente</th>
											<th>Origen</th>
											<th>Destino</th>
											<th>Estatus</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($opes as $operador)
											<tr>
												<td>{{ $operador->user->last_name }} {{ $operador->user->first_name }}</td>
												<td>
													<a href="#" id="cliente_id{{ $operador->id }}" data-type="select" data-pk="{{$operador->id}}" data-url="/api/edita_cliente" data-title="Selecciona Cliente"></a>
												</td>
												<td>
													<a href="#" id="origen{{$operador->id}}" data-value="Sin Origen" data-type="text" data-pk="{{$operador->id}}">{{ $operador->origen }}</a>
												</td>
												<td>
													<a href="#" id="destino{{$operador->id}}" data-value="Sin Destino" data-type="text" data-pk="{{$operador->id}}">{{ $operador->destino }}</a>
												</td>
												<td>
													<a href="#" id="estatus{{$operador->id}}" data-value="Sin Estatus" data-type="text" data-pk="{{$operador->id}}">{{ $operador->estatus }}</a>
												</td>
											</tr>
											<script>
											$(function(){
											    $('#cliente_id{{ $operador->id }}').editable({
															showbuttons: false,
											        value: {{$operador->cliente_id}},
											        source: [
											              {value: 0, text: 'Seleccionar uno'},
																		@foreach ($clientes as $cliente)
																			{value: {{ $cliente->id }}, text: '{{ $cliente->cliente }}'},
																		@endforeach
											           ]
											    });
													$('#origen{{ $operador->id }}').editable({
											        url: '/api/edita_origen',
															placeholder: 'Escribe un Origen',

											        title: 'Origen'
											    });
													$('#destino{{ $operador->id }}').editable({
											        url: '/api/edita_destino',
															placeholder: 'Escribe un Destino',

											        title: 'Destino'
											    });
													$('#estatus{{ $operador->id }}').editable({
											        url: '/api/edita_estatus',
															placeholder: 'Escribe un Estatus',

											        title: 'Estatus'
											    });
											});
											</script>
										@endforeach
									</tbody>
								</table>
			        </div>
			      </div>
			    </div>
			  </div>
			</div>

			<div class="row">
	      <div class="col-md-4">
	          <!-- Navy tile -->
	          <div class="box box-info">
	              <div class="box-header">
	                  <h3 class="box-title">Vigencia de Polizas de Seguro</h3>
	                  <!-- tools box -->
	                  <div class="pull-right box-tools">
	                      <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
	                  </div><!-- /. tools -->
	              </div>
	              <div class="box-body">
									<div class="table-responsive">
										<table id="unidad" class="table table-bordered ">
											<thead>
												<tr>
													<th>Unidad</th>
													<th>Placas</th>
													<th>Número de Serie</th>
													<th>Póliza</th>
													<th>Aseguradora</th>
													<th>Vigencia</th>
													<th>Diferencia</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($unidades as $unidad)
													<?php
													$fecha1 = new DateTime($hoy);
													$fecha2 = new DateTime($unidad->vigencia);
													$interval = $fecha1->diff($fecha2);
													$days = $interval->format('%r%a');

													if ($days >=15) {
														$color = 'success';
													} elseif ($days < 15 AND $days > 0) {
														$color = 'warning';
													} else {
														$color = 'danger';
													}
													?>
													<tr class="{{ $color }}">
														<td>{{ $unidad->unidad }}</td>
														<td>{{ $unidad->placas }}</td>
														<td>{{ $unidad->serie }}</td>
														<td>{{ $unidad->poliza }}</td>
														<td>{{ $unidad->aseguradora }}</td>
														<td>{{ $unidad->vigencia }}</td>
														<td>{{ $interval->format('%r%a') }} días</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
	              </div><!-- /.box-body -->
	          </div><!-- /.box -->
	      </div><!-- /.col -->
				<div class="col-md-4">
				  <div class="box box-info">
				    <div class="box-header">
				      <h3 class="box-title">Vigencia Licencias de Conducir</h3>
				      <div class="box-tools pull-right">
				        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
				      </div>
				    </div>
				    <div class="box-body">
				      <div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Operador</th>
											<th>Vigencia de la Licencia</th>
											<th>Diferencia</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($ops as $operador)
											<?php
											$fecha1 = new DateTime($hoy);
											$fecha2 = new DateTime($operador->vigencia);
											$interval = $fecha1->diff($fecha2);
											$days = $interval->format('%r%a');

											if ($days >=15) {
												$color = 'success';
											} elseif ($days < 15 AND $days > 0) {
												$color = 'warning';
											} else {
												$color = 'danger';
											}
											?>
											<tr class="{{ $color }}">
												<td>{{ $operador->user->first_name }} {{ $operador->user->last_name }}</td>
												<td>{{ $operador->vigencia }}</td>
												<td>{{ $interval->format('%r%a') }} días</td>
											</tr>
										@endforeach
									</tbody>
								</table>
				      </div>
				    </div>
				  </div>
				</div>
				<div class="col-md-4">
				  <div class="box box-info">
				    <div class="box-header">
				      <h3 class="box-title">Vigencia Medicina Preventiva</h3>
				      <div class="box-tools pull-right">
				        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
				      </div>
				    </div>
				    <div class="box-body">
				      <div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Operador</th>
											<th>Vigencia Medicina Preventiva</th>
											<th>Diferencia</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($medica as $operador)
											<?php
											$fecha1 = new DateTime($hoy);
											$fecha2 = new DateTime($operador->medica);
											$interval = $fecha1->diff($fecha2);
											$days = $interval->format('%r%a');

											if ($days >=15) {
												$color = 'success';
											} elseif ($days < 15 AND $days > 0) {
												$color = 'warning';
											} else {
												$color = 'danger';
											}
											?>
											<tr class="{{ $color }}">
												<td>{{ $operador->user->first_name }} {{ $operador->user->last_name }}</td>
												<td>{{ $operador->medica }}</td>
												<td>{{ $interval->format('%r%a') }} días</td>
											</tr>
										@endforeach
									</tbody>
								</table>
				      </div>
				    </div>
				  </div>
				</div>
			</div>
		@endif
		@if($user->inGroup($admin) OR $user->inGroup($operadores))
			<div class="row">
	      <div class="col-md-6 col-md-offset-3">
	          <!-- Info box -->
	          <div class="box box-warning">
	              <div class="box-header">
	                  <h3 class="box-title">Operadores</h3>
										<div class="pull-right box-tools">
											<button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
										</div><!-- /. tools -->
	              </div>
	              <div class="box-body">
									<table class="table table-striped table-hover table-bordered table-condensed clickable " style="font-size: 22px;"
									    aria-labelled-by="" aria-described-by="">

									    <!-- Optional Caption -->
									    <caption>Mis Rutas Asignadas</caption>

									    <thead>
									        <tr>
									            <th>Folio</th>
															<th>Cliente</th>
															<th>Ruta</th>
									        </tr>
									    </thead>

									    <tbody>
												@foreach ($asignaciones as $asignacion)
													<tr onclick="document.location='{{ URL::route('showAsignacion', $asignacion->id) }}';">
														<td>{{ $asignacion->cotizacion->folio }}</td>
														<td>{{ $asignacion->cotizacion->cliente->cliente }}</td>
														<td>{{ $asignacion->cotizacion->ruta->nombre }}</td>
													</tr>
													@if (isset($asignacion->cotizacion->folio))
													@endif
												@endforeach
									    </tbody>

									</table>
	              </div><!-- /.box-body -->
	          </div><!-- /.box -->
	      </div><!-- /.col -->
			</div>
		@endif
		@if($user->inGroup($admin) OR $user->inGroup($operadoresesp))
			<div class="row">
		    <div class="col-md-6 col-md-offset-3">
		      <div class="panel panel-primary">
		        <div class="panel-heading">
		          <h3 class="panel-title"> <i class="fas fa-gas-pump"></i> Carga de Combustible</h3>
		        </div>
		        <div class="panel-body">

							<?php
								$mioperador = Operador::where('user_id',$user->id)->withTrashed()->first();
							?>
							{{-- dump($mioperador->unidad_id) --}}

		          {{ Form::open(['id'=>'form','files' => true,'action' => 'AsignacionCombustibleController@storeEsp']) }}
							{{ Form::hidden('user_id', $user->id) }}
							{{ Form::hidden('unidad_id', isset($mioperador->unidad_id)?$mioperador->unidad_id:0) }}
		          @include('sistema.asignacioncombustible.form')
		        </div>
		        <div class="panel-footer">
		          <div class="form-group">
		            {{ Form::submit('Guardar', ['class'=>'btn btn-lg btn-success']) }}
		          </div>
		          {{ Form::close() }}
		        </div>
		      </div>
		    </div>
		  </div>
		@endif


@endsection

@section('scripts')

  <!-- page script -->
  {{ HTML::script('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js') }}
  {{ HTML::script('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.bootstrap.min.js') }}
  {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js') }}
  {{ HTML::script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js') }}
  {{ HTML::script('//cdn.datatables.net/buttons/1.4.0/js/buttons.colVis.min.js') }}

  <!-- page script -->
  <script type="text/javascript">

      $(function() {
          $('#operadores').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "sDom": '<"top"Bif>rt<"bottom"pl><"clear">',
              "sButtons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ operadores por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ operadores",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 operadores",
              "sInfoFiltered": "(filtrado de un total de _MAX_ operadores)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 0, "desc" ]],
          });
      });
    </script>


	  <script type="text/javascript">
	    $("#form").validate();
	    $( function() {
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
	  </script>


@stop
