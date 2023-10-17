<?php

namespace App\Exports;

use App\Models\config\CONF_UNIDADES;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnidadesExport implements FromView, ShouldAutoSize, WithStyles
{
    public $unidad;
    public $status;

    public function __construct($unidad,$status)
    {
        $this->unidad = $unidad;
        $this->status = $status;
    }
    
    public function view(): View
    {
        $unidades_Collection_filtro = CONF_UNIDADES::whereUnidad($this->unidad)
        ->whereUnidadStatus($this->status)
        ->get();
        return view('exports.unidades',[
            'Unidades' => $unidades_Collection_filtro
        ]);
    }

    public function styles(Worksheet $sheet)    
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
