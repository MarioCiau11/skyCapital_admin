<?php

namespace App\Models\config;

use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogosSat\CAT_SAT_METODOPAGO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;


class CONF_CONDICIONES_CRED extends Model
{
    use HasFactory;

    protected $table = 'conf_condiciones_cobro';

    protected $primaryKey = 'idCondicionesc';

    public $timestamps = false;

    public $columns = [
        0 => array('name' => 'Nombre','default' => true),
        1 => array('name' => 'Tipo de condicion','default' => true),
        2 => array('name' => 'Días para vencimiento','default' => true),
        3 => array('name' => 'Tipo de días','default' => true),
        4 => array('name' => 'Días hábiles','default' => true),
        5 => array('name' => 'Método de pago SAT','default' => true),
        6 => array('name' => 'Estatus','default' => true),
        7 => array('name' => 'Usuario','default' => true),
        8 => array('name' => 'Fecha de Alta','default' => true),
        9 => array('name' => 'Fecha de Baja','default' => true),
        10 => array('name' => 'Fecha de última modificación','default' => true),
    ];

    public function getCondicionies()
    {
        return $this->where('estatus', 1)->pluck('nombrecondicion','idCondicionesc');
    }
    public function scopewhereCondicionName($query,$nombreCondicion){
        if (!is_null($nombreCondicion)) {
            return $query->where('nombrecondicion' , 'LIKE' , '%'.$nombreCondicion.'%');
        }
        return $query;
    }

    public function scopewhereCondicionStatus($query,$status){
        if (!is_null($status)) {

            if ($status == 'Todos') {
                return $query;
            }
            return $query->where('estatus','=',$status);
        }
        return $query;
    }

    public function getMetodoPago() {
        return $this->belongsTo('App\Models\catalogosSat\CAT_SAT_METODOPAGO', 'metodoPago', 'c_MetodoPago');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    public function clientesRelation()
    {
        return $this->hasOne(CAT_CLIENTES::class,'condicionPago','idCondicionesc');
    }

    function getCondicion($id)
    {
        return $this->where('idCondicionesc', $id)->first();
    }
}


