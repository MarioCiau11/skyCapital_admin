<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_PROYECTOS_ARCHIVOS extends Model
{
    use HasFactory;

    protected $table = 'CAT_PROYECTOS_ARCHIVOS';

    protected $primaryKey = 'idFileProyecto';

    protected $guarded = ['idFileProyecto'];

    public $timestamps = false;

    public $attributes_map = [
        'idFileProyecto' => 'Identificador del registro',
        'idProyecto' => 'Identificador del proyecto',
        'file' => 'Nombre del archivo',
        'path' => 'Ruta del archivo',
        'fechaAlta' => 'Fecha de creación del registro',
        'fechaCambio' => 'Fecha de actualización del registro',
    ];
}
