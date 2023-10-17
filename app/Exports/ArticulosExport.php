<?php

namespace App\Exports;

use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\config\CONF_CONCEPTOS_MODULOS;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticulosExport implements FromView, ShouldAutoSize, WithStyles
{
    public $nombre;
    public $status;
    public $categoria;
    public $grupo;

    public function __construct($nombre,$categoria,$grupo,$status)
    {
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->grupo = $grupo;
        $this->status = $status;
    }

    public function view() : view
    {
        $articulo_collection_filtro = CAT_ARTICULOS::whereArticuloNombre($this->nombre)
        ->whereArticuloGrupo($this->grupo)
        ->whereArticuloCategoria($this->categoria)
        ->whereArticuloEstatus($this->status)
        ->get();

        return view('exports.articulos',[
            'Articulos' => $articulo_collection_filtro
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
