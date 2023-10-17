<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_UNIDADES;
use App\Models\config\CONF_USUARIO_EMPRESAS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'username',
        'user_email',
        'password',
        'user_rol',
        'user_status',
    ];

    public $columns = [
        0 => array('name' => 'ID', 'default' => true),
        1 => array('name' => 'Nombre', 'default' => true),
        2 => array('name' => 'Usuario', 'default' => true),
        3 => array('name' => 'Correo eléctronico', 'default' => true),
        4 => array('name' => 'Rol', 'default' => true),
        5 => array('name' => 'Estatus', 'default' => true),
      ];

      public $empresas_data = [
        'old' => [],
        'new' => [],
      ];
    
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $auditExclude = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //FUNCION PARA VERIFICAR EL USUARIO DADO DE ALTA
    public function userVerificate($name)
    {
        $exist = false;
        $user = $this->where('username', $name)->where('user_status', 1)->first();

        if ($user) {
            $exist = true;
        }
        return $exist;
    }

    public function passVerificate($name, $pass)
    {
        $passBoolean = false;
        $user = $this->where('username', $name)->where('user_status', 1)->select('user_id', 'password')->first();

        if ($user) {
            if (password_verify($pass, $user->password)) {
                $passBoolean = true;
            }
        }
        return ['passBoolean' => $passBoolean, 'user' => $user];
    }

    public function getCompaniesUser($id)
    {
        $companies = CONF_USUARIO_EMPRESAS::where('user_id', $id)->select('idEmpresa')->get();
        return $companies;
    }

    public function user()
    {
        return $this->hasMany('App\Models\config\CONF_USUARIO_EMPRESAS', 'user_id', 'id');
    }

    public function getCompanyIdsAttribute()
    {
      return $this->company()->get()->pluck('idEmpresa')->toArray();
    }
  

    public function company()
    {
      return $this->belongsToMany('App\Models\catalogos\CAT_EMPRESAS', 'CONF_USUARIO_EMPRESAS', 'user_id', 'idEmpresa');
    }
  

    public function getUser($id)
    {
        return $this->where('user_id', $id)->first();
    }

    public function getUsers()
    {
        return $this->get();
    }

    public function getUsuario() 
    {
        return $this->where('user_status', 1)->get()->pluck('username', 'user_id');
    }
    public function search()
    {
        $users = $this->newQuery();
        $toFlash = [];

        if (request()->has('name')) {
            $users->where('user_name', 'like', '%' . request('name') . '%');
            $toFlash['name'] = request('name');
        }


    }

    public function scopewhereUserName($query, $name)
    {
        if(!is_null($name)){
            return $query->where('user_name', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopewhereUserNames($query, $user)
    {
        if(!is_null($user)){
            return $query->where('username', 'like', $user.'%');
        }
        return $query;
    }

    public function scopewhereUserRoles($query, $rol)
    {
        if(!is_null($rol)){
            return $query->where('user_rol', '=', $rol);
        }
        return $query;
    }

    public function scopewhereUserStatus($query, $status){
        if(!is_null($status)){

            if($status === 'Todos'){
                return $query;
            }

            return $query->where('user_status', '=', $status);
        }
        return $query;
    }

    // public function getCreatedAtAttribute($value){
    //     return Carbon::parse($value)->format('d-m-Y');
    // }

    // public function getUpdatedAtAttribute($value){
    //     return Carbon::parse($value)->format('d-m-Y');
    // }
    public function relationCondiciones()
    {
        return $this->hasOne(CONF_CONDICIONES_CRED::class,'user_id','user_id');
    }
    public function relationMoneda()
    {
        return $this->hasOne(CONF_MONEDA::class,'user_id','user_id');
    }
    public function reltionUnidades()
    {
        return $this->hasOne(CONF_UNIDADES::class,'user_id','user_id');
    }
    public function relationFormas()
    {
        return $this->hasOne(CONF_FORMAS_PAGO::class,'user_id','user_id');
    }
    public function relationArticulos()
    {
        return $this->hasOne(CAT_ARTICULOS::class,'user_id','user_id');
    }
    public function relationClientes()
    {
        return $this->hasOne(CAT_CLIENTES::class,'user_id','user_id');
    }

}
