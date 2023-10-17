<?php

namespace App\Models\config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_PARAMETROS_GENERALES extends Model
{
    use HasFactory;

    protected $table = 'CONF_PARAMETROS_GENERALES';

    protected $primaryKey = 'idParametro';

    public function getCompany()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }

    public function byCompany($id)
    {
        return $this->where('idEmpresa', $id);
    }

    public function monedaByCompany($id)
    {
        return $this->where('idEmpresa', $id)->join('CONF_MONEDA', 'CONF_MONEDA.idMoneda', '=', 'CONF_PARAMETROS_GENERALES.monedaDefault');
    }
}
