<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_FILES_IMAGENES extends Model
{
    use HasFactory;

    protected $table = 'CAT_FILES_IMAGENES';

    protected $primaryKey = 'idImg';

    protected $guarded = ['idImg'];

    public $timestamps = false;

    public $attributes_map = [
        'idImg' => 'Identificador del registro',
        'idCatalogo' => 'Identificador del catalogo',
        'catalogo' => 'Nombre del catalogo',
        'file' => 'Nombre del archivo',
        'path' => 'Ruta del archivo',
        'fechaAlta' => 'Fecha de creación del registro',
        'fechaCambio' => 'Fecha de actualización del registro',
    ];

    public function getTipo($tipo, $id)
    {
        return $this->where('idCatalogo', $id)->where('catalogo', $tipo)->get();
    }

}
