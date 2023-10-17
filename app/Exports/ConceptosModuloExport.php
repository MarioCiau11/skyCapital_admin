<?php

namespace App\Exports;

use App\Models\config\CONF_CONCEPTOS_MODULOS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConceptosModuloExport implements  FromView, ShouldAutoSize, WithStyles
{
    public $name;

    public $status;
    public function __construct($name,$status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    public function view() : View
    {
        $conceptos_collection_filtro = CONF_CONCEPTOS_MODULOS::whereConceptoName($this->name)
        ->whereConceptoEstatus($this->status)
        ->get();
        
        return view('exports.conceptoModulo',[
            'conceptosMod' => $conceptos_collection_filtro
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
