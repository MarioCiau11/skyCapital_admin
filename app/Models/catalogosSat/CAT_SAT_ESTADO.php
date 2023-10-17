<?php

namespace App\Models\catalogosSat;

use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_SAT_ESTADO extends Model
{
    use HasFactory;

    protected $table = "CAT_SAT_ESTADO";

    public function empresaRelation()
    {
        return $this->hasOne(CAT_EMPRESAS::class, 'idEmpresa', 'c_Estado');
    }

    public function getEstados()
    {
        $estados = CAT_SAT_ESTADO::all()->pluck('nombreEstado','c_Estado');
        return $estados;
    }
}