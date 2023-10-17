<?php

namespace App\Exports;

use App\Models\config\CONF_CONDICIONES_CRED;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CondicionesCreditoExport implements FromView, ShouldAutoSize, WithStyles
{
    public $name;
    public $status;

    public function __construct($name, $status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    public function view(): View
    {

        $condiciones_collection_filtro =  CONF_CONDICIONES_CRED::whereCondicionName($this->name)
        ->whereCondicionStatus($this->status)
        ->get();

        return view('exports.condicionesCredito', [
            'condicionesCredito' => $condiciones_collection_filtro
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
