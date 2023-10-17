<?php

namespace App\Exports;

use App\Models\config\CONF_FORMAS_PAGO;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormasPagoExport implements FromView, ShouldAutoSize, WithStyles
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

        $formas_collection_filtro =  CONF_FORMAS_PAGO::whereFormaName($this->name)
        ->whereFormaStatus($this->status)
        ->get();

        return view('exports.formasPago', [
            'formasPago' => $formas_collection_filtro
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
