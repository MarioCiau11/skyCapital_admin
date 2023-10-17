<?php

namespace App\Models\catalogosSat;

use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_CP extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_CP";

    public function getCP()
    {
        return $this->all();
    }

    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class,'idEmpresa','c_CodigoPostal');
    }
}
