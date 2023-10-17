<?php

namespace App\Models\proc\comercial;

use App\Models\config\CONF_FORMAS_PAGO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasCobro extends Model
{
    use HasFactory;

    
    protected $table = 'PROC_VENTAS_COBRO';
    protected $primaryKey = 'idCobro';
    public $timestamps = false;

    public function getCuenta()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CUENTAS_DINERO', 'idCuentasDinero', 'cuentaDinero');
    }

    public function getFormaPago()
    {
        return $this->hasOne(CONF_FORMAS_PAGO::class, 'idFormaspc', 'formaCobro');
    }
}
