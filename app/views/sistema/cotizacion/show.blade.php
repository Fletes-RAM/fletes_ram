@extends('dashboard.layouts.dashboard.master')

@section('content')
	@include('notifications')
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
	  	<p class="pull-right">Tuxtla Gutiérrez, Chiapas {{ $dia }} de {{ $mes }} de {{ $year }}</p>
	    <br>
	    <h1 class="text-center"><b>Asunto: Cotización</b></h1>

	      <h3>
	        Atención: <br>
	        {{ $cotizacion->cliente->nombre_contacto }} <br>
	        {{ $cotizacion->cliente->cliente }}
	      </h3>
	    <br>
	    <p>Es un gusto saludarlo y al mismo tiempo presentarle la cotización solicitada:</p>
	    <br>
			<table width="100%" border="1">
	      <thead>
	        <tr bgcolor="#dd0a13">
	          <th>Servicio</th>
	          <th>Costo</th>
	        </tr>
	      </thead>
	      <tbody>
	        <tr>
	          <td>Flete en {{ $cotizacion->tipounidad->tipo_de_unidad }} <br><b>Ruta:</b> {{ $cotizacion->ruta->nombre }}</td>
	          <td class="text-center">$ {{ number_format($cotizacion->propuesta, 2, '.', ',') }}</td>
	        </tr>
	      </tbody>
	    </table>
	    <br>
	    <p class="justify">
	      {{ $cotizacion->observaciones }}
	    </p>
	    <br>
			<p>
	      <b>
	        No incluye impuestos ni maniobras. <br>
	        Esta cotización tiene una vigencia de 30 días. <br>
	        No se considera el pago de permiso por carga o descarga, en caso de que aplique.
	      </b>
	    </p>
			<br>
	    <p>
	      Sin mas por el momento quedo a sus órdenes.
	    </p>
	    <br><br>
	    <h3 class="text-center">
	      RESPETUOSAMENTE <br>
	      Lic. Miguel Ángel Lezama Zúñiga
	    </h3>
		</div>
		<br><br>
		<div class="col-md-6 col-md-offset-3">
			<a class="btn btn-primary btn-lg btn-block" href="{{ URL::route('showCotizacionPdf', $cotizacion->id) }}" role="button" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
			<a class="btn btn-default btn-lg btn-block" href="{{ URL::route('sendCotizacion', $cotizacion->id) }}" role="button"><i class="fas fa-envelope"></i> Enviar al Cliente</a>
		</div>
	</div>
@endsection
