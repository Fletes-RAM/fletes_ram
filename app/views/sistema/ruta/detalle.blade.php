<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Detalle de Rutas</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="content" style="padding: 20px;">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Detalle de Ruta <b>{{ $ruta->nombre }}</b></h3>
            </div>
            <div class="panel-body">
              <table class="table table-bordered table-striped table-responsive">
                <thead>
                  <th>Estado Origen</th>
                  <th>Del / Mun Origen</th>
                  <th>Estado Destino</th>
                  <th>Del / Mun Destino</th>
                  <th>Km</th>
                </thead>
                @foreach ($ruta->rutaDetalle as $key => $val)
                  <?php
                    $est      = Estado::where('idEstado',$val->estado)->first();
                    $est_dest = Estado::where('idEstado',$val->estado_destino)->first();
                  ?>
                  <tr>
                    <td>{{ $est->estado }}</td>
                    <td>{{ $val->origen }}</td>
                    <td>{{ $est_dest->estado }}</td>
                    <td>{{ $val->destino }}</td>
                    <td>{{ $val->km }} Km</td>
                  </tr>
                @endforeach
                <tfoot>
                  <th colspan="4" class="text-right"><h3><b>Total Km:</b></h3></th>
                  <th><h3><b>{{ $ruta->total_km }} Km</b></h3></th>
                </tfoot>
              </table>
            </div>
            <div class="panel-footer">
              <h3  class="panel-title">Total de Km. <b>{{ $ruta->total_km }}</b></h3>
            </div>
          </div>
          <div class="well">{{ $ruta->observaciones }}</div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 text-center">
          <button type="button" class="btn btn-danger btn-lg" onclick="cerrar();">Cerrar Ventana</button>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript">
      function cerrar() {
        close();
      }
    </script>
  </body>
</html>
