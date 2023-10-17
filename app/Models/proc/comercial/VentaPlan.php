<?php

namespace App\Models\proc\comercial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaPlan extends Model
{
    use HasFactory;
    protected $table = 'proc_ventas_plan';
    protected $primaryKey = 'idPlan';
    public $timestamps = false;

    function getPlan($idVenta){
        $plan = VentaPlan::where('idVenta', $idVenta)->first();
        if ($plan == null) {
            return null;
        }
        return $plan;
    }
}
