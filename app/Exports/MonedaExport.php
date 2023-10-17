<?php

namespace App\Exports;

use App\Models\config\CONF_MONEDA;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonedaExport implements FromView, ShouldAutoSize, WithStyles
{
    public $name;
    public $status;
    public $clave;

    public function __construct($name, $status,$clave)
    {
        $this->name = $name;
        $this->status = $status;
        $this->clave = $clave;
    }

    public function view(): View
    {

        $moneda_collection_filtro =  CONF_MONEDA::whereMonedaName($this->name)
        ->whereStatus($this->status)
        ->whereClave($this->clave)
        ->get();

        return view('exports.monedas', [
            'Monedas' => $moneda_collection_filtro
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
