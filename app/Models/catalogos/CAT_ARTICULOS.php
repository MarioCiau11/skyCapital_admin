<?php

namespace App\Models\catalogos;

use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\config\CONF_UNIDADES;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CAT_ARTICULOS extends Model
{
    use HasFactory;
    protected $table = 'cat_articulos';
    protected $primaryKey = 'idArticulos';
    public $timestamps = false;
    public $columns = [
        0 => array('name' => ['Clave', 'default' => true]),
        1 => array('name' => ['Tipo', 'default' => true]),
        2 => array('name' => ['Estatus', 'default' => true]),
        3 => array('name' => ['Descripción', 'default' => true]),
        4 => array('name' => ['Unidad Venta', 'default' => true]),
        5 => array('name' => ['Categoría', 'default' => true]),
        6 => array('name' => ['IVA', 'default' => true]),
        7 => array('name' => ['Lista de Precio', 'default' => true]),
        8 => array('name' => ['Usuario que lo registró', 'default' => true]),
        9 => array('name' => ['Fecha de Alta', 'default' => true]),
        10 => array('name' => ['Última actualización', 'default' => true]),
        11 => array('name' => ['Fecha de Baja', 'default' => true]),
    ];
    public function getNextID()
    {
        $table = (new self())->getTable();
        $nextId = DB::select("SHOW TABLE STATUS LIKE '{$table}'")[0]->Auto_increment;
        return $nextId;
    }

    public function unidadesRelation()
    {
        return $this->belongsTo(CONF_UNIDADES::class, 'unidadVenta', 'idUnidades');
    }
    public function getCategoria()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class,'categoria','idCategoria');
    }
    public function getGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class,'grupo','idGrupo');
    }

    public function getUser()
    {
        return $this->BelongsTo(User::class,'user_id','user_id');
    }
    public function historicRelation()
    {
        return $this->hasMany(HISTORIC_ARTICULOS::class,'articulo','idArticulos');
    }

    public function scopewhereArticuloNombre($query, $nombre)
    {
        if (!is_null($nombre)) {
            return $query->where('descripcion', 'like', '%' . $nombre . '%');
        }
        return $query;
    }

    public function scopewhereArticuloCategoria($query, $categoria)
    {
        if (!is_null($categoria)) {
            if ($categoria == 'Todos') {
                return $query;
            }
            return $query->where('categoria', $categoria);
        }
        return $query;
    }

    public function scopewhereArticuloGrupo($query, $grupo)
    {
        if (!is_null($grupo)) {
            if ($grupo == 'Todos') {
                return $query;
            }
            return $query->where('grupo', $grupo);
        }

        return $query;
    }

    public function scopewhereArticuloEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }

    public function getArticulos()
    {
        return $this->where('cat_articulos.estatus', 1)->join('conf_unidades', 'cat_articulos.unidadVenta', '=', 'conf_unidades.idUnidades')->get();
    }

    public function getArticulo($id)
    {
        return $this->where('cat_articulos.clave', $id)->join('conf_unidades', 'cat_articulos.unidadVenta', '=', 'conf_unidades.idUnidades')->first();
    }

}