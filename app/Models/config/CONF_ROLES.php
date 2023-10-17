<?php

namespace App\Models\config;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_ROLES extends Model
{
    use HasFactory;

    protected $table = 'roles';
    public $autoincrement = true;

    protected $fillable = [
        'name',
        'descript',
        'status',
        'identifier',
    ];

    public function getRole($id)
    {
        return $this->where('identifier', $id);
    }

    public function getRoles()
    {
        return $this::where('status', 1)->get();
    }

    public function selectRoles()
    {
        $array = array();
        $roles = $this->getRoles();

        foreach ($roles as $key => $rol) {
            $array[$rol->identifier] = $rol->identifier;
        }

        return $array;
    }

    public function selectRolesStatus()
    {
        $array = array();
        $roles = $this->getRoles()->where('status', 1);

        foreach ($roles as $key => $rol) {
            $array[$rol->identifier] = $rol->identifier;
        }

        return $array;
    }

    public function scopewhereRolName($query, $name){
        if(!is_null($name)){
           return $query->where('name', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereRolStatus($query, $status){
        if(!is_null($status)){
            if($status === 'Todos'){
                return $query;
            }
            return $query->where('status', '=', (int) $status);
        }
        return $query;
    }

     public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }
}
