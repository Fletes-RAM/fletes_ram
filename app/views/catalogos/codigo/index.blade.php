@extends('dashboard.layouts.dashboard.master')

@section('content')

	<div class="row">
		<div class="col-lg-3">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Nuevo Municipio</h3>
					<!-- tools box -->
          <div class="pull-right box-tools">
              <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
          </div><!-- /. tools -->
				</div>
				<div class="box-body">
					{{ Form::open(['role'=>'form','id'=>'form','action'=>'CodigoController@store','mode'=>'post','autocomplete'=>'off']) }}

						<div class="row">
							<div class="col-lg-12 form-group">
								{{ Form::label('', 'Estado') }}
								{{ Form::select('estado', [null=>'Seleccione uno']+$estados, null, ['class'=>'form-control required']) }}
								{{ $errors->first('estado', '<p class="text-danger">:message</p>') }}
							</div>
							<div class="col-lg-12 form-group">
								{{ Form::label('', 'Municipio') }}
								{{ Form::text('municipio', null, ['class'=>'form-control required']) }}
								{{ $errors->first('estado', '<p class="text-danger">:message</p>') }}
							</div>
						</div>

				</div>
				<div class="box-footer">
						{{ Form::submit('Guardar',['class'=>'btn btn-success']) }}
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Estados y Municipio</h3>
					<!-- tools box -->
          <div class="pull-right box-tools">
              <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
          </div><!-- /. tools -->
				</div>
				<div class="box-body">
					<table id="codigos" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Estado</th>
								<th>Municipio</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($municipios as $municipio)
								<tr>
									<td>{{ $municipio->estado }}</td>
									<td>{{ $municipio->municipio }}</td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Estado</th>
								<th>Municipio</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
  <!-- page script -->
  <script type="text/javascript">
  	$("#form").validate();

      $(function() {
          $('#codigos').dataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": true,
              "oLanguage": {
              "sLengthMenu": "_MENU_ municipios por página",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ municipios",
              "sEmptyTable": "No se encontraron datos en la tabla",
              "sInfoEmpty": "Mostrando del 0 al 0 de 0 municipios",
              "sInfoFiltered": "(filtrado de un total de _MAX_ municipios)",
              "sLoadingRecords": "Cargando...",
              "sProcessing": "Procesando...",
              "sSearch": "Buscar:",
              "sZeroRecords": "No se encontraron registros con la búsqueda",
              "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
              }
            },
            "aaSorting": [[ 0,'asc' ],[ 1,'asc' ]],
          });
      });
   	window.onload=function () {
        $('#Menu1').addClass('active');
        $('#Menu1-1-5').addClass('active');
    };
    </script>

@stop
