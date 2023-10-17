<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\UTILS\PROC_AUXILIAR;
use App\Models\UTILS\PROC_SALDOS;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuxiliaresController extends Controller
{
    //

    public function agregarAuxiliarVentas($mov)
    {
        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'Ventas';
        $auxiliar->idModulo = $mov->idVenta;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->propietarioPrincipal;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = $mov->total;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 0;
        $auxiliar->save();

    }

    public function agregarAuxiliarCxC($mov)
    {
        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'CxC';
        $auxiliar->idModulo = $mov->idCXC;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->getCliente->idCliente;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = null;
        $auxiliar->abono = $mov->total;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 0;
        $auxiliar->save();

    }

    public function agregarAuxiliarCxCAplicacion($mov)
    {

        $movimientos = $mov->getDetalle;
        if($movimientos) {
            foreach ($movimientos as $movimiento) {
            $auxiliar = new PROC_AUXILIAR();

            $auxiliar->idEmpresa = $mov->idEmpresa;
            $auxiliar->idSucursal = $mov->idSucursal;
            $auxiliar->rama = 'CxC';
            $auxiliar->modulo = 'CxC';
            $auxiliar->idModulo = $mov->idCXC;
            $auxiliar->movimiento = $mov->movimiento;
            $auxiliar->folio = $mov->folioMov;
            $auxiliar->moneda = $mov->getMoneda->clave;
            $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
            $auxiliar->cuenta = $mov->getCliente->idCliente;
            $auxiliar->año = Carbon::now()->year;
            $auxiliar->periodo = Carbon::now()->month;
            $auxiliar->saldoInicial = null;
            $auxiliar->cargo = null;
            $auxiliar->abono = $movimiento->importe;
            $auxiliar->referencia = null;
            $auxiliar->aplica = $movimiento->aplica;
            $auxiliar->idAplica = $movimiento->aplicaConsecutivo;
            $auxiliar->cancelado = 0;
            $auxiliar->save();
            }
        }

        $anticipo = $mov->getAnticipo;

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'CxC';
        $auxiliar->idModulo = $mov->idCXC;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->getCliente->idCliente;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = $anticipo->total;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $anticipo->movimiento;
        $auxiliar->idAplica = $anticipo->folioMov;
        $auxiliar->cancelado = 0;
        $auxiliar->save();

    }

    public function quitarAuxiliarCxCAplicacion($mov)
    {

        $movimientos = $mov->getDetalle;
        if($movimientos) {
            foreach ($movimientos as $movimiento) {
            $auxiliar = new PROC_AUXILIAR();

            $auxiliar->idEmpresa = $mov->idEmpresa;
            $auxiliar->idSucursal = $mov->idSucursal;
            $auxiliar->rama = 'CxC';
            $auxiliar->modulo = 'CxC';
            $auxiliar->idModulo = $mov->idCXC;
            $auxiliar->movimiento = $mov->movimiento;
            $auxiliar->folio = $mov->folioMov;
            $auxiliar->moneda = $mov->getMoneda->clave;
            $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
            $auxiliar->cuenta = $mov->getCliente->idCliente;
            $auxiliar->año = Carbon::now()->year;
            $auxiliar->periodo = Carbon::now()->month;
            $auxiliar->saldoInicial = null;
            $auxiliar->cargo = null;
            $auxiliar->abono = '-'.$movimiento->importe;
            $auxiliar->referencia = null;
            $auxiliar->aplica = $movimiento->aplica;
            $auxiliar->idAplica = $movimiento->aplicaConsecutivo;
            $auxiliar->cancelado = 1;
            $auxiliar->save();
            }
        }

        $anticipo = $mov->getAnticipo;

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'CxC';
        $auxiliar->idModulo = $mov->idCXC;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->getCliente->idCliente;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = '-'.$anticipo->total;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $anticipo->movimiento;
        $auxiliar->idAplica = $anticipo->folioMov;
        $auxiliar->cancelado = 1;
        $auxiliar->save();

    }

    public function agregarAuxiliarTesoreria($mov)
    {

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'Tesoreria';
        $auxiliar->modulo = 'Tesoreria';
        $auxiliar->idModulo = $mov->idTesoreria;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->movimiento != 'Transferencia' ? $mov->cuentaDinero : $mov->cuentaDineroDestino;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;

        $primerIngreso = $auxiliar->getPrimerSaldo($mov);
        if($primerIngreso){
            $auxiliar->saldoInicial = $mov->importeTotal;
        }else{
            $auxiliar->saldoInicial = null;
        }

        $auxiliar->cargo = $mov->importeTotal;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 0;
        $auxiliar->save();

    }


    public function cancelarAuxiliarTesoreria($mov)
    {

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'Tesoreria';
        $auxiliar->modulo = 'Tesoreria';
        $auxiliar->idModulo = $mov->idTesoreria;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta =$mov->movimiento != 'Transferencia' ? $mov->cuentaDinero : $mov->cuentaDineroDestino;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo ='-'. $mov->importeTotal;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 1;
        $auxiliar->save();

    }

    public function quitarAuxiliarTesoreria($mov)
    {

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'Tesoreria';
        $auxiliar->modulo = 'Tesoreria';
        $auxiliar->idModulo = $mov->idTesoreria;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->cuentaDinero;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = null;
        $auxiliar->abono = $mov->importeTotal;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 0;
        $auxiliar->save();

    }

    public function cancelarAuxiliarTesoreriaAbono($mov)
    {

        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'Tesoreria';
        $auxiliar->modulo = 'Tesoreria';
        $auxiliar->idModulo = $mov->idTesoreria;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->cuentaDinero;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = null;
        $auxiliar->abono = '-'.$mov->importeTotal;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 1;
        $auxiliar->save();

    }

    public function quitarAuxiliarVentas($mov)
    {
        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'Ventas';
        $auxiliar->idModulo = $mov->idVenta;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->getCliente->idCliente;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = '-'.$mov->total;
        $auxiliar->abono = null;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 1;
        $auxiliar->save();

    }

    public function quitarAuxiliarCxC($mov)
    {
        $auxiliar = new PROC_AUXILIAR();

        $auxiliar->idEmpresa = $mov->idEmpresa;
        $auxiliar->idSucursal = $mov->idSucursal;
        $auxiliar->rama = 'CxC';
        $auxiliar->modulo = 'CxC';
        $auxiliar->idModulo = $mov->idCXC;
        $auxiliar->movimiento = $mov->movimiento;
        $auxiliar->folio = $mov->folioMov;
        $auxiliar->moneda = $mov->getMoneda->clave;
        $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
        $auxiliar->cuenta = $mov->getCliente->idCliente;
        $auxiliar->año = Carbon::now()->year;
        $auxiliar->periodo = Carbon::now()->month;
        $auxiliar->saldoInicial = null;
        $auxiliar->cargo = null;
        $auxiliar->abono = '-'.$mov->total;
        $auxiliar->referencia = null;
        $auxiliar->aplica = $mov->movimiento;
        $auxiliar->idAplica = $mov->folioMov;
        $auxiliar->cancelado = 1;
        $auxiliar->save();

    }

    public function agregarSaldoCliente($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->getCliente->idCliente, $mov->idEmpresa, $mov->idSucursal, 'CxC', $mov->getMoneda->clave);

        if ($saldo == null) {
            $auxiliar = new PROC_SALDOS();
            $auxiliar->idEmpresa = $mov->idEmpresa;
            $auxiliar->idSucursal = $mov->idSucursal;
            $auxiliar->rama = 'CxC';
            $auxiliar->moneda = $mov->getMoneda->clave;
            $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
            $auxiliar->cuenta = $mov->getCliente->idCliente; //cliente
            $auxiliar->saldo = $mov->total;

            $auxiliar->save();
        } else {
            $saldo->saldo = $saldo->saldo + $mov->total;
            $saldo->save();
        }
    }

    
    public function quitarSaldoCliente($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->getCliente->idCliente, $mov->idEmpresa, $mov->idSucursal, 'CxC', $mov->getMoneda->clave);

        if ($saldo == null) {
            $auxiliar = new PROC_SALDOS();
            $auxiliar->idEmpresa = $mov->idEmpresa;
            $auxiliar->idSucursal = $mov->idSucursal;
            $auxiliar->rama = 'CxC';
            $auxiliar->moneda = $mov->getMoneda->clave;
            $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
            $auxiliar->cuenta = $mov->getCliente->idCliente; //cliente
            $auxiliar->saldo = $auxiliar->saldo - $mov->total;

            $auxiliar->save();
        } else{
            $saldo->saldo = $saldo->saldo - $mov->total;
            $saldo->save();
        }
    }

    public function agregarSaldoInicial($mov)
    {
        $cuenta = CAT_CUENTAS_DINERO::where('clave', $mov->cuentaDinero)->first();
   
        if($cuenta != null) {
            if($cuenta->saldoInicial == null){
                $cuenta->saldoInicial = $mov->importeTotal;
                $cuenta->save();
            }
        }
    }

    public function agregarSaldoCuenta($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->movimiento != 'Transferencia' ? $mov->cuentaDinero : $mov->cuentaDineroDestino, $mov->idEmpresa, $mov->idSucursal, 'Tesoreria', $mov->getMoneda->clave);

        $cuenta = CAT_CUENTAS_DINERO::where('clave', $mov->movimiento != 'Transferencia' ? $mov->cuentaDinero : $mov->cuentaDineroDestino)->first();

        if ($saldo == null) {
            $auxiliar = new PROC_SALDOS();
            $auxiliar->idEmpresa = $mov->idEmpresa;
            $auxiliar->idSucursal = $mov->idSucursal;
            $auxiliar->rama = 'Tesoreria';
            $auxiliar->moneda = $mov->getMoneda->clave;
            $auxiliar->tipoCambio = $mov->getMoneda->tipoCambio;
            $auxiliar->cuenta = $mov->movimiento != 'Transferencia' ? $mov->cuentaDinero : $mov->cuentaDineroDestino;
            $auxiliar->saldo = $mov->importeTotal;

            $auxiliar->save();
        } else {
            $saldo->saldo = $saldo->saldo + $mov->importeTotal;
            $saldo->save();
        }

        if($cuenta != null) {
            $cuenta->saldo = $cuenta->saldo + $mov->importeTotal;
            $cuenta->save();
        }
    }

    public function cancelarAgregarSaldoCuenta($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->cuentaDinero, $mov->idEmpresa, $mov->idSucursal, 'Tesoreria', $mov->getMoneda->clave);

        $cuenta = CAT_CUENTAS_DINERO::where('clave', $mov->cuentaDinero)->first();

        if ($saldo != null) {
            $saldo->saldo = $saldo->saldo + $mov->importeTotal;
            $saldo->save();
        }
        if($cuenta != null) {
            $cuenta->saldo = $cuenta->saldo + $mov->importeTotal;
            $cuenta->save();
        }
    }

    public function quitarSaldoCuenta($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->cuentaDinero, $mov->idEmpresa, $mov->idSucursal, 'Tesoreria', $mov->getMoneda->clave);

        $cuenta = CAT_CUENTAS_DINERO::where('clave', $mov->cuentaDinero)->first();

        if ($saldo != null) {
            $saldo->saldo = $saldo->saldo - $mov->importeTotal;
            $saldo->save();
        }

        if($cuenta != null) {
            $cuenta->saldo = $cuenta->saldo - $mov->importeTotal;
            $cuenta->save();
        }
    }

    public function cancelarQuitarSaldoCuenta($mov)
    {
        $saldos = new PROC_SALDOS();
        $saldo = $saldos->getSaldos($mov->cuentaDineroDestino, $mov->idEmpresa, $mov->idSucursal, 'Tesoreria', $mov->getMoneda->clave);

        $cuenta = CAT_CUENTAS_DINERO::where('clave', $mov->cuentaDineroDestino)->first();

        if ($saldo != null) {
            $saldo->saldo = $saldo->saldo - $mov->importeTotal;
            $saldo->save();
        }

        if($cuenta != null) {
            $cuenta->saldo = $cuenta->saldo - $mov->importeTotal;
            $cuenta->save();
        }
    }



}