@extends('dashboard.layouts.dashboard.master')

@section('content')
	
	<div class="row">
    @include('notifications')
  </div>

  <div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"><i class="fas fa-address-book"></i> {{ $user->first_name }} {{ $user->last_name }}</h3>
				</div>
				<div class="box-body">
					<table id="sueldos" class="table table-bordered table-striped table-responsive">
						<caption>
							Fechas de pago de Sueldo de: {{ $user->first_name }} {{ $user->last_name }}
						</caption>
						<thead>
							<tr>
								<th>Fecha de Inicio</th>
								<th>Fecha Fin</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($sueldos as $sueldo)
								<tr>
									<td>{{ $sueldo->fecha_inicio }}</td>
									<td>{{ $sueldo->fecha_fin }}</td>
									<td align="center">
                    <a href="{{ URL::to('sueldos?operador='.$operador.'&fecha1='.$sueldo->fecha_inicio.'&fecha2='.$sueldo->fecha_fin) }}" class="btn btn-sm btn-default" target="_blank"><i class="fas fa-money-bill-alt"></i> Detalle</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

@stop