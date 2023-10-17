<?php

namespace App\Models\config;

use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_UNIDADES extends Model
{
    use HasFactory;

    protected $table = 'conf_unidades';
    protected $primaryKey = 'idUnidades';
    public $timestamps = false;

    public $columns = [
        0 => array('name' => ['Unidad', 'default' => true]),
        1 => array('name', ['Decimal válida', 'default' => true]),
        2 => array('name', ['Estatus', 'default' => true]),
        3 => array('name', ['Usuario que lo registró' => true]),
        4 => array('name', ['Fecha de creación' => false]),
        5 => array('name', ['Fecha de actualización' => false]),
        6 => array('name', ['Fecha de baja' => false])

    ];

    public function scopewhereUnidad($query, $unidad)
    {
        if (!is_null($unidad)) {
            return $query->where('unidad', $unidad);
        }
        return $query;
    }

    public function scopewhereUnidadStatus($query, $status)
    {
        if (!is_null($status)) {
            if ($status == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $status);
        }
        return $query;
    }
    public function userRelation()
    {
        return $this->BelongsTo(User::class, 'user_id', 'user_id');
    }
    public function articulosRelation()
    {
        return $this->hasOne(CAT_ARTICULOS::class, 'unidadVenta', 'idUnidades');
    }
    public function getUnidades()
    {
        return $this->pluck('unidad', 'idUnidades');
    }
}