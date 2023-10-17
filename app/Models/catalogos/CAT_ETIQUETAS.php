<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CAT_ETIQUETAS extends Model
{
    use HasFactory;

    protected $table = 'CAT_ETIQUETAS';
    protected $primaryKey = 'idEtiqueta';
    protected $guarded = ['idEtiqueta'];

    public $timestamps = false;

    public $attributes_map = [
        'idEtiqueta' =>'Identificador del registro',
        'user_id' =>'Clave del usuario',
        'nombre' =>'Nombre de la etiqueta',
        'estatus' =>'Estatus de la etiqueta',
        'fechaAlta' =>'Fecha de creación de la etiqueta',
        'fechaCambio' =>'Fecha de actualización de la etiqueta',

      ];

    public function getEtiquetas() {
        $etiquetas = $this->where('estatus',1)->pluck('nombre','idEtiqueta')->toArray();
        return $etiquetas;
    }
    
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

    public function scopewhereetiquetaKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewhereetiquetaName($query, $name){
        if(!is_null($name)){
           return $query->where('nombre', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereetiquetaStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', (int)$status);
        }
        return $query;
    }


}
