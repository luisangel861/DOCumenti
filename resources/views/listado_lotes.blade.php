
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12" style="padding-right:20px; padding-bottom:15px;"><a href="{{ route('mostrar_registro') }}" class="btn btn-primary">Registrar Lote</a></div>    
    <div class="col-md-12">
        <table class="table table-light">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Numero lote</th>
                    <th>Nombre lote</th>
                    <th>Fecha Entrega</th>
                    <th># Documentos Guardado</th>
                    {{-- <th>Editar</th>
                    <th>Eliminar</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($Lotes as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->num_lote}}</td>
                    <td>{{$item->nombre}}</td>
                    <td>{{$item->fecha_entrega}}</td>
                    <td></td>
                    {{-- <td>
                    <a> href="{{ url('mostrar_registro'.$item->id.'/edit')}}"</a>
                    </td>
                    <td>Eliminar</td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
@endsection
