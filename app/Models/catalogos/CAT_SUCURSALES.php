<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SUCURSALES extends Model
{
    use HasFactory;

    protected $table = 'CAT_SUCURSALES';
    protected $primaryKey = 'idSucursal';
    protected $guarded = ['idSucursal'];

    public $timestamps = false;

    public $attributes_map = [
        'idSucursal' =>'Identificador del registro',
        'idEmpresa' =>'Clave de la empresa',
        'clave' =>'Clave de la sucursal',
        'nombre' =>'Nombre de la sucursal',
        'estatus' =>'Estatus de la sucursal',
        'direccion' =>'Dirección de la sucursal',
        'colonia' =>'Colonia de la sucursal',
        'codigoPostal' =>'Código postal de la sucursal',
        'ciudad' =>'Ciudad de la sucursa',
        'estado' =>'Estado de la sucursal',
        'pais' =>'País de la sucursal',

      ];

    public function empresas()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }
    public function getUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }

    public function scopewheresucursalKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewheresucursalName($query, $name){
        if(!is_null($name)){
           return $query->where('nombre', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewheresucursalStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', (int)$status);
        }
        return $query;
    }

    public function getSucursales()
    {
        return $this->where('estatus', 1)->get()->pluck('nombre', 'idSucursal');
    }

    public function getSucursal($idSucursal)
    {
        return $this->where('idSucursal', $idSucursal)->pluck('nombre', 'idSucursal');
    }

}
