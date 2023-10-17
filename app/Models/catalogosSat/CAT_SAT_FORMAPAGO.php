<?php

namespace App\Models\catalogosSat;

use App\Models\config\CONF_FORMAS_PAGO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_FORMAPAGO extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_FORMAPAGO";

    public function getFormasPago( )
    {
        return $this->all();
    }
    public function formasPagoRelation ()
    {
        return $this->hasOne(CONF_FORMAS_PAGO::class,'formaPagosat','c_FormaPago');
    }
}
