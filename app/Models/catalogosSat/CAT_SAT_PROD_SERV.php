<?php

namespace App\Models\catalogosSat;

use App\Models\config\CONF_CONCEPTOS_MODULOS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_PROD_SERV extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_PROD_SERV";

    public function ConceptosModulosRelation()
    {
        return $this->hasOne(CONF_CONCEPTOS_MODULOS::class,'claveProdServ','c_ClaveProdServ');
    }

    public function getProdServ()
    {
        return $this->all();
    }
}
