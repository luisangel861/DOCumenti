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
}
