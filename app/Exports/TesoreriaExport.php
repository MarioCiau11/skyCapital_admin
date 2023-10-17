<?php

namespace App\Exports;
use App\Models\proc\finanzas\Tesoreria;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;

class TesoreriaExport implements FromView, ShouldAutoSize, WithStyles
{
    public $folio;
    public $movimiento;
    public $cuentad;
    public $status;
    public $fecha;
    public $usuario;
    public $sucursal; 
    public $moneda;



    public function __construct($folio,$movimiento,$cuentad,$status,$fecha,$usuario,$sucursal, $moneda)
    {
        $this->folio = $folio;
        $this->movimiento = $movimiento;
        $this->cuentad = $cuentad;
        $this->status = $status;
        $this->fecha = $fecha;
        $this->usuario = $usuario;
        $this->sucursal = $sucursal;
        $this->moneda = $moneda;

    }
    public function view() : view
    {
        $tesoreria_filtro = Tesoreria::whereTesoreriaFolio($this->folio)
        ->whereTesoreriaMovimiento($this->movimiento)
        ->whereTesoreriaCuentaD($this->cuentad)
        ->whereTesoreriaEstatus($this->status)
        ->whereTesoreriaFecha($this->fecha)
        ->whereTesoreriaUsuario($this->usuario)
        ->whereTesoreriaMoneda($this->moneda)
        ->whereTesoreriaSucursal($this->sucursal)
        ->get();

        return view('exports.tesoreria',[
            'Tesoreria' => $tesoreria_filtro
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
