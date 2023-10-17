<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;

use App\Http\Controllers\helpers\FlujoController;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\utils\PROC_AUXILIAR;
use App\Models\utils\PROC_FLUJO;
use App\Models\proc\finanzas\CxC;
use App\Exports\ReporteCxCExport;
use App\Http\Controllers\helpers\ImagesController;
use App\Http\Controllers\proc\finanzas\CxController;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\reports\ReportesVenta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PDF;
use stdClass;
use Illuminate\Support\Collection;

class ReportesCxCController extends Controller
{
    public $ventas, $auxiliar, $flujo, $status, $mensaje;
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'CONCLUIDO',
        3 => 'CANCELADO',
    ];
    private $catalogo = 'Clientes';

    public function __construct(Ventas $ventas, PROC_AUXILIAR $auxiliar, PROC_FLUJO $flujo)
    {
        $this->ventas = $ventas;
        $this->auxiliar = $auxiliar;
        $this->flujo = $flujo;
    }
    public function cxcSaldos()
    {
        $monedas = new CONF_MONEDA();
        $clientes = new CAT_CLIENTES();

        // Obtener saldos pendientes de VENTAS y facturas pendientes de CXC
        $saldos = $this->ventas->obtenerDatos();
        return view('page.reports.cxc.reportSaldos', [
            'saldos' => $saldos,
            'monedas' => $monedas->getMonedas(),
            'clientes' => $clientes->getClientes()->toArray(),
        ]);
    }

    public function cxcEstado()
    {
        $monedas = new CONF_MONEDA();
        $clientes = new CAT_CLIENTES();
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        // Obtener estado de cuenta de CXC
        // $estados = $this->auxiliar->obtenerDatos();
        $estados_ventas = $this->ventas->where('idEmpresa', session('company')->idEmpresa)->where('estatus', 'PENDIENTE')->where('moneda', $param != null ? $param->idMoneda : 1)->get();
        foreach ($estados_ventas as $value) {
            $origen = $value->getOrigen;
        }
        // dd(collect($origen), $estados_ventas);
        //convertir a collection

        // dd($estados, $estados_ventas);
        // $estado_cuenta = $estados->merge($estados_ventas);
        // foreach ($estado_cuenta as $key => $value) {
        //     if ($value->movimiento == 'Depósito' && $value->modulo == 'Tesoreria') {
        //         $depositoTes = Tesoreria::find($value->idModulo);
        //         $flujo = $depositoTes->getFlujo;
        //         dd($flujo);
        //         $movNew = $flujo->getMovientoPosterior($depositoTes->idEmpresa, $depositoTes->idSucursal, 'Ventas', $depositoTes->idTesoreria);
        //         $clientNew = $depositoTes->getCliente->idCliente;
        //         $value->aplica = $movNew->destinoMovimiento;
        //         $value->idAplica = $movNew->destinoId;
        //         $value->cuenta = $clientNew;
        //         $value->movimiento = $movNew->origenMovimiento;
        //         $value->folio = $depositoTes->folioMov;
        //     }
        //     if ($value->movimiento == 'Factura' && $value->modulo == 'Ventas') {
        //         $facturaVenta = Ventas::find($value->idModulo);
        //         $facturaVenta = Ventas::find($facturaVenta->origenId);
        //         $value->movimiento = $facturaVenta->movimiento;
        //         $value->folio = $facturaVenta->folioMov;
        //     }
        // }
        // dd($estados);
        return view('page.reports.cxc.reportEstadoCuenta', [
            'estados' => $estados_ventas,
            'monedas' => $monedas->getMonedasClave(),
            'clientes' => $clientes->getClientes()->toArray(),
        ]);
    }

    public function cxcIngresos()
    {
        $cxc = new CxC();
        $flujo = new PROC_FLUJO();
        $ventas = new Ventas();
        $proyectos = new CAT_PROYECTOS();
        $monedas = new CONF_MONEDA();
        $clientes = new CAT_CLIENTES();
        $categorias = new AGRUP_CATEGORIA();
        $grupos = new AGRUP_GRUPO();
        $parametros = new CONF_PARAMETROS_GENERALES();
        $param = $parametros->monedaByCompany(session('company')->idEmpresa)->first();

        $ingresos = $this->dataIngresos($cxc, $ventas, $flujo, $param);
        return view('page.reports.cxc.reportIngresos', [
            'ingresos' => $ingresos,
            'proyectos' => $proyectos->getProjects()->toArray(),
            'monedas' => $monedas->getMonedasClave(),
            'clientes' => $clientes->getClientesRazon()->toArray(),
            'categorias' => $categorias->getCategoriaNombre($this->catalogo)->toArray(),
            'grupos' => $grupos->getGrupoNombre($this->catalogo)->toArray(),
            'parametroGen' => $param,
        ]);
    }
    public function reportesAction(Request $request)
    {
        $data = $request->all();
        
        $imgController = new ImagesController();
        $pathImg = $imgController->resizeLogo();
        $ventaModel = new Ventas();
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();

        if (array_key_exists('action3', $request->input())) {
            $cliente = $data["selectCliente"] == 'Todos' ? $data["selectCliente"] : $data["selectCliente"];
            $fecha = $data["selectFecha"] == 'Todos' ? $data["selectFecha"] : $data["selectFecha"];
            $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
            $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];
            $moneda = $data['selectMoneda'] == 'Todos' ? $data['selectMoneda'] : $data['selectMoneda'];
            $categoria = $data['selectCategoria'] == 'Todos' ? $data['selectCategoria'] : $data['selectCategoria'];
            $grupo = $data['selectGrupo'] == 'Todos' ? $data['selectGrupo'] : $data['selectGrupo'];
            $contrato = $data['selectTipoContrato'] == 'Todos' ? $data['selectTipoContrato'] : $data['selectTipoContrato'];

            $cxc = new CxC();
            $ventas = new Ventas();
            $flujo = new PROC_FLUJO();
            $ingresos = $this->dataIngresos($cxc, $ventas, $flujo, $param);
            $reportes_filtro_ingresos = $this->filtrarIngresos($ingresos, $cliente, $categoria, $grupo, $fecha, $moneda, $contrato, $fechaInicio, $fechaFinal);
        } else {
            $cliente2 = $data['selectCliente2'] == 'Todos' ? $data['selectCliente2'] : (int) $data['selectCliente2'];
            $estatus = $data['selectEstatus'] == 'Todos' ? $data['selectEstatus'] : $data['selectEstatus'];
            $plazo = $data['selectPlazo'] == 'Todos' ? $data['selectPlazo'] : $data['selectPlazo'];
            $cliente = $data['selectCliente'] == 'Todos' ? $data['selectCliente'] : (int) $data['selectCliente'];
            $fecha = $data['selectFecha'] == 'Todos' ? $data['selectFecha'] : $data['selectFecha'];
            $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
            $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];
            $moneda = $data['selectMoneda'] == 'Todos' ? $data['selectMoneda'] : $data['selectMoneda'];

            // obtener el idMoneda de la moneda seleccionada
            CONF_MONEDA::where('clave', $moneda)->first() != null ? $moneda2 = CONF_MONEDA::where('clave', $moneda)->first()->idMoneda : $moneda2 = 'Todos';
            // Tabla de antiguedad de saldos
            $saldos_filtro = $this->ventas->obtenerDatos();
            // Tabla de estado de cuenta
            // $estados_filtro = $this->auxiliar->obtenerDatos();
            $estados_ventas_filtro = $this->ventas->where('idEmpresa', session('company')->idEmpresa)->where('estatus', 'PENDIENTE')->where('moneda', $param != null ? $param->idMoneda : 1)->get();
            // cambiar el valor de $value->moneda (idMoneda) a su clave correspondiente
            foreach ($estados_ventas_filtro as $value) { $value->moneda = $value->getMoneda->clave;}

            // dd($estados_filtro, $estados_ventas_filtro);
            //Validar si están vacíos
            if ($saldos_filtro->count() == 0) { $saldos_filtro = []; }
            // if ($estados_filtro->count() == 0) { $estados_filtro = []; }
            if ($estados_ventas_filtro->count() == 0) { $estados_ventas_filtro = []; }

            //Aplicar filtros antiguedad de saldos
            if (!empty($saldos_filtro)) {
                foreach ($saldos_filtro as $value) {
                    $reportes_filtro_saldos = $value->whereReportVentasSaldos($cliente, $plazo, $moneda)->get();
                }
            } else { $reportes_filtro_saldos = collect([]); }

            //Aplicar filtros estado de cuenta
            if (!empty($estados_filtro) || !empty($estados_ventas_filtro)) {
                // foreach ($estados_filtro as $value) {
                //     $reportes_filtro_estados = $value->whereReportAuxCliente($cliente)
                //                                       ->whereReportAuxFecha($fecha)
                //                                       ->whereReportAuxMoneda($moneda)
                //                                       ->whereReportAuxEstatus($estatus)->get();
                // }
                
                foreach ($estados_ventas_filtro as $value) {
                    $reportes_filtro_estados_ventas = $value->whereReportVentasCliente($cliente)
                                                            ->whereVentasFecha($fecha)
                                                            ->whereVentasMoneda($moneda2)
                                                            ->whereVentasEstatus($estatus)->get();
                }
                // $reportes_filtro_estados_cuenta = $reportes_filtro_estados->merge($reportes_filtro_estados_ventas);
                // foreach ($reportes_filtro_estados_ventas as $key => $value) {
                //     if ($value->movimiento == 'Depósito' && $value->modulo == 'Tesoreria') {
                //         $depositoTes = Tesoreria::find($value->idModulo);
                        
                        
                //         $flujo = $depositoTes->getFlujo;
                //         dd($flujo);
                //         $movNew = $flujo->getMovientoPosterior($depositoTes->idEmpresa, $depositoTes->idSucursal, 'Ventas', $flujo->idFlujo);
                //         $clientNew = $depositoTes->getCliente->idCliente;
                //         $value->aplica = $movNew->destinoMovimiento;
                //         $value->idAplica = $movNew->destinoId;
                //         $value->cuenta = $clientNew;
                //         $value->movimiento = $movNew->origenMovimiento;
                //         $value->folio = $depositoTes->folioMov;
                //     }
                //     if ($value->movimiento == 'Factura' && $value->modulo == 'Ventas') {
                //         $facturaVenta = $ventaModel->find($value->idModulo);
                //         $facturaVenta = $ventaModel->find($facturaVenta->origenId);
                //         $value->movimiento = $facturaVenta->movimiento;
                //         $value->folio = $facturaVenta->folioMov;
                //     }
                // }
                // dd($reportes_filtro_estados, $reportes_filtro_estados_ventas);
            } else { $reportes_filtro_estados_ventas = collect([]); }
        }
        //Cases de antiguedad de saldos
        switch ($request->input('action1')) {
            case 'Búsqueda':
                return redirect()->route('report.cxc.saldos')->with('reportes_filtro', $reportes_filtro_saldos)
                                                             ->with('selectCliente', $cliente)
                                                             ->with('selectCliente2', $cliente2)
                                                             ->with('selectPlazo', $plazo)
                                                             ->with('selectMoneda', $moneda);
            case 'Exportar excel':
                $reportes = new ReporteCxCExport($reportes_filtro_saldos, $cliente, null, $plazo, null, null, $moneda, null, null, null, null);
                return Excel::download($reportes, 'Antigüedad de saldos.xlsx');
            case 'Exportar PDF':
                if ($reportes_filtro_saldos->count() > 0) {
                    $pdf = PDF::loadView('exports.reports.reportCxCSaldos', ['saldos' => $reportes_filtro_saldos,
                                                                             'cliente' => $cliente,
                                                                             'moneda' => $moneda,
                                                                             'fecha' => $fecha,
                                                                             'fechaInicio' => $fechaInicio,
                                                                             'fechaFinal' => $fechaFinal,
                                                                             'imgLogo' => $pathImg,
                                                                             'estatus' => $estatus,])
                                                                             ->setPaper(array(0, 0, 600.00, 1200.00), 'landscape');
                    return $pdf->stream('Antigüedad de saldos.pdf');
                } else {
                    $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                    $this->status = false;
                    return redirect()->route('report.cxc.saldos')
                                     ->with('message', $this->mensaje)
                                     ->with('status', $this->status);
                }
        }
        //Cases de estado de cuenta
        switch ($request->input('action2')) {
            case 'Búsqueda':
                return redirect()->route('report.cxc.estado')->with('reportes_filtro', $reportes_filtro_estados_ventas)
                                                             ->with('selectCliente', $cliente)
                                                             ->with('selectFecha', $fecha)
                                                             ->with('inputFechaInicio', $fechaInicio)
                                                             ->with('inputFechaFinal', $fechaFinal)
                                                             ->with('selectMoneda', $moneda)
                                                             ->with('selectEstatus', $estatus);
            case 'Exportar excel':
                $reportes = new ReporteCxCExport($reportes_filtro_estados_ventas, $cliente, $fecha, null, $fechaInicio, $fechaFinal, $moneda, $estatus, null, null, null);
                return Excel::download($reportes, 'Estado de cuenta.xlsx');
            case 'Exportar PDF':
                foreach ($reportes_filtro_estados_ventas as $value) { $value->moneda = $value->getMoneda->clave;}
                if ($reportes_filtro_estados_ventas->count() > 0) {
                    $pdf = PDF::loadView('exports.reports.reportCxCEstado', ['estados' => $reportes_filtro_estados_ventas,
                                                                             'cliente' => $cliente,
                                                                             'moneda' => $moneda,
                                                                             'fecha' => $fecha,
                                                                             'fechaInicio' => $fechaInicio,
                                                                             'fechaFinal' => $fechaFinal,
                                                                             'imgLogo' => $pathImg,
                                                                             'estatus' => $estatus,])
                                                                             ->setPaper(array(0, 0, 600.00, 1200.00), 'landscape');
                    return $pdf->stream('Estado de cuenta.pdf');
                } else {
                    $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                    $this->status = false;
                    return redirect()->route('report.cxc.estado')
                                     ->with('message', $this->mensaje)
                                     ->with('status', $this->status);
                }

        }
        //Cases de ingresos por proyecto
        switch ($request->input('action3')) {
            case 'Búsqueda':
                return redirect()->route('report.cxc.ingresos')->with('reportes_filtro', $reportes_filtro_ingresos)
                                                               ->with('selectCliente', $cliente)
                                                               ->with('selectCategoria', $categoria)
                                                               ->with('selectGrupo', $grupo)
                                                               ->with('selectFecha', $fecha)
                                                               ->with('inputFechaInicio', $fechaInicio)
                                                               ->with('inputFechaFinal', $fechaFinal)
                                                               ->with('selectMoneda', $moneda)
                                                               ->with('selectTipoContrato', $contrato);
            case 'Exportar excel':
                $reportes = new ReporteCxCExport($reportes_filtro_ingresos, $cliente, $fecha, null, $fechaInicio, $fechaFinal, $moneda, null, $categoria, $grupo, $contrato);
                return Excel::download($reportes, 'Ingresos por proyecto.xlsx');
            case 'Exportar PDF':
                if ($reportes_filtro_ingresos->count() > 0) {
                    $pdf = PDF::loadView('exports.reports.reportCxCIngreso', ['ingresos' => $reportes_filtro_ingresos,
                                                                             'cliente' => $cliente,
                                                                             'categoria' => $categoria,
                                                                             'grupo' => $grupo,
                                                                             'fecha' => $fecha,
                                                                             'fechaInicio' => $fechaInicio,
                                                                             'fechaFinal' => $fechaFinal,
                                                                             'moneda' => $moneda,
                                                                             'imgLogo' => $pathImg,
                                                                             'contrato' => $contrato])
                                                                             ->setPaper(array(0,0,800.00, 1400.00), 'landscape');
                    return $pdf->stream('Ingresos por proyecto.pdf');
                } else {
                    $this->mensaje = 'Error al generar el documento PDF, no hay información para mostrar.';
                    $this->status = false;
                    return redirect()->route('report.cxc.ingresos')
                                     ->with('message', $this->mensaje)
                                     ->with('status', $this->status);
                }

        }
    }
    public function dataIngresos($cxc, $ventas, $flujo, $param){
        $movimientos = [];
        //TRAER LOS ANTICIPOS EN CXC 
        $anticipos = $cxc->whereEmpresa(session('company')->idEmpresa)
        ->whereCxCSucursal(session('sucursal')->idSucursal)
        ->whereCxCFecha('Mes')
        ->whereCxCMoneda($param->idMoneda)
        ->where(function($query){
            $query->where('movimiento', '=', 'Anticipo')
            ->where('estatus', '=', 'PENDIENTE');
        })->get();
        foreach ($anticipos as $key => $anticipo) {
            $movimientoObjeto  = new stdClass();
            $movimientoObjeto->cliente = $anticipo->getCliente->razonSocial;
            $movimientoObjeto->categoria = $anticipo->getCliente != null ? ($anticipo->getCliente->getCategoria == null ? '' : $anticipo->getCliente->getCategoria->nombre) : '';
            $movimientoObjeto->grupo = $anticipo->getCliente != null ? ($anticipo->getCliente->getGrupo == null ? '' : $anticipo->getCliente->getGrupo->nombre) : '';
            $movimientoObjeto->movimiento = $anticipo->movimiento;
            $movimientoObjeto->folio = $anticipo->folioMov;
            $movimientoObjeto->ingreso = $anticipo->total;
            $movimientoObjeto->Banco = $anticipo->cuentaDinero;
            $movimientoObjeto->folioExterno = $anticipo->referencia;
            $movimientoObjeto->moneda = $anticipo->getMoneda->clave;
            $movimientoObjeto->tipoCambio = $anticipo->tipoCambio;
            $movimientoObjeto->tipo = 'Anticipo';
            $movimientoObjeto->fecha = Carbon::parse($anticipo->fechaEmision)->format('Y-m-d');
            $movimientoObjeto->proyecto = $anticipo->getProyecto != null ? $anticipo->getProyecto->nombre : null;
            $movimientoObjeto->modulo = $anticipo->getModulo != null ? $anticipo->getModulo->descripcion : null;
            $movimientoObjeto->estatus = $anticipo->estatus;
            array_push($movimientos, $movimientoObjeto);
        }
        //TRAER LAS FACTURAS EN VENTAS
        $facturas = $ventas->whereEmpresa(session('company')->idEmpresa)
        ->whereVentasSucursal(session('sucursal')->idSucursal)
        ->whereVentasFecha('Mes')
        ->whereVentasMoneda($param->idMoneda)
        ->whereVentasMovimiento('Factura')
        ->whereVentasEstatus('CONCLUIDO')->get();
        //SI LA FACTURA ES DE CREDITO AGARRO EL COBRO DE CXC Y SI ES DE CONTADO AGARRO EL DEPOSITO GENERADO EN TESORERIA
        foreach ($facturas as $key => $movimiento) {
            $condicion = $movimiento->getCondition;
            $money = $movimiento->getMoneda != null ? $movimiento->getMoneda->clave :  null;
            $tipo = $movimiento->tipoContrato !== null ? ($movimiento->tipoContrato == '1' ? 'Venta' : 'Renta') : 'Anticipo';
            $proyecto = $movimiento->getProyecto != null ? $movimiento->getProyecto->nombre : null;
            $modulo = $movimiento->getModulo != null ? $movimiento->getModulo->descripcion : null;
            $movimientoObjeto  = new stdClass();
            if ($condicion->tipoCondicion == 'Crédito') {
                $facturaCxc = $movimiento->getCXC;
                if ($facturaCxc) {
                    $cobro = $flujo->getMovientoPosterior(session('company')->idEmpresa, session('sucursal')->idSucursal, 'CxC', $facturaCxc->idCXC);
                    $cobro = CxC::find($cobro->destinoId);
                    // dd($cobro);
                    $movimientoObjeto->cliente = $movimiento->getCliente->razonSocial;
                    $movimientoObjeto->categoria = $movimiento->getCliente != null ? ($movimiento->getCliente->getCategoria == null ? '' : $movimiento->getCliente->getCategoria->nombre) : '';
                    $movimientoObjeto->grupo = $movimiento->getCliente != null ? ($movimiento->getCliente->getGrupo == null ? '' : $movimiento->getCliente->getGrupo->nombre) : '';
                    $movimientoObjeto->movimiento = $movimiento->movimiento;
                    $movimientoObjeto->folio = $facturaCxc->folioMov;
                    $movimientoObjeto->ingreso = $cobro->total;
                    $movimientoObjeto->Banco = $cobro->cuentaDinero;
                    $movimientoObjeto->folioExterno = $cobro->referencia;
                    $movimientoObjeto->moneda = $money;
                    $movimientoObjeto->tipoCambio = $cobro->tipoCambio;
                    $movimientoObjeto->tipo = $tipo;
                    $movimientoObjeto->fecha = Carbon::parse($movimiento->fechaEmision)->format('Y-m-d');
                    $movimientoObjeto->proyecto = $proyecto;
                    $movimientoObjeto->modulo = $modulo;
                    $movimientoObjeto->estatus = $movimiento->estatus;
                    array_push($movimientos, $movimientoObjeto);
                }
            }else{
                $FacturaCobrada = $movimiento->getCobro;
                if ($FacturaCobrada) {
                    $movimientoObjeto->cliente = $movimiento->getCliente->razonSocial;
                    $movimientoObjeto->categoria = $movimiento->getCliente != null ? ($movimiento->getCliente->getCategoria == null ? '' : $movimiento->getCliente->getCategoria->nombre) : '';
                    $movimientoObjeto->grupo = $movimiento->getCliente != null ? ($movimiento->getCliente->getGrupo == null ? '' : $movimiento->getCliente->getGrupo->nombre) : '';
                    $movimientoObjeto->movimiento = $movimiento->movimiento;
                    $movimientoObjeto->folio = $movimiento->folioMov;
                    $movimientoObjeto->ingreso = $FacturaCobrada->totalCobrado;
                    $movimientoObjeto->Banco = CAT_CUENTAS_DINERO::find($FacturaCobrada->cuentaDinero)->clave;
                    $movimientoObjeto->folioExterno = $FacturaCobrada->informacionAdicional;
                    $movimientoObjeto->moneda = $money;
                    $movimientoObjeto->tipoCambio = $FacturaCobrada->getFormaPago->getMoneda->tipoCambio;
                    $movimientoObjeto->tipo = $tipo;
                    $movimientoObjeto->fecha = Carbon::parse($movimiento->fechaEmision)->format('Y-m-d');
                    $movimientoObjeto->proyecto = $proyecto;
                    $movimientoObjeto->modulo = $modulo;
                    $movimientoObjeto->estatus = $movimiento->estatus;
                    array_push($movimientos, $movimientoObjeto);
                }
            }
        }
        $ingresos = collect($movimientos);
        return $ingresos;
    }
    public function filtrarIngresos($colection,$cliente,$categoria,$grupo,$fecha,$moneda,$contrato,$fechaInicio,$fechaFinal){
         //Filtrar los movimientos_filtro según el cliente seleccionado en el select
         if (!is_null($cliente)) {
            if ($cliente != 'Todos') {
                $colection = $colection->where('cliente', $cliente);
            }
        }
        //Filtrar los movimientos_filtro según la categoria seleccionada en el select
        if (!is_null($categoria)) {
            if ($categoria != 'Todos') {
                $colection = $colection->where('categoria', $categoria);
            }
        }
        //Filtrar los movimientos_filtro según el grupo seleccionada en el select
        if (!is_null($grupo)) {
            if ($grupo != 'Todos') {
                $colection = $colection->where('grupo', $grupo);
            }
        }
        //Filtrar los movimientos_filtro según la fecha seleccionada en el select
        if (!is_null($fecha)) {
            switch($fecha) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    $colection = $colection->where('fecha', $fechaHoy);
                    break;
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    $colection = $colection->where('fecha', $fechaAyer);
                    break;
                case 'Semana':
                    $colection = $colection->whereBetween('fecha', [
                        Carbon::now()->startOfWeek(Carbon::SUNDAY),
                        Carbon::now()->endOfWeek(Carbon::SATURDAY)
                        ]);
                    break;
                case 'Mes':
                    $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');
                    $colection = $colection->where('fecha', '>=', $inicioMes)
                                ->where('fecha', '<=', $finMes);
                    break;
                case 'Año móvil':
                    $inicioAnio = Carbon::now()->startOfYear()->format('Y-m-d');
                    $finAnio = Carbon::now()->endOfYear()->format('Y-m-d');
                    $colection = $colection->where('fecha', '>=', $inicioAnio)
                                ->where('fecha', '<=', $finAnio);
                    break;
                case 'Año pasado':
                    $inicioAnioPasado = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                    $finAnioPasado = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                    $colection = $colection->where('fecha', '>=', $inicioAnioPasado)
                                            ->where('fecha', '<=', $finAnioPasado);
                    break;
                case 'Rango de fechas':
                    $colection = $colection->where('fecha', '>=', $fechaInicio)
                                            ->where('fecha', '<=', $fechaFinal);
                    break;
            }
        }
        //Filtrar los movimientos_filtro según la moneda seleccionada en el select
        if (!is_null($moneda)) {
            if ($moneda != 'Todos') {
                $colection = $colection->where('moneda', $moneda);
            }
        }
        //Filtrar los movimientos_filtro según el tipo de contrato seleccionado en el select
        if (!is_null($contrato)) {
            if ($contrato != 'Todos') {
                $colection = $colection->where('tipo', $contrato);
            }
        }
        return $colection;
    }
}