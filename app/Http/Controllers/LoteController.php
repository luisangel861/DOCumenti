<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lote;

class LoteController extends Controller
{
    public function mostrar_lotes(){

        $Lotes = Lote::all();
        
    	return view('listado_lotes',['Lotes'=>$Lotes]);
    }
    public function mostrar_registro(){
    	return view('mostrar_registro');

    }
    public function registrar_lote(Request $request){
        //ALTER TABLE lotes ADD id_usuario int after fecha_entrega;
        if($request['estatus'] == 0){

        $validaciones =[ 
            'nombre' => 'required|max:255',
            'numero'=>'required',
            'fecha' => 'required',
            'archivo' => 'required|mimes:xlsx,csv'
        ];
        $mensajes =[
            'nombre.required' => 'Campo obligatorio',
            'nombre.max' => 'Número de carácteres permitidos: 255',
            'numero.required' => 'Campo obligatorio',
            'fecha.required' => 'Campo obligatorio',
            'archivo.required' => 'Campo obligatorio',
            'archivo.mimes'=>'Formato permitido: .cvs y .xlsx'
        ];

        $datos = request()->validate($validaciones,$mensajes);
        
    }
    $lote = [];
    $lote['nombre'] = $request['nombre'];
    $lote['num_lote'] = $request['numero'];
    $lote['fecha_entrega'] = $request['fecha'];
    $lote['id_usuario'] = Auth()->user()->id;
    Lote::create($lote);
    $Lotes = Lote::all();
        return view('listado_lotes',['Lotes'=>$Lotes]);
    }
    
}
