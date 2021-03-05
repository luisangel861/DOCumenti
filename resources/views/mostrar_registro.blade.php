
@extends('layouts.app')

@section('content')
<style>
    .custom-input-file {
  background-color: #3490dc;
  color: #fff;
  cursor: pointer;
  font-size: 14.4px;
  margin: 0 auto 0;
  min-height: 8px;
  overflow: hidden;
  padding: 5px;
  position: relative;
  text-align: center;
  width: 150px;
  border-radius: 5px;
}

.custom-input-file .input-file {
 border: 10000px solid transparent;
 cursor: pointer;
 font-size: 10000px;
 margin: 0;
 opacity: 0;
 outline: 0 none;
 padding: 0;
 position: absolute;
 right: -1000px;
 top: -1000px;
}
</style>
<div class="container">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Registro de lote</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
         
            <a  href="{{url('/')}}/mostrar_lotes" name="submit" class="btn btn-primary">Ver lista de lotes</a>
        </div>
      </div>
  @if(!isset($lote))
  <form method="post" action="{{ route('registrar_lote') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
  @endif
  <div class="row">
    <div class="col-12 col-md-6">
    <div class="form-group">
      <label for="exampleInputEmail1" class="font-weight-bold">Nombre</label>
      @if(isset($lote))
      <p>{{$lote[0]->nombre}}</p>
      @else
      <input type="text" class="form-control" name="nombre" value="{{old('nombre')}}">
      @endif
      @if($errors->has('nombre'))
          <label class="text-danger">{{ $errors->first('nombre') }}</label>
      @endif
    </div>
  </div>
  <div class="col-12 col-md-6">
    <div class="form-group">
      <label for="exampleInputPassword1" class="font-weight-bold">Número</label>
      @if(isset($lote))
      <p>{{$lote[0]->num_lote}}</p>
      @else
      <input class="form-control" name="numero" value="{{old('numero')}}">
      @endif
      @if($errors->has('numero'))
          <label class="text-danger">{{ $errors->first('numero') }}</label>
      @endif
    </div>
  </div>
  <div class="col-12 col-md-6">
    <div class="form-group">
      <label for="exampleInputPassword1" class="font-weight-bold">Fecha de entrega</label>
      @if(isset($lote))
      <p>{{$lote[0]->fecha_entrega}}</p>
      @else
      <input type="date" class="form-control" name="fecha" value="{{old('fecha')}}">
      @endif
      @if($errors->has('fecha'))
          <label class="text-danger">{{ $errors->first('fecha') }}</label>
      @endif
    </div>
  </div>
  <div class="col-12 col-md-6">
    @if(!isset($lote))
      <div class="form-group">
        <label for="exampleFormControlFile1" class="font-weight-bold">Archivo</label>
          <div style="position:relative;">
            <a class='btn btn-primary' href='javascript:;'>
              Cargar archivo
              <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file" size="40"  onchange='$("#upload-file-info").text(this.files[0].name);'>
            </a>
            &nbsp;
            <span class='label label-info' id="upload-file-info"></span>
            </div>
        @if($errors->has('file'))
            <label class="text-danger">{{ $errors->first('file') }}</label>
        @endif
      </div>
      
      <div class="d-flex justify-content-end py-4">
      <button type="submit" class="btn btn-primary" name="submit">Registrar</button>
      @endif
    </div>

  </div>
  </form>
  @if(isset($archivos) || isset($id_lote))
   <form method="post" action="{{ route('agregar_archivo') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Agregar archivo</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
            <button type="submit" class="btn btn-primary" name="submit">Agregar archivo</button>
        </div> 
  </div>
 
    <div class="form-group">
      <div class="form-group">
          <div style="position:relative;">
      <a class='btn btn-primary' href='javascript:;'>
        Cargar archivo
        <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file" onchange='$("#upload-file-info").text(this.files[0].name);'>
      </a>
      &nbsp;
      <span class='label label-info' id="upload-file-info"></span>
      
    </div>
    @if($errors->has('file'))
        <label class="text-danger">{{ $errors->first('file') }}</label>
      @endif
        
      </div>

      <input type="hidden" name="id_lote" value="{{$id_lote}}">
    </div>
    <!--
    <button type="submit" class="btn btn-primary" name="submit">Agregar archivo</button>
  -->
  </form>
  @endif
  @if(isset($errores) && sizeof($errores) > 0)
  <div class="col-md-12">
      <div class="pb-3 font-weight-bold">El archivo {{$archivo}} contiene las siguientes observaciones:</div>
      <table class="table table-light table-bordered border" style="height: 600px; display:block;overflow-y:scroll">
          <thead class="thead-light">
              <tr class="text-center">
                  <th>Filas vacias</th>
                  <th>Columnas vacias</th>
                  <th>Formato de fechas</th>
                  <th>Campos vacios</th>
                  <th>Nombres de archivos</th>                      
                  
              </tr>
          </thead>
          <tbody>
              <tr>
                <td>
                  <ul>
                  @foreach ($errores['fila_vacia'] as $error)
                  <li>{{$error}}</li>
                  @endforeach
                  </ul>
                </td>
                <td>
                  <ul>
                  @foreach ($errores['columna_vacia_nombre'] as $error)
                  <li>{{$error}}</li>
                  @endforeach
                  </ul>
                </td>
                <td>
                  <ul>
                  @foreach ($errores['fecha'] as $error)
                  <li>{{$error}}</li>
                  @endforeach
                  </ul>
                </td>
                <td>
                  <ul>
                  @foreach ($errores['campo_vacio'] as $error)
                  <li>{{$error}}</li>
                  @endforeach
                  </ul>
                </td>
                <td>
                  <ul>
                  @foreach ($errores['archivo'] as $error)
                  <li>{{$error}}</li>
                  @endforeach
                  </ul>
                </td>
              </tr>
              
          </tbody>
      </table>
  </div>
  @endif
  @if(isset($archivos) && sizeof($archivos) > 0)
      <table class="table table-light table-hover">
          <thead class="thead-light">
              <tr class="text-center">
                  <th>#</th>
                  <th>Nombre</th> 
                  <th>Descargar</th> 
              </tr>
          </thead>
          <tbody>
              @foreach ($archivos as $item)
              <tr class="text-center">
                  <td>{{$loop->iteration}}</td>
                  <td>{{$item->nombre_archivo}}</td>
                  <td><a href="{{url('/')}}/{{$item->ruta_archivo}}" target="_blank" download="{{$item->nombre_archivo}}"><img src="{{asset('images/descargar.png')}}" height="30" width="30" data-toggle="tooltip" data-placement="top" title="Descagar archivo"></a></td>
              </tr>
              @endforeach
          </tbody>
      </table>
  @endif

</div>
@endsection
<script type="text/javascript">
  $('#file-upload').change(function() {
  var i = $(this).prev('label').clone();
  var file = $('#file-upload')[0].files[0].name;
  $(this).prev('label').text(file);
});
</script>