<?php

namespace App\Http\Controllers\proc\comercial;

use App\Http\Controllers\Controller;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\FilesystemAdapter;
use App\Models\utils\PROC_FILES_ARCHIVOS;
use App\Models\proc\comercial\Ventas;
use Illuminate\Support\Facades\Crypt;

class AnexosVentasController extends Controller
{

    public function index(Request $request)
    {
        try{
            $files = [];
            $lastID = Crypt::decrypt($request->venta);
            // dd($lastID);
    
            $ventas = Ventas::find($lastID);
            $paypal = $ventas->getPayPal;
            // dd($paypal);
            $docsVentas = new PROC_FILES_ARCHIVOS();
            $files = $docsVentas->getTipo('Ventas', $lastID);
    
            // dd($lastID);
    
            return view('exports.anexos.anexoVentas', ['lastID' => $lastID, 'docsVentas' => $files, 'ventas' => $ventas, 'paypal' => $paypal]);
        }catch(\Exception $e){
            return redirect()->back();
        }
       
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $lastID = $request->id;
        $fileDocs = $request->file('file');
        // dd($lastID);
        if ($fileDocs != null) {
            $this->subirDocAnexo($fileDocs, $lastID);
        } else {
            return redirect()->route('proc.venta.viewAnexo', ['venta' => $lastID]);
        }
        return redirect()->route('proc.venta.viewAnexo', ['venta' => $lastID]);
    }

    public function subirDocAnexo($file, $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        $fileDocs = $file; //Recupera el archivo
        $lastID = $id; //Recupera el ultimo id de la tabla
        // dd($fileDocs, $lastID);
        foreach ($fileDocs as $docf => $doc) {
            $docs = new PROC_FILES_ARCHIVOS();
            $docs->idProceso = $lastID;
            $docs->proceso = 'Ventas';
            if ($paramGen->docsMovimientos != null) {
                $docs->path = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/',  $paramGen->docsMovimientos . '/' . $fileDocs[$docf]->getClientOriginalName());
            } else {
                $docs->path = str_replace(['//', '///', '////'], '/', 'ventas/' . $lastID . '-' . $fileDocs[$docf]->getClientOriginalName());
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'ventas/' . $lastID . '/' . $fileDocs[$docf]->getClientOriginalName());
            }

            $docs->file = $fileDocs[$docf]->getClientOriginalName();

            Storage::disk('public')->put($rutaFinal, File::get($fileDocs[$docf]));
            $docs->save();
        }
    }
}