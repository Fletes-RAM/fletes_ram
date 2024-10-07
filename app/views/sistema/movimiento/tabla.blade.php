<?php
  if (isset($saldos)) {
    $saldo_inicial = $saldos->saldo_inicial;
  } else {
    $saldo_inicial = 0;
  }

  if (isset($sumatoria)) {
    $sumatoria_total = $sumatoria->total;
  } else {
    $sumatoria_total = 0;
  }

  $total_ingresos = 0;
  $total_egresos  = 0;
?>

<table id="detalle" class="table table-bordered table-striped table-responsive nowrap">
  <caption style="text-align: center; font-size:20px;">
    Detalle de Movimientos: {{ $banco->banco }} | <b>{{ $banco->no_cuenta }}</b> | CLABE: <b>{{ $banco->clabe }}</b> <br>
    Periodo: <b>{{ $periodo->periodo }}</b> <br>
    Saldo Inicial: <b>$ {{ number_format($saldo_inicial, 2, '.', ',') }}</b> <br>
    Saldo Final: <b>$ {{ number_format($saldo_inicial + $sumatoria_total, 2, '.', ',') }}</b>
  </caption>
	<thead>
		<tr>
      <th>Acciones</th>
      <th>Fecha</th>
      <th>Folio</th>
      <th>Categoría</th>
      <th>Subcategoría</th>
			<th>Movimiento</th>
			<th>Ingreso</th>
			<th>Egreso</th>
			<th>Observaciones</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($detalles as $detalle)
      <?php
        if ($detalle->total>0) {
          $total_ingresos = $total_ingresos + $detalle->total;
        } else {
          $total_egresos = $total_egresos + $detalle->total;
        }
      ?>
			<tr>
        <td>
          <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Movimiento Bancario" data-toggle="modal" data-target=".bs-detalle-modal-lg{{ $detalle->id }}"><i class="fas fa-trash"></i> Borrar</a></td>
        </td>
				<td nowrap>{{ $detalle->fecha }}</td>
				<td>{{ $detalle->folio }}</td>
        <td>{{ $detalle->categoria->categoria }}</td>
        <td>{{ $detalle->subcategoria->subcategoria }}</td>
				<td>{{ $detalle->movimiento }}</td>
				<td style="color:green; text-align: right;">{{ $detalle->total>0?'$ '.number_format($detalle->total, 2, '.', ','):'' }}</td>
				<td style="color:red; text-align: right;">{{ $detalle->total<0?'$ '.number_format($detalle->total, 2, '.', ','):'' }}</td>
				<td>{{ substr(strip_tags($detalle->observaciones),0,60) }} ...</td>
			</tr>
      <div class="modal fade bs-detalle-modal-lg{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-body">

            <h1>¡Atención!</h1>
            <h3>Se va a eliminar el Movimiento Bancario: <b>{{ $detalle->movimiento }}</b>.</h3>
            <h3>¿Está seguro?</h3>
            {{ Form::open(['route'=>['deleteBancoMov',$detalle->id],'id'=>'myForm'.$detalle->id]) }}
              <a href="#" onclick="document.getElementById('myForm{{ $detalle->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
              <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-2x"></i> Cancelar</b></button>
            {{ Form::close() }}
            </div>
          </div>
        </div>
      </div>
		@endforeach
	</tbody>
	<tfoot>
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th style="color:green; text-align: right; font-size:20px;">Total Ingresos =<b> $ {{ number_format($total_ingresos, 2, '.', ',') }}</b></th>
      <th style="color:red; text-align: right; font-size:20px;">Total Egresos = <b>$ {{ number_format($total_egresos, 2, '.', ',') }}</b></th>
      <th></th>
    </tr>
	</tfoot>
</table>
