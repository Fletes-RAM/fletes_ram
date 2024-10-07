<!-- Modal -->
@foreach ($rutas as $ruta)
  <div class="modal fade" id="myModal{{ $ruta->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalle de Ruta</h4>
        </div>
        <div class="modal-ruta">
          <div class="row">
            <div class="col-sm-3" align="center">
            </div>
            <div class="col-sm-6">
              <h2 align="center">{{ $ruta->nombre }}</h2>
              <hr>
              <div class="row">
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
              <br>
              <div class="row">

              </div>
              <br>
              <div class="row">
                <div class="col-xs-12 col-sm-12">
                  Observaciones: <br>
                  <div align="justify">
                    <b>{{ $ruta->observaciones }}</b>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
@endforeach
