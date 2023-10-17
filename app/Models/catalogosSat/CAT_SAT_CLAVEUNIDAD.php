<?php

namespace App\Models\catalogosSat;

use App\Models\config\CONF_UNIDADES;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_CLAVEUNIDAD extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_CLAVEUNIDAD";


    public function getClaveU()
    {
        return $this->all();
    }

    public function UnidadesRelation()
    {
        return $this->hasOne(CONF_UNIDADES::class,'claveSat','c_ClaveUnidad');
    }
}
