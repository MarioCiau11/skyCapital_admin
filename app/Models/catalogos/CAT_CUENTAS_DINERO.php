<?php

namespace App\Models\catalogos;

use App\Models\catalogosSat\CAT_SAT_MONEDA;
use App\Models\config\CONF_MONEDA;
use App\Models\UTILS\PROC_SALDOS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_CUENTAS_DINERO extends Model
{
    use HasFactory;
    protected $table = 'cat_cuentas_dinero';
    protected $primaryKey = 'idCuentasDinero';
    public $timestamps = false;
    public $columns = [
        0 => array('name' => ['','default' => true])
    ];

    public function getInstituciones()
    {
        return $this->belongsTo(CAT_INSTITUCIONES_FINANCIERAS::class,'idInstitucionf','idInstitucionf');
    }

    public function getEmpresas()
    {
        return $this->belongsTo(CAT_EMPRESAS::class,'idEmpresa','idEmpresa');
    }
    public function getMonedas()
    {
        return $this->belongsTo(CONF_MONEDA::class,'idMoneda','idMoneda');
    }

    public function scopewhereCuentasClave($query,$clave){
        if (!is_null($clave)) {
            return $query->where('clave','like','%'.$clave.'%');
        }
        return $query;
    }
    public function scopewhereCuentasCuenta($query,$cuenta){
        if (!is_null($cuenta)) {
            return $query->where('noCuenta','like','%'.$cuenta.'%');
        }
        return $query;
    }
    public function scopewhereCuentasEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }

    public function getCuentas()
    {
        return $this->where('estatus', 1)->get()->pluck('clave', 'idCuentasDinero');
    }

    public function getCuentasEmpresa($id)
    {
        return $this->where('estatus', 1)->where('idEmpresa', $id)->get()->pluck('clave', 'idCuentasDinero');
    }

    public function getCuenta($id)
    {
        return $this->where('idCuentasDinero', $id)->first();
    }

    function getCuentaMoneda() {
        return $this->belongsTo(CONF_MONEDA::class,'idMoneda','idMoneda');
    }

    public function getSaldoCuenta($id, $idEmpresa, $idSucursal, $rama)
    {
        $saldo = PROC_SALDOS::where('cuenta', $id)->where('idEmpresa', $idEmpresa)->where('idSucursal', $idSucursal)->first();
        
        return $saldo;
    }
    
}
