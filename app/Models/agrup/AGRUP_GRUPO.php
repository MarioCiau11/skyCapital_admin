<?php

namespace App\Models\agrup;

use App\Models\catalogos\CAT_AGENTES_VENTA;
use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_PROYECTOS;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AGRUP_GRUPO extends Model
{
    use HasFactory;

    protected $table = 'agrup_grupo';
    protected $primaryKey = 'idGrupo';
    public $timestamps = false;

    public function getGrupo($catalogo)
    {
        return $this->where('catalogo', $catalogo)->where('estatus',1)->pluck('nombre', 'idGrupo');

    }
    public function getGrupoNombre($catalogo)
    {
        return $this->where('catalogo', $catalogo)->where('estatus',1)->pluck('nombre', 'nombre');

    }

    public function relationAgentes()
    {
        return $this->hasOne(CAT_AGENTES_VENTA::class,'grupo','idGrupo');
    }
    public function relationArticulos()
    {
        return $this->hasOne(CAT_ARTICULOS::class,'grupo','idGrupo');
    }
    public function relationClientes()
    {
        return $this->hasOne(CAT_CLIENTES::class,'grupo','idgrupo');
    }

    public function relationProyectos()
    {
        return $this->hasOne(CAT_PROYECTOS::class,'grupo','idgrupo');
    }
}
