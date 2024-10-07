@extends('dashboard.layouts.dashboard.master')

@section('content')

  <div class="row">
    @include('notifications')
  </div>

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <div class="row">
              <div class="col-md-3">
                <i class="fas fa-info" data-fa-transform="shrink-3" data-fa-mask="fas fa-circle"></i> Folio:</br> {{ $asignacion->cotizacion->folio }}
              </div>
              <div class="col-md-3">
                <i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i> Ruta</br> {{ $asignacion->cotizacion->ruta->nombre }}
              </div>
              <div class="col-md-3">
                <i class="fas fa-address-book"></i> Cliente:</br> {{ $asignacion->cotizacion->cliente->cliente }}
              </div>
              @if ($user->inGroup($admin))
                <div class="col-md-3">
                  <i class="fas fa-gas-pump"></i> Combustible Cotizado</br> $ {{ number_format($asignacion->cotizacion->combustible,2,'.',',') }}
                </div>
              @endif
            </div>

          </h3>
        </div>
        <div class="panel-body text-center">
          @if ($user->inGroup($operadores))
            <a href="{{ URL::route('newAsignacionCombustible', $asignacion->id) }}" class="button7 btn btn-primary" >
              <span style="font-size:3em; font-family:'Comic Sans MS'; border-right:1px solid rgba(255,255,255,0.5); padding-right:0.3em; margin-right:0.3em; vertical-align:middle"> <i class="fas fa-gas-pump"></i> </span>
              Carga de Combustible
            </a>
            <br> <br> <br>
            <a  class="button7 btn btn-danger" href="#" data-placement="top" title="Terminar Ruta" data-toggle="modal" data-target=".bs-example-modal-lg{{ $asignacion->id }}">
              <span style="font-size:3em; font-family:'Comic Sans MS'; border-right:1px solid rgba(255,255,255,0.5); padding-right:0.3em; margin-right:0.3em; vertical-align:middle"> <i class="fas fa-warehouse"></i> </span>
              Terminar Ruta
            </a>
            <div class="modal fade bs-example-modal-lg{{ $asignacion->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">

                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-8 col-md-offset-2">
                        <h1>¡Atención!</h1>
                        <h3>Terminar la Ruta con Folio: <b>{{ $asignacion->cotizacion->folio }} </b>.</h3>
                        <h3>¿Está seguro?</h3>
                        {{ Form::open(['route'=>['putAsignacionPost',$asignacion->id],'id'=>'myForm'.$asignacion->id]) }}
                        <a href="#" onclick="document.getElementById('myForm{{ $asignacion->id }}').submit();" class="btn btn-danger btn-lg pull-left"><b><i class="fas fa-warehouse fa-2x"></i> Terminar Ruta</b></a>
                        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fas fa-thumbs-up fa-2x"></i> Cancelar</b></button>
                        {{ Form::close() }}
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          @else
            <div class="table-responsive">
               <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Gasolinera</th>
                    <th>Ticket</th>
                    <th>Litros</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>KM</th>
                    <th>Rendimiento</th>
                    <th>Foto Km</th>
                    <th>Foto Tablero Antes</th>
                    <th>Foto Ticket</th>
                    <th>Foto Tablero Despues</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($combustibles as $combustible)
                    <tr>
                      <td nowrap>{{ $combustible->fecha }}</td>
                      <td>
                        @if ($combustible->gasolinera_id == 0)
                          Otra
                        @else
                          {{ $combustible->gasolinera->gasolinera }}
                        @endif
                      </td>
                      <td>{{ $combustible->ticket }}</td>
                      <td>{{ number_format($combustible->litros,2,'.',',') }}</td>
                      <td class="text-right" nowrap>$ {{ number_format($combustible->precio,2,'.',',') }}</td>
                      <td class="text-right" nowrap>{{ number_format($combustible->total,2,'.',',') }}</td>
                      <td>{{ $combustible->kilometraje }}</td>
                      <td nowrap>{{ $combustible->rendimiento }} Lts x Km</td>
                      <td class="text-center"> <a href="#" id="myImg4{{ $combustible->id }}"><i class="fas fa-tachometer-alt fa-3x"></i></a> </td>
                      <td class="text-center"> <a href="#" id="myImg2{{ $combustible->id }}"><i class="fas fa-thermometer-empty fa-3x"></i></a> </td>
                      <td class="text-center"> <a href="#" id="myImg{{ $combustible->id }}"><i class="fas fa-receipt fa-3x"></i></a> </td>
                      <td class="text-center"> <a href="#" id="myImg3{{ $combustible->id }}"><i class="fas fa-thermometer-full fa-3x"></i></a> </td>
                      <td>
                        <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Captura de Combustible" data-toggle="modal" data-target=".bs-example-modal-lg{{ $combustible->id }}"><i class="fas fa-trash"></i> Borrar</a>
                      </td>
                    </tr>

                    <div class="modal fade bs-example-modal-lg{{ $combustible->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    								  <div class="modal-dialog modal-lg">
    								    <div class="modal-content">

    								      <div class="modal-body">

    								      <h1>¡Atención!</h1>
    								      <h3>Se va a eliminar la Captura de Combustible.</h3>
    								      <h3>¿Está seguro?</h3>
    								      {{ Form::open(['route'=>['deleteAsignacionCom',$combustible->id],'id'=>'myForm'.$combustible->id]) }}
    								        <a href="#" onclick="document.getElementById('myForm{{ $combustible->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-2x"></i> Borrar</b></a>
    								        <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fas fa-thumbs-up fa-2x"></i> Cancelar</b></button>
    								      {{ Form::close() }}
    								      </div>
    								    </div>
    								  </div>
    								</div>

                    <!-- The Modal -->
                    <div id="myModal{{ $combustible->id }}" class="modal">

                      <!-- The Close Button -->
                      <span class="close" id="close{{ $combustible->id }}"> <i class="fas fa-times fa-2x"></i> </span>

                      <!-- Modal Content (The Image) -->
                      <img class="modal-content" id="img01{{ $combustible->id }}" width="500px">

                      <!-- Modal Caption (Image Text) -->
                      <div id="caption"></div>
                    </div>
                    <script type="text/javascript">
                      // Get the modal
                      var modal = document.getElementById('myModal{{ $combustible->id }}');

                      // Get the image and insert it inside the modal - use its "alt" text as a caption
                      var img = document.getElementById('myImg{{ $combustible->id }}');
                      var img2 = document.getElementById('myImg2{{ $combustible->id }}');
                      var img3 = document.getElementById('myImg3{{ $combustible->id }}');
                      var img4 = document.getElementById('myImg4{{ $combustible->id }}');
                      var modalImg = document.getElementById("img01{{ $combustible->id }}");
                      img.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($combustible->foto_ticket) }}';
                      }
                      img2.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($combustible->foto_tablero_antes) }}';
                      }
                      img3.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($combustible->foto_tablero_despues) }}';
                      }
                      img4.onclick = function(){
                        modal.style.display = "block";
                        modalImg.src = '{{ asset($combustible->foto_tablero_km) }}';
                      }

                      // Get the <span> element that closes the modal
                      var span = document.getElementById("close{{ $combustible->id }}");

                      // When the user clicks on <span> (x), close the modal
                      span.onclick = function() {
                      modal.style.display = "none";
                      }
                    </script>
                  @endforeach
                </tbody>
               </table>
            </div>
          @endif
        </div>
        <div class="panel-footer">
        </div>
      </div>
    </div>
  </div>

@stop
