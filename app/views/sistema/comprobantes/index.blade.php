@extends('dashboard.layouts.dashboard.window')

@section('content')
  @include('notifications')

  <div class="row text-center">
    <a href="#" onclick="cerrar();" class="btn btn-lg btn-success">Cerrar ventana</a>
  </div>

@stop

@section('scripts')
  <script type="text/javascript">
    function cerrar() {
      opener.location.reload();
      window.close(); // or self.close();
    }
  </script>
@stop
