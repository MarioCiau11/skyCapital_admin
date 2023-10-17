<?php

namespace App\Models\catalogosSat;

use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_PAIS extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_PAIS";


    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class,'idEmpresa','c_Pais');
    }
    public function getPaises()
    {
        $paises = CAT_SAT_PAIS::all()->pluck('descripcion','c_Pais')->toArray();
        return $paises;
    }

}
