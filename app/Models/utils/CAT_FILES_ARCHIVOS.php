<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_FILES_ARCHIVOS extends Model
{
    use HasFactory;

    protected $table = 'CAT_FILES_ARCHIVOS';

    protected $primaryKey = 'idFile';

    protected $guarded = ['idFile'];

    public $timestamps = false;

    public $attributes_map = [
        'idFile' => 'Identificador del registro',
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
