<?php

namespace App\Exports;

use App\Models\catalogos\CAT_MODULOS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ModulosExport implements FromView, ShouldAutoSize, WithStyles
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
        
        $modulo_filtro = CAT_MODULOS::whereModuloKey($this->key)->whereModuloName($this->name)->whereModuloStatus($this->status)->get();

        
        // dd($user_collection_filtro);
       
        return view('exports.modulos', [
            'modulos' => $modulo_filtro
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
