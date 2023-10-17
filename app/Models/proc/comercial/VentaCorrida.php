<?php

namespace App\Models\proc\comercial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCorrida extends Model
{
    use HasFactory;

    protected $table = 'proc_ventas_corrida';
    protected $primaryKey = 'idCorrida';
    public $timestamps = false;

    function getCorrida($idVenta){
        $corrida = VentaCorrida::where('idVenta', $idVenta)->first();
        if ($corrida == null) {
            return null;
        }
        return $corrida;
    }
}
