<?php

namespace App\Exports;

use App\Models\catalogos\CAT_ETIQUETAS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EtiquetasExport implements FromView, ShouldAutoSize, WithStyles
{
    public $key;
    public $name;

    public $status;

    public function __construct($key, $name, $status)
    {
        $this->key = $key;
        $this->name = $name;
        $this->status = $status;
    }

    public function view(): View
    {
        // dd($this->name, $this->user, $this->rol, $this->status);
        
        $etiqueta_filtro = CAT_ETIQUETAS::whereEtiquetaKey($this->key)->whereEtiquetaName($this->name)->whereEtiquetaStatus($this->status)->get();
        
        // dd($user_collection_filtro);
       
        return view('exports.etiquetas', [
            'etiquetas' => $etiqueta_filtro
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
