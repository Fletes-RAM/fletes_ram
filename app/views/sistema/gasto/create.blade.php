@extends('dashboard.layouts.dashboard.window')

@section('content')
  @include('notifications')

  <div class="col-sm-12">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Comprobantes de Gastos</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        {{ Form::open(['id'=>'form','route'=>'gasto.store']) }}
          {{ Form::hidden('user_id', $operador)  }}
          {{ Form::hidden('fecha1', $fecha1)  }}
          {{ Form::hidden('fecha2', $fecha2)  }}
          <div class="col-sm-6">
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                {{ Form::label('', 'Fecha') }}
                {{ Form::text('fecha', null, ['class'=>'form-control date-picker']) }}
              </div>
              <div class="form-group">
                {{ Form::label('', 'Descripción') }}
                {{ Form::text('descripcion', null, ['class'=>'required form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('', 'Factura') }}
                {{ Form::text('factura', null, ['class'=>'required form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('', 'Total') }}
                {{ Form::text('total', null, ['class'=>'required form-control']) }}
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {{ Form::label('', 'Observaciones') }}
              {{ Form::textarea('observaciones', null, ['class'=>'form-control']) }}
            </div>
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
  {{ HTML::script('packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}
  {{ HTML::script('packages/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}
  <script type="text/javascript">
    $('form').validate();

    $( function() {
      CKEDITOR.replace('observaciones');
    } );

    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd',
      language: 'es',
      todayHighlight: true,
      todayBtn: "linked",
      autoclose: true
    });
  </script>
@stop
