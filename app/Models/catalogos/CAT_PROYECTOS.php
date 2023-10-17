<?php

namespace App\Models\catalogos;

use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CAT_PROYECTOS extends Model
{
    use HasFactory;

    
    protected $table = 'CAT_PROYECTOS';
    protected $primaryKey = 'idProyecto';
    protected $guarded = ['idProyecto'];

    public $timestamps = false;

    public $attributes_map = [
        'idModulo' =>'Identificador del registro',
        'user_id' =>'Clave del proyecto',
        'nombre' =>'Nombre del proyecto',
        'estatus' =>'Estatus del proyecto',
        'fechaAlta' =>'Fecha de creación del proyecto',
        'fechaCambio' =>'Fecha de actualización del proyecto',

      ];

      
    public function getClaveNombre() {
        $claveNombre = array();
        $proyectos = $this->where('estatus',1)->get();
        foreach ($proyectos as $item =>$value) {
            $claveNombre[$value['clave']] = $value['clave']." - ".$value['nombre'];
        }
        return $claveNombre;
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

    public function scopewhereproyectoKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewhereproyectoName($query, $name){
        if(!is_null($name)){
           return $query->where('nombre', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereproyectoStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', (INT) $status);
        }
        return $query;
    }

    public function getCategoria()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class,'categoria','idCategoria');
    }
    public function getGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class,'grupo','idGrupo');
    }

    public function getProjects()
    {
        return $this->where('estatus', 1)->pluck('nombre', 'idProyecto');
    }
    function getmodulos() {

        return $this->belongsTo(CAT_MODULOS::class,'proyecto','idProyecto');
    }
}
