<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\utils\CAT_FILES_ARCHIVOS;
use App\Models\utils\PROC_FILES_ARCHIVOS;
use Illuminate\Http\Request;



class FilesController extends Controller
{
    public function eliminarDoc(Request $request)
    {
        $idDoc = $request->idDoc;
        $delete = CAT_FILES_ARCHIVOS::WHERE('idFile', '=', $idDoc)->delete();

        if ($delete) {
            return response()->json(['status' => $delete, 'mensaje' => 'El documento se ha eliminado de la base de datos'], 200);
        } else {
            return response()->json(['status' => $delete, 'mensaje' => 'El documento no se ha podido eliminado de la base de datos'], 404);
        }
    }

    public function descargarDoc($file)
    {
        $file = CAT_FILES_ARCHIVOS::find($file);
        $path = $file->path;
        $patch = explode('-', $path)[0];
        $name = $file->file;
        // dd($patch);
        $ruta = storage_path('app/public/' . $patch.'/'.$name);
        // dd($ruta);
        if (!file_exists($ruta)) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        return response()->download($ruta, $name);
    }

    public function descargarDocPortal($file)
    {
        $archivo = CAT_FILES_ARCHIVOS::find($file);
        // dd($archivo);
        $path = $archivo->path;
        $patch = explode('-', $path)[0];
        $name = $archivo->file;
        if (!$archivo) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        
        $ruta = storage_path('app/public/'  . $patch.'/'.$name);
        
        if (!file_exists($ruta)) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        
        return response()->download($ruta, $archivo->file);
    }
    public function descargarAnexosPortal($file)
    {
        $archivo = PROC_FILES_ARCHIVOS::find($file);
        // dd($archivo);
        $path = $archivo->path;
        $patch = explode('-', $path)[0];
        $name = $archivo->file;
        if (!$archivo) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        
        $ruta = storage_path('app/public/'  . $patch.'/'.$name);
        
        if (!file_exists($ruta)) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        
        return response()->download($ruta, $archivo->file);
    }


    public function eliminarAnexo(Request $request)
    {
        $idDoc = $request->idDoc;
        $delete = PROC_FILES_ARCHIVOS::WHERE('idFile', '=', $idDoc)->delete();
        // dd($delete);
        if ($delete) {
            return response()->json(['status' => $delete, 'mensaje' => 'El documento se ha eliminado de la base de datos'], 200);
        } else {
            return response()->json(['status' => $delete, 'mensaje' => 'El documento no se ha podido eliminar de la base de datos'], 404);
        }
    }

    public function descargarAnexo($file)
    {
        $file = PROC_FILES_ARCHIVOS::find($file);
        $path = $file->path;
        $patch = explode('-', $path)[0];
        $name = $file->file;
        // dd($patch);
        $ruta = storage_path('app/public/' . $patch.'/'.$name);
        if (!file_exists($ruta)) {
            return response()->json(['status' => false, 'mensaje' => 'El archivo no existe'], 404);
        }
        return response()->download($ruta, $name);
    }
}
