<?php

namespace App\Exports;
use App\Models\proc\comercial\Ventas;
use App\Models\config\CONF_MONEDA;
use App\Models\utils\PROC_FLUJO;
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

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class ReporteVentasExport implements FromView, ShouldAutoSize, WithStyles, WithEvents, WithDrawings, WithDefaultStyles
{
    public $venta;
    public $proyecto;
    public $fecha;
    public $fechaInicio;
    public $fechaFinal;
    public $moneda;

    public function __construct($ventas,$proyecto,$fecha,$fechaInicio,$fechaFinal,$moneda)
    {
        $ventas = new Ventas();
        $this->venta = $ventas;      
        $this->proyecto = $proyecto;
        $this->fecha = $fecha;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->moneda = $moneda;
    }
    public function view() : view
    {
        if ($this->moneda != 'Todos') {
            $monedas = new CONF_MONEDA();
            $moneda = $monedas->where('idMoneda', $this->moneda)->first()->clave;
        } else { $moneda = 'Todos'; }

        $ventas = $this->venta->where('idEmpresa', '=', session('company')->idEmpresa)
        ->where('movimiento', '=', 'Contrato')
        ->where('estatus', '=', 'CONCLUIDO')
        ->whereVentasProyecto($this->proyecto)
        ->whereVentasFecha($this->fecha)
        ->whereVentasMoneda($this->moneda)
        ->get();
        $flujo = new PROC_FLUJO();

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
        
        $reportes_filtro = $ventas;
        // Obtenemos el nombre de la acción
        $validar = array_keys($_REQUEST);
        
        if ($validar[6] == 'action1') {
            return view('exports.reportesInfoModulo',[
                'articulos' => $reportes_filtro,
                'fecha' => $this->fecha,
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $this->fechaFinal,
                'moneda' => $moneda,
            ]);
        } 
        else if ($validar[6] == 'action2') {
            return view('exports.reportesVentaModulo',[
                'articulos' => $reportes_filtro,
                'fecha' => $this->fecha,
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $this->fechaFinal,
                'moneda' => $moneda,
            ]);
        }
        
    }
    // Agregar logo de empresa a excel
    public function drawings()
    {
        if (session('company')->logo != null && file_exists(storage_path('app/public/empresas/'.session('company')->idEmpresa.'/logo.png'))	) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo empresa');
            $drawing->setPath(storage_path('app/public/empresas/'.session('company')->idEmpresa.'/logo.png'));
            $drawing->setCoordinates('A1');
            $drawing->setHeight(65);
            $drawing->setResizeProportional(true);

            return $drawing;
        }
        else {
            return [];
        }
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                // Filtro ultima celda
                $i = 8;
                $ii = $i-1;
                $lastCellAddress = null;
                
                $lastCellAddress2 = $event->sheet->getHighestColumn().$ii;
                $ventas = $this->venta->where('idEmpresa', '=', session('company')->idEmpresa)
                ->where('movimiento', '=', 'Contrato')
                ->where('estatus', '=', 'CONCLUIDO')
                ->whereVentasProyecto($this->proyecto)
                ->whereVentasFecha($this->fecha)
                ->whereVentasMoneda($this->moneda)
                ->get();
                $reportes_filtro = $ventas;

                foreach ($reportes_filtro as $value) {
                    $lastCellAddress = $event->sheet->getHighestColumn().$i;
                    $lastCellAddress2 = $event->sheet->getHighestColumn().$ii;
                    $event->sheet->getDelegate()->getStyle('A'.$i.':'.$lastCellAddress)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $event->sheet->getStyle('A'.$i.':'.$lastCellAddress)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                    $event->sheet->getStyle('A'.$i.':'.$lastCellAddress)->getFill()->applyFromArray([
                        'fillType' => 'solid','rotation' => 0, 'color' => [
                            'rgb' => 'EBEBEB'
                        ],
                    ]);
                    $event->sheet->setPrintGridlines(false);
                    
                    $i++;
                }
                $event->sheet->getDelegate()->getStyle('A'.$ii.':'.$lastCellAddress2)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A'.$ii.':'.$lastCellAddress2)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0070C0');
                $event->sheet->getDelegate()->getStyle('A'.$ii.':'.$lastCellAddress2)
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');
                $event->sheet->getDelegate()->getRowDimension('7')->setRowHeight(25);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
        
    }
    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_WHITE],
            ],
        ];
    }
}
