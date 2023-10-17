<?php

namespace App\Exports;

use App\Models\catalogos\CAT_AGENTES_VENTA;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgentesExport implements FromView, ShouldAutoSize, WithStyles
{
    public $name;
    public $status;
    public $clave;

    public function __construct($name,$status,$clave)
    {
        $this->name = $name;
        $this->status = $status;
        $this->clave = $clave;
    }

    public function view() : view
    {
        $agentes_collection_filtro = CAT_AGENTES_VENTA::whereAgenteClave($this->clave)
        ->whereAgenteEstatus($this->status)
        ->whereAgenteName($this->name)
        ->get();

        return view('exports.agentesVenta',[
            'Agentes' => $agentes_collection_filtro
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
