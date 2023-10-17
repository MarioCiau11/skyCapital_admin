<?php

namespace App\Models\proc\finanzas;

use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_COSECUTIVOS;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\UTILS\PROC_SALDOS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class Tesoreria extends Model
{
    use HasFactory;
    protected $table = 'PROC_TESORERIA';

    protected $primaryKey = 'idTesoreria';

    protected $guarded = ['idTesoreria'];

    public $timestamps = false;


    public function tesoreriaParametros()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        return $this->where('idEmpresa', '=', session('company')->idEmpresa)
                    ->where('idSucursal', '=', session('sucursal')->idSucursal)
                    ->where('estatus', '=', 'CONCLUIDO')
                    ->where('moneda', '=', $param != null ? $param->idMoneda : 1)
                    ->where('user_id', '=', Auth::user()->user_id)
                    ->orderBy('idTesoreria', 'desc')
                    ->orderBy('fechaCambio', 'desc')
                    ->get();
    }
    public function scopewhereTesoreriaFolio($query, $folio)
    {
        if (!is_null($folio)) {
            return $query->where('folioMov', 'like', '%' . $folio . '%');
        }
        return $query;
    }

    public function scopewhereTesoreriaMovimiento($query, $movimiento)
    {
        if (!is_null($movimiento)) {
            if ($movimiento == 'Todos') {
                return $query;
            }
            return $query->where('movimiento', $movimiento);
        }
        return $query;
    }

    public function scopewhereTesoreriaCuentaD($query, $cuentaD)
    {
        if (!is_null($cuentaD)) {
            if ($cuentaD == 'Todos') {
                return $query;
            }
            $claveCuenta = CAT_CUENTAS_DINERO::find($cuentaD)->clave;
            // dd($claveCuenta);
            return $query->where('cuentaDinero', $claveCuenta);
        }
        return $query;
    }
    public function scopewhereTesoreriaFecha($query,$fecha)
    {
        if (!is_null($fecha)) {
            switch ($fecha) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    $query->whereDate('fechaEmision', '=', $fechaHoy);
                    // dd($fechaHoy, $query);
                    break;
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    $query->whereDate('fechaEmision', '=', $fechaAyer);
                    // dd($fechaAyer, $query);
                    break;
                case 'Semana':
                    $query->whereBetween('fechaEmision', [
                        Carbon::now()->startOfWeek(Carbon::SUNDAY), 
                        Carbon::now()->endOfWeek(Carbon::SATURDAY)]);
                    break;
                case 'Mes':
                    $fechaMes = Carbon::now()->format('m');
                    $fechaAnio = Carbon::now()->format('Y');
                    $query->whereMonth('fechaEmision', '=', $fechaMes)
                    ->whereYear('fechaEmision', '=', $fechaAnio);
                    break;
                case 'Año móvil':
                    $fechaAnioAct = Carbon::now()->format('Y');
                    $query->whereYear('fechaEmision', '=', $fechaAnioAct);
                    // dd($fechaAnioAct, $query);
                    break;
                case 'Año pasado':
                    $fechaAnioAnt = Carbon::now()->subYear()->format('Y');
                    $query->whereYear('fechaEmision', '=', $fechaAnioAnt);
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::parse(request()->get('inputFechaInicio'))->format('Y-m-d');
                    $fechaFinal = Carbon::parse(request()->get('inputFechaFinal'))->format('Y-m-d');
                    // dd($fechaInicio, $fechaFinal);
                    $query->whereBetween('fechaEmision', [$fechaInicio.' 00:00:00' , $fechaFinal. ' 23:59:59']);
                    // dd($query);
                    break;
                case 'Todos':
                    break;
            } 
            return $query;
        } 
        return $query;
    }


    public function scopewhereTesoreriaEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }

    
    public function scopewhereTesoreriaMoneda($query, $moneda)
    {
        if (!is_null($moneda)) {
            if ($moneda == 'Todos') {
                return $query;
            }
            return $query->where('moneda', $moneda);
        }
        return $query;
    }


    public function scopewhereTesoreriaUsuario($query, $usuario)
    {
        if (!is_null($usuario)) {
            if ($usuario == 'Todos') {
                return $query;
            }
            return $query->where('user_id', $usuario);
        }
        return $query;
    }

    public function scopewhereTesoreriaSucursal($query, $sucursal)
    {
        if (!is_null($sucursal)) {
            if ($sucursal == 'Todos') {
                return $query->where('idSucursal', '=', session('sucursal')->idSucursal);
            }
            return $query->where('idSucursal', $sucursal);
        }
        return $query;
    }


    public function getSaldoCuenta($id, $idEmpresa, $idSucursal, $moneda)
    {
        // dd($id, $idEmpresa, $idSucursal, $moneda);
        $saldo = PROC_SALDOS::where('cuenta', $id)->where('idEmpresa', $idEmpresa)->where('idSucursal', $idSucursal)->where('rama', 'Tesoreria')->where('moneda', $moneda)->first();
        
        return $saldo;
    }
    public function getFolio($mov)
    {
        $tesoreria = $mov;
        $consecutivos = CONF_COSECUTIVOS::where('idEmpresa', '=', $tesoreria->idEmpresa)->where('idSucursal', '=', $tesoreria->idSucursal)->first();
        // dd($tesoreria);
        if ($tesoreria->folioMov == null) {
            $folio = $this->where('movimiento', '=', $tesoreria->movimiento)
                ->where('idEmpresa', '=', $tesoreria->idEmpresa)
                ->where('idSucursal', '=', $tesoreria->idSucursal)
                ->max('folioMov');

            if ($folio == null) {
                if ($consecutivos != null) {
                   
                        if ($tesoreria->movimiento == 'Egreso') {
                            $folio = $consecutivos->consEgreso;
                            $folio = $folio + 1;
                            $consecutivos->consEgreso = $folio;
                            $consecutivos->save();
                        }
                        if ($tesoreria->movimiento == 'Ingreso') {
                            $folio = $consecutivos->consIngreso;
                            $folio = $folio + 1;
                            $consecutivos->consIngreso = $folio;
                            $consecutivos->save();
                        }

                        if ($tesoreria->movimiento == 'Transferencia') {
                            $folio = $consecutivos->consTransferencia;
                            $folio = $folio + 1;
                            $consecutivos->consTransferencia = $folio;
                            $consecutivos->save();
                        }



                } else {
                    $folio = 1;
                    if ($tesoreria->movimiento == 'Egreso') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $tesoreria->idEmpresa;
                        $consecutivos->idSucursal = $tesoreria->idSucursal;
                        $consecutivos->consEgreso = $folio;
                        $consecutivos->save();
                    }
                    if ($tesoreria->movimiento == 'Ingreso') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $tesoreria->idEmpresa;
                        $consecutivos->idSucursal = $tesoreria->idSucursal;
                        $consecutivos->consIngreso = $folio;
                        $consecutivos->save();
                    }

                    if ($tesoreria->movimiento == 'Transferencia') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $tesoreria->idEmpresa;
                        $consecutivos->idSucursal = $tesoreria->idSucursal;
                        $consecutivos->consTransferencia = $folio;
                        $consecutivos->save();
                    }
                }

            } else {

                if ($consecutivos != null) {

                    if ($tesoreria->movimiento == 'Egreso') {
                        $folio = $consecutivos->consEgreso;
                        $folio = $folio + 1;
                        $consecutivos->consEgreso = $folio;
                        $consecutivos->save();
                    }
                    if ($tesoreria->movimiento == 'Ingreso') {
                        $folio = $consecutivos->consIngreso;
                        $folio = $folio + 1;
                        $consecutivos->consIngreso = $folio;
                        $consecutivos->save();
                    }
                    if ($tesoreria->movimiento == 'Transferencia') {
                        $folio = $consecutivos->consTransferencia;
                        $folio = $folio + 1;
                        $consecutivos->consTransferencia = $folio;
                        $consecutivos->save();
                    }

                }
            }

            if ($tesoreria->movimiento == 'Depósito') {
                $folio = $folio + 1;
            }
            if ($tesoreria->movimiento == 'Solicitud Depósito') {
                $folio = $folio + 1;
            }


            return $folio;
        } else {
            $folio = $tesoreria->folioMov;

            return $folio;
        }
    }

    public function getVenta()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'idVenta');
    }
    public function getFlujo()
    {
        // hacer return según el idTesoreria y otro segun el movimiento
        return $this->hasOne('App\Models\utils\PROC_FLUJO', 'destinoId', 'idTesoreria')
                    ->where('destinoModulo', '=', 'Tesoreria');

    }
    public function getFormaPago ()
    {
        return $this->hasOne('App\Models\config\CONF_FORMAS_PAGO', 'idFormaspc','formaPago');
    }
    public function getCondition()
    {
        return $this->hasOne('App\Models\config\CONF_CONDICIONES_CRED', 'idCondicionesc', 'condicion');
    }
    public function getProyecto()
    {
        return $this->hasOne('App\Models\catalogos\CAT_PROYECTOS', 'idProyecto', 'proyecto');
    }
    public function getMoneda()
    {
        return $this->hasOne('App\Models\config\CONF_MONEDA', 'idMoneda', 'moneda');
    }
    public function getModulo()
    {
        return $this->hasOne('App\Models\catalogos\CAT_MODULOS', 'idModulo', 'modulo');
    }
    public function getCliente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CLIENTES', 'idCliente', 'beneficiario');
    }
    public function getClientes()
    {
        return $this->where('estatus', 1)->get()->pluck('razonSocial', 'idCliente');
    }
    public function getEmpresa()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }

    public function getDetalle() {
        return $this->hasMany('App\Models\proc\finanzas\TesoreriaDetalle', 'idTesoreria', 'idTesoreria');
    }

}
