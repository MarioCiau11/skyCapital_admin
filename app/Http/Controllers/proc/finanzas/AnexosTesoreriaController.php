<?php

namespace App\Http\Controllers\proc\finanzas;

use App\Http\Controllers\Controller;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\utils\PROC_FILES_ARCHIVOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AnexosTesoreriaController extends Controller
{
    public function index(Request $request) {
        try{
        $files = [];
        $lastId = Crypt::decrypt($request->tesoreria);

        $tesoreria = Tesoreria::find($lastId);
        $docsTes = new PROC_FILES_ARCHIVOS();
        $files = $docsTes->getTipo('Tesoreria', $lastId);
        return view('exports.anexos.anexoTesoreria', [
            'lastId' => $lastId, 
            'docsTes' => $files, 
            'tesoreria' => $tesoreria
        ]);
    }catch(\Exception $e){
        return redirect()->back();
    }
    }
    public function store(Request $request){
        $lastID = $request->id;
        $fileDocs=$request->file('file');
        // dd($lastID);
        if ($fileDocs != null) {
            $this->subirDocAnexo($fileDocs, $lastID);
        }
        else {
            return redirect()->route('proc.tesoreria.viewAnexo', ['tesoreria' => $lastID]);
        }
        return redirect()->route('proc.tesoreria.viewAnexo', ['tesoreria' => $lastID]);
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
            $docs->proceso = 'Tesoreria';
            if ($paramGen->docsMovimientos != null) {
                $docs->path = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '/' . $fileDocs[$docf]->getClientOriginalName());
            } else {
                $docs->path = str_replace(['//', '///', '////'], '/', 'tesoreria/' . $lastID . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'tesoreria/' . $lastID . '/' . $fileDocs[$docf]->getClientOriginalName());
            }
            // $docs->path= str_replace(['//', '///', '////'], '/', 'tesoreria/'.$lastID.'-'.$fileDocs[$docf]->getClientOriginalName());
            $docs->file = $fileDocs[$docf]->getClientOriginalName();
            // $rutaFinal = str_replace(['//', '///', '////'], '/', 'tesoreria/' .$lastID.'/' .$fileDocs[$docf]->getClientOriginalName());
            Storage::disk('public')->put($rutaFinal, File::get($fileDocs[$docf]));
            $docs->save();
        }
    }
}
