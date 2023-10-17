<?php

namespace App\Models\catalogos;

use App\Models\catalogosSat\CAT_SAT_CP;
use App\Models\catalogosSat\CAT_SAT_ESTADO;
use App\Models\catalogosSat\CAT_SAT_MUNICIPIO;
use App\Models\catalogosSat\CAT_SAT_PAIS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class CAT_EMPRESAS extends Model
{
  use HasFactory;

  protected $table = 'CAT_EMPRESAS';
  protected $primaryKey = 'idEmpresa';
  protected $guarded = ['idEmpresa'];

  public $timestamps = false;

  public $attributes_map = [
    'idEmpresa' => 'Identificador de la empresa',
    'clave' => 'Clave de la empresa',
    'nombreEmpresa' => 'Nombre de la empresa',
    'nombreCorto' => 'Nombre corto de la empresa',
    'descripcion' => 'Descripción de la empresa',
    'RFC' => 'RFC de la empresa',
    'regimenFiscal' => 'Régimen fiscal',
    'registroPatronal' => 'Registro Patronal',
    'representante' => 'Representante',
    'direccion' => 'Dirección de la empresa',
    'ciudad' => 'Ciudad',
    'colonia' => 'Colonia',
    'estado' => 'Estado',
    'codigoPostal' => 'Código Postal',
    'pais' => ' País',
    'telefono1' => 'Teléfono de oficina',
    'telefono2' => 'Teléfono de casa',
    'correoElectronico' => 'Correo electrónico',
    'logo' => 'Logo de la empresa',
    'estatus' => 'Estatus',
    'rutaLlave' => 'Ruta de la llave',
    'rutaCertificado' => 'Ruta del certificado',
    'rutaDocumentos' => 'Ruta de los documentos',
    'password' => 'Contraseña',
    'fechaAlta' => 'Fecha de alta',
    'fechaCambio' => 'Fecha de última modificación',
    'fechaBaja' => 'Fecha de baja',
  ];

  public function getNextID()
  {
      $table = (new self())->getTable();
      $nextId = DB::select("SHOW TABLE STATUS LIKE '{$table}'")[0]->Auto_increment;
      return $nextId;
  }


  public function getCompanies()
  {
    return $this->select('idEmpresa', 'nombreEmpresa')->where('estatus',1)->pluck('nombreEmpresa', 'idEmpresa');
  }

  //relaciones
  public function users()
  {
    return $this->belongsToMany('App\Models\User', 'CONF_USUARIO_EMPRESAS', 'idEmpresa', 'user_id');
  }

  public function sucursales()
  {
    return $this->hasMany('App\Models\catalogos\CAT_SUCURSALES', 'idEmpresa', 'idEmpresa')->where('estatus', 1);
  }

  public function paisRelation()
  {
    return $this->belongsTo(CAT_SAT_PAIS::class, 'idEmpresa', 'c_Pais');
  }

  public function estadoRelation()
  {
    return $this->belongsTo(CAT_SAT_ESTADO::class,'idEmpresa','c_Estado');
  }
  public function municipioRelation()
  {
    return $this->belongsTo(CAT_SAT_MUNICIPIO::class,'idEmpresa','c_Municipio');
  }

  public function cpRelation()
  {
    return $this->belongsTo(CAT_SAT_CP::class,'idEmpresa','c_CodigoPostal');
  }

  public function getUser()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'user_id');
  }

  public function cuentasDineroRelation()
  {
    return $this->hasOne(CAT_CUENTAS_DINERO::class,'idEmpresa','idEmpresa');
  }

  //aqui terminan las relaciones

  public function scopewhereCompaniesKey($query, $key){
    if(!is_null($key)){
        return $query->where('clave', 'like', $key.'%');
    }
    return $query;
  }

  public function scopewhereCompaniesName($query, $name){
      if(!is_null($name)){
          return $query->where('nombreEmpresa', 'like', '%'.$name.'%');
      }
      return $query;
  }

  public function scopewhereCompaniesStatus($query, $status){
      if(!is_null($status)){

          if($status === 'Todos'){
              return $query;
          }

          return $query->where('estatus', '=', (int) $status);
      }
      return $query;
  }

  
}