@extends('dashboard.layouts.dashboard.master')

@section('content')
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Editar Ticket de Combustible</h3>
        </div>
        <div class="panel-body">
          {{ Form::open(['method'=>'PUT','route'=>['combustible.update',$i],'autocomplete'=>'off','id'=>'form']) }}

            {{ Form::hidden('tipo', $tipo) }}
            {{ Form::hidden('fecha1', Input::get('fecha1')) }}
            {{ Form::hidden('fecha2', Input::get('fecha2')) }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Fecha</label>
                  {{ Form::text('fecha', $ticket->fecha, ['class'=>'form-control date-picker','required']) }}
                  {{ $errors->first('fecha', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Ticket</label>
                  {{ Form::text('ticket', $ticket->ticket, ['class'=>'form-control','required']) }}
                  {{ $errors->first('ticket', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Litros</label>
                  {{ Form::text('litros', $ticket->litros, ['class'=>'form-control','required']) }}
                  {{ $errors->first('litros', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Precio</label>
                  {{ Form::text('precio', $ticket->precio, ['class'=>'form-control','required']) }}
                  {{ $errors->first('precio', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Kilometraje Actual en el Tablero</label>
                  {{ Form::text('kilometraje', $ticket->kilometraje, ['class'=>'form-control','required']) }}
                  {{ $errors->first('kilometraje', '<p class="text-danger">:message</p>') }}
                </div>
              </div>
              <div class="col-md-6">

              </div>
            </div>


            {{ Form::submit('Guardar', ['class'=>'btn btn-success']) }}

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
    $("#form").validate();

    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd',
      language: 'es',
      todayHighlight: true,
      todayBtn: "linked",
      autoclose: true
    });
  </script>
@stop
