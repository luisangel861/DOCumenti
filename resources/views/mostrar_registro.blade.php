
@extends('layouts.app')

@section('content')
<div class="container">
    <form>
        <div class="form-group">
          <label for="nombrelote">Nombre del lote</label>
          <input type="text" class="form-control" id="nombrelote" aria-describedby="emailHelp">
        </div>
        <div class="form-group">
          <label for="numerolote">Número de lote</label>
          <input type="text" class="form-control" id="numerolote">
        </div>
        <div class="form-group">
          <label for="fechaentrega">Fecha de entrega</label>
          <input type="date" class="form-control" id="fechaentrega">
        </div>
        <div class="form-group">
          <label for="campoarchivo">Archivo</label>
          <input type="file" class="form-control-file" id="campoarchivo" name="archivo[]">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
      </form>
  </div>
@endsection
