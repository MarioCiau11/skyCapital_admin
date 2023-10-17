<?php

namespace App\Models\config;

use App\Models\catalogosSat\CAT_SAT_PROD_SERV;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_CONCEPTOS_MODULOS extends Model
{
    use HasFactory;

    protected $table = 'conf_conceptos_modulos';
    protected $primaryKey = 'idConceptosm';
    public $timestamps = false;

    public $columns = [
        0 => array(),
        1 => array(),
        2 => array()
    ];

    public function Cat_ClaveProdRelation()
    {
        return $this->belongsTo(CAT_SAT_PROD_SERV::class,'claveProdServ','c_ClaveProdServ');
    }

    public function scopewhereConceptoName($query,$nombreConcepto)
    {
        if (!is_null($nombreConcepto)) {
            return $query->where('nombreconcepto',$nombreConcepto);
        }
        return $query;
    }
    public function scopewhereConceptoEstatus($query,$estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus =='Todos') {
                return $query;
            }
            return $query->where('estatus',$estatus);
        }
        return $query;
    }
}
