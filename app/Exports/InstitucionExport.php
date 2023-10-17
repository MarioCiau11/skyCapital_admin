<?php

namespace App\Exports;

use App\Models\catalogos\CAT_INSTITUCIONES_FINANCIERAS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstitucionExport implements FromView, ShouldAutoSize, WithStyles
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
        
        $institucion_filtro = CAT_INSTITUCIONES_FINANCIERAS::whereInstitucionKey($this->key)->whereInstitucionName($this->name)->whereInstitucionStatus($this->status)->get();

        // dd($institucion_filtro);
       
        return view('exports.instituciones', [
            'instituciones' => $institucion_filtro
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
