<?php

namespace App\Models\config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_USUARIO_EMPRESAS extends Model
{
    use HasFactory;

    protected $table = 'CONF_USUARIO_EMPRESAS';
    protected $primaryKey = 'idUsuarioEmp';
    protected $guarded = ['idUsuarioEmp'];
    protected $touches = ['users'];

    public $attributes_map = [
        'idUsuarioEmp' =>'Identificador del registro',
        'user_id' =>'Identificador del usuario',
        'idEmpresa' =>'Clave de la empresa',
      ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }

    public function empresas()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa')->select('idEmpresa', 'nombreEmpresa');
    }
}
