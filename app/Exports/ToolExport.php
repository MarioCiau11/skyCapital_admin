<?php

namespace App\Exports;

use App\Models\proc\comercial\Ventas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ToolExport implements FromView, ShouldAutoSize, WithStyles
{
    private $cliente, $tipo, $movimiento, $fecha, $estatus, $sucursal;

    public function __construct($cliente, $tipo, $movimiento, $fecha, $estatus, $sucursal) {
        $this->cliente = $cliente;
        $this->tipo = $tipo;
        $this->movimiento = $movimiento;
        $this->fecha = $fecha;
        $this->estatus = $estatus;
        $this->sucursal = $sucursal;
    }
    public function view(): View
    {
         $movimientos_filtro = Ventas::whereVentasCliente($this->cliente)
                ->whereVentasMovimiento($this->movimiento)
                ->whereVentasFecha($this->fecha)
                ->whereVentasTipo($this->tipo)
                ->whereVentasEstatus($this->estatus)
                ->whereVentasSucursal($this->sucursal)
                    ->orderBy('idVenta', 'DESC')
                    ->get();
        // dd($movimientos_filtro); 
        return view('exports.tools.facturacionMasiva', [
            'movimientos_filtro' => $movimientos_filtro
        ]);
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
