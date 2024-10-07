<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cotización {{ $cotizacion->folio }}</title>
    <style>
      #watermark { position: fixed; bottom: -10px; right: -10px; width: 100%; height: 1000px; z-index: -1;}
      .right { text-align: right; }
      .center { text-align: center; }
    </style>
  </head>
  <body>
    <div id="watermark"><img src="images/fondo.png" height="100%" width="100%"></div>
    <br><br><br><br><br><br><br>
    <p class="right">Tuxtla Gutiérrez, Chiapas {{ $dia }} de {{ $mes }} de {{ $year }}</p>
    <br>
    <h1 class="center"><b>Asunto: Cotización</b></h1>

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
          <td class="center">$ {{ number_format($cotizacion->propuesta, 2, '.', ',') }}</td>
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
      <h3 class="center">
        RESPETUOSAMENTE <br>
        Lic. Miguel Ángel Lezama Zúñiga
      </h3>
  </body>
</html>
