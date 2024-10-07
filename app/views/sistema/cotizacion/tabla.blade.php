<table id="cotizacion" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Folio</th>
			<th>Cliente</th>
			<th>Tipo de Unidad</th>
			<th>Ruta</th>
			<th>Tot. Km.</th>
			<th>Propuesta</th>
			<th>Utilidad</th>
			<th>Gastos Admon</th>
			<th>Sueldo Ope</th>
			<th>Otros Gastos</th>
			<th>Combustible</th>
			<th>Casetas</th>
			<th>Observaciones</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($cotizaciones as $cotizacion)
			<tr>
				<td nowrap>{{ $cotizacion->folio }}</td>
				<td>{{ $cotizacion->cliente->cliente }}</td>
				<td>{{ $cotizacion->tipounidad->tipo_de_unidad }}</td>
				<td>{{ $cotizacion->ruta->nombre }}</td>
				<td>{{ $cotizacion->tot_km }} Km.</td>
				<td nowrap>$ {{ number_format($cotizacion->propuesta, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->utilidad, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->gastos_admon, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->sueldo_ope, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->otros_gastos, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->combustible, 2, '.', ',') }}</td>
				<td nowrap>$ {{ number_format($cotizacion->caseta, 2, '.', ',') }}</td>
				<td nowrap>{{ substr(strip_tags($cotizacion->observaciones),0,60) }} ...</td>
				<td align="center" nowrap>
					<div class="btn-group-vertical" role="group" aria-label="Acciones">
						<a href="{{ URL::route('putCotizacion', $cotizacion->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Editar Cotización"><i class="fas fa-edit"></i> Editar Cotización</a>
	          <a href="{{ URL::route('showCotizacion', $cotizacion->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Ver Cotización"><i class="fas fa-money-check-alt"></i> Ver Cotización</a>
	          <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Cotizacion" data-toggle="modal" data-target=".bs-example-modal-lg{{ $cotizacion->id }}"><i class="fas fa-trash"></i> Borrar</a>
					</div>
				</td>
			</tr>
			<div class="modal fade bs-example-modal-lg{{ $cotizacion->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">

			      <div class="modal-body">

			      <h1>¡Atención!</h1>
			      <h3>Se va a eliminar al Cotización: <b>{{ $cotizacion->folio }}</b>.</h3>
			      <h3>¿Está seguro?</h3>
			      {{ Form::open(['route'=>['deleteCotizacion',$cotizacion->id],'id'=>'myForm'.$cotizacion->id]) }}
			        <a href="#" onclick="document.getElementById('myForm{{ $cotizacion->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
			        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-x2"></i> Cancelar</b></button>
			      {{ Form::close() }}
			      </div>
			    </div>
			  </div>
			</div>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>Folio</th>
			<th>Cliente</th>
			<th>Tipo de Unidad</th>
			<th>Ruta</th>
			<th>Tot. Km.</th>
			<th>Propuesta</th>
			<th>Utilidad</th>
			<th>Gastos Admon</th>
			<th>Sueldo Ope</th>
			<th>Otros Gastos</th>
			<th>Combustible</th>
			<th>Casetas</th>
			<th>Observaciones</th>
			<th>Acciones</th>
		</tr>
	</tfoot>
</table>
