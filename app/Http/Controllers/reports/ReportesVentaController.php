<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;

use App\Models\proc\comercial\VentaDetalle;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use App\Exports\ReporteVentasExport;
use App\Http\Controllers\helpers\ImagesController;
use App\Models\utils\PROC_FLUJO;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\reports\ReportesVenta;
use App\Models\User;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportesVentaController extends Controller
{
    public $venta, $status, $mensaje;
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'CONCLUIDO',
        3 => 'CANCELADO',
    ];

    public function __construct(Ventas $ventas)
    {
        $this->venta = $ventas;
    }
    public function infoMod()
    {
        $proyectos = new CAT_PROYECTOS();
        $monedas = new CONF_MONEDA();

        // Obtener las ventas
        $ventas = $this->venta->where('idEmpresa', '=', session('company')->idEmpresa)
        ->where('movimiento', '=', 'Contrato')
        ->where('estatus', '=', 'CONCLUIDO')
        ->whereVentasProyecto('Todos')
        ->whereVentasFecha('Mes')
        ->whereVentasMoneda(1)
        ->get();

        return view('page.reports.ventas.reportInfoMod', [
            'articulos' => $ventas,
            'proyectos' => $proyectos->getProjects()->toArray(),
            'monedas' => $monedas->getMonedas(),
        ]);
    }

    public function ventaMod()
    {
        $proyectos = new CAT_PROYECTOS();
        $monedas = new CONF_MONEDA();

        // Obtener las ventas y el detalle de cada una
       $ventas = $this->venta->where('idEmpresa', '=', session('company')->idEmpresa)
        ->where('movimiento', '=', 'Contrato')
        ->where('estatus', '=', 'CONCLUIDO')
        ->whereVentasProyecto('Todos')
        ->whereVentasFecha('Mes')
        ->whereVentasMoneda(1)
        ->get();
        return view('page.reports.ventas.reportVentaMod', [
            'articulos' => $ventas,
            'proyectos' => $proyectos->getProjects()->toArray(),
            'monedas' => $monedas->getMonedas(),
        ]);
    }
    public function reportesAction(Request $request)
    {
        // Obtener los datos del request por select o input
        $data = $request->all();
        $proyecto = $data['selectProyecto'] == 'Todos' ? $data['selectProyecto'] : $data['selectProyecto'];
        $fecha = $data['selectFecha'] == 'Todos' ? $data['selectFecha'] : $data['selectFecha'];
        $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
        $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];
        $moneda = $data['selectMoneda'] == 'Todos' ? $data['selectMoneda'] : (int) $data['selectMoneda'];
        $flujo = new PROC_FLUJO();
        
        $imgController = new ImagesController();
        $pathImg = $imgController->resizeLogo();

        //Traer ventas para aplicar filtro
        $ventas = $this->venta->where('idEmpresa', '=', session('company')->idEmpresa)
        ->where('movimiento', '=', 'Contrato')
        ->where('estatus', '=', 'CONCLUIDO')
        ->whereVentasProyecto($proyecto)
        ->whereVentasFecha($fecha)
        ->whereVentasMoneda($moneda)
        ->get();

        //buscamos la factura 
        foreach ($ventas as $key => $registro) {
            $movGen = $flujo->getMovientosPosteriores(session('company')->idEmpresa, session('sucursal')->idSucursal, 'Ventas',$registro->idVenta);
            // dd($movGen);
            foreach ($movGen as $registrosFlujo) {
                if ($registrosFlujo->destinoMovimiento == 'Inversión Inicial') {
                    $facturaFlujo = $flujo->getMovientoPosterior(session('company')->idEmpresa, session('sucursal')->idSucursal, 'Ventas',$registrosFlujo->destinoId);

                    if ($facturaFlujo != null) {
                        $registro->enganchePagado = 'SI';
                    }else{
                        $registro->enganchePagado = 'NO';
                    }
                }
            }
        }      
        // dd($ventas);

        $reportes_filtro = $ventas;
        // Cases de informacion de módulos
        switch ($request->input('action1')) {
            case 'Búsqueda':
                return redirect()->route('report.ventas.info')->with('reportes_filtro', $reportes_filtro)
                                                              ->with('selectProyecto', $proyecto)
                                                              ->with('selectFecha', $fecha)
                                                              ->with('inputFechaInicio', $fechaInicio)
                                                              ->with('inputFechaFinal', $fechaFinal)
                                                              ->with('selectMoneda', $moneda);
            case 'Exportar excel':
                $reportes = new ReporteVentasExport($reportes_filtro, $proyecto, $fecha, $fechaInicio, $fechaFinal, $moneda);
                return Excel::download($reportes, 'Información de módulos.xlsx');
            
            case 'Exportar PDF':
                if ($moneda != 'Todos') {
                    $monedas = new CONF_MONEDA();
                    $moneda = $monedas->where('idMoneda', $moneda)->first()->clave;
                } else { $moneda = 'Todos'; }
                if ($reportes_filtro->count() > 0) {
                    $pdf = PDF::loadView('exports.reports.reportVentasInfoMod', ['articulos' => $reportes_filtro->unique('claveProyecto')->values(),
                                                                                 'proyecto' => $proyecto,
                                                                                 'moneda' => $moneda,
                                                                                 'fecha' => $fecha,
                                                                                 'fechaInicio' => $fechaInicio,
                                                                                 'imgLogo' => $pathImg,
                                                                                 'fechaFinal' => $fechaFinal])
                                                                                 ->setPaper(array(0,0,800.00, 2000.00), 'landscape');
                    return $pdf->stream('Información de módulos.pdf');
                } else {
                    $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar';
                    $this->status = false;
                    return redirect()
                    ->route('report.ventas.info')
                    ->with('message',$this->mensaje)
                    ->with('status',$this->status);
                }
        }
        // Cases de venta de módulos
        switch ($request->input('action2')) {
            case 'Búsqueda':
                return redirect()->route('report.ventas.venta')->with('reportes_filtro', $reportes_filtro)
                                                              ->with('selectProyecto', $proyecto)
                                                              ->with('selectFecha', $fecha)
                                                              ->with('inputFechaInicio', $fechaInicio)
                                                              ->with('inputFechaFinal', $fechaFinal)
                                                              ->with('selectMoneda', $moneda);
            case 'Exportar excel':
                $reportes = new ReporteVentasExport($reportes_filtro, $proyecto, $fecha, $fechaInicio, $fechaFinal, $moneda);
                return Excel::download($reportes, 'Venta de módulos.xlsx');

            case 'Exportar PDF':
                if ($moneda != 'Todos') {
                    $monedas = new CONF_MONEDA();
                    $moneda = $monedas->where('idMoneda', $moneda)->first()->clave;
                } else {
                    $moneda = 'Todos';
                }
                if ($reportes_filtro->count() > 0) {
                    $pdf = PDF::loadView('exports.reports.reportVentasVentaMod', ['articulos' => $reportes_filtro->unique('claveProyecto')->values(),
                                                                                  'proyecto' => $proyecto,
                                                                                  'moneda' => $moneda,
                                                                                  'fecha' => $fecha,
                                                                                  'fechaInicio' => $fechaInicio,
                                                                                  'imgLogo' => $pathImg,
                                                                                  'fechaFinal' => $fechaFinal])
                                                                                  ->setPaper(array(0,0,800.00, 1800.00), 'landscape');
                    return $pdf->stream('Venta de módulos.pdf');
                } else {
                    $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                    $this->status = false;
                    return redirect()
                    ->route('report.ventas.venta')
                    ->with('message',$this->mensaje)
                    ->with('status',$this->status);
                }
                                                            
        }
    }
}
