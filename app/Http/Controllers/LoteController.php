<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\LoteArchivo;
  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
            'file' => 'required',
        ];
        $mensajes =[
            'nombre.required' => 'Campo obligatorio',
            'nombre.max' => 'Número de carácteres permitidos: 255',
            'numero.required' => 'Campo obligatorio',
            'fecha.required' => 'Campo obligatorio',
            'file.required' => 'Campo obligatorio'
        ];

        $datos = request()->validate($validaciones,$mensajes);
        
    }

    $lote = [];
    $lote['nombre'] = $request['nombre'];
    $lote['num_lote'] = $request['numero'];
    $lote['fecha_entrega'] = $request['fecha'];
    $lote['id_usuario'] = Auth()->user()->id;
    $errors = array();
    if (isset($_POST['submit'])) {
         
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     
            if(isset($_FILES['file']['name'])) {
             
                $arr_file = explode('.', $_FILES['file']['name']);
                $extension = end($arr_file);

             
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                if (!empty($sheetData)) {

                    for ($i=1; $i<count($sheetData); $i++) {

                        $numero_registro = $sheetData[$i][0];
                        $fecha_registro = $sheetData[$i][9];
                        $nombre_archivo_digital = $sheetData[$i][5];
                        $nombre_expediente = $sheetData[$i][1];
                        $entidad_jlca = $sheetData[$i][2];
                        $nombre_jlca = $sheetData[$i][3];
                        $tipo_documental= $sheetData[$i][4];
                        $id = $sheetData[$i][6];
                        $documentacion_complementaria = $sheetData[$i][7];
                        $nombre_patron = $sheetData[$i][8];
                        $fecha_ultima_modificacion = $sheetData[$i][10];
                        $nombre_funcionario_certifica = $sheetData[$i][11];
                        $numero_hojas = $sheetData[$i][12];
                        $descripcion = $sheetData[$i][13];
                        $observaciones = $sheetData[$i][14];
                        $registro_sindical = $sheetData[$i][15];
                        $responsable_digitalizacion_1= $sheetData[$i][16];
                        $responsable_digitalizacion_2= $sheetData[$i][17];
                        $fecha_digitalizacion = $sheetData[$i][18];
                        $derechos = $sheetData[$i][19];
                        $formato_digital = $sheetData[$i][20];
                        $numero_paginas = $sheetData[$i][21];
                        $usuarios = $sheetData[$i][22];
                        $hash = $sheetData[$i][23];
                        $estatus = $sheetData[$i][24];
                        $nombre_quien_publica = $sheetData[$i][25];
                        if($numero_registro == NULL && $fecha_registro == NULL && $nombre_archivo_digital== NULL && $nombre_expediente== NULL &&  $entidad_jlca== NULL && $nombre_jlca== NULL  && $tipo_documental== NULL  && $id== NULL && $documentacion_complementaria== NULL && $nombre_patron== NULL && $fecha_ultima_modificacion== NULL && $nombre_funcionario_certifica== NULL && $numero_hojas== NULL && $descripcion== NULL && $observaciones == NULL && $registro_sindical== NULL && $responsable_digitalizacion_1== NULL && $responsable_digitalizacion_2== NULL && $fecha_digitalizacion== NULL && $derechos== NULL && $formato_digital== NULL && $numero_paginas== NULL && $usuarios== NULL && $hash== NULL && $estatus== NULL && $$nombre_quien_publica== NULL){
                            $errors[] = array("Fila vacia: La Fila ".($i+1)." esta vacia");
                        }else{
                        //echo $fecha_registro;
                        
                        if($fecha_registro != NULL){
                            $correcto = $this->validar_fecha($fecha_registro);

                            if($correcto == false){

                            $errors[] = array("Fecha registro: Error en la Fila ".($i+1)." fecha con formato incorrecto. ". $fecha_registro);
                            }
                        }
                        else{
                            $errors[] = array("Fecha registro: Error en la Fila ".($i+1)." Fecha con campo vacio. ".$fecha_registro);
                        }

                        if($fecha_digitalizacion != NULL){
                            $correcto = $this->validar_fecha($fecha_digitalizacion);

                            if($correcto == false){

                            $errors[] = array("Fecha digitalización: Error en la Fila ".($i+1)." fecha con formato incorrecto. ". $fecha_digitalizacion);
                            }
                        }
                        else{
                            $errors[] = array("Fecha digitalización: Error en la Fila ".($i+1)." Fecha con campo vacio. ".$fecha_digitalizacion);
                        }

                        if($fecha_ultima_modificacion != NULL){
                            $correcto = $this->validar_fecha($fecha_ultima_modificacion);

                            if($correcto == false){

                            $errors[] = array("Fecha última modificación: Error en la Fila ".($i+1)." fecha con formato incorrecto. ". $fecha_ultima_modificacion);
                            }
                        }
                        else{
                            $errors[] = array("Fecha última modificación: Error en la Fila ".($i+1)." Fecha con campo vacio. ".$fecha_ultima_modificacion);
                        }
                        
                        
                        if($nombre_archivo_digital != NULL){
                            $correcto = $this->validar_nombre_archivo($nombre_archivo_digital);
                            if($correcto == false){

                                $errors[] = array("Nombre del archivo: Error en la Fila ".($i+1)." con carácteres especiales o espacios en blanco. ".$nombre_archivo_digital);
                            }
                        }
                        else{
                            $errors[] = array("Nombre del archivo:: Error en la Fila ".($i+1)." con campo vacio. ".$nombre_archivo_digital);
                        }
                    }
                        
                        
                    }
                }
              
            }
        
        }
    
    if(sizeof($errors) > 0){
        return view('mostrar_registro',['errores'=>$errors]);
    }else{
        $id_lote = Lote::create($lote)->id;
        $logoImage = $request->file('file');
        $name = $logoImage->getClientOriginalName();
        $documento['id_lote'] = $id_lote;
        $documento['nombre_archivo'] = $name;
        $documento['ruta_archivo']= str_replace('public', 'storage',$request->file('file')->store('public'));
        LoteArchivo::create($documento);

        $archivos = LoteArchivo::where('id_lote',$id_lote)
                                ->get();

        $lotes = Lote::where('id',$id_lote)
                    ->get();
        return view('mostrar_registro',['lote'=>$lotes,'archivos'=>$archivos,'id_lote'=>$id_lote]);


    }

    }
    public function agregar_archivo(Request $request){
        if($request['estatus'] == 0){
            $validaciones =[ 
                //'archivo' => 'required|mimes:xlsx,csv'
            ];
            $mensajes =[
                //'archivo.required' => 'Campo obligatorio',
                //'archivo.mimes'=>'Formato permitido: .csv y .xlsx'
            ];
        }
        //$datos = request()->validate($validaciones,$mensajes);
        if (isset($_POST['submit'])) {
         
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     
            if(isset($_FILES['file']['name'])) {
             
                $arr_file = explode('.', $_FILES['file']['name']);
                $extension = end($arr_file);

             
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $errors = array();
         
                if (!empty($sheetData)) {
                    for ($i=1; $i<count($sheetData); $i++) {
                        $fecha_registro = $sheetData[$i][9];
                        $nombre_archivo = $sheetData[$i][5];
                        //echo $fecha_registro;
                        
                        if($fecha_registro != NULL){
                            $correcto = $this->validar_fecha($fecha_registro);

                            if($correcto == false){

                            $errors[] = array('error_fecha_registro' => "Error en la Fila ".($i+1)." Fecha con formato incorrecto. ". $fecha_registro);
                            }
                        }
                             else{
                                $errors[] = array('error_fecha_registro' => "Error en la Fila ".($i+1)." Fecha con campo vacio. ".$fecha_registro);
                            }
                        
                        
                        if($nombre_archivo != NULL){
                            $correcto = $this->validar_nombre_archivo($nombre_archivo);
                            if($correcto == false){

                                $errors[] = array('error_nombre_archivo' => "Error en la Fila ".($i+1)." Nombre del archivo digital con carácteres especiales o espacios en blanco. ".$nombre_archivo);
                            }
                        }
                        else{
                            $errors[] = array('error_nombre_archivo' => "Error en la Fila ".($i+1)." Nombre del archivo digital con campo vacio. ".$nombre_archivo);
                        }
                        
                        
                    }
                }
              
            }
        
        }
        if(sizeof($errors) > 0){
        return view('mostrar_registro',['errores'=>$errors]);

        } else{
        $logoImage = $request->file('file');
        $name = $logoImage->getClientOriginalName();
        $documento['id_lote'] = $request['id_lote'];
        $documento['nombre_archivo'] = $name;
        $documento['ruta_archivo']= str_replace('public', 'storage',$request->file('file')->store('public'));
        LoteArchivo::create($documento);

        $archivos = LoteArchivo::where('id_lote',$request['id_lote'])
                                ->get();

        $lotes = Lote::where('id',$request['id_lote'])
                    ->get();
        return view('mostrar_registro',['lote'=>$lotes,'archivos'=>$archivos,'id_lote'=>$request['id_lote']]);
    }


   
}
    

    function validar_fecha($fecha){
        $patron = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
        $validacion = preg_match($patron, $fecha);
        if ($validacion == 1) {
        $valores = explode('-', $fecha);
            if(count($valores) == 3 && checkdate( $valores[1], $valores[2], $valores[0])){
                return true;
            }
            else{
                return false;
            }
        }else{
             return false;
        }        
    }
    function validar_nombre_archivo($nombre_archivo){
        
        $patron = '/^[a-zA-Z0-9.\-_]+$/';
        $validacion = preg_match($patron, $nombre_archivo);
        if ($validacion == 1) {
            return true;
        } else {
            return false;
        }

    }
    public function ver_lote(){
        $id_lote = $_GET["id_lote"];
        echo "ver lote";
    }
}


