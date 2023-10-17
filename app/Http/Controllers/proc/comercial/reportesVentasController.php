<?php

namespace App\Http\Controllers\proc\comercial;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\ImagesController;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\comercial\VentaCorrida;
use App\Models\utils\conf_plantillas;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Crypt;
use PDF;


class reportesVentasController extends Controller
{
    function index()
    {

    }
    function repContrato(Request $request)
    {
        try {
            $lastID = Crypt::decrypt($request->venta);
            $venta = Ventas::find($lastID);
            $ventaEnganche = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Inversión Inicial')->first();
            $ventaMensualidad = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Mensualidad 1')->first();
            $ventaFiniquito = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Finiquito')->first();

            $imgController = new ImagesController();
            $imgLogo = $imgController->resizeLogo();

            // dd($plantilla);
            $pdf = PDF::loadView('exports.reports.reporteContrato', [
                'lastID' => $lastID,
                'venta' => $venta,
                'imgLogo' => $imgLogo,
                'ventaEnganche' => $ventaEnganche,
                'ventaMensualidad' => $ventaMensualidad,
                'ventaFiniquito' => $ventaFiniquito
            ])
                ->setPaper('a4', 'landscape');
            return $pdf->stream('contrato-factura.pdf');
        } catch (\Exception $e) {
            return redirect()->back();
        }

    }

    function repFinanc(Request $request)
    {
        try {
            $lastID = Crypt::decrypt($request->venta);
            $venta = Ventas::find($lastID);
            $ventaEnganche = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Inversión Inicial')->first();
            $ventaMensualidad = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Mensualidad 1')->first();
            $ventaFiniquito = VentaCorrida::where('idVenta', $lastID)->where('mensualidad', 'Finiquito')->first();

            $imgController = new ImagesController();
            $imgLogo = $imgController->resizeLogo();

            $nameProyectImg = $venta->getProyecto->imagenPrincipal;
            if ($nameProyectImg) {
                $idProyecto = $venta->getProyecto->idProyecto;
                $pahtProyectImg = storage_path('app/public/proyectos/' . $idProyecto . '/' . $nameProyectImg);
                if (file_exists($pahtProyectImg)) {
                    $imgProyecto = $imgController->resizeLogoProyect($pahtProyectImg);
                } else {
                    $imgProyecto = null;
                }
            }

            // dd($plantilla);
            $pdf = PDF::loadView('exports.reports.reporteFinanciamiento', [
                'lastID' => $lastID,
                'venta' => $venta,
                'imgLogo' => $imgLogo,
                'imgProyecto' => $imgProyecto,
                'ventaEnganche' => $ventaEnganche,
                'ventaMensualidad' => $ventaMensualidad,
                'ventaFiniquito' => $ventaFiniquito
            ]);
            return $pdf->stream('financiamiento.pdf');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    function repComisiones(Request $request)
    {
        try {
            $lastID = Crypt::decrypt($request->venta);
            $venta = Ventas::find($lastID);

            $imgController = new ImagesController();


            $nameProyectImg = $venta->getProyecto->imagenPrincipal;
            if ($nameProyectImg) {
                $idProyecto = $venta->getProyecto->idProyecto;
                $pahtProyectImg = storage_path('app/public/proyectos/' . $idProyecto . '/' . $nameProyectImg);
                if (file_exists($pahtProyectImg)) {
                    $imgProyecto = $imgController->resizeLogoProyect($pahtProyectImg);
                } else {
                    $imgProyecto = null;
                }
            }
            $pdf = PDF::loadView('exports.reports.reporteComisiones', [
                'lastID' => $lastID,
                'imgProyecto' => $imgProyecto,
                'venta' => $venta
            ]);
            return $pdf->stream('comisiones.pdf');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}