<?php

namespace App\Models\agrup;

use App\Models\catalogos\CAT_AGENTES_VENTA;
use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_PROYECTOS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AGRUP_CATEGORIA extends Model
{
    use HasFactory;
    protected $table = 'agrup_categoria';
    protected $primaryKey = 'idCategoria';
    public $timestamps = false;

    public function getCategoria($catalogo)
    {
        return $this->where('catalogo',$catalogo)->where('estatus',1)->pluck('nombre','idCategoria');
    }
    public function getCategoriaNombre($catalogo)
    {
        return $this->where('catalogo',$catalogo)->where('estatus',1)->pluck('nombre', 'nombre');
    }

    public function relationAgentes()
    {
        return $this->hasOne(CAT_AGENTES_VENTA::class,'categoria','idCategoria');
    }
    public function relationArticulos()
    {
        return $this->hasOne(CAT_ARTICULOS::class,'categoria','idCategoria');
    }
    public function relationClientes()
    {
        return $this->hasOne(CAT_CLIENTES::class,'categoria','idCategoria');
    }
    public function relationProyectos()
    {
        return $this->hasOne(CAT_PROYECTOS::class,'categoria','idCategoria');
    }
}
