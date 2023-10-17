<?php

namespace App\Exports;
use App\Models\catalogos\CAT_CLIENTES;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;

class ClientesExport implements FromView, ShouldAutoSize, WithStyles
{
    public $clave;
    public $nombre;
    public $razonSocial;
    public $categoria;
    public $grupo;
    public $status;


    public function __construct($clave,$nombre,$razonSocial,$categoria,$grupo,$status)
    {
        $this->clave = $clave;
        $this->nombre = $nombre;
        $this->razonSocial = $razonSocial;
        $this->categoria = $categoria;
        $this->grupo = $grupo;
        $this->status = $status;
    }

    public function view() : view
    {
        $cliente_filtro = CAT_CLIENTES::whereClientesClave($this->clave)
        ->whereClientesNombre($this->nombre)
        ->whereClientesRazon($this->razonSocial)
        ->whereClientesCategoria($this->categoria)
        ->whereClientesGrupo($this->grupo)
        ->whereClientesEstatus($this->status)
        ->get();

        return view('exports.clientes',[
            'Clientes' => $cliente_filtro
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
