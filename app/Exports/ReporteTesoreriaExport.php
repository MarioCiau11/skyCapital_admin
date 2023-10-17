<?php

namespace App\Exports;

use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_MONEDA;
use App\Models\UTILS\PROC_AUXILIAR;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ReporteTesoreriaExport implements FromView, ShouldAutoSize, WithStyles, WithDrawings,WithColumnWidths
{
    public $idCuenta;
    public $fechaInicio;
    public $fechaFinal;
    public $idMoneda;
    public $fecha;
    public $movimiento;
    public $reporte;

    public function __construct($cuenta, $fechaInicio, $fechaFinal, $moneda, $fecha, $movimiento, $reporte)
    {
        $this->idCuenta = $cuenta;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->idMoneda = $moneda;
        $this->fecha = $fecha;
        $this->movimiento = $movimiento;
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        if ($this->idMoneda != 'Todos') {
            $monedas = new CONF_MONEDA();
            $moneda = $monedas->where('idMoneda', $this->idMoneda)->first()->clave;
        } else {
            $moneda = 'Todos';
        }

        if ($this->idCuenta != 'Todos') {
            $cuentaDinero = new CAT_CUENTAS_DINERO();
            $cuenta = $cuentaDinero->where('idCuentasDinero', $this->idCuenta)->first()->clave;
        } else {
            $cuenta = 'Todos';
        }
        //validamos que reporte deseamos exportar
        if ($this->reporte == 'CONCENTRADO') {
            $reporte = $this->filtro($cuenta,$this->fecha,$moneda,$this->movimiento);
            $reporteSumado = $this->arregloDatosConcentrado($reporte);

            return view('exports.reportesEstadoCuentaTesoreria', [
                'reporte_filtro' => $reporteSumado,
                'moneda' => $moneda,
                'reporte' => $this->reporte,
            ]);
        }
        elseif('DESGLOSADO'){
            $reporte = $this->filtro($cuenta,$this->fecha,$moneda,$this->movimiento);
            $datosDesglosados = $this->arregloDatosDesglosado($reporte);
            return view('exports.reportesEstadoCuentaTesoreria', [
                'reporte_filtro' => $datosDesglosados,
                'moneda' => $moneda,
                'reporte' => $this->reporte,
            ]);
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la clase "encabezados"
            '6' => ['font' => ['size' => 15, 'bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],

            // Estilo para la clase "subencabezado"
            '3:6' => ['font' => ['size' => 12], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],

            // Estilo para la clase "encabezadoTabla"
            '8' => [
                'font' => ['size' => 12], 
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT], 
                'width' => 120,
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ],

            // Estilo para el rango A1:G6 (fondo blanco)
            'A1:G6' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FFFFFF', // Color blanco en formato RGB
                    ],
                ],
            ],
        ];
        
    }
    public function columnWidths():array{
        if ($this->reporte == 'CONCENTRADO') {
            return[
                'A' => 20,
                'B' => 25,
                'C' => 20,
                'D' => 20,
                'E' => 20,
                'F' => 20,
                'G' => 20,
            ];
        }else{
            return[
                'A' => 20,
                'B' => 25,
                'C' => 25,
                'D' => 30,
                'E' => 20,
                'F' => 20,
                'G' => 20,
            ];
        }
        
    }
    public function drawings()
    {
        if (session('company')->logo != null && file_exists(storage_path('app/public/empresas/' . session('company')->idEmpresa . '/logo.png'))) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo empresa');
            $drawing->setPath(storage_path('app/public/empresas/' . session('company')->idEmpresa . '/logo.png'));
            $drawing->setCoordinates('A1');
            $drawing->setHeight(65);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);
            $drawing->setResizeProportional(true);

            return $drawing;
        } else {
            return [];
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
