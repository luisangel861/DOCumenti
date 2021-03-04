
@extends('layouts.app')

@section('content')
<div class="container">
  <form method="post" action="{{ route('registrar_lote') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
    <div class="form-group">
      <label for="exampleInputEmail1">Nombre del lote</label>
      <input type="text" class="form-control" name="nombre">
      @if($errors->has('nombre'))
          <label class="text-danger">{{ $errors->first('nombre') }}</label>
      @endif
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Número de lote</label>
      <input class="form-control" name="numero">
      @if($errors->has('numero'))
          <label class="text-danger">{{ $errors->first('numero') }}</label>
      @endif
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Fecha de entrega</label>
      <input type="date" class="form-control" name="fecha">
      @if($errors->has('fecha'))
          <label class="text-danger">{{ $errors->first('fecha') }}</label>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
  </form>
  <form method="post" action="{{ route('validar_archivo') }}" enctype="multipart/form-data" accept-charset="UTF-8">
  {{csrf_field() }}
  <h3 class="mt-4">Archivos</h3>
    <div class="form-group">
      <label for="exampleFormControlFile1">Archivo</label>
      <input type="file" class="form-control-file" name="archivo">
      @if($errors->has('archivo'))
          <label class="text-danger">{{ $errors->first('archivo') }}</label>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Validar</button>
  </form>
</div>
@endsection