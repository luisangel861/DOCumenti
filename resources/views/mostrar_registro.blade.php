
@extends('layouts.app')

@section('content')
<div class="container">
  <a class="btn btn-primary" href="{{url('/')}}/mostrar_lotes" name="submit">Ver lista de lotes</a>
  @if(!isset($lote))
  <form method="post" action="{{ route('registrar_lote') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
  @endif
    <div class="form-group">
      <label for="exampleInputEmail1">Nombre del lote</label>
      @if(isset($lote))
      <p>{{$lote[0]->nombre}}</p>
      @else
      <input type="text" class="form-control" name="nombre">
      @endif
      @if($errors->has('nombre'))
          <label class="text-danger">{{ $errors->first('nombre') }}</label>
      @endif
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Número de lote</label>
      @if(isset($lote))
      <p>{{$lote[0]->num_lote}}</p>
      @else
      <input class="form-control" name="numero">
      @endif
      @if($errors->has('numero'))
          <label class="text-danger">{{ $errors->first('numero') }}</label>
      @endif
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Fecha de entrega</label>
      @if(isset($lote))
      <p>{{$lote[0]->fecha_entrega}}</p>
      @else
      <input type="date" class="form-control" name="fecha">
      @endif
      @if($errors->has('fecha'))
          <label class="text-danger">{{ $errors->first('fecha') }}</label>
      @endif
    </div>
    @if(isset($lote))
      <p>{{$lote[0]->fecha_entrega}}</p>
      @else
      <div class="form-group">
        <label for="exampleFormControlFile1">Archivo</label>
        <input type="file" class="form-control-file" name="file">
        @if($errors->has('file'))
            <label class="text-danger">{{ $errors->first('file') }}</label>
        @endif
      </div>
      <button type="submit" class="btn btn-primary" name="submit">Registrar</button>

  </form>
  @endif
  @if(isset($archivos) || isset($id_lote))
  <form method="post" action="{{ route('agregar_archivo') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
    <div class="form-group">
      <label for="exampleFormControlFile1">Archivo</label>
      <input type="file" class="form-control-file" name="file">
      @if($errors->has('file'))
          <label class="text-danger">{{ $errors->first('file') }}</label>
      @endif

      <input type="hidden" name="id_lote" value="{{$id_lote}}">
    </div>
    <button type="submit" class="btn btn-primary" name="submit">Agregar archivo</button>
  </form>
  @endif
  @if(isset($errores) && sizeof($errores) > 0)
  <div class="col-md-12">
      <div>Nombre del Archivo</div>
      <table class="table table-light">
          <thead class="thead-light">
              <tr>
                  <td>#</td>
                  <th>Mensajes Validacón</th>                      
                  
              </tr>
          </thead>
          <tbody>
              @foreach ($errores as $item)
              <tr>
                <td>{{$loop->iteration}}</td>
                  <td>{{$item[0]}}</td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  @endif
  @if(isset($archivos) && sizeof($archivos) > 0)
  <div class="col-md-12">
      <div>Lista de archivos</div>
      <table class="table table-light">
          <thead class="thead-light">
              <tr>
                  <th>#</th>
                  <th>Nombre del archivo</th> 
                  <th>Ver archivo</th> 
              </tr>
          </thead>
          <tbody>
              @foreach ($archivos as $item)
              <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$item->nombre_archivo}}</td>
                  <td><a href="{{url('/')}}/{{$item->ruta_archivo}}" target="_blank" download="{{$item->nombre_archivo}}">Descargar archivo</a></td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  @endif

</div>
@endsection