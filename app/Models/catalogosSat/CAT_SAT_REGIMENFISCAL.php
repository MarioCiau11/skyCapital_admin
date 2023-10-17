<?php

namespace App\Models\catalogosSat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_REGIMENFISCAL extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_REGIMENFISCAL";

    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class,'regimenFiscal','c_RegimenFiscal');
    }

    public function getRegimenFiscal()
    {
        $regimen = CAT_SAT_REGIMENFISCAL::all()->pluck('descripcion','c_RegimenFiscal')->toArray();
        return $regimen;
    }
}
