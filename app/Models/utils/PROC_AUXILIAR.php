<?php

namespace App\Models\UTILS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PROC_AUXILIAR extends Model
{
    use HasFactory;

    
    protected $table = 'PROC_AUXILIAR';
    protected $primaryKey = 'idAuxiliar';
    protected $guarded = ['idAuxiliar'];
    public $timestamps = false;
    public $attributes_map = [
        'idAuxiliar' => 'Identificador del registro',
        'idEmpresa' => 'Identificador de la empresa',
        'idSucursal' => 'Identificador de la sucursal',
        'rama' => 'Rama del registro',
        'modulo' => 'Módulo de origen',
        'idModulo' => 'Identificador del registro de origen',
        'movimiento' => 'Movimiento de origen',
        'folio' => 'Folio de origen',
        'moneda' => 'Moneda de origen',
        'tipoCambio' => 'Tipo de cambio de origen',
        'cuenta' => 'Cuenta de origen',
        'año' => 'Año de origen',
        'periodo' => 'Periodo de origen',
        'saldoInicial' => 'Saldo inicial de origen',
        'cargo' => 'Cargo de origen',
        'abono' => 'Abono de origen',
        'referencia' => 'Referencia de origen',
        'aplica' => 'Aplica de origen', 
        'idAplica' => 'Identificador de aplica de origen', 
        'cancelado' => 'Cancelado', 
        'fechaEmision' => 'Fecha de creación del registro', 

    ];

    public function scopewhereModulo($query,$modulo) {
        if (!is_null($modulo)) {
            return $query->where('modulo', $modulo);
        }
        return $query;
    }
    public function scopewhereReportAuxCliente($query, $cliente)
    {
        $aux = PROC_AUXILIAR::select('PROC_AUXILIAR.*') 
                        ->selectRaw('(CASE
                                        WHEN PROC_AUXILIAR.modulo = "CxC" THEN PROC_CXC.estatus 
                                        WHEN PROC_AUXILIAR.modulo = "Tesoreria" THEN PROC_TESORERIA.estatus
                                        WHEN PROC_AUXILIAR.modulo = "Ventas" THEN CXC_VENTAS.estatus
                                        ELSE NULL 
                                    END) as estatus')
                        ->leftJoin('PROC_CXC', function ($join) {
                            $join->on('PROC_AUXILIAR.idModulo', '=', 'PROC_CXC.idCXC')
                                ->where('PROC_AUXILIAR.modulo', '=', 'CxC');
                        })
                        ->leftJoin('PROC_TESORERIA', function ($join) {
                            $join->on('PROC_AUXILIAR.idModulo', '=', 'PROC_TESORERIA.idTesoreria')
                                 ->where('PROC_AUXILIAR.modulo', '=', 'Tesoreria');
                        })
                        ->leftJoin('PROC_CXC as CXC_VENTAS', function ($join) {
                            $join->on('PROC_AUXILIAR.idModulo', '=', 'CXC_VENTAS.origenId')
                                ->where('PROC_AUXILIAR.modulo', '=', 'Ventas');
                        })
                        ->whereIn('PROC_AUXILIAR.rama', ['CxC', 'Tesoreria']);
        if (!is_null($cliente)) {
            if ($cliente == 'Todos') {
                return $aux;
            }
            return $aux->where('PROC_AUXILIAR.cuenta', $cliente);
        }
    }

    public function scopewhereReportAuxFecha($query,$fecha)
    {
        if (!is_null($fecha)) {
            switch ($fecha) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    return $query->whereDate('PROC_AUXILIAR.fechaEmision', '=', $fechaHoy);
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    return $query->whereDate('PROC_AUXILIAR.fechaEmision', '=', $fechaAyer);
                case 'Semana':
                    return $query->whereBetween('PROC_AUXILIAR.fechaEmision', [
                        Carbon::now()->startOfWeek(Carbon::SUNDAY), 
                        Carbon::now()->endOfWeek(Carbon::SATURDAY)]);
                case 'Mes':
                    $fechaMes = Carbon::now()->format('m');
                    $fechaAnio = Carbon::now()->format('Y');
                    return $query->whereMonth('PROC_AUXILIAR.fechaEmision', '=', $fechaMes)
                    ->whereYear('PROC_AUXILIAR.fechaEmision', '=', $fechaAnio);
                case 'Año móvil':
                    $fechaAnioAct = Carbon::now()->format('Y');
                    return $query->whereYear('PROC_AUXILIAR.fechaEmision', '=', $fechaAnioAct);
                case 'Año pasado':
                    $fechaAnioAnt = Carbon::now()->subYear()->format('Y');
                    return $query->whereYear('PROC_AUXILIAR.fechaEmision', '=', $fechaAnioAnt);
                case 'Rango de fechas':
                    $fechaInicio = request()->get('inputFechaInicio');
                    $fechaFinal = request()->get('inputFechaFinal');
                    return $query->whereBetween('PROC_AUXILIAR.fechaEmision', [$fechaInicio, $fechaFinal]);
                case 'Todos':
                    return $query;
            }
            return $query;
        }
    }
    public function scopewhereReportAuxEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('PROC_CXC.estatus', $estatus)
                        ->orWhere('CXC_VENTAS.estatus', $estatus);
        }
        return $query;
    }
    public function scopewhereReportAuxMoneda($query, $moneda)
    {
        if (!is_null($moneda)) {
            if ($moneda == 'Todos') {
                return $query;
            }
            return $query->where('PROC_AUXILIAR.moneda', $moneda);
        }
    }
    public function scopewhereCuenta($query, $clave)
    {
        if (!is_null($clave)) {
            
            if ($clave == 'Todos') {
                return $query;
            }
            return $query->where('cuenta', $clave);
            
        }
        return $query;
    }

    public function scopewhereMovimiento($query,$movimiento){
        if (!is_null($movimiento)) {
            if ($movimiento == 'Todos') {
                return $query;
            }
            return $query->where('movimiento', $movimiento);
        }
        return $query;
    }
        

    public function obtenerDatos()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        return $this->select('PROC_AUXILIAR.*') 
                ->selectRaw('(CASE
                                WHEN PROC_AUXILIAR.modulo = "CxC" THEN PROC_CXC.estatus 
                                WHEN PROC_AUXILIAR.modulo = "Tesoreria" THEN PROC_TESORERIA.estatus
                                WHEN PROC_AUXILIAR.modulo = "Ventas" THEN CXC_VENTAS.estatus
                                ELSE NULL 
                            END) as estatus')
                ->leftJoin('PROC_CXC', function ($join) {
                    $join->on('PROC_AUXILIAR.idModulo', '=', 'PROC_CXC.idCXC')
                         ->where('PROC_AUXILIAR.modulo', '=', 'CxC');
                })
                ->leftJoin('PROC_TESORERIA', function ($join) {
                    $join->on('PROC_AUXILIAR.idModulo', '=', 'PROC_TESORERIA.idTesoreria')
                         ->where('PROC_AUXILIAR.modulo', '=', 'Tesoreria');
                })
                ->leftJoin('PROC_CXC as CXC_VENTAS', function ($join) {
                    $join->on('PROC_AUXILIAR.idModulo', '=', 'CXC_VENTAS.origenId')
                         ->where('PROC_AUXILIAR.modulo', '=', 'Ventas');
                })
                ->orderBy('PROC_AUXILIAR.folio', 'ASC')
                ->whereIn('PROC_AUXILIAR.rama', ['CxC', 'Tesoreria'])
                ->where('PROC_AUXILIAR.moneda', '=', $param != null ? $param->clave : 'PESOS')
                ->get();
    }
    public function getPrimerSaldo($tesoreria) {

        $primerIngreso = true;

        $query = PROC_AUXILIAR::where('modulo', 'Tesoreria')->where('movimiento', 'Ingreso')->where('idEmpresa', $tesoreria->idEmpresa)->where('idSucursal', $tesoreria->idSucursal)->where('cuenta', $tesoreria->cuentaDinero)->first();

        if($query != null){
            $primerIngreso = false;
        }

        return $primerIngreso;
    }

    public function getAuxiliar()
    {
        return $this->where('PROC_AUXILIAR.idEmpresa', session('company')->idEmpresa)->where('PROC_AUXILIAR.rama', 'CxC')
        ->rightJoin('PROC_VENTAS', 'PROC_AUXILIAR.idModulo', '=', 'PROC_VENTAS.idVenta')->get();
    }
    public function getVenta()
    {
        return $this->hasMany('App\Models\proc\comercial\Ventas', 'idVenta', 'idModulo');
    }
    public function getTesoreria(){
        return $this->hasMany('App\Models\proc\finanzas\Tesoreria', 'idTesoreria', 'idModulo');
    }
    public function getCxC()
    {
        return $this->hasMany('App\Models\proc\finanzas\CxC', 'idCxC', 'idModulo');
    }
    public function getCliente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CLIENTES', 'idCliente', 'cuenta');
    }

    public function getSucursal()
    {
        return $this->hasOne('App\Models\catalogos\CAT_SUCURSALES', 'idSucursal', 'idSucursal');
    }

    public function getSaldos()
    {
        return $this->hasMany('App\Models\utils\PROC_SALDOS', 'cuenta', 'cuenta');
    }
    public function getMoneda()
    {
        return $this->hasOne('App\Models\config\CONF_MONEDA', 'idMoneda', 'moneda');
    }
    public function modulo()
{
    return $this->morphTo('modulo', 'idModulo');
}
}
