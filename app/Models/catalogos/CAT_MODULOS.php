<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CAT_MODULOS extends Model
{
    use HasFactory;

    
    protected $table = 'CAT_MODULOS';
    protected $primaryKey = 'idModulo';
    protected $guarded = ['idModulo'];

    public $timestamps = false;

    public $attributes_map = [
        'idModulo' =>'Identificador del registro',
        'user_id' =>'Clave del usuario',
        'clave' => 'Clave del modulo',
        'nombre' =>'Nombre del modulo',
        'estatus' =>'Estatus del modulo',
        'fechaAlta' =>'Fecha de creación del modulo',
        'fechaCambio' =>'Fecha de actualización del modulo',

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

    public function scopewheremoduloKey($query, $key){
        if(!is_null($key)){
           return $query->where('clave', 'like', $key.'%');
        }
        return $query;
    }
    
       public function scopewheremoduloName($query, $name){
        if(!is_null($name)){
           return $query->where('descripcion', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewheremoduloStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }
            
            return $query->where('estatus', '=', $status);
        }
        return $query;
    }
    

    public function getBank()
    {
        return $this->hasOne('App\Models\catalogos\CAT_INSTITUCIONES_FINANCIERAS', 'idInstitucionf', 'banco');
    }

    public function getModuloByProject($id, $estatus)
    {
        // dd($estatus);
        if($estatus == 'SIN AFECTAR' || $estatus == 'POR CONFIRMAR'){
            return $this->where('proyecto', $id)->whereIn('estatus', ['Disponible', 'Apartado'])->get();
        }else{
            return $this->where('proyecto', $id)->get();
        }
    }

    public function getModuloByProject2($id)
    {

        return $this->where('proyecto', $id)->get();

    }

    function getModulo($id)
    {
        return $this->where('clave', $id)->first();
    }

    public function getModulos()
    {
        return $this->whereIn('estatus', ['Disponible', 'No disponible', 'Apartado', 'Vendido', 'Baja'])->get();
    }
    function getProyectos() {
        return $this->hasOne(CAT_PROYECTOS::class, 'idProyecto', 'proyecto');
    }
    
}
