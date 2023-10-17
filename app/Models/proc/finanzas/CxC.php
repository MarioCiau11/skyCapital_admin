<?php

namespace App\Models\proc\finanzas;

use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\catalogos\CAT_SUCURSALES;
use App\Models\config\CONF_COSECUTIVOS;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CxC extends Model
{
    use HasFactory;

    protected $table = 'PROC_CXC';

    protected $primaryKey = 'idCXC';

    protected $guarded = ['idCXC'];

    public $timestamps = false;

    public function cxcParametros()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        return $this->where('idEmpresa', '=', session('company')->idEmpresa)
                    ->where('idSucursal', '=', session('sucursal')->idSucursal)
                    ->where('estatus', '=', 'PENDIENTE')
                    ->where('user_id', '=', Auth::user()->user_id)
                    ->where('moneda', '=', $param != null ? $param->idMoneda : 1)
                    ->get();
    }

    public function scopewhereCxCFolio($query, $folio)
    {
        if (!is_null($folio)) {
            return $query->where('folioMov', 'like', '%' . $folio . '%');
        }
        return $query;
    }
    public function scopewhereEmpresa($query,$empresa)
    {
        if (!is_null($empresa)) {
            return $query->where('idEmpresa', $empresa);
        }
        return $query;
    }
    public function scopewhereCxcClientes($query,$clientes){
        if (!is_null($clientes)) {
            return $query->whereBetween('cliente', $clientes);
        }
        return $query;
    }

    public function scopewhereCxCCliente($query, $cliente)
    {
        if (!is_null($cliente)) {
            if ($cliente != 'Todos') {
                return $query->where('cliente', $cliente);
            }
            return $query;
        }
        return $query;
    }

    public function scopewhereCxCMovimiento($query, $movimiento)
    {
        if (!is_null($movimiento)) {
            if ($movimiento == 'Todos') {
                return $query;
            }
            return $query->where('movimiento', $movimiento);
        }
        return $query;
    }
    public function scopewhereCxCFecha($query,$fecha)
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
                    $fechaInicio = request()->get('inputFechaInicio');
                    $fechaFinal = request()->get('inputFechaFinal');
                    // dd($fechaInicio, $fechaFinal);
                    $query->whereBetween('fechaEmision', [$fechaInicio, $fechaFinal]);
                    break;
                case 'Todos':
                    break;
            } 
            return $query;
        } 
        return $query;
    }

    public function scopewhereCxCFechaV($query,$fechaV)
    {
        if (!is_null($fechaV)) {
            switch ($fechaV) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    $query->whereDate('fechaVencimiento', '=', $fechaHoy)
                          ->orWhere('fechaVencimiento', '=', null);
                    // dd($fechaHoy, $query);
                    break;
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    $query->whereDate('fechaVencimiento', '=', $fechaAyer)
                          ->orWhere('fechaVencimiento', '=', null);
                    // dd($fechaAyer, $query);
                    break;
                case 'Semana':
                    $query->whereBetween('fechaVencimiento', [
                          Carbon::now()->startOfWeek(Carbon::SUNDAY), 
                          Carbon::now()->endOfWeek(Carbon::SATURDAY)])
                          ->orWhere('fechaVencimiento', '=', null);
                    break;
                case 'Mes':
                    $fechaMes = Carbon::now()->format('m');
                    $fechaAnio = Carbon::now()->format('Y');
                    $query->whereMonth('fechaVencimiento', '=', $fechaMes)
                          ->whereYear('fechaVencimiento', '=', $fechaAnio)
                          ->orWhere('fechaVencimiento', '=', null);
                    break;
                case 'Año móvil':
                    $fechaAnioAct = Carbon::now()->format('Y');
                    $query->whereYear('fechaVencimiento', '=', $fechaAnioAct)
                          ->orWhere('fechaVencimiento', '=', null);
                    // dd($fechaAnioAct, $query);
                    break;
                case 'Año pasado':
                    $fechaAnioAnt = Carbon::now()->subYear()->format('Y');
                    $query->whereYear('fechaVencimiento', '=', $fechaAnioAnt)
                          ->orWhere('fechaVencimiento', '=', null);
                    break;
                case 'Rango de fechas':
                    $fechaInicioV = request()->get('inputFechaInicioV');
                    $fechaFinalV = request()->get('inputFechaFinalV');
                    // dd($fechaInicio, $fechaFinal);
                    $query->whereBetween('fechaVencimiento', [$fechaInicioV, $fechaFinalV])
                          ->orWhere('fechaVencimiento', '=', null);
                    break;
                case 'Todos':
                    break;
            } 
            return $query;
        } 
        return $query;
    }
    

    public function scopewhereCxCEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }


    public function scopewhereCxCUsuario($query, $usuario)
    {
        if (!is_null($usuario)) {
            if ($usuario == 'Todos') {
                return $query;
            }
            return $query->where('user_id', $usuario);
        }
        return $query;
    }

    public function scopewhereCxCSucursal($query, $sucursal)
    {
        if (!is_null($sucursal)) {
            if ($sucursal == 'Todos') {
                return $query->where('idSucursal', '=', session('sucursal')->idSucursal);
            }
            return $query->where('idSucursal', $sucursal);
        }
        return $query;
    }
    
    public function scopewhereCxCMoneda($query, $moneda)
    {
        if (!is_null($moneda)) {
            if ($moneda == 'Todos') {
                return $query;
            }
            return $query->where('moneda', $moneda);
        }
        return $query;
    }


    public function getVenta()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'idVenta');
    }

    public function obtenerVenta()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'origenId');
    }
    public function getVentas()
    {
        return $this->join('proc_ventas', 'proc_cxc.user_id', '=', 'proc_ventas.user_id')
                    ->where('proc_cxc.movimiento', '=', 'Factura')
                    ->where('proc_ventas.estatus', '=', 'PENDIENTE')
                    ->select('proc_cxc.*');
    }
    public function getMoneda()
    {
        return $this->hasOne('App\Models\config\CONF_MONEDA', 'idMoneda', 'moneda');
    }
    public function getCondition()
    {
        return $this->hasOne('App\Models\config\CONF_CONDICIONES_CRED', 'idCondicionesc', 'condicion');
    }
    public function getProyecto()
    {
        return $this->hasOne('App\Models\catalogos\CAT_PROYECTOS', 'idProyecto', 'proyecto');
    }
    public function getModulo()
    {
        return $this->hasOne('App\Models\catalogos\CAT_MODULOS', 'clave', 'modulo');
    }
    public function getCliente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CLIENTES', 'idCliente', 'cliente');
    }
    public function getClientes()
    {
        return $this->where('estatus', 1)->get()->pluck('razonSocial', 'idCliente');
    }
    public function getEmpresa()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }
    public function getSucursal() {
        return $this->hasOne('App\Models\catalogos\CAT_SUCURSALES', 'idSucursal', 'idSucursal');
    }
    public function empresa()
    {
        return $this->belongsTo(CAT_EMPRESAS::class, 'idEmpresa', 'idEmpresa');
    }

    // Relación pertenece a CAT_SUCURSALES a través de idSucursal
    public function sucursal()
    {
        return $this->belongsTo(CAT_SUCURSALES::class, 'idSucursal', 'idSucursal');
    }
    public function money()
    {
        return $this->belongsTo(CONF_MONEDA::class, 'moneda', 'idMoneda');
    }

    public function getFormaPago()
    {
        return $this->hasOne('App\Models\config\CONF_FORMAS_PAGO', 'idFormaspc', 'formaPago');
    }
    public function getCuentaDinero()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CUENTAS_DINERO', 'idCuentasDinero', 'cuentaDinero');
    }
    public function getFolio($mov)
    {
        $cxc = $mov;
        $consecutivos = CONF_COSECUTIVOS::where('idEmpresa', '=', $cxc->idEmpresa)->where('idSucursal', '=', $cxc->idSucursal)->first();
        // dd($cxc);
        if ($cxc->folioMov == null) {
            $folio = $this->where('movimiento', '=', $cxc->movimiento)
                ->where('idEmpresa', '=', $cxc->idEmpresa)
                ->where('idSucursal', '=', $cxc->idSucursal)
                ->max('folioMov');

            if ($folio == null) {
                if ($consecutivos != null) {
                   
                        if ($cxc->movimiento == 'Anticipo') {
                            $folio = $consecutivos->consAnticipo;
                            $folio = $folio + 1;
                            $consecutivos->consAnticipo = $folio;
                            $consecutivos->save();
                        }

                        if ($cxc->movimiento == 'Aplicación') {
                            $folio = $consecutivos->consAplicacion;
                            $folio = $folio + 1;
                            $consecutivos->consAplicacion = $folio;
                            $consecutivos->save();
                        }

                        if ($cxc->movimiento == 'Cobro') {
                            $folio = $consecutivos->consCobro;
                            $folio = $folio + 1;
                            $consecutivos->consCobro = $folio;
                            $consecutivos->save();
                        }

                    

                } else {
                    $folio = 1;
                    if ($cxc->movimiento == 'Anticipo') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $cxc->idEmpresa;
                        $consecutivos->idSucursal = $cxc->idSucursal;
                        $consecutivos->consAnticipo = $folio;
                        $consecutivos->save();
                    }

                    if ($cxc->movimiento == 'Aplicación') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $cxc->idEmpresa;
                        $consecutivos->idSucursal = $cxc->idSucursal;
                        $consecutivos->consAplicacion = $folio;
                        $consecutivos->save();
                    }
                    if ($cxc->movimiento == 'Cobro') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $cxc->idEmpresa;
                        $consecutivos->idSucursal = $cxc->idSucursal;
                        $consecutivos->consCobro = $folio;
                        $consecutivos->save();
                    }
                    if ($cxc->movimiento == 'Factura') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $cxc->idEmpresa;
                        $consecutivos->idSucursal = $cxc->idSucursal;
                        $consecutivos->consFactura2 = $folio;
                        $consecutivos->save();
                    }
                }
            } else {

                if ($consecutivos != null) {

                    if ($cxc->movimiento == 'Anticipo') {
                        $folio = $consecutivos->consAnticipo;
                        $folio = $folio + 1;
                        $consecutivos->consAnticipo = $folio;
                        $consecutivos->save();
                    }
                    if ($cxc->movimiento == 'Aplicación') {
                        $folio = $consecutivos->consAplicacion;
                        $folio = $folio + 1;
                        $consecutivos->consAplicacion = $folio;
                        $consecutivos->save();
                    }
                    if ($cxc->movimiento == 'Cobro') {
                        $folio = $consecutivos->consCobro;
                        $folio = $folio + 1;
                        $consecutivos->consCobro = $folio;
                        $consecutivos->save();
                    }
                }
            }

            return $folio;
        } else {
            $folio = $cxc->folioMov;

            return $folio;
        }
    }
    public function getCxC()
    {
        return $this->hasOne('App\Models\proc\finanzas\CxC', 'idCXC', 'idCXC');
    }
    public function getCxCs()
    {
        return $this->where('idEmpresa', session('company')->idEmpresa)->get();
    }
    public function getDetalle()
    {
        return $this->hasMany('App\Models\proc\finanzas\CxCDetalle', 'idCXC', 'idCXC');
    }

    public function getAnticipo() {
        return $this->hasOne('App\Models\proc\finanzas\CxC', 'idCXC', 'idAnticipo');
    }
    
    public function getMovCxC($cliente, $moneda, $movimiento, $modulo = null) {
        return $this::with('empresa', 'sucursal', 'money')
            ->where('cliente', '=', $cliente)
            ->where('moneda', '=', $moneda)
            ->where('movimiento', '=', $movimiento)
            ->where('estatus', '=', 'PENDIENTE')
            ->when($modulo !== null, function ($query) use ($modulo) {
                return $query->where('modulo', '=', $modulo);
            })
            ->where('idEmpresa', '=', session('company')->idEmpresa)
            ->where('idSucursal', '=', session('sucursal')->idSucursal)
            ->get();
    }
    
    public function getAuxiliar() {
        return $this->belongsTo('App\Models\utils\PROC_AUXILIAR', 'idModulo', 'idCXC');
    }

}
