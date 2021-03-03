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
}
