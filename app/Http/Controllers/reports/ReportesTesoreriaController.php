<?php

namespace App\Http\Controllers\reports;

use App\Exports\ReporteTesoreriaExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\ImagesController;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use App\Models\UTILS\PROC_AUXILIAR;
// use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportesTesoreriaController extends Controller
{
    public $venta, $status, $mensaje;
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'CONCLUIDO',
        3 => 'CANCELADO',
    ];
    private $catalogo = 'Clientes';

    public function __construct(Ventas $ventas)
    {
        $this->venta = $ventas;
    }

    public function tesoreriaConc()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        $monedas = new CONF_MONEDA();
        $monedaDefault = $monedas->where('idMoneda', $param->monedaDefault != null ? $param->monedaDefault : 1)->first();
        $cuentasdinero = new CAT_CUENTAS_DINERO();

        $reporte = $this->filtro('Todos','mes',$monedaDefault->clave,'Todos');
        return view('page.reports.tesoreria.reportConcentrados', [
            'monedas' => $monedas->getMonedas(),
            'monedaDefault' => $monedaDefault,
            'cuentasdinero' => $cuentasdinero->getCuentas()->toArray(),
            'reporte' => $reporte,
        ]);
    }

    public function tesoreriaDesg()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        $monedas = new CONF_MONEDA();
        $monedaDefault = $monedas->where('idMoneda', $param->monedaDefault != null ? $param->monedaDefault : 1)->first();
        $cuentasdinero = new CAT_CUENTAS_DINERO();

        $reporte = $this->filtro('Todos','mes',$monedaDefault->clave,'Todos');

        return view('page.reports.tesoreria.reportDesglosados', [
            'monedas' => $monedas->getMonedas(),
            'monedaDefault' => $monedaDefault,
            'cuentasdinero' => $cuentasdinero->getCuentas()->toArray(),
            'reporte' => $reporte,
        ]);
    }

    public function reportesAction(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $idCuenta = $data['selectCuentaD'];
        $fecha = $data['selectFecha'];
        $idMoneda = $data['selectMoneda'];
        $movimiento = $data['selectMovimiento'];
        $fechaInicio = $data['inputFechaInicio'];
        $fechaFin = $data['inputFechaFinal'];
        $reporteKardex = $data['inputReporte'];
        
        $imgController = new ImagesController();
        $pathImg = $imgController->resizeLogo();

        if ($idMoneda != 'Todos' ) { 
            $monedas = new CONF_MONEDA();
            $moneda = $monedas->where('idMoneda', $idMoneda)->first()->clave;
        } else {
            $moneda = 'Todos';
        }

        if($idCuenta != 'Todos'){
            $cuentaDinero = new CAT_CUENTAS_DINERO();
            $cuenta = $cuentaDinero->where('idCuentasDinero', $idCuenta)->first()->clave;
        }else{
            $cuenta = 'Todos';
        }

        // dd($moneda, $cuenta);
        if ($reporteKardex == 'CONCENTRADO') {
            switch ($request->input('action')) {
                case 'Búsqueda':
                    $reporte = $this->filtro($cuenta,$fecha,$moneda,$movimiento);
                    return redirect()
                        ->route('report.tesoreria.conc')
                        ->with('reporte_filtro', $reporte)
                        ->with('selectCuentaD', $idCuenta)
                        ->with('selectFecha', $fecha)
                        ->with('inputFechaInicio', $fechaInicio)
                        ->with('inputFechaFinal', $fechaFin)
                        ->with('selectMoneda', $idMoneda)
                        ->with('selectMovimiento', $movimiento);
                        
                case 'Exportar excel':
                    $tesoreria = new ReporteTesoreriaExport($idCuenta,$fechaInicio,$fechaFin,$idMoneda,$fecha,$movimiento,$reporteKardex);
                    return Excel::download($tesoreria, 'Estado de Cuenta Concentrado.xlsx');
                case 'Exportar PDF':
                    $auxiliar = new PROC_AUXILIAR();
                    $reporte = $this->filtro($cuenta,$fecha,$moneda,$movimiento);
                    if ($reporte->isEmpty()) {
                        $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                        $this->status = false;
                        return redirect()
                            ->route('report.tesoreria.conc')
                            ->with('message', $this->mensaje)
                            ->with('status', $this->status);
                    }else{
                        $reporteSumado = $this->arregloDatosConcentrado($reporte);
                    }
                    
                    // dd($reporteSumado);
                    $pdf = PDF::loadView('exports.reports.reporteTesoreriaEstadoCuenta', [
                        'reporte_filtro' => $reporteSumado,
                        'moneda' => $moneda,
                        'movimiento' => $movimiento,
                        'reporte' => $reporteKardex,
                        'imgLogo' => $pathImg,
                    ])->setPaper(array(0,0,450.00, 800.00), 'landscape');
                    return $pdf->stream('ESTADO DE CUENTA POR CUENTA DE DINERO - CONCENTRADO.pdf');
            }
        }
        elseif($reporteKardex == 'DESGLOSADO'){
            switch ($request->input('action')) {
                case 'Búsqueda':
                    $reporte = $this->filtro($cuenta,$fecha,$moneda,$movimiento);
                    return redirect()
                        ->route('report.tesoreria.desg')
                        ->with('reporte_filtro', $reporte)
                        ->with('selectCuentaD', $idCuenta)
                        ->with('selectFecha', $fecha)
                        ->with('inputFechaInicio', $fechaInicio)
                        ->with('inputFechaFinal', $fechaFin)
                        ->with('selectMoneda', $idMoneda)
                        ->with('selectMovimiento', $movimiento);
                        
                case 'Exportar excel':
                    $tesoreria = new ReporteTesoreriaExport($idCuenta,$fechaInicio,$fechaFin,$idMoneda,$fecha,$movimiento,$reporteKardex);
                    return Excel::download($tesoreria, 'Estado de Cuenta Desglosado.xlsx');
                case 'Exportar PDF':
                    $reporte = $this->filtro($cuenta,$fecha,$moneda,$movimiento);
                    if ($reporte->isEmpty()) {
                        $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                        $this->status = false;
                        return redirect()
                            ->route('report.tesoreria.desg')
                            ->with('message', $this->mensaje)
                            ->with('status', $this->status);
                    } else {
                       $datosDesglosados = $this->arregloDatosDesglosado($reporte);
                    }
        
                    $pdf = PDF::loadView('exports.reports.reporteTesoreriaEstadoCuenta', [
                        'reporte_filtro' => $datosDesglosados,
                        'moneda' => $moneda,
                        'movimiento' => $movimiento,
                        'reporte' => $reporteKardex,
                        'imgLogo' => $pathImg,
                    ])->setPaper(array(0,0,450.00, 800.00), 'landscape');
                    return $pdf->stream('ESTADO DE CUENTA POR CUENTA DE DINERO - CONCENTRADO.pdf');
            }
        }
    }

    public function filtro($cuenta,$fecha,$moneda,$movimiento){
        $auxiliar = new PROC_AUXILIAR();
        $reporte = $auxiliar
            ->whereModulo('Tesoreria')
            ->whereCuenta($cuenta)
            ->whereReportAuxFecha($fecha)
            ->whereReportAuxMoneda($moneda)
            ->whereMovimiento($movimiento)
            ->orderBy('fechaEmision','asc')
            ->get();

        return $reporte;
    }

    public function arregloDatosDesglosado($filtro){
        $datosDesglosados = [];
        foreach ($filtro as $key => $reportes) {
            $cliente = $reportes->getTesoreria->first()->getCliente;
            if ($cliente != null) {
                $beneficiario = $cliente->razonSocial;
            }
            else{
                $beneficiario = 'N/A';
            }
            // dd($beneficiario);
            $fecha = Carbon::parse($reportes->fechaEmision)->format('d/m/Y');
            $cuenta = $reportes->cuenta;
            $moneda = $reportes->moneda;
            //primero verificamos si existe la moneda en el array después si exoste la fecha en el sub array de moneda y por ultimo verificamos si existe la cuenta en el sub array de fecha
            if (array_key_exists($moneda, $datosDesglosados) && array_key_exists($fecha, $datosDesglosados[$moneda]) && array_key_exists($cuenta, $datosDesglosados[$moneda][$fecha])) {
                $datosFinales = [
                    'cuenta' => $reportes->cuenta,
                    'movimiento' => $reportes->movimiento . " " . $reportes->folio,
                    'beneficiario' => $beneficiario,
                    'referencia' => $reportes->referencia,
                    'fecha' => $fecha,
                    'cargo' => $reportes->cargo,
                    'abono' => $reportes->abono,
                ];
                array_push($datosDesglosados[$moneda][$fecha][$cuenta], $datosFinales);
            }else{
                $datosDesglosados[$moneda][$fecha][$cuenta] = [];
                $datosFinales = [
                    'cuenta' => $reportes->cuenta,
                    'movimiento' => $reportes->movimiento . " " . $reportes->folio,
                    'beneficiario' => $beneficiario,
                    'referencia' => $reportes->referencia,
                    'fecha' => $fecha,
                    'cargo' => $reportes->cargo,
                    'abono' => $reportes->abono,
                ];
                array_push($datosDesglosados[$moneda][$fecha][$cuenta], $datosFinales);
            }
        }
        // dd($datosDesglosados);
        return $datosDesglosados;
    }
    public function arregloDatosConcentrado($filtro){
        $reporteAgrupados = $filtro->groupBy('cuenta');
        // dd($reporteAgrupados);
        $reporteSumado = $reporteAgrupados->map(function ($item, $key) {
            // dd($item,$key);
            $total_abono = 0;
            $total_cargo = 0;
            $cuentaDinero = new CAT_CUENTAS_DINERO();
            $saldoInicial = $item->first()->saldoInicial;
            $cuenta = $item->first()->cuenta;
            $nombreCuenta = $cuentaDinero->where('clave', $cuenta)->first();
            $descripcion = $nombreCuenta->clave . '-' . $nombreCuenta->noCuenta;
            $numeroCuenta = $cuentaDinero->where('clave', $cuenta)->first()->noCuenta;
            $moneda = $item->first()->moneda; 

            foreach ($item as $registro) {
            $estatusMov = $registro->getTesoreria->first()->estatus;
            // dd($estatusMov);
                if (($registro->movimiento == 'Depósito' && $estatusMov == 'CONCLUIDO'  && $registro->cancelado == 0) ||( $registro->movimiento == 'Ingreso' && $estatusMov == 'CONCLUIDO' && $registro->cancelado == 0)) {
                    $total_cargo += $registro->cargo;
                }elseif (($registro->movimiento == 'Egreso' && $registro->cancelado == 0 && $estatusMov == 'CONCLUIDO') ) {
                    $total_abono += $registro->abono;
                }elseif ($registro->movimiento == 'Transferencia' && $registro->cancelado == 0 && $estatusMov == 'CONCLUIDO') {
                    if ($registro->cargo != null) {
                        $total_cargo += $registro->cargo;
                    }else{
                        $total_abono += $registro->abono;
                    }
                }
            }
            //dd($total_abono,$total_cargo);
            $saldoFinal = $total_cargo - $total_abono;

            return (object) [
                'cuenta' => $cuenta,
                'descripcion' => $descripcion,
                'numeroCuenta' => $numeroCuenta,
                'saldoInicial' => $saldoInicial,
                'cargos' => $total_cargo,
                'abonos' => $total_abono,
                'saldoFinal' => $saldoFinal,
                'moneda' => $moneda,
            ];
        });

        return $reporteSumado;
    } 
}
