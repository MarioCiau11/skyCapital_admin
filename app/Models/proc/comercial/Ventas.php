<?php

namespace App\Models\proc\comercial;

use App\Models\catalogos\CAT_CLIENTES;
use App\Models\config\CONF_COSECUTIVOS;
use App\Models\utils\PROC_FLUJO;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\finanzas\CxC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Ventas extends Model
{
    use HasFactory;
    protected $table = 'PROC_VENTAS';
    protected $primaryKey = 'idVenta';
    protected $guarded = ['idVenta'];
    public $timestamps = false;
    public function ventasParametros()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        // dd($param);
        return $this->where('idEmpresa', '=', session('company')->idEmpresa)
            ->where('idSucursal', '=', session('sucursal')->idSucursal)
            ->where('estatus', '=', 'PENDIENTE')
            ->where('user_id', '=', Auth::user()->user_id)
            ->where('moneda', '=', $param != null ? $param->idMoneda : 1)
            ->orderBy('idVenta', 'desc')
            ->orderBy('fechaCambio', 'desc')
            ->get();
    }

    public function scopewhereVentasFolio($query, $folio)
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

    public function scopewhereVentasClientes($query,$clientes){
        if (!is_null($clientes)) {
            return $query->whereBetween('propietarioPrincipal', $clientes);
        }
        return $query;
    }

    public function scopewhereVentasProyecto($query, $proyecto)
    {
        if (!is_null($proyecto)) {
            if ($proyecto == 'Todos') {
                return $query;
                    
            }
            return $query->where('proyecto', $proyecto);
                
        }
        return $query;
    }
    public function scopewhereVentasCliente($query, $cliente)
    {
        if (!is_null($cliente)) {
            if ($cliente == 'Todos') {
                return $query;
            }
            return $query->where('propietarioPrincipal', $cliente);
        }
        return $query;
    }
    public function scopewhereReportVentasCliente($query, $cliente)
    {
        if (!is_null($cliente)) {
            if ($cliente == 'Todos') {
                return $query->where('estatus', 'PENDIENTE');
            }
            return $query->where('propietarioPrincipal', $cliente)->where('estatus', 'PENDIENTE');
        }
        return $query;
    }
    public function scopewhereReportVentasSaldos($query, $cliente, $plazo, $moneda)
    {
        $cliente1 = request()->get('selectCliente');
        $cliente2 = request()->get('selectCliente2');
        $saldos = Ventas::where('proc_ventas.idEmpresa', '=', session('company')->idEmpresa)
            ->where('proc_ventas.estatus', '=', 'PENDIENTE')
            ->select(
                'proc_ventas.idVenta', 
                'proc_ventas.movimiento', 
                'proc_ventas.folioMov', 
                'proc_ventas.claveProyecto', 
                'proc_ventas.moneda', 
                'proc_ventas.tipoCambio', 
                'proc_ventas.fechaEmision', 
                'proc_ventas.propietarioPrincipal', 
                'proc_ventas.condicionPago', 
                'proc_ventas.fechaVencimiento', 
                'proc_ventas.estatus', 
                'proc_ventas.idEmpresa', 
                'proc_ventas.idSucursal', 
                'proc_ventas.tipoContrato',
                'proc_ventas.saldo',  
                'proc_ventas.origenId', 
                'proc_ventas.fechaAlta', 
                'proc_ventas.fechaCambio', 
                DB::raw('NULL as idCXC, NULL as diasMoratorios, NULL as origenTipo, NULL as origen, NULL as origenId')
            );

        $saldos_filtro = CxC::join('proc_ventas', 'proc_cxc.origenId', '=', 'proc_ventas.idVenta')
                    ->where('proc_cxc.idEmpresa', '=', session('company')->idEmpresa)
                    ->where('proc_ventas.estatus', '=', 'CONCLUIDO')
                    ->where('proc_ventas.movimiento', '=', 'Factura')
                    ->where('proc_cxc.origenTipo', 'Ventas')
                    ->where('proc_cxc.estatus', 'PENDIENTE')
                    ->select(
                        'proc_ventas.idVenta', 
                        'proc_cxc.movimiento as cxc_movimiento', 
                        'proc_cxc.folioMov as cxc_folioMov',
                        'proc_ventas.claveProyecto', 
                        'proc_cxc.moneda', 
                        'proc_cxc.tipoCambio', 
                        'proc_cxc.fechaEmision as cxc_fechaEmision', 
                        'proc_cxc.cliente as propietarioPrincipal2', 
                        'proc_cxc.condicion', 
                        'proc_cxc.fechaVencimiento', 
                        'proc_cxc.estatus', 
                        'proc_cxc.idEmpresa', 
                        'proc_cxc.idSucursal', 
                        'proc_ventas.tipoContrato',
                        'proc_cxc.saldo',  
                        'proc_cxc.origenId', 
                        'proc_ventas.fechaAlta', 
                        'proc_cxc.fechaCambio', 
                        'proc_cxc.idCXC', 
                        'proc_cxc.diasMoratorios', 
                        'proc_cxc.origenTipo', 
                        'proc_cxc.origen', 
                        'proc_cxc.origenId'
                    );
        // dd($saldos->get());
        if (!is_null($cliente)) {
            if ($cliente != 'Todos') {
                $saldos->whereBetween('PROC_VENTAS.propietarioPrincipal', [$cliente1, $cliente2]);
                $saldos_filtro->whereBetween('PROC_CXC.cliente', [$cliente1, $cliente2]);
            }
        }
        if (!is_null($plazo)) {
            switch ($plazo) {
                case 'A partir del corriente':
                    $saldos->where('proc_ventas.fechaVencimiento', '>=', Carbon::today());
                    $saldos_filtro->where('proc_cxc.fechaVencimiento', '>=', Carbon::today()); 
                    break;
                case 'A partir del 1 al 15':
                    $saldos->where(function ($query) {
                        $query->where('proc_ventas.fechaVencimiento', '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 15 DAY)'), '>=', Carbon::today());
                    });
                    $saldos_filtro->where(function ($query) {
                        $query->where('proc_cxc.fechaVencimiento', '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 15 DAY)'), '>=', Carbon::today());
                    });
                    break;
                case 'A partir del 16 al 30':
                    $saldos->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 15 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 30 DAY)'), '>=', Carbon::today());
                    });
                    $saldos_filtro->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 15 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 30 DAY)'), '>=', Carbon::today());
                    });
                    break;
                case 'A partir del 31 al 60':
                    $saldos->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 30 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 60 DAY)'), '>=', Carbon::today());
                    });
                    $saldos_filtro->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 30 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 60 DAY)'), '>=', Carbon::today());
                    });
                    break;
                case 'A partir del 61 al 90':
                    $saldos->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 60 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 90 DAY)'), '>=', Carbon::today());
                    });
                    $saldos_filtro->where(function ($query) {
                        $query->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 60 DAY)'), '<', Carbon::today())
                              ->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 90 DAY)'), '>=', Carbon::today());
                    });
                    break;
                case 'Más de 90 días':
                    $saldos->where(DB::raw('DATE_ADD(proc_ventas.fechaVencimiento, INTERVAL 90 DAY)'), '<', Carbon::today());
                    $saldos_filtro->where(DB::raw('DATE_ADD(proc_cxc.fechaVencimiento, INTERVAL 90 DAY)'), '<', Carbon::today());
                    break;
            }
        }
        if (!is_null($moneda)) {
            if ($moneda != 'Todos') {
                $saldos->where('PROC_VENTAS.moneda', '=', $moneda);
                $saldos_filtro->where('PROC_CXC.moneda', '=', $moneda);
            }
        }
        return $saldos->union($saldos_filtro);
        
    }
    public function scopewhereVentasMovimiento($query, $movimiento)
    {
        if (!is_null($movimiento)) {
            if ($movimiento == 'Todos') {
                return $query;
            } else if ($movimiento == 'Mensualidad') {
                return $query->where('movimiento', 'like', '%' . $movimiento . '%');
            }
            return $query->where('movimiento', $movimiento);
        }
        return $query;
    }
    public function scopewhereVentasFecha($query, $fecha)
    {
        if (!is_null($fecha)) {
            switch ($fecha) {
                case 'Hoy':
                    $fechaHoy = Carbon::now()->format('Y-m-d');
                    $query->whereDate('fechaEmision', '=', $fechaHoy);
                    break;
                case 'Ayer':
                    $fechaAyer = Carbon::now()->subDay()->format('Y-m-d');
                    $query->whereDate('fechaEmision', '=', $fechaAyer);
                    break;
                case 'Semana':
                    $query->whereBetween('fechaEmision', [
                        Carbon::now()->startOfWeek(Carbon::SUNDAY),
                        Carbon::now()->endOfWeek(Carbon::SATURDAY)
                    ]);
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
                    break;
                case 'Año pasado':
                    $fechaAnioAnt = Carbon::now()->subYear()->format('Y');
                    $query->whereYear('fechaEmision', '=', $fechaAnioAnt);
                    break;
                case 'Rango de fechas':
                    $fechaInicio = request()->get('inputFechaInicio');
                    $fechaFinal = request()->get('inputFechaFinal');
                    $query->whereBetween('fechaEmision', [$fechaInicio, $fechaFinal]);
                    break;
                case 'Todos':
                    break;
            }
            return $query;
        }
        return $query;
    }
    public function scopewhereVentasEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }

    public function scopewhereVentasTipo($query, $tipo)
    {
        if (!is_null($tipo)) {
            return $query->where('tipoContrato', $tipo);
        }
        return $query;
    }
    public function scopewhereVentasUsuario($query, $usuario)
    {
        if (!is_null($usuario)) {
            if ($usuario == 'Todos') {
                return $query;
            }
            return $query->where('user_id', $usuario);
        }
        return $query;
    }
    public function scopewhereVentasSucursal($query, $sucursal)
    {
        if (!is_null($sucursal)) {
            if ($sucursal == 'Todos') {
                return $query->where('idSucursal', '=', session('sucursal')->idSucursal);
            }
            return $query->where('idSucursal', $sucursal);
        }
        return $query;
    }
    public function scopewhereVentasMoneda($query, $moneda)
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
    public function getCXC()
    {
        $facturaCxC = $this->hasOne(CxC::class, 'origenId', 'idVenta')->where('origenTipo', 'Ventas')
        ->where('origenTipo', 'Ventas')
        ->where('estatus', 'CONCLUIDO');
        return $facturaCxC;
    }
    public function getTes()
    {
        return $this->where('origenTipo', 'Ventas')
            ->join('proc_tesoreria', 'proc_ventas.folioMov', '=', 'proc_tesoreria.origenId')
            ->where('proc_tesoreria.estatus', 'CONCLUIDO');
    }
    public function obtenerDatos()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        $saldos = Ventas::where('proc_ventas.idEmpresa', '=', session('company')->idEmpresa)
            ->where('proc_ventas.estatus', '=', 'PENDIENTE')
            ->where('proc_ventas.moneda', '=', $param != null ? $param->idMoneda : 1)
            ->select(
                'proc_ventas.idVenta', 
                'proc_ventas.movimiento', 
                'proc_ventas.folioMov', 
                'proc_ventas.claveProyecto', 
                'proc_ventas.moneda', 
                'proc_ventas.tipoCambio', 
                'proc_ventas.fechaEmision', 
                'proc_ventas.propietarioPrincipal', 
                'proc_ventas.condicionPago', 
                'proc_ventas.fechaVencimiento', 
                'proc_ventas.estatus', 
                'proc_ventas.idEmpresa', 
                'proc_ventas.idSucursal', 
                'proc_ventas.tipoContrato', 
                'proc_ventas.saldo',
                'proc_ventas.origenId', 
                'proc_ventas.fechaAlta', 
                'proc_ventas.fechaCambio', 
                DB::raw('NULL as idCXC, NULL as diasMoratorios, NULL as origenTipo, NULL as origen, NULL as origenId')
            );
            
        $saldos_filtro = CxC::join('proc_ventas', 'proc_cxc.origenId', '=', 'proc_ventas.idVenta')
                    ->where('proc_cxc.idEmpresa', '=', session('company')->idEmpresa)
                    ->where('proc_ventas.estatus', '=', 'CONCLUIDO')
                    ->where('proc_ventas.movimiento', '=', 'Factura')
                    ->where('proc_cxc.origenTipo', 'Ventas')
                    ->where('proc_cxc.estatus', 'PENDIENTE')
                    ->where('proc_cxc.moneda', '=', $param != null ? $param->idMoneda : 1)
                    ->select(
                        'proc_ventas.idVenta', 
                        'proc_cxc.movimiento as cxc_movimiento', 
                        'proc_cxc.folioMov as cxc_folioMov',
                        'proc_ventas.claveProyecto', 
                        'proc_cxc.moneda', 
                        'proc_cxc.tipoCambio', 
                        'proc_cxc.fechaEmision as cxc_fechaEmision', 
                        'proc_cxc.cliente as propietarioPrincipal2', 
                        'proc_cxc.condicion', 
                        'proc_cxc.fechaVencimiento', 
                        'proc_cxc.estatus', 
                        'proc_cxc.idEmpresa', 
                        'proc_cxc.idSucursal', 
                        'proc_ventas.tipoContrato',
                        'proc_cxc.saldo', 
                        'proc_cxc.origenId', 
                        'proc_ventas.fechaAlta', 
                        'proc_cxc.fechaCambio', 
                        'proc_cxc.idCXC', 
                        'proc_cxc.diasMoratorios', 
                        'proc_cxc.origenTipo', 
                        'proc_cxc.origen', 
                        'proc_cxc.origenId'
                    );
        return $saldos->union($saldos_filtro)->get();
    }
    public function getMoneda()
    {
        return $this->hasOne('App\Models\config\CONF_MONEDA', 'idMoneda', 'moneda');
    }
    public function getCondition()
    {
        return $this->hasOne('App\Models\config\CONF_CONDICIONES_CRED', 'idCondicionesc', 'condicionPago');
    }
    public function getProyecto()
    {
        return $this->hasOne('App\Models\catalogos\CAT_PROYECTOS', 'idProyecto', 'proyecto');
    }
    public function getAgente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_AGENTES_VENTA', 'idAgentes', 'vendedor');
    }
    public function getEtiqueta()
    {
        return $this->hasOne('App\Models\catalogos\CAT_ETIQUETAS', 'idEtiqueta', 'etiqueta');
    }
    public function getModulo()
    {
        return $this->hasOne('App\Models\catalogos\CAT_MODULOS', 'clave', 'modulo');
    }
    public function getCliente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CLIENTES', 'idCliente', 'propietarioPrincipal');
    }
    public function getEmpresa()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }
    public function getDetalle()
    {
        return $this->hasMany('App\Models\proc\comercial\VentaDetalle', 'idVenta', 'idVenta');
    }

    public function getPayPal() 
    {
        return $this->hasOne('App\Models\proc\comercial\VentasPayPal', 'idVenta', 'idVenta');
    }
        
    
    public function getVentas()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        return $this->where('idEmpresa', '=', session('company')->idEmpresa)
                    ->where('moneda', '=', $param != null ? $param->idMoneda : 1);
    }
    public function getDetalles()
    {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $param = $parametro->monedaByCompany(session('company')->idEmpresa)->first();
        return $this->whereIn('movimiento', ['Contrato', 'Factura'])
            ->where('idEmpresa', '=', session('company')->idEmpresa)
            ->where('moneda', '=', $param != null ? $param->idMoneda : 1)
            ->join('proc_ventasd', 'proc_ventas.idVenta', '=', 'proc_ventasd.idVenta');
    }
    public function getPlan()
    {
        return $this->hasOne('App\Models\proc\comercial\VentaPlan', 'idVenta', 'idVenta');
    }
    public function getPlans()
    {
        return $this->hasMany(VentaPlan::class, 'idVenta', 'idVenta');
    }
    public function getCoprops()
    {
        return $this->hasMany(VentaCoprops::class, 'idVenta', 'idVenta');
    }
    public function getCorrida()
    {
        return $this->hasMany('App\Models\proc\comercial\VentaCorrida', 'idVenta', 'idVenta');
    }
    public function getCobro()
    {
        return $this->hasOne('App\Models\proc\comercial\VentasCobro', 'idVenta', 'idVenta');
    }
    public function getCobros()
    {
        return $this->hasMany('App\Models\proc\comercial\VentasCobro', 'idVenta', 'idVenta');
    }
    public function getFlujo()
    {
        return $this->hasMany(PROC_FLUJO::class, 'origenId', 'idVenta');
    }
    public function getArticulo()
    {
        return $this->hasOne('App\Models\catalogos\CAT_ARTICULOS', 'idArticulos', 'articulo');
    }
    public function getSucursal()
    {
        return $this->hasOne('App\Models\catalogos\CAT_SUCURSALES', 'idSucursal', 'idSucursal');
    }

    public function getCategoria()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class, 'categoria', 'idCategoria');
    }
    public function getGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class, 'grupo', 'idGrupo');
    }

    public function getUsuario()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }
    public function getFolio($mov)
    {
        $venta = $mov;
        $consecutivos = CONF_COSECUTIVOS::where('idEmpresa', '=', $venta->idEmpresa)->where('idSucursal', '=', $venta->idSucursal)->first();
        // dd($venta);
        if ($venta->folioMov == null) {
            $folio = $this->where('movimiento', '=', $venta->movimiento)
                ->where('idEmpresa', '=', $venta->idEmpresa)
                ->where('idSucursal', '=', $venta->idSucursal)
                ->max('folioMov');

            if ($folio == null) {
                if ($consecutivos != null) {
                   
                        if ($venta->movimiento == 'Contrato') {
                            $folio = $consecutivos->consContrato;
                            $folio = $folio + 1;
                            $consecutivos->consContrato = $folio;
                            $consecutivos->save();
                        }

                        if ($venta->movimiento == 'Factura') {
                            $folio = $consecutivos->consFactura;
                            $folio = $folio + 1;
                            $consecutivos->consFactura = $folio;
                            $consecutivos->save();
                        }

                } else {
                    $folio = 1;
                    if ($venta->movimiento == 'Contrato') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $venta->idEmpresa;
                        $consecutivos->idSucursal = $venta->idSucursal;
                        $consecutivos->consContrato = $folio;
                        $consecutivos->save();
                    }

                    if ($venta->movimiento == 'Factura') {
                        $consecutivos = new CONF_COSECUTIVOS();
                        $consecutivos->idEmpresa = $venta->idEmpresa;
                        $consecutivos->idSucursal = $venta->idSucursal;
                        $consecutivos->consFactura = $folio;
                        $consecutivos->save();
                    }
                }
            } else {

                if ($consecutivos != null) {

                    if ($venta->movimiento == 'Contrato') {
                        $folio = $consecutivos->consContrato;
                        $folio = $folio + 1;
                        $consecutivos->consContrato = $folio;
                        $consecutivos->save();
                    }
                    if ($venta->movimiento == 'Factura') {
                        $folio = $consecutivos->consFactura;
                        $folio = $folio + 1;
                        $consecutivos->consFactura = $folio;
                        $consecutivos->save();
                    }

                }
            }

            return $folio;
        } else {
            $folio = $venta->folioMov;

            return $folio;
        }
    }
    public function getOrigen()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'origenId');
    }

    function getMensualidades()
    {
        return $this->whereNotIn('movimiento', ['Contrato', 'Factura', 'Inversión Inicial'])->where('estatus', 'PENDIENTE')->get();
    }
    function getAuxiliar()
    {
        return $this->belongsTo('App\Models\utils\PROC_AUXILIAR', 'idModulo', 'idVenta');
    }
    public function getCopropietarios(VENTAS $venta){
        $copropietarios = array();
        $coprops = $venta->getCoprops;
        if ($venta->coPropietario != null) {
            $copropietarios[] = CAT_CLIENTES::find($venta->coPropietario);
        }
        if ($coprops != null) {
            foreach ($coprops as $key => $value) {
                $copropietarios[] = CAT_CLIENTES::find($value->coprop);
            }
        }
        return $copropietarios;
    }
}