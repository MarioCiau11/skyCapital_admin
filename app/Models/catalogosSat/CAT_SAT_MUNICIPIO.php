<?php

namespace App\Models\catalogosSat;

use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_MUNICIPIO extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_MUNICIPIO";

    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class,'ciudad','c_Municipio');
    }

    public function getMunicipios()
    {
        $municipios = CAT_SAT_MUNICIPIO::all()->pluck('descripcion','c_Municipio')->toArray();
        return $municipios;
    }
}