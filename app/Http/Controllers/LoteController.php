<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\LoteArchivo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoteController extends Controller
{
    public function mostrar_lotes(){

        $Lotes = Lote::where('id_usuario',Auth()->user()->id)
            ->get();
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
            $errores['fila_vacia'] = [];
            $errores['columna_vacia'] = [];
            $errores['columna_vacia_nombre'] = [];
            $errores['fecha'] = [];
            $errores['campo_vacio'] = [];
            $errores['archivo'] = [];

     
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
                    $titulos = ['Número de registro','Nombre expediente','Entidad JLCA','Nombre de JLCA','Tipo documental','Nombre del archivo digital','ID','Documentación complementaria','Nombre patrón','Fecha registro','Fecha de última modificación','Nombre del funcionario que certifica','Número de hojas','Descripción','Observaciones','Registro sindical', 'Responsable digitalización 1','Responsable digitalización 2','Fecha digitalización','Derechos','Formato digital','Número de páginas PDF','Usuarios','Hash','Estatus','Nombre de quien publica'];
                    
                    for ($i=1; $i<count($sheetData); $i++) {
                        //0 -> vacia 1->llena
                        $fila_vacia = 0;
                        for($j=0; $j <= 25; $j++){
                            $fila = $i+1;
                            if($sheetData[$i][$j] != NULL){
                                $fila_vacia = 1;
                            }
                        }
                        if($fila_vacia == 0){
                            array_push($errores['fila_vacia'],($fila));
                            
                        }
                    } 
                    for ($j=0; $j <=25; $j++) {
                        //0 -> vacia 1->llena
                       
                        $columna_vacia = 0;
                        for($i=1; $i<count($sheetData); $i++){
                            $columna = $j;
                            if($sheetData[$i][$j] != NULL){
                                $columna_vacia = 1;
                            }
                        }
                        if($columna_vacia == 0){
                            array_push($errores['columna_vacia'],($j+1));
                            array_push($errores['columna_vacia_nombre'],$titulos[$j]);
                            
                        }
                    }
                    
                    for ($i=1; $i<count($sheetData); $i++) {
                        for($j=0; $j <= 25; $j++){
                            if((!in_array($i+1,$errores['fila_vacia'])) && (!in_array($j+1,$errores['columna_vacia']))) {
                                
                                if($j == 9 || $j==10 || $j==18){
                                    if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }else{
                                    $fila = $i+1;
                                    $correcto = $this->validar_fecha($sheetData[$i][$j]);

                                    if($correcto == false){
                                    array_push($errores['fecha'],"Fecha sin el formato correcto en la fila ".$fila." columna ". $titulos[$j]." ".$sheetData[$i][$j]);
                                    }
                                }
                                }
                                if($j == 5 ){
                                    if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }else{
                                    $fila = $i+1;
                                    $correcto = $this->validar_nombre_archivo($sheetData[$i][$j]);

                                    if($correcto == false){
                                    array_push($errores['archivo'],"El nombre del archivo digital contiene carácteres especiales en la fila ".$fila);
                                    }
                                }
                                }
                                if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }
                            }
                            

                            }                       
                        }
                        
                    }    
                }     
                    
            }
    if(sizeof($errores['campo_vacio']) > 0 ||sizeof($errores['fila_vacia']) > 0 ||sizeof($errores['columna_vacia']) > 0 || sizeof($errores['fecha']) > 0 ||  sizeof($errores['archivo']) > 0){
        $logoImage = $request->file('file');
        $name = $logoImage->getClientOriginalName();
        return view('mostrar_registro',['errores'=>$errores,'archivo'=>$name]);
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
                'file' => 'required'
            ];
            $mensajes =[
                'file.required' => 'Campo obligatorio',
                //'file.mimes'=>'Formato permitido: .csv y .xlsx'
            ];
        }
        $datos = request()->validate($validaciones,$mensajes);
            
        if (isset($_POST['submit'])) {
         
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $errores['fila_vacia'] = [];
            $errores['columna_vacia'] = [];
            $errores['columna_vacia_nombre'] = [];
            $errores['fecha'] = [];
            $errores['campo_vacio'] = [];
            $errores['archivo'] = [];

     
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
                    $titulos = ['Número de registro','Nombre expediente','Entidad JLCA','Nombre de JLCA','Tipo documental','Nombre del archivo digital','ID','Documentación complementaria','Nombre patrón','Fecha registro','Fecha de última modificación','Nombre del funcionario que certifica','Número de hojas','Descripción','Observaciones','Registro sindical', 'Responsable digitalización 1','Responsable digitalización 2','Fecha digitalización','Derechos','Formato digital','Número de páginas PDF','Usuarios','Hash','Estatus','Nombre de quien publica'];
                    
                    for ($i=1; $i<count($sheetData); $i++) {
                        //0 -> vacia 1->llena
                        $fila_vacia = 0;
                        for($j=0; $j <= 25; $j++){
                            $fila = $i+1;
                            if($sheetData[$i][$j] != NULL){
                                $fila_vacia = 1;
                            }
                        }
                        if($fila_vacia == 0){
                            array_push($errores['fila_vacia'],($fila));
                            
                        }
                    } 
                    for ($j=0; $j <=25; $j++) {
                        //0 -> vacia 1->llena
                       
                        $columna_vacia = 0;
                        for($i=1; $i<count($sheetData); $i++){
                            $columna = $j;
                            if($sheetData[$i][$j] != NULL){
                                $columna_vacia = 1;
                            }
                        }
                        if($columna_vacia == 0){
                            array_push($errores['columna_vacia'],($j+1));
                            array_push($errores['columna_vacia_nombre'],$titulos[$j]);
                            
                        }
                    }
                    
                    for ($i=1; $i<count($sheetData); $i++) {
                        for($j=0; $j <= 25; $j++){
                            if((!in_array($i+1,$errores['fila_vacia'])) && (!in_array($j+1,$errores['columna_vacia']))) {
                                
                                if($j == 9 || $j==10 || $j==18){
                                    if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }else{
                                    $fila = $i+1;
                                    $correcto = $this->validar_fecha($sheetData[$i][$j]);

                                    if($correcto == false){
                                    array_push($errores['fecha'],"Fecha sin el formato correcto en la fila ".$fila." columna ". $titulos[$j]." ".$sheetData[$i][$j]);
                                    }
                                }
                                }
                                if($j == 5 ){
                                    if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }else{
                                    $fila = $i+1;
                                    $correcto = $this->validar_nombre_archivo($sheetData[$i][$j]);

                                    if($correcto == false){
                                    array_push($errores['archivo'],"El nombre del archivo digital contiene carácteres especiales en la fila ".$fila);
                                    }
                                }
                                }
                                if($sheetData[$i][$j] == NULL){
                                    $fila = $i+1;
                                    array_push($errores['campo_vacio'],"El campo esta vacio en la fila ".$fila." y columna ".$titulos[$j]);
                                    }
                            }
                            

                            }                       
                        }
                        
                    }  
              
            }
        
        
        if(sizeof($errores['campo_vacio']) > 0 ||sizeof($errores['fila_vacia']) > 0 ||sizeof($errores['columna_vacia']) > 0 || sizeof($errores['fecha']) > 0 ||  sizeof($errores['archivo']) > 0){
        $archivos = LoteArchivo::where('id_lote',$request['id_lote'])
                                ->get();

        $lotes = Lote::where('id',$request['id_lote'])
                    ->get();
        $logoImage = $request->file('file');
        $name = $logoImage->getClientOriginalName();

        return view('mostrar_registro',['errores'=>$errores,'archivos'=>$archivos,'id_lote'=>$request['id_lote'],'lote'=>$lotes,'archivo'=>$name]);

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
}
    
    function ver_lote(){
        $id_lote = $_GET["id_lote"];
        $archivos = LoteArchivo::where('id_lote',$id_lote)
                                ->get();

        $lotes = Lote::where('id',$id_lote)
                    ->get();
        return view('mostrar_registro',['lote'=>$lotes,'archivos'=>$archivos,'id_lote'=>$id_lote]);
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
    public function registrar_usuario(){
        $usuarios = user::all();
        //echo "<pre>";
        return view('crear_usuario',['usuarios'=>$usuarios]);
        
    }
    public function crear_usuario(Request $request){
    if($request['estatus'] == 0){

        $validaciones =[ 
            'nombre' => 'required',
            'apellido_paterno'=>'required',
            'apellido_materno' => 'required',
            'email' => 'required',
            'password' => 'required',

        ];
        $mensajes =[
            'nombre.required' => 'Campo obligatorio',
            'apellido_paterno.required' => 'Campo obligatorio',
            'apellido_materno.required' => 'Campo obligatorio',
            'email.required' => 'Campo obligatorio',
            'password.required' => 'Campo obligatorio'
        ];

        $datos = request()->validate($validaciones,$mensajes);
        
    }
    $usuario = [];
    $usuario['nombre'] = $request['nombre'];
    $usuario['apellido_paterno'] = $request['apellido_paterno'];
    $usuario['apellido_materno'] = $request['apellido_materno'];
    $usuario['email'] = $request['email'];
    $usuario['email_verified_at'] = '2021-03-05';
    $usuario['password'] = Hash::make($request['password']);
    

    user::create($usuario);
    $usuarios = user::all();
    return view('crear_usuario',['usuarios'=>$usuarios]);

    }
    
}
