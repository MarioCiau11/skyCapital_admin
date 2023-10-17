<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CAT_PROMOCIONES extends Model
{
    use HasFactory;
    
    protected $table = 'CAT_PROMOCIONES';
    protected $primaryKey = 'idPromocion';
    protected $guarded = ['idPromocion'];

    public $timestamps = false;

    public $attributes_map = [
        'idPromocion' =>'Identificador del registro',
        'user_id' =>'Clave del usuario',
        'nombre' =>'Nombre de la promoción',
        'estatus' =>'Estatus de la promoción',
        'fechaAlta' =>'Fecha de creación de la promoción',
        'fechaCambio' =>'Fecha de actualización de la promoción',

      ];

      
    public function getNextID()
    {
        $table = (new self())->getTable();
        $nextId = DB::select("SHOW TABLE STATUS LIKE '{$table}'")[0]->Auto_increment;
        return $nextId;
    }

    
    public function getUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
    }

    public function scopewherepromocionKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewherepromocionName($query, $name){
        if(!is_null($name)){
           return $query->where('nombre', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewherepromocionStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', (int)$status);
        }
        return $query;
    }

    public function getPromociones()
    {
        return $this->pluck('nombre','idPromocion');
    }
}
