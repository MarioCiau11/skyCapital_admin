<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_PROYECTOS_IMG extends Model
{
    use HasFactory;

    protected $table = 'CAT_PROYECTOS_IMG';

    protected $primaryKey = 'idImgProyecto';

    protected $guarded = ['idImgProyecto'];

    public $timestamps = false;

    public $attributes_map = [
        'idImgProyecto' => 'Identificador del registro',
        'idProyecto' => 'Identificador del proyecto',
        'file' => 'Nombre de la imagen',
        'path' => 'Ruta de la imagen',
        'fechaAlta' => 'Fecha de creación del registro',
        'fechaCambio' => 'Fecha de actualización del registro',
    ];
}
