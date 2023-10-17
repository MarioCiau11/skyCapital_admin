<?php

namespace App\Exports;
use App\Models\proc\finanzas\CxC;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;

class CxCExport implements FromView, ShouldAutoSize, WithStyles
{
    public $folio;
    public $cliente;
    public $movimiento;
    public $status;
    public $fecha;
    public $fechav;
    public $usuario;
    public $sucursal; 
    public $moneda;



    public function __construct($folio,$cliente,$movimiento,$status,$fecha,$fechav,$usuario,$sucursal,$moneda)
    {
        $this->folio = $folio;
        $this->cliente = $cliente;
        $this->movimiento = $movimiento;
        $this->status = $status;
        $this->fecha = $fecha;
        $this->fechav = $fechav;
        $this->usuario = $usuario;
        $this->sucursal = $sucursal;
        $this->moneda = $moneda;
    }
    public function view() : view
    {
        $cxc_filtro = CxC::whereCxCFolio($this->folio)
        ->whereCxCCliente($this->cliente)
        ->whereCxCMovimiento($this->movimiento)
        ->whereCxCEstatus($this->status)
        ->whereCxCFecha($this->fecha)
        ->whereCxCFechaV($this->fechav)
        ->whereCxCUsuario($this->usuario)
        ->whereCxCSucursal($this->sucursal)
        ->whereCxCMoneda($this->moneda)
        ->get();

        return view('exports.cxc',[
            'Cxc' => $cxc_filtro
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
