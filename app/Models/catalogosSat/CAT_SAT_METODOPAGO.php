<?php

namespace App\Models\catalogosSat;

use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\config\CONF_FORMAS_PAGO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_METODOPAGO extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_METODOPAGO";

    public function getMetodoPago()
    {
        return $this->all();
    }

    public function condicionesCredRelation()
    {
        return $this->hasOne(CONF_CONDICIONES_CRED::class,'metodoPago','c_Moneda');
    }
}
