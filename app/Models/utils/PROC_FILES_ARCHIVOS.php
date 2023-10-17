<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PROC_FILES_ARCHIVOS extends Model
{
    use HasFactory;

    protected $table = 'PROC_FILES_ARCHIVOS';

    protected $primaryKey = 'idFile';

    protected $guarded = ['idFile'];

    public $timestamps = false;

    public $attributes_map = [
        'idFile' => 'Identificador del registro',
        'idProceso' => 'Identificador del proceso',
        'proceso' => 'Nombre del proceso',
        'file' => 'Nombre del archivo',
        'path' => 'Ruta del archivo',
        'fechaAlta' => 'Fecha de creación del registro',
        'fechaCambio' => 'Fecha de actualización del registro',
    ];

    public function getTipo($tipo, $id)
    {
        return $this->where('idProceso', $id)->where('proceso', $tipo)->get();
    }
}
