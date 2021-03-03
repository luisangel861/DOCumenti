<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function mostrar_lotes(){
    	return view('listado_lotes');
    }
    public function mostrar_registro(){
    	return view('mostrar_registro');

    }
    public function registrar_lote(Request $request){

    	if($request['estatus'] == 0){

        $validaciones =[ 
            'nombre' => 'required|max:255',
            'numero'=>'required',
            'fecha' => 'required',
            'archivo' => 'required|file|mimes:pdf'
        ];
        $mensajes =[
            'nombre.required' => 'Campo obligatorio',
            'numero.required' => 'Campo obligatorio',
            'fecha.required' => 'Campo obligatorio',
            'archivo.required' => 'Campo obligatorio',
            'archivo.mimes'=>'Formato permitido: cvs y xls.',
        ];

        $datos = request()->validate($validaciones,$mensajes);
       

    }

    	return view('listado_lotes');
    }
}
