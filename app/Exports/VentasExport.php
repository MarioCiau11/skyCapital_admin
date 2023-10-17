<?php

namespace App\Exports;
use App\Models\proc\comercial\Ventas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;

class VentasExport implements FromView, ShouldAutoSize, WithStyles
{
    public $folio;
    public $cliente;
    public $movimiento;
    public $status;
    public $fecha;
    public $usuario;
    public $sucursal; 
    public $moneda;



    public function __construct($folio,$cliente,$movimiento,$status,$fecha,$usuario,$sucursal,$moneda)
    {
        $this->folio = $folio;
        $this->cliente = $cliente;
        $this->movimiento = $movimiento;
        $this->status = $status;
        $this->fecha = $fecha;
        $this->usuario = $usuario;
        $this->sucursal = $sucursal;
        $this->moneda = $moneda;
    }
    public function view() : view
    {
        $venta_filtro = Ventas::whereVentasFolio($this->folio)
        ->whereVentasCliente($this->cliente)
        ->whereVentasMovimiento($this->movimiento)
        ->whereVentasEstatus($this->status)
        ->whereVentasFecha($this->fecha)
        ->whereVentasUsuario($this->usuario)
        ->whereVentasSucursal($this->sucursal)
        ->whereVentasMoneda($this->moneda)
        ->get();

        return view('exports.ventas',[
            'Ventas' => $venta_filtro
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
