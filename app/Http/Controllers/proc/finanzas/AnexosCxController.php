<?php

namespace App\Http\Controllers\proc\finanzas;

use App\Http\Controllers\Controller;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\FilesystemAdapter;
use App\Models\utils\PROC_FILES_ARCHIVOS;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\finanzas\CxC;
use Illuminate\Support\Facades\Crypt;

class AnexosCxController extends Controller
{
    
    public function index(Request $request)
    {
        try{
        $files = [];
        $lastID = Crypt::decrypt($request->cxc);
        // dd($lastID);

        $cxc = CxC::find($lastID);
        $docsCxC = new PROC_FILES_ARCHIVOS();
        $files = $docsCxC->getTipo('CxC', $lastID);

        // dd($lastID);
        
        return view('exports.anexos.anexoCxC',['lastID' => $lastID, 'docsCxC' => $files, 'cxc' => $cxc]);	
    }catch(\Exception $e){
        return redirect()->back();
    }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $lastID = $request->id;
        $fileDocs=$request->file('file');
        // dd($lastID);
        if ($fileDocs != null) {
            $this->subirDocAnexo($fileDocs, $lastID);
        }
        else {
            return redirect()->route('proc.cxc.viewAnexo', ['cxc' => $lastID]);
        }
        return redirect()->route('proc.cxc.viewAnexo', ['cxc' => $lastID]);
    }

    public function subirDocAnexo ($file, $id) {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        $fileDocs = $file; //Recupera el archivo
        $lastID = $id; //Recupera el ultimo id de la tabla
        // dd($fileDocs, $lastID);
        foreach($fileDocs as $docf => $doc) {
            $docs = new PROC_FILES_ARCHIVOS();
            $docs->idProceso = $lastID;
            $docs->proceso = 'CxC';
            if ($paramGen->docsMovimientos != null) {
                $docs->path = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '/' . $fileDocs[$docf]->getClientOriginalName());
            } else {
                $docs->path = str_replace(['//', '///', '////'], '/', 'cxc/' . $lastID . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'cxc/' . $lastID . '/' . $fileDocs[$docf]->getClientOriginalName());
            }
            // $docs->path= str_replace(['//', '///', '////'], '/', 'cxc/'.$lastID.'-'.$fileDocs[$docf]->getClientOriginalName());
            $docs->file = $fileDocs[$docf]->getClientOriginalName();
            // $rutaFinal = str_replace(['//', '///', '////'], '/', 'cxc/' .$lastID.'/' .$fileDocs[$docf]->getClientOriginalName());
            Storage::disk('public')->put($rutaFinal, File::get($fileDocs[$docf]));
            $docs->save();
        }
    }
}
