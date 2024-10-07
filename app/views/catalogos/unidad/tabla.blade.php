<table id="unidad" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Unidad</th>
      <th>Tipo de Unidad</th>
      <th>Placas</th>
      <th>Número de Serie</th>
      <th>Póliza</th>
      <th>Aseguradora</th>
      <th>Vigencia</th>
      <th>Observaciones</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($unidades as $unidad)
      <tr>
        <td>{{ $unidad->unidad }}</td>
        <td>{{ $unidad->tipounidad->tipo_de_unidad }}</td>
        <td>{{ $unidad->placas }}</td>
        <td>{{ $unidad->serie }}</td>
        <td>{{ $unidad->poliza }}</td>
        <td>{{ $unidad->aseguradora }}</td>
        <td>{{ $unidad->vigencia }}</td>
        <td>{{ $unidad->observaciones }}</td>
        <td align="center">
          <a class="btn btn-sm btn-info" href="{{ URL::route('putUnidad', $unidad->id) }}" data-toggle="tooltip" data-placement="top" title="Editar Unidad Autorizada"><i class="fas fa-pencil-alt"></i> Editar</a>
          <a class="btn btn-sm btn-danger" href="#" data-placement="top" title="Borrar Unidad Autorizada" data-toggle="modal" data-target=".bs-example-modal-lg{{ $unidad->id }}"><i class="fas fa-trash"></i> Borrar</a>
          <a class="btn btn-sm btn-primary" href="{{ URL::route('bitacora.index', ['unidad_id'=>$unidad->id]) }}"><i class="fa fa-book"></i> Bitácora</a>
        </td>
        </td>
      </tr>
      <div class="modal fade bs-example-modal-lg{{ $unidad->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-body">

            <h1>¡Atención!</h1>
            <h3>Se va a eliminar la Unidad: <b>{{ $unidad->unidad }}</b> con Placas: <b>{{ $unidad->placas }}</b>.</h3>
            <h3>¿Está seguro?</h3>
            {{ Form::open(['route'=>['deleteUnidad',$unidad->id],'id'=>'myForm'.$unidad->id,'autocomplete'=>'off']) }}
              <a href="#" onclick="document.getElementById('myForm{{ $unidad->id }}').submit();" class="btn btn-danger btn-lg"><b><i class="fas fa-trash fa-x2"></i> Borrar</b></a>
              <button type="button" class="btn btn-default btn-lg pull-right" data-dismiss="modal"><b><i class="fa fa-thumbs-up fa-x2"></i> Cancelar</b></button>
            {{ Form::close() }}
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th>Unidad</th>
      <th>Tipo de Unidad</th>
      <th>Placas</th>
      <th>Número de Serie</th>
      <th>Póliza</th>
      <th>Aseguradora</th>
      <th>Vigencia</th>
      <th>Observaciones</th>
      <th>Acciones</th>
    </tr>
  </tfoot>
</table>
