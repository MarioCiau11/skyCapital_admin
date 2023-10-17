<?php

namespace App\Http\Controllers\proc\finanzas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\ImagesController;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\finanzas\CxC;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\utils\conf_plantillas;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Crypt;
use Luecano\NumeroALetras\NumeroALetras;
// use Luecano\NumeroALetras\NumeroALetras;
use PDF;



class reportesFinanzasController extends Controller
{
    function index()
    {

    }
    function repCxC(Request $request)
    {

        try {
            $lastID = Crypt::decrypt($request->cxc);
            $cxc = CxC::find($lastID);

            $imgController = new ImagesController();
            $imgLogo = $imgController->resizeLogo();
            // dd($plantilla);
            $pdf = PDF::loadView('exports.reports.reporteCxC', [
                'lastID' => $lastID,
                'imgLogo' => $imgLogo,
                'cxc' => $cxc
            ])
                ->setPaper(array(0, 0, 450.00, 800.00), 'landscape');
            return $pdf->stream($cxc->movimiento . '-' . $cxc->folioMov . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function reporteTesoreria(Request $request)
    {

        try {
            $id = Crypt::decrypt($request->idMovimiento);
            $tesoreria = Tesoreria::find($id);
            $importeNum = $tesoreria->importeTotal;
            $formatter = new NumeroALetras();
            $importeLetra = $formatter->toMoney($importeNum, 2, 'PESOS', 'CENTAVOS');
            // dd($tesoreria);
            $cuentaDinero = new CAT_CUENTAS_DINERO();
            $cuentaOrigen = $cuentaDinero->where('clave', '=', $tesoreria->cuentaDinero)->first();

            $imgController = new ImagesController();
            $imgLogo = $imgController->resizeLogo();

            // dd($cuentaDestino);
            $pdf = PDF::loadView('exports.reports.reporteTesoreria', [
                'tesoreria' => $tesoreria,
                'id' => $id,
                'imgLogo' => $imgLogo,
                'cuentaOrigen' => $cuentaOrigen,
                'cuentaDestino' => $tesoreria->nombreCuentaDestino,
                'importeLetra' => $importeLetra,
            ])->setPaper(array(0, 0, 450.00, 800.00), 'landscape');
            return $pdf->stream($tesoreria->movimiento . '-' . $tesoreria->folioMov . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

}