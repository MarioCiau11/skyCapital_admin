<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_CLIENTES_ARCHIVOS extends Model
{
    use HasFactory;
    protected $table = 'cat_clientes_archivos';
    protected $primaryKey = 'idFileCliente';
    protected $guarded = ['idFileCliente'];
    public $timestamps = false;
    public $attributes_map = [
        'idFileCliente' => 'Identificador del registro',
        'idCliente' => 'Identificador del cliente',
        'file' => 'Nombre del archivo',
        'path' => 'Ruta del archivo',
        'fechaAlta' => 'Fecha de creación del registro',
        'fechaCambio' => 'Fecha de actualización del registro',
    ];
}