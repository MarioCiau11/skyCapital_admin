<?php

namespace App\Models\catalogosSat;

use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\config\CONF_MONEDA;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_MONEDA extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_MONEDA";



    public function getMonedas()
    {
        return $this->all();
    }

    public function FormasPagoRelacion()
    {
        return $this->hasOne(CONF_FORMAS_PAGO::class,'monedaSat','c_Moneda');
    }
    public function MonedasRelation()
    {
        return $this->hasOne(CONF_MONEDA::class,'claveSat','c_Moneda');
    }
}
