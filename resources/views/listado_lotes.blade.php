
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Lote</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
         
            <a href="{{ route('mostrar_registro') }}" class="btn btn-primary">Registrar</a>
        </div>
      </div>
        
    <div class="col-md-12">
        <table class="table table-light table-hover table-bordered ">
            <thead class="thead-light text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Número</th>
                    <th>Fecha de entrega</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Lotes as $item)
                <tr class="text-center">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->nombre}}</td>
                    <td>{{$item->num_lote}}</td>
                    <td>{{$item->fecha_entrega}}</td>
                    <td><a href="{{url('/')}}/ver_lote?id_lote={{$item->id}}" data-toggle="tooltip" data-placement="top" title="Ver o editar"><img src="{{asset('images/ver.png')}}"></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
@endsection
