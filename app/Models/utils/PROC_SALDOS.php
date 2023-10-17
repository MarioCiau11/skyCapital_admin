<?php

namespace App\Models\UTILS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PROC_SALDOS extends Model
{
    use HasFactory;

    protected $table = 'PROC_SALDOS';
    protected $primaryKey = 'idSaldo';
    protected $guarded = ['idSaldo'];
    public $timestamps = false;
    public $attributes_map = [
        'idSaldo' => 'Identificador del registro',
        'idEmpresa' => 'Identificador de la empresa',
        'idSucursal' => 'Identificador de la sucursal',
        'rama' => 'Rama del registro',
        'moneda' => 'Moneda de origen',
        'tipoCambio' => 'Tipo de cambio de origen',
        'cuenta' => 'Cuenta de origen',
        'saldo' => 'Saldo inicial de origen',
        'fechaEmision' => 'Fecha de creación del registro', 

    ];

    public function getSaldos($id, $idEmpresa, $idSucursal, $rama, $moneda)
    {
        $saldo = PROC_SALDOS::where('cuenta', $id)->where('idEmpresa', $idEmpresa)->where('idSucursal', $idSucursal)->where('rama', $rama)->where('moneda', $moneda)->first();
        
        return $saldo;
    }

    function getSaldoCliente($cliente, $moneda) {
        $saldo = PROC_SALDOS::where('cuenta', $cliente)->where('idEmpresa', session('company')->idEmpresa)->where('idSucursal', session('sucursal')->idSucursal)->where('rama', 'CxC')->where('moneda', $moneda)->first();
        
        return $saldo;
    }
}
