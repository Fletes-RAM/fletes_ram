@extends('dashboard.layouts.dashboard.window')

@section('content')
  @include('notifications')

  <div class="col-sm-12">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Comprobantes de Combustible</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          {{ Form::open(['id'=>'form','route'=>'comprobante_combustible.store']) }}
          {{ Form::hidden('user_id', $operador)  }}
          {{ Form::hidden('fecha1', $fecha1)  }}
          {{ Form::hidden('fecha2', $fecha2)  }}
          <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Ticket</th>
                <th>Litros</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Seleccionar</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comprobantes_combustibles as $comprobante_combustible)
                <tr>
                  <td>{{ $comprobante_combustible->fecha }}</td>
                  <td>{{ $comprobante_combustible->ticket }}</td>
                  <td>{{ $comprobante_combustible->litros }}</td>
                  <td class="text-right">$ {{ number_format($comprobante_combustible->precio,2,'.',',') }}</td>
                  <td class="text-right">$ {{ number_format($comprobante_combustible->total,2,'.',',') }}</td>
                  <td>{{ Form::checkbox('combustible_id[]',$comprobante_combustible->id) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <button type="submit" class="btn btn-lg btn-default ">Guardar</button>
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script type="text/javascript">
    $('form').validate();
  </script>
@stop
