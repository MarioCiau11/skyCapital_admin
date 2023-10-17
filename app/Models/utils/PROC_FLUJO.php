<?php

namespace App\Models\utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PROC_FLUJO extends Model
{
    use HasFactory;

    protected $table = 'PROC_FLUJO';
    protected $primaryKey = 'idFlujo';
    protected $guarded = ['idFlujo'];
    public $timestamps = false;
    public $attributes_map = [
        'idFlujo' => 'Identificador del registro',
        'idEmpresa' => 'Identificador de la empresa',
        'idSucursal' => 'Identificador de la sucursal',
        'origenModulo' => 'Módulo de origen',
        'origenId' => 'Identificador del registro de origen',
        'origenMovimiento' => 'Movimiento de origen',
        'origenFolio' => 'Folio de origen',
        'destinoModulo' => 'Módulo de destino',
        'destinoId' => 'Identificador del registro de destino',
        'destinoMovimiento' => 'Movimiento de destino',
        'destinoFolio' => 'Folio de destino',
        'cancelado' => 'Cancelado',
        'fechaEmision' => 'Fecha de creación del registro',
    ];

    public function scopewhereEmpresa($query,$idEmpresa){
        if ($idEmpresa != null) {
            return $query->where('idEmpresa','=',$idEmpresa);
        }
        return $query;
    }

    public function scopewhereSucursal($query,$idSucursal){
        if ($idSucursal != null) {
            return $query->where('idSucursal','=',$idSucursal);
        }
        return $query;
    }

    public function scopewhereDestinoModulo($query,$modulo){
        if ($modulo != null) {
            return $query->where('destinoModulo','=',$modulo);
        }
        return $query;
    }
    public function scopewhereOrigenModulo($query,$modulo){
        if ($modulo != null) {
            return $query->where('origenModulo','=',$modulo);
        }
        return $query;
    }

    public function scopewhereOrigenId($query,$id){
        if ($id != null) {
            return $query->where('origenId','=',$id);
        }
        return $query;
    }
    public function scopewhereDestinoId($query,$id) {
        if ($id != null) {
            return $query->where('destinoId','=',$id);
        }
        return $query;
    }

    public function getMovientosPosteriores($idEmpresa,$idSucursal,$modulo,$id)
    {
        return $this->where('idEmpresa','=',$idEmpresa)->where('idSucursal','=',$idSucursal)->where('origenModulo','=',$modulo)->where('origenId','=',$id)->get();
    }

    public function getMovientoPosterior($idEmpresa,$idSucursal,$modulo,$id)
    {
        return $this->where('idEmpresa','=',$idEmpresa)->where('idSucursal','=',$idSucursal)->where('origenModulo','=',$modulo)->where('origenId','=',$id)->first();
    }

    public function getMovimientoAnterior($idEmpresa,$idSucursal,$modulo,$destino)
    {
        return $this->where('idEmpresa','=',$idEmpresa)->where('idSucursal','=',$idSucursal)->where('origenModulo','=',$modulo)->where('destinoMovimiento','=',$destino)->first();
    }
}
