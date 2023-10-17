<?php

namespace App\Models\config;

use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CONF_MONEDA extends Model
{
    use HasFactory;

    protected $table = 'CONF_MONEDA';
    protected $primaryKey = 'idMoneda';
    protected $guarded = ['idMoneda'];
    public $timestamps = false;

    public $columns = [
        0 => array('name' => 'Clave','default' => true),
        1 => array('name' => 'Nombre','default' => true),
        2 => array('name' => 'Descripción','default' => true),
        3 => array('name' => 'Tipo de cambio','default' => true),
        4 => array('name' => 'Estatus','default' => true),
        5 => array('name' => 'Usuario','default' => true),
        6 => array('name' => 'Fecha de Alta','default' => true),
        7 => array('name' => 'Fecha de Baja','default' => true),
        8 => array('name' => 'Fecha de última modificación','default' => true),
    ];

    public function scopewhereMonedaName($query,$name){
        if (!is_null($name)) {
            return $query-> where('nombre','like','%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereClave($query,$clave)
    {
        if (!is_null($clave)) {
            return $query->where('clave','like','%'.$clave.'%');
        }
        return $query;
    }

    public function scopewhereStatus($query,$status)
    {
       if (!is_null($status)) {
            if ($status == 'Todos') {
                return $query;
            }
        return $query->where('estatus',$status);
       }
       return $query;
    }

    public function getMonedas()
    {
        $array = [];
        $monedas = $this->where('estatus', 1)->select('idMoneda', 'clave', 'nombre')->get()->toArray();
        foreach ($monedas as $key => $value) {
            $array[$value['idMoneda']] = $value['clave'];
        }
        return $array;
    }

    public function getMonedasClave()
    {
        $array = [];
        $monedas = $this->where('estatus', 1)->select('idMoneda', 'clave', 'nombre')->get()->toArray();
        foreach ($monedas as $key => $value) {
            $array[$value['clave']] = $value['clave'];
        }
        return $array;
    }

    public function userRelation()
    {
       return $this->BelongsTo(User::class,'user_id','user_id');
    }
    public function cuentasDineroRelation()
    {
        return $this->hasOne(CAT_CUENTAS_DINERO::class,'idMoneda','idMoneda');
    }

    public function getMoneda($id)
    {
        return $this->where('idMoneda', $id)->first();
    }
}
