<?php

namespace App\Models\proc\comercial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCoprops extends Model
{
    use HasFactory;
    protected $table = 'proc_ventas_coprop';
    protected $primaryKey = 'idCoprop';
    public $timestamps = false;

    public function getCoprops($idVenta) {
        return $this->where('idVenta', $idVenta)->pluck('coprop')->toArray();
    }
    public function getVentas() {
        return $this->belongsToMany(Ventas::class,'idVenta','idVenta');
    }
}
