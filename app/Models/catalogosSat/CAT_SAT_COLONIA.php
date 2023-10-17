<?php

namespace App\Models\catalogosSat;

use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_COLONIA extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_COLONIA";

    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class,'idEmpresa','c_Colonia');
    }

    public function getColonias()
    {
        return $this->all();
    }
}
