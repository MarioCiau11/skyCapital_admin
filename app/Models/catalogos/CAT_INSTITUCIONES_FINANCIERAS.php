<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_INSTITUCIONES_FINANCIERAS extends Model
{
    use HasFactory;

    protected $table = 'CAT_INSTITUCIONES_FIN';
    protected $primaryKey = 'idInstitucionf';
    public $timestamps = false;

    public function cuentasDineroRelation()
    {
        return $this->hasOne(CAT_CUENTAS_DINERO::class,'idInstitucion','idInstitucionf');
    }

    public function getUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }

    public function scopewhereinstitucionKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewhereinstitucionName($query, $name){
        if(!is_null($name)){
           return $query->where('nombre', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereinstitucionStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', (int)$status);
        }
        return $query;
    }

    public function getBanks()
    {
        return $this->where('estatus', 1)->pluck('clave', 'idInstitucionf');
    }

}
