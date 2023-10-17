<?php

namespace App\Exports;

use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;

class CuentasDineroExport implements FromView, ShouldAutoSize, WithStyles
{
    public $clave;
    public $cuenta;
    public $status;

    public function __construct($clave,$cuenta,$status) {
        $this->clave = $clave;
        $this->cuenta = $cuenta;
        $this->status = $status;
    }

    public function view(): view {    
        $cuentas_filtro = CAT_CUENTAS_DINERO::whereCuentasClave($this->clave)
            ->whereCuentasCuenta($this->cuenta)
            ->whereCuentasEstatus($this->status)
            ->get();

        return view('exports.cuentasDinero',[
            'CuentasDinero' => $cuentas_filtro
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
