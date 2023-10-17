<?php

namespace App\Models\config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class CONF_FORMAS_PAGO extends Model
{
    use HasFactory;

    protected $table = 'conf_pago_cobro';
    protected $primaryKey = 'idFormaspc';
    public $timestamps = false;


    public $columns = [
        0 => array('name' => 'Nombre', 'default' => true),
        1 => array('name' => 'Clave', 'default' => true),
        2 => array('name' => 'Descripción', 'default' => true),
        // 3 => array('name' => 'Forma de pago SAT', 'default' => true),
        3 => array('name' => 'Moneda', 'default' => true),
        4 => array('name' => 'Estatus', 'default' => true),
        5 => array('name' => 'Usuario', 'default' => true),
        6 => array('name' => 'Fecha de alta', 'default' => true),
        7 => array('name' => 'Fecha de última modificación', 'default' => true),
        8 => array('name' => 'Fecha de baja', 'default' => true)
    ];

    public function scopewhereFormaName($query, $nombreForma)
    {
        if (!is_null($nombreForma)) {
            return $query->where('nombre', 'LIKE', '%'.$nombreForma.'%');
        }
        return $query;
    }

    public function scopewhereFormaStatus($query, $status)
    {
        if (!is_null($status)) {

            if ($status == 'Todos') {
                return $query;
            }
            return $query->where('estatus', '=', $status);
        }
        return $query;
    }
    public function userRelation()
    {
       return $this->BelongsTo(User::class,'user_id','user_id');
    }

    public function getFormasPago()
    {
        return $this->where('estatus', 1)->get()->pluck('clave', 'idFormaspc');
    }

    function getFormaPago($id)
    {
        return $this->where('idFormaspc', $id)->first();
    }
    function getMoneda(){
        return $this->hasOne(CONF_MONEDA::class, 'idMoneda', 'monedaSat');
    }
}
