<?php

namespace App\Exports;
use App\Models\proc\comercial\Ventas;
use App\Models\utils\PROC_AUXILIAR;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\config\CONF_MONEDA;
use App\Models\proc\finanzas\CxC;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;

use stdClass;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class ReporteCxCExport implements FromView, ShouldAutoSize, WithStyles, WithDrawings, WithDefaultStyles
{
    public $ventas;
    public $cliente;
    public $fecha;
    public $plazo;
    public $fechaInicio;
    public $fechaFinal;
    public $moneda;
    public $estatus;
    public $categoria;
    public $grupo;
    public $tipo;

    public function __construct($ventas,$cliente,$fecha,$plazo,$fechaInicio,$fechaFinal,$moneda,$estatus,$categoria,$grupo,$tipo)
    {
        $ventas = new Ventas();
        $this->ventas = $ventas;
        $this->cliente = $cliente;
        $this->fecha = $fecha;
        $this->plazo = $plazo;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->moneda = $moneda;
        $this->estatus = $estatus;
        $this->categoria = $categoria;
        $this->grupo = $grupo;
        $this->tipo = $tipo;
    }

    public function validar() {

    }
    public function view() : view
    {
        $ventaModel = new Ventas();
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        // obtener el idMoneda de la moneda seleccionada
        CONF_MONEDA::where('clave', $this->moneda)->first() != null ? $moneda2 = CONF_MONEDA::where('clave', $this->moneda)->first()->idMoneda : $moneda2 = 'Todos';
        //Excel para saldos
        $saldos_filtro = $this->ventas->obtenerDatos();

        if ($saldos_filtro->count() == 0) { $saldos_filtro = []; }
        
        if (!empty($saldos_filtro))
                    foreach ($saldos_filtro as $key => $value) {
                        $reportes_filtro_saldos = $value->whereReportVentasSaldos($this->cliente, $this->plazo, $this->moneda)->get();
                    }
        else { $reportes_filtro_saldos = []; }
        //Excel para estados
        // $auxiliares = PROC_AUXILIAR::select('PROC_AUXILIAR.*') 
        //                 ->selectRaw('(CASE
        //                                 WHEN PROC_AUXILIAR.modulo = "CxC" THEN PROC_CXC.estatus 
        //                                 WHEN PROC_AUXILIAR.modulo = "Ventas" THEN CXC_VENTAS.estatus
        //                                 ELSE NULL 
        //                             END) as estatus')
        //                 ->leftJoin('PROC_CXC', function ($join) {
        //                     $join->on('PROC_AUXILIAR.idModulo', '=', 'PROC_CXC.idCXC')
        //                         ->where('PROC_AUXILIAR.modulo', '=', 'CxC');
        //                 })
        //                 ->leftJoin('PROC_CXC as CXC_VENTAS', function ($join) {
        //                     $join->on('PROC_AUXILIAR.idModulo', '=', 'CXC_VENTAS.origenId')
        //                         ->where('PROC_AUXILIAR.modulo', '=', 'Ventas');
        //                 })
        //                 ->where('PROC_AUXILIAR.rama', '=', 'CxC')->get();
        $estados_ventas_filtro = $this->ventas->where('idEmpresa', session('company')->idEmpresa)->where('estatus', 'PENDIENTE')->where('moneda', $param != null ? $param->idMoneda : 1)->get();
        foreach ($estados_ventas_filtro as $value) { $value->moneda = $value->getMoneda->clave;}

        // if ($auxiliares->count() == 0) { $auxiliares = []; }
        if ($estados_ventas_filtro->count() == 0) { $estados_ventas_filtro = []; }

        if (!empty($estados_ventas_filtro)) {
            // foreach ($auxiliares as $key => $value) { $reportes_filtro_estados = $value->whereReportAuxCliente($this->cliente)
            //                                                                    ->whereReportAuxFecha($this->fecha)
            //                                                                    ->whereReportAuxMoneda($this->moneda)
            //                                                                    ->whereReportAuxEstatus($this->estatus)
            //                                                                    ->get(); }
            foreach ($estados_ventas_filtro as $value) { $reportes_filtro_estados_ventas = $value->whereReportVentasCliente($this->cliente)
                                                                                                 ->whereVentasFecha($this->fecha)
                                                                                                 ->whereVentasMoneda($moneda2)
                                                                                                 ->whereVentasEstatus($this->estatus)->get(); }

            if(!empty($reportes_filtro_estados_ventas)) {
                // foreach ($reportes_filtro_estados as $key => $value) {
                //     if ($value->movimiento == 'Factura' && $value->modulo == 'Ventas') {
                //         $facturaVenta = $ventaModel->find($value->idModulo);
                //         $facturaVenta = $ventaModel->find($facturaVenta->origenId);
                //         $value->movimiento = $facturaVenta->movimiento;
                //         $value->folio = $facturaVenta->folioMov;
                //     }
                // }
            } else { $reportes_filtro_estados_ventas = collect([]); }
        } else { $reportes_filtro_estados = collect([]); $reportes_filtro_estados_ventas = collect([]); }
        //Excel para ingresos

        $cxc = new CxC();
        $parametros = new CONF_PARAMETROS_GENERALES();
        $param = $parametros->monedaByCompany(session('company')->idEmpresa)->first();
        $ventas = new Ventas();

        $movimientos = [];
        //TRAER LOS ANTICIPOS EN CXC 
        $anticipos = $cxc->whereEmpresa(session('company')->idEmpresa)
                         ->whereCxCSucursal(session('sucursal')->idSucursal)
                         ->where(function($query){
                            $query->where('movimiento', '=', 'Anticipo')
                                  ->where('estatus', '=', 'PENDIENTE');
                        })->get();
        //TRAER LAS FACTURAS EN VENTAS
        $facturas = $ventas->whereEmpresa(session('company')->idEmpresa)
                           ->whereVentasSucursal(session('sucursal')->idSucursal)
                           ->whereVentasMovimiento('Factura')
                           ->whereVentasEstatus('CONCLUIDO')
                           ->get();
        //SI LA FACTURA ES DE CREDITO AGARRO EL COBRO DE CXC Y SI ES DE CONTADO AGARRO EL DEPOSITO GENERADO EN TESORERIA
        foreach ($facturas as $key => $movimiento) {
            $condicion = $movimiento->getCondition;
            if ($condicion->tipoCondicion == 'Crédito') {
                $facturaCxc = $movimiento->getCXC()->get();
            }else{
                $facturaTes = $movimiento->getTes()->get();
                foreach ($facturaTes as $movimiento) {
                    $movimiento->movimiento = $movimiento->origen;
                }
            }
        }
        //Validar si hay facturaCxc O facturaTes
        if (!isset($facturaCxc)) {
            $facturaCxc = collect([]);
        }
        if (!isset($facturaTes)) {
            $facturaTes = collect([]);
        }
        $movimientosT = $anticipos->merge($facturaCxc)->merge($facturaTes);
        foreach ($movimientosT as $key => $movimiento) {
            // dd($movimiento->fechaEmision);
            $money = $movimiento->getMoneda != null ? $movimiento->getMoneda->clave :  null;
            $tipo = $movimiento->tipoContrato !== null ? ($movimiento->tipoContrato == '1' ? 'Venta' : 'Renta') : 'Anticipo';
            $proyecto = $movimiento->getProyecto != null ? $movimiento->getProyecto->nombre : null;
            $modulo = $movimiento->getModulo != null ? $movimiento->getModulo->descripcion : null;
            $movimientoObjeto =  new stdClass();
            $movimientoObjeto->cliente = $movimiento->getCliente->razonSocial;
            $movimientoObjeto->categoria = $movimiento->getCliente != null ? ($movimiento->getCliente->getCategoria == null ? '' : $movimiento->getCliente->getCategoria->nombre) : '';
            $movimientoObjeto->grupo = $movimiento->getCliente != null ? ($movimiento->getCliente->getGrupo == null ? '' : $movimiento->getCliente->getGrupo->nombre) : '';
            $movimientoObjeto->movimiento = $movimiento->movimiento;
            $movimientoObjeto->folio = $movimiento->folioMov;
            $movimientoObjeto->ingreso = $movimiento->total;
            $movimientoObjeto->Banco = $movimiento->cuentaDinero;
            $movimientoObjeto->folioExterno = $movimiento->referencia;
            $movimientoObjeto->moneda = $money;
            $movimientoObjeto->tipoCambio = $movimiento->tipoCambio;
            $movimientoObjeto->tipo = $tipo;
            $movimientoObjeto->fecha = Carbon::parse($movimiento->fechaEmision)->format('Y-m-d');
            $movimientoObjeto->proyecto = $proyecto;
            $movimientoObjeto->modulo = $modulo;
            $movimientoObjeto->estatus = $movimiento->estatus;

            array_push($movimientos, $movimientoObjeto);
        }

        // dd($moneda, $cliente, $categoria, $grupo, $fecha);
        $movimientos_filtro = collect($movimientos);
        // dd($movimientos_filtro);
        $reportes_filtro_ingresos = $movimientos_filtro;
        //Filtrar los movimientos_filtro según el cliente seleccionado en el select
        if (!is_null($this->cliente)) {
            if ($this->cliente != 'Todos') {
                $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('cliente', $this->cliente);
            }
        }
        //Filtrar los movimientos_filtro según la categoria seleccionada en el select
        if (!is_null($this->categoria)) {
            if ($this->categoria != 'Todos') {
                $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('categoria', $this->categoria);
            }
        }
        //Filtrar los movimientos_filtro según el grupo seleccionada en el select
        if (!is_null($this->grupo)) {
            if ($this->grupo != 'Todos') {
                $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('grupo', $this->grupo);
            }
        }
        //Filtrar los movimientos_filtro según la fecha seleccionada en el select
        if (!is_null($this->fecha)) {
            switch($this->fecha) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', $fechaHoy);
                    break;
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', $fechaAyer);
                    break;
                case 'Semana':
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->whereBetween('fecha', [
                        Carbon::now()->startOfWeek(Carbon::SUNDAY),
                        Carbon::now()->endOfWeek(Carbon::SATURDAY)
                    ]);
                    break;
                case 'Mes':
                    $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', '>=', $inicioMes)
                                                                         ->where('fecha', '<=', $finMes);
                    break;
                case 'Año móvil':
                    $inicioAnio = Carbon::now()->startOfYear()->format('Y-m-d');
                    $finAnio = Carbon::now()->endOfYear()->format('Y-m-d');
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', '>=', $inicioAnio)
                                                                         ->where('fecha', '<=', $finAnio);
                    break;
                case 'Año pasado':
                    $inicioAnioPasado = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                    $finAnioPasado = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', '>=', $inicioAnioPasado)
                                                                         ->where('fecha', '<=', $finAnioPasado);
                    break;
                case 'Rango de fechas':
                    $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('fecha', '>=', $this->fechaInicio)
                                                                         ->where('fecha', '<=', $this->fechaFinal);
                    break;
            }
        }
        //Filtrar los movimientos_filtro según la moneda seleccionada en el select
        if (!is_null($this->moneda)) {
            if ($this->moneda != 'Todos') {
                $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('moneda', $this->moneda);
            }
        }
        //Filtrar los movimientos_filtro según el tipo de contrato seleccionado en el select
        if (!is_null($this->tipo)) {
            if ($this->tipo != 'Todos') {
                $reportes_filtro_ingresos = $reportes_filtro_ingresos->where('tipo', $this->tipo);
            }
        }
        $validar = array_keys($_REQUEST);
        // dd($validar);
        if ($validar[9] == 'action1') {
            return view('exports.reportesCxCSaldos',[
                'saldos' => $reportes_filtro_saldos,
                'fecha' => $this->fecha,
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $this->fechaFinal,
                'moneda' => $this->moneda,
            ]);
        } 
        else if ($validar[9] == 'action2') {
            return view('exports.reportesCxCEstados',[
                'estados' => $reportes_filtro_estados->merge($reportes_filtro_estados_ventas),
                'cliente' => $this->cliente,
                'fecha' => $this->fecha,
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $this->fechaFinal,
                'moneda' => $this->moneda,
                'estatus' => $this->estatus,
            ]);
        }
        else if ($validar[9] == 'action3') {
            return view('exports.reportesCxCIngresos',[
                'ingresos' => $reportes_filtro_ingresos,
                'cliente' => $this->cliente,
                'categoria' => $this->categoria,
                'grupo' => $this->grupo,
                'fecha' => $this->fecha,
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $this->fechaFinal,
                'moneda' => $this->moneda,
                'tipo' => $this->tipo,
            ]);
        }
        
    }
    public function drawings()
    {
        if (session('company')->logo != null && file_exists(storage_path('app/public/empresas/'.session('company')->idEmpresa.'/logo.png'))	) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo empresa');
            $drawing->setPath(storage_path('app/public/empresas/'.session('company')->idEmpresa.'/logo.png'));
            $drawing->setCoordinates('A1');
            $drawing->setHeight(60);
            $drawing->setResizeProportional(true);

            return $drawing;
        }
        else {
            return [];
        }
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
        
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Or return the styles array
        return [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_WHITE],
            ],
        ];
    }
}
