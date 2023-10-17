<?php

namespace App\Http\Controllers\proc\comercial;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\AuxiliaresController;
use App\Http\Controllers\helpers\ProcSaldosController;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\catalogos\CAT_AGENTES_VENTA;
use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\CAT_MODULOS;
use App\Models\catalogos\CAT_ETIQUETAS;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_PROMOCIONES;
use App\Models\catalogos\CAT_SUCURSALES;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\comercial\VentaDetalle;
use App\Models\proc\comercial\VentaCorrida;
use App\Models\proc\comercial\VentaPlan;
use App\Models\proc\comercial\VentaCoprops;
use App\Models\proc\comercial\VentasCobro;
use App\Exports\VentasExport;
use App\Http\Controllers\proc\finanzas\CxController;
use App\Http\Controllers\proc\finanzas\TesoreriaController;
use App\Http\Requests\proc\VentaRequest;
use App\Mail\EnviarCorreo;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\proc\finanzas\CxC;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\User;
use App\Models\utils\PROC_FLUJO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VentasController extends Controller
{
    public $parametro, $monedas, $condiciones, $clientes, $modulos, $proyectos, $articulos, $etiquetas, $agentes, $promociones, $sucursales, $venta, $ventaPlan, $ventaCorrida, $coProp, $status, $mensaje, $usuarios, $cuentas, $formasPago;
    public $pagesize = 10;
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'POR CONFIRMAR',
        3 => 'CONCLUIDO',
        4 => 'CANCELADO',
    ];
    public function __construct(Ventas $ventas)
    {
        $this->venta = $ventas;
    }
    public function index()
    {
        $clientes = new CAT_CLIENTES();
        $usuarios = new User();
        $sucursales = new CAT_SUCURSALES();
        $monedas = new CONF_MONEDA();
        $parametro = new CONF_PARAMETROS_GENERALES();
        $ventas = $this->venta->ventasParametros(); 


        $parametroGeneral = $parametro->byCompany(session('company')->idEmpresa)->first();
        if($parametroGeneral == null || $parametroGeneral->monedaDefault == null){
            return redirect()->route('config.parametros-generales.index')->with('status', false)->with('message', 'No se ha configurado los parametros generales de la empresa');
        }
        // dd($sucursales->getSucursal(1)->toArray());
        return view('page.proc.ventas.index', [
            'clientes' => $clientes->getClientes(),
            'usuarios' => $usuarios->getUsuario()->toArray(),
            'sucursales' => $sucursales->getSucursal(session('sucursal')->idSucursal)->toArray(),
            'monedas' => $monedas->getMonedas(),
            'ventas' => $ventas,
            'parametro' => $parametro->monedaByCompany(session('company')->idEmpresa)->first(),
        ]);
    }
    public function create(Request $request)
    {

        try {
            // dd($request->all());
            $parametro = new CONF_PARAMETROS_GENERALES();
            $monedas = new CONF_MONEDA();
            $clientes = new CAT_CLIENTES();
            $condiciones = new CONF_CONDICIONES_CRED();
            $modulos = new CAT_MODULOS();
            $proyectos = new CAT_PROYECTOS();
            $articulos = new CAT_ARTICULOS();
            $etiquetas = new CAT_ETIQUETAS();
            $agentes = new CAT_AGENTES_VENTA();
            $promociones = new CAT_PROMOCIONES();
            $formasPago = new CONF_FORMAS_PAGO();
            $cuentas = new CAT_CUENTAS_DINERO();

            if ($request->has('venta')) {
                $ventaDescript = Crypt::decrypt($request->venta);
                $venta = $this->venta->find($ventaDescript);
            } else {
                $venta = new Ventas();
            }

            $validacionPermiso = $this->validarPermisosConsulta($venta);
            // dd($validacionPermiso->original['status']);
            if (!$validacionPermiso->original['status']) {
                // dd($validacionPermiso->original['message']);
                return redirect()->route('proc.ventas.index')->with('status', false)->with('message', $validacionPermiso->original['message']);
            }
            $movimientos = $this->obtenerMovimientos($venta);
            $saldos = new ProcSaldosController();

            // dd($this->venta->getCopropietarios($venta));
            return view('page.proc.ventas.create', [
                'parametro' => $parametro->monedaByCompany(session('company')->idEmpresa)->first(),
                'monedas' => $monedas->getMonedas(),
                'clientes' => $clientes->getClientes(),
                'condiciones' => $condiciones->getCondicionies(),
                'modulos' => $modulos->getModulos(),
                'proyectos' => $proyectos->getProjects(),
                'articulos' => $articulos->getArticulos(),
                'etiquetas' => $etiquetas->getEtiquetas(),
                'vendedor' => $agentes->getAgentes(),
                'promociones' => $promociones->getPromociones(),
                'formasPago' => $formasPago->getFormasPago(),
                'cuentas' => $cuentas->getCuentasEmpresa(session('company')->idEmpresa),
                'venta' => $venta,
                'saldoGlobalCliente' => $saldos->getSaldosCliente($venta->propietarioPrincipal, 'CXC'),
                'movimientos' => $movimientos
            ]);
        } catch (\Exception $e) {
            
            return redirect()->route('proc.ventas.index')->with('status', false)->with('message', 'No se ha podido cargar la venta: ' . $e->getMessage() . ' ' . $e->getLine());
        }
    }
    public function storeFactura(Request $request){
        try {
            $data = $request->all();
            // dd($data);
            $data['inputID'] != null ? $venta = $this->venta->find($data['inputID']) : $venta = $this->venta;

            $venta->estatus = $this->estatus[0];

            $this->guardarVenta($venta, $data);
            $this->eliminarArticulos($data);
            $create = $venta->save();
            if ($data['inputID'] == null) {
                $lastVenta = $venta::latest('idVenta')->first();
                $mensaje = 'Se ha creado correctamente la venta';
            } else {
                $lastVenta = $venta;
                $mensaje = 'Se ha actualizado correctamente la venta';
            }

            if ($create) {
                $this->guardarArticulos($data, $lastVenta);
                $this->guardarCobro($data, $lastVenta);
                $this->guardarPlanVenta($data, $lastVenta);
                $this->guardarCorrida($data, $lastVenta);
                $this->guardarCoprops($data, $lastVenta);

                $this->mensaje = $mensaje;
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear la venta';
            }

        } catch (\Exception $e) {
            // dd($e);
            $this->mensaje = 'No se ha podido crear la venta: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status,'message' => $this->mensaje,'id' => $lastVenta->idVenta]);
        }

        return response()->json(['status' => $this->status,'message' => $this->mensaje ,'id' => Crypt::encrypt($lastVenta->idVenta)]);


    }
    public function store(VentaRequest $request)
    {
        try {
            // dd($request->all());
            $data = $request->validated();
            // dd($data);
            $data['inputID'] != null ? $venta = $this->venta->find($data['inputID']) : $venta = $this->venta;

            if($data['inputEstatus'] != 'POR CONFIRMAR'){

                $venta->estatus = $this->estatus[0];
            }

            $this->guardarVenta($venta, $data);
            $this->eliminarArticulos($data);
            $create = $venta->save();
            if ($data['inputID'] == null) {
                $lastVenta = $venta::latest('idVenta')->first();
                $mensaje = 'Se ha creado correctamente la venta';
            } else {
                $lastVenta = $venta;
                $mensaje = 'Se ha actualizado correctamente la venta';
            }

            if ($create) {
                $this->guardarArticulos($data, $lastVenta);
                $this->guardarCobro($data, $lastVenta);
                $this->guardarPlanVenta($data, $lastVenta);
                $this->guardarCorrida($data, $lastVenta);
                $this->guardarCoprops($data, $lastVenta);

                $this->mensaje = $mensaje;
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear la venta';
            }

        } catch (\Exception $e) {
            // dd($e);
            $this->mensaje = 'No se ha podido crear la venta: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return redirect()->route('proc.ventas.index')->with('status', $this->status)->with('message', $this->mensaje);
        }

        return redirect()->route('proc.ventas.create', ['venta' => Crypt::encrypt($lastVenta->idVenta)])->with('status', $this->status)->with('message', $this->mensaje);

    }
    public function destroy(Request $request)
    {
        try {
            $venta = $this->venta->find($request->id);
            $venta->getCorrida->each->delete();
            $venta->getPlans->each->delete();
            $venta->getDetalle->each->delete();
            $venta->getCoprops->each->delete();
            $venta->getCobros->each->delete();
            $venta->getVenta->delete();

            return response()->json([
                'status' => true,
                'message' => 'Se ha eliminado correctamente la venta',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'No se ha podido eliminar la venta'. $e->getMessage() . ' ' . $e->getLine(),
            ]);
        }
    }
    public function copy(Request $request)
    {
        // Venta
        $venta = $this->venta->find($request->id);
        if ($venta != null) {
            $newVenta = $venta->replicate();
            $newVenta->folioMov = null;
            $newVenta->claveProyecto = null;
            $newVenta->estatus = $this->estatus[0];
            $newVenta->fechaEmision = Carbon::now();
            $newVenta->save();
        }
        // Venta corrida
        // $ventaCorrida = $venta->getCorrida;
        // // dd($ventaCorrida);
        // if ($ventaCorrida->count() > 0) {
        //     foreach ($ventaCorrida as $key => $value) {
        //         $newVentaCorrida = $value->replicate();
        //         $newVentaCorrida->idVenta = $newVenta->idVenta;
        //         $newVentaCorrida->save();
        //     }
        // }
        // Venta plan
        // $ventaPlan = $venta->getPlan;
        // if ($ventaPlan != null) {
        //     $newVentaPlan = $ventaPlan->replicate();
        //     $newVentaPlan->idVenta = $newVenta->idVenta;
        //     $newVentaPlan->save();
        // }
        // Venta detalle
        $ventaDetalle = $venta->getDetalle;
        if ($ventaDetalle->count() > 0) {
            foreach ($ventaDetalle as $key => $value) {
                $newVentaDetalle = $value->replicate();
                $newVentaDetalle->idVenta = $newVenta->idVenta;
                $newVentaDetalle->save();
            }
        }
        // Venta coprop
        $ventaCoprop = $venta->getCoprops;
        if ($ventaCoprop != null) {
            foreach ($ventaCoprop as $key => $value) {
                $newVentaCoprop = $value->replicate();
                $newVentaCoprop->idVenta = $newVenta->idVenta;
                $newVentaCoprop->save();
            }
        }
        // Venta cobro
        $ventaCobro = $venta->getCobros;
        if ($ventaCobro->count() > 0) {
            foreach ($ventaCobro as $key => $value) {
                $newVentaCobro = $value->replicate();
                $newVentaCobro->idVenta = $newVenta->idVenta;
                $newVentaCobro->save();
            }
        }

        return response()->json(['status' => true, 'message' => 'Se ha copiado correctamente la venta', 'id' => Crypt::encrypt($newVenta->idVenta)]);
    }
    public function ventasAction(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $folio = $data['inputFolio'];
        $cliente = $data['selectCliente'];
        $movimiento = $data['selectMovimiento'] == 'Todos' ? $data['selectMovimiento'] : $data['selectMovimiento'];
        $estatus = $data['selectEstatus'] == 'Todos' ? $data['selectEstatus'] : $data['selectEstatus'];
        $fecha = $data['selectFecha'] == 'Todos' ? $data['selectFecha'] : $data['selectFecha'];
        $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
        $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];
        $usuario = $data['selectUsuario'] == 'Todos' ? $data['selectUsuario'] : (int) $data['selectUsuario'];
        $sucursal = $data['selectSucursal'] == 'Todos' ? $data['selectSucursal'] : (int) $data['selectSucursal'];
        $moneda = $data['selectMoneda'] == 'Todos' ? $data['selectMoneda'] : (int) $data['selectMoneda'];
        // dd($data);
        switch ($request->input('action')) {
            case 'Búsqueda':
                $ventas_filtro = Ventas::whereVentasFolio($folio)
                    ->whereVentasCliente($cliente)
                    ->whereVentasMovimiento($movimiento)
                    ->whereVentasEstatus($estatus)
                    ->whereVentasFecha($fecha)
                    ->whereVentasUsuario($usuario)
                    ->whereVentasSucursal($sucursal)
                    ->whereVentasMoneda($moneda)
                    ->orderBy('idVenta', 'DESC')
                    ->get();
                // dd($ventas_filtro);
                return redirect()->route('proc.ventas.index')->with('ventas_filtro', $ventas_filtro)
                                                             ->with('inputFolio', $folio)
                                                             ->with('selectCliente', $cliente)
                                                             ->with('selectMovimiento', $movimiento)
                                                             ->with('selectEstatus', $estatus)
                                                             ->with('selectFecha', $fecha)
                                                             ->with('inputFechaInicio', $fechaInicio)
                                                             ->with('inputFechaFinal', $fechaFinal)
                                                             ->with('selectUsuario', $usuario)
                                                             ->with('selectSucursal', $sucursal)
                                                             ->with('selectMoneda', $moneda);

            case 'Exportar excel':
                $ventas = new VentasExport($folio, $cliente, $movimiento, $estatus, $fecha, $usuario, $sucursal, $moneda);
                return Excel::download($ventas, 'Ventas.xlsx');

        }
    }
    public function afectar(Request $request)
    {
        try {
            $data = $request->all();
            //  dd($data);
            $data['inputID'] != null ? $venta = $this->venta->find($data['inputID']) : $venta = $this->venta;

            $this->guardarVenta($venta, $data);
            // dd($venta);
            $this->updateFechaCambio($venta);
            $this->eliminarArticulos($data);
            $venta->estatus = $this->asignarEstatus($data['selectMovimiento'], $data['inputEstatus']);
            $venta->folioMov = $venta->getFolio($venta);
            //  dd($venta);
            $venta->claveProyecto = $this->agregarFolio($venta);
            $venta->user_id = $data['inputUser'];

            $create = $venta->save();

            if ($data['inputID'] == null) {
                $lastVenta = $venta::latest('idVenta')->first();
            } else {
                $lastVenta = $venta;
            }
            if ($create) {
                $this->guardarArticulos($data, $lastVenta);
                $this->guardarCobro($data, $lastVenta);
                $this->guardarPlanVenta($data, $lastVenta);
                $this->guardarCorrida($data, $lastVenta);
                $this->guardarCoprops($data, $lastVenta);
                $this->enviarCorreo($data, $lastVenta);
                $this->concluirFactura($lastVenta);

                $this->mensaje = 'Se ha afectado correctamente la venta';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido afectar la venta';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido afectar la venta: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }
        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastVenta->idVenta)]);
    }
    public function updateFechaCambio($venta)
    {
        $venta->fechaCambio = Carbon::now()->toDateTime();
        $venta->save();
    }
    public function generarFactura(Request $request)
    {
        try {
            $data = $request->all();
            // dd($data);
            $venta = $this->venta;

            $movimientoOld = $data['selectMovimiento'];
            $folioOld = $data['inputFolio'];

            $data['selectMovimiento'] = 'Factura';
            // $data['inputClave'] = null;
            $data['inputFolio'] = null;
            //en caso de que falle algo de la factura cambiar al metodo de guardarVenta
            $this->guardarVentaFactura($venta, $data);
            // $this->guardarVenta($venta, $data);
            $venta->estatus = $this->estatus[0];
            $venta->origenId = $data['inputID'];
            $venta->total = $data['inputSaldo'] != null ? str_replace(['$', ','], '', $data['inputSaldo']) : 0;
            $venta->saldo = $data['inputSaldo'] != null ? str_replace(['$', ','], '', $data['inputSaldo']) : 0;
            $create = $venta->save();

            if ($data['inputID'] == null) {
                $lastVenta = $venta::latest('idVenta')->first();
            } else {
                $lastVenta = $venta;
            }
            if ($create) {
                //para guardar artículos
                $this->armarDetalle($data, $lastVenta, $movimientoOld, $folioOld);
                $this->guardarArticulos($data, $lastVenta, true, $movimientoOld, $folioOld, false);
                $this->guardarPlanVenta($data, $lastVenta);
                $this->guardarCorrida($data, $lastVenta);
                $this->guardarCoprops($data, $lastVenta);

                $this->mensaje = 'Se ha generado correctamente la factura';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido generar la factura';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido generar la factura: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }
        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastVenta->idVenta)]);
    }
    public function asignarEstatus($movimiento, $estado)
    {
        $estatus = '';

        if ($movimiento == 'Contrato' && $estado == 'SIN AFECTAR') {
            $estatus = $this->estatus[2];
        } else if ($movimiento == 'Factura' && $estado == 'SIN AFECTAR') {
            $estatus = $this->estatus[3];
        }

        if ($movimiento == 'Contrato' && $estado == 'POR CONFIRMAR') {
            $estatus = $this->estatus[3];
        }
        return $estatus;
    }
    public function guardarPlanVenta($data, $venta)
    {
        $ObjPlan = json_decode($data['inputTablePlanVenta']);

        if ($ObjPlan != null) {
            if ($venta->getPlan != null) {
                $planVenta = VentaPlan::find($venta->getPlan->idPlan);
            } else {
                $planVenta = new VentaPlan();
            }
            $planVenta->idVenta = $venta->idVenta;
            $planVenta->precioInmueble = $ObjPlan[0]->precioInmueble;
            $planVenta->inversionInicial = $ObjPlan[0]->inversionInicial;
            $planVenta->porcentajeInversion = $ObjPlan[0]->inversionInicialPorcentaje;
            $planVenta->mensualidades = $ObjPlan[0]->mensualidades;
            $planVenta->porcentajeMensualidades = $ObjPlan[0]->mensualidadesPorcentaje;
            $planVenta->finiquito = $ObjPlan[0]->finiquito;
            $planVenta->porcentajeFiniquito = $ObjPlan[0]->finiquitoPorcentaje;
            $planVenta->save();
        }
    }
    public function guardarCorrida($data, $venta)
    {
        $ObjCorrida = json_decode($data['inputResultadoCorrida']);
        // dd($ObjCorrida);
        if ($ObjCorrida != null) {
            if ($venta->idVenta != null) {
                $ventaCorrida = VentaCorrida::where('idVenta', $venta->idVenta)->delete();
            }
            foreach ($ObjCorrida as $value) {
                $newCorrida = new VentaCorrida();
                $newCorrida->idVenta = $venta->idVenta;
                $newCorrida->idCliente = $venta->propietarioPrincipal;
                $newCorrida->noMensualidad = $value->noMensualidad;
                $newCorrida->mensualidad = $value->mensualidad;
                $newCorrida->fechaPagoCorrida = Carbon::parse($value->fecha);
                $newCorrida->montoCorrida = $value->monto;
                $newCorrida->ivaCorrida = $value->iva;
                $newCorrida->totalCorrida = $value->total;
                $newCorrida->subTotalMonto = $value->totalMonto;
                $newCorrida->subTotalIva = $value->totalIva;
                $newCorrida->total = $value->sumaTotal;
                $newCorrida->save();

            }
        }
    }
    public function guardarArticulos($data, $venta, $nuevo = null, $movimiento = null, $folio = null, $sumar = null)
    {
        $articulos = $data['inputArticles'];

        $articulos = json_decode($articulos);
        if ($articulos) {
            foreach ($articulos as $articulo) {
                if ($articulo->articulo != "") {
                    if ($articulo->id != null) {
                        $articuloDetalle = VentaDetalle::find($articulo->id);
                    } else {
                        $articuloDetalle = new VentaDetalle();
                    }

                    if ($nuevo) {
                        $articuloDetalle = new VentaDetalle();

                        $articuloDetalle->origen = $movimiento;
                        $articuloDetalle->origenId = $folio;

                        if ($sumar) {
                            $venta->subTotal = $venta->subTotal + $articulo->importe;
                            $venta->impuestos = $venta->impuestos + $articulo->iva;
                            $venta->total = $venta->total + $articulo->total;
                            $venta->saldo = $venta->total;
                            $venta->save();
                        }
                    }
                    $articuloDetalle->idVenta = $venta->idVenta;
                    $articuloDetalle->articulo = $articulo->articulo;
                    $articuloDetalle->descripcionArticulo = $articulo->descripcion;
                    $articuloDetalle->cantidad = $articulo->cantidad;
                    $articuloDetalle->unidadVenta = $articulo->unidad;
                    $articuloDetalle->precioArticulo = $articulo->precio;
                    $articuloDetalle->importe = $articulo->importe;
                    $articuloDetalle->IVA = $articulo->porcentajeIva;
                    $articuloDetalle->importeIVA = $articulo->iva;
                    $articuloDetalle->importeTotal = $articulo->total;
                    $articuloDetalle->save();
                }
            }
        }
    }
    public function guardarCobro($data, $venta)
    {
        $cobro = $data['inputCobro'];

        $cobro = json_decode($cobro);
        // dd($cobro);
        if ($cobro) {
            if ($venta->getCobro != null) {
                $cobroVenta = VentasCobro::find($venta->getCobro->idCobro);
            } else {
                $cobroVenta = new VentasCobro();
            }
            $cobroVenta->idVenta = $venta->idVenta;
            $cobroVenta->formaCobro = $cobro->formaCobro;
            $cobroVenta->importe = $cobro->importe;
            $cobroVenta->cuentaDinero = $cobro->cuentaDinero;
            $cobroVenta->formaCambio = $cobro->formaCambio;
            $cobroVenta->informacionAdicional = $cobro->informacionAdicional;
            $cobroVenta->totalFactura = $cobro->totalFactura;
            $cobroVenta->totalCobrado = $cobro->totalCobrado;
            $cobroVenta->cambio = $cobro->cambio;
            $cobroVenta->saldo = $cobro->saldo;

            $cobroVenta->save();
        }
    }
    public function guardarVenta($venta, $data)
    {
        $venta->movimiento = $data['selectMovimiento'];
        $venta->folioMov = $data['inputFolio'];
        $venta->claveProyecto = $data['inputClave'];
        $venta->moneda = $data['selectMoneda'];
        $venta->tipoCambio = str_replace(['$', ','], '', $data['inputTipoCambio']);
        $venta->fechaEmision = Carbon::now();
        $venta->proyecto = $data['selectProyecto'];
        $venta->modulo = $data['selectModulo'];
        $venta->valorOperacion = $data['inputValor'];
        $venta->nivelPiso = $data['inputNivel'];
        $venta->MT2 = $data['inputMT2'];
        $venta->numCajones = $data['inputCajones'];
        $venta->tipoModulo = $data['inputTipo'];
        $venta->observaciones = $data['inputObservaciones'];
        $venta->propietarioPrincipal = $data['selectProp'];
        $venta->importeAsignado = $data['inputAsignadoPropietario'] != null ? str_replace(['$', ','], '', $data['inputAsignadoPropietario']) : 0;
        $venta->coPropietario = $data['selectCoprop'];
        $venta->importeAsignadoCo = $data['impAsign'] != null ? str_replace(['$', ','], '', $data['impAsign']) : 0;
        $venta->condicionPago = $data['selectCondicion'];
        $venta->fechaVencimiento = $data['inputFechaVencimiento'];
        $venta->user_id = Auth::user()->user_id;
        $venta->idEmpresa = session('company')->idEmpresa;
        $venta->idSucursal = session('sucursal')->idSucursal;
        // dd($data['radioContrato']);
        $venta->tipoContrato = isset($data['radioContrato']) ? ($data['radioContrato'] == 'Venta' ? 1 : 0) : null;
        $venta->esquemaPago = isset($data['radioEsquema']) ? ($data['radioEsquema'] == 'Mensualidad' ? 1 : 0) : null;
        $venta->subTotal = $data['inputSubtotal'] != null ? str_replace(['$', ','], '', $data['inputSubtotal']) : 0;
        // $venta->subTotalOld = $data['inputSubtotal'] != null ? str_replace(['$', ','], '', $data['inputSubtotal']) : 0;
        $venta->impuestos = $data['inputImpuestos'] != null ? str_replace(['$', ','], '', $data['inputImpuestos']) : 0;
        // $venta->impuestosOld = $data['inputImpuestos'] != null ? str_replace(['$', ','], '', $data['inputImpuestos']) : 0;
        $venta->total = $data['inputTotal'] != null ? str_replace(['$', ','], '', $data['inputTotal']) : 0;
        $venta->totalOld = $data['inputTotal'] != null ? str_replace(['$', ','], '', $data['inputTotal']) : 0;
        $venta->renglones = $data['renglones'];
        //petaña financiamiento
        $venta->fechaContrato = $data['inputFechaContrato'];
        $venta->promocion = $data['selectPromo'];
        $venta->periocidad = $data['selectPeriodicidad'];
        $venta->fechaInicioMensualidad = $data['inputFechaIni'];
        $venta->mantenimientoAnual = $data['inputAnual'] != null ? str_replace(['$', ','], '', $data['inputAnual']) : 0;
        $venta->financiamientoMeses = $data['inputMeses'];
        $venta->enganche = $data['inputEnganche'] != null ? str_replace(['%', ','], '', $data['inputEnganche']) : 0;
        $venta->fechaFinContrato = $data['inputFechaFin'];
        $venta->subTotalMonto = $data['inputMonto'] != null ? str_replace(['$', ','], '', $data['inputMonto']) : 0;
        $venta->subTotalIva = $data['inputIVA'] != null ? str_replace(['$', ','], '', $data['inputIVA']) : 0;
        $venta->totalCorrida = $data['inputTotalTabla'] != null ? str_replace(['$', ','], '', $data['inputTotalTabla']) : 0;
        //pestaña comisiones
        $venta->tipoComision = isset($data['chekTipoComisión']) ? (int) $data['chekTipoComisión'] : null;
        $venta->etiqueta = $data['selectEtiqueta'];
        $venta->vendedor = $data['selectVendedor'];
        $venta->valorOperacionComision = $data['inputTotal'] != null ? str_replace(['$', ','], '', $data['inputTotal']) : 0;
        $venta->porcentajeEnganche = $data['inputEngancheC'] != null ? str_replace(['%', ','], '', $data['inputEngancheC']) : 0;
        $venta->importeEnganche = $data['inputImporteEnganche'] != null ? str_replace(['$', ','], '', $data['inputImporteEnganche']) : 0;
        $venta->montoComisionable = $data['inputMontoComisionable'] != null ? str_replace(['$', ','], '', $data['inputMontoComisionable']) : 0;
        $venta->porcentajeComisionable = $data['inputComisionable'] != null ? str_replace(['%', ','], '', $data['inputComisionable']) : 0;
        $venta->asesor = $data['inputAsesor'];
        $venta->porcentajeAsesor = $data['inputPorcentajeAsesor'] != null ? str_replace(['%', ','], '', $data['inputPorcentajeAsesor']) : 0;
        $venta->netoAsesor = $data['inputNetoAsesor'] != null ? str_replace(['$', ','], '', $data['inputNetoAsesor']) : 0;
        $venta->facturaAsesor = $data['importeFacAsesor'] != null ? str_replace(['$', ','], '', $data['importeFacAsesor']) : 0;
        $venta->formaPagoAsesor = $data['inputFormaPagoAsesor'];
        $venta->referido = $data['inputReferido'];
        $venta->porcentajeReferido = $data['inputPorcentajeReferido'] != null ? str_replace(['%', ','], '', $data['inputPorcentajeReferido']) : 0;
        $venta->netoReferido = $data['inputNetoReferido'] != null ? str_replace(['$', ','], '', $data['inputNetoReferido']) : 0;
        $venta->facturaReferido = $data['inputFacReferido'] != null ? str_replace(['$', ','], '', $data['inputFacReferido']) : 0;
        $venta->formaPagoReferido = $data['inputFormaPagoReferido'];
        $venta->broker = $data['inputBroker'];
        $venta->porcentajeBroker = $data['inputPorcentajeBroker'] != null ? str_replace(['%', ','], '', $data['inputPorcentajeBroker']) : 0;
        $venta->netoBroker = $data['inputNetoBroker'] != null ? str_replace(['$', ','], '', $data['inputNetoBroker']) : 0;
        $venta->facturaBroker = $data['inputFacBroker'] != null ? str_replace(['$', ','], '', $data['inputFacBroker']) : 0;
        $venta->formaPagoBroker = $data['inputFormaPagoBroker'];
        $venta->totalNeto = $data['inputSumaNeto'] != null ? str_replace(['$', ','], '', $data['inputSumaNeto']) : 0;
        $venta->totalFactura = $data['inputSumaImpFac'] != null ? str_replace(['$', ','], '', $data['inputSumaImpFac']) : 0;
        $venta->retencion = $data['inputRetencion'] != null ? str_replace(['$', ','], '', $data['inputRetencion']) : 0;
        $venta->totalPago = $data['inputTotalNeto'] != null ? str_replace(['$', ','], '', $data['inputTotalNeto']) : 0;

    }
    public function guardarVentaFactura($venta,$data){
        $venta->movimiento = $data['selectMovimiento'];
        $venta->folioMov = $data['inputFolio'];
        $venta->claveProyecto = $data['inputClave'];
        $venta->moneda = $data['selectMoneda'];
        $venta->tipoCambio = str_replace(['$', ','], '', $data['inputTipoCambio']);
        $venta->fechaEmision = Carbon::now();
        $venta->proyecto = $data['selectProyecto'];
        $venta->modulo = $data['selectModulo'];
        $venta->valorOperacion = $data['inputValor'];
        $venta->nivelPiso = $data['inputNivel'];
        $venta->MT2 = $data['inputMT2'];
        $venta->numCajones = $data['inputCajones'];
        $venta->tipoModulo = $data['inputTipo'];
        $venta->observaciones = $data['inputObservaciones'];
        $venta->propietarioPrincipal = $data['selectProp'];
        $venta->importeAsignado = $data['inputAsignadoPropietario'] != null ? str_replace(['$', ','], '', $data['inputAsignadoPropietario']) : 0;
        $venta->coPropietario = $data['selectCoprop'];
        $venta->importeAsignadoCo = $data['impAsign'] != null ? str_replace(['$', ','], '', $data['impAsign']) : 0;
        $venta->condicionPago = $data['selectCondicion'];
        $venta->fechaVencimiento = $data['inputFechaVencimiento'];
        $venta->user_id = Auth::user()->user_id;
        $venta->idEmpresa = session('company')->idEmpresa;
        $venta->idSucursal = session('sucursal')->idSucursal;
        // dd($data['radioContrato']);
        $venta->tipoContrato = isset($data['radioContrato']) ? ($data['radioContrato'] == 'Venta' ? 1 : 0) : null;
        $venta->esquemaPago = isset($data['radioEsquema']) ? ($data['radioEsquema'] == 'Mensualidad' ? 1 : 0) : null;
        $venta->subTotal = $data['inputSubtotal'] != null ? str_replace(['$', ','], '', $data['inputSubtotal']) : 0;
        // $venta->subTotalOld = $data['inputSubtotal'] != null ? str_replace(['$', ','], '', $data['inputSubtotal']) : 0;
        $venta->impuestos = $data['inputImpuestos'] != null ? str_replace(['$', ','], '', $data['inputImpuestos']) : 0;
        // $venta->impuestosOld = $data['inputImpuestos'] != null ? str_replace(['$', ','], '', $data['inputImpuestos']) : 0;
        $venta->total = $data['inputTotal'] != null ? str_replace(['$', ','], '', $data['inputTotal']) : 0;
        $venta->totalOld = $data['inputTotal'] != null ? str_replace(['$', ','], '', $data['inputTotal']) : 0;
        $venta->renglones = $data['renglones'];
        //petaña financiamiento
        $venta->fechaContrato = $data['inputFechaContrato'];
        $venta->promocion = $data['selectPromo'];
        $venta->periocidad = $data['selectPeriodicidad'];
        $venta->fechaInicioMensualidad = $data['inputFechaIni'];
        $venta->mantenimientoAnual = $data['inputAnual'] != null ? str_replace(['$', ','], '', $data['inputAnual']) : 0;
        $venta->financiamientoMeses = $data['inputMeses'];
        $venta->enganche = $data['inputEnganche'] != null ? str_replace(['%', ','], '', $data['inputEnganche']) : 0;
        $venta->fechaFinContrato = $data['inputFechaFin'];
        $venta->subTotalMonto = $data['inputMonto'] != null ? str_replace(['$', ','], '', $data['inputMonto']) : 0;
        $venta->subTotalIva = $data['inputIVA'] != null ? str_replace(['$', ','], '', $data['inputIVA']) : 0;
        $venta->totalCorrida = $data['inputTotalTabla'] != null ? str_replace(['$', ','], '', $data['inputTotalTabla']) : 0;
    }
    public function guardarCoprops($data, $venta)
    {
        $coProp = new VentaCoprops();
        if (isset($data['selectCopropArray']) && isset($data['impAsignArray'])) {
            $ObjCoprops = $data['selectCopropArray'];
            $ObjImporte = $data['impAsignArray'];
            // dd($venta);
            if ($ObjCoprops != null && $ObjImporte != null) {
                $deletecoprops = $coProp->where('idVenta', $venta->idVenta)->delete();
                foreach ($ObjCoprops as $key => $copropietario) {
                    if ($copropietario != null) {
                        $copropietarioDetalle = new VentaCoprops();
                        $copropietarioDetalle->idVenta = $venta->idVenta;
                        $copropietarioDetalle->propietarioPrincipal = $venta->propietarioPrincipal;
                        $copropietarioDetalle->coprop = $copropietario;
                        $copropietarioDetalle->importe = $ObjImporte[$key];
                        // dd($copropietarioDetalle);
                        $copropietarioDetalle->save();
                    }
                }
            }
        }

    }
    public function eliminarArticulos($data)
    {
        $articulosDelete = $data['inputArticlesDelete'];
        $articulosDelete = json_decode($articulosDelete);

        if ($articulosDelete) {
            foreach ($articulosDelete as $articulo) {
                if ($articulo->id != null) {
                    $articuloDetalle = VentaDetalle::find($articulo->id);
                    $articuloDetalle->delete();
                }
            }
        }
    }
    public function armarDetalle($data, $venta, $movimiento, $folio)
    {
        $articuloDetalle = new VentaDetalle();
        $articuloDetalle->idVenta = $venta->idVenta;
        $articuloDetalle->articulo = null;
        $articuloDetalle->descripcionArticulo = $movimiento;
        $articuloDetalle->cantidad = 1;
        $articuloDetalle->unidadVenta = null;
        $articuloDetalle->precioArticulo = null;
        $articuloDetalle->importe = $data['inputSubtotalOld'] != null ? str_replace(['$', ','], '', $data['inputSubtotalOld']) : 0;
        $articuloDetalle->IVA = 16;
        $articuloDetalle->importeIVA = $data['inputImpuestosOld'] != null ? str_replace(['$', ','], '', $data['inputImpuestosOld']) : 0;
        $articuloDetalle->importeTotal = $data['inputTotalOld'] != null ? str_replace(['$', ','], '', $data['inputTotalOld']) : 0;
        $articuloDetalle->origen = $movimiento;
        $articuloDetalle->origenId = $folio;
        $articuloDetalle->save();

        $venta->renglones = $venta->renglones + 1;
        $venta->save();
    }
    public function enviarCorreo($data, $venta)
    {
        $venta = $this->venta->find($venta->idVenta);
        if ($venta->movimiento == 'Contrato' && $venta->estatus == $this->estatus[3]) {
            $cliente = $venta->getCliente;
    
            // Verificar si el cliente ya tiene una contraseña generada
            if (!$cliente->contraseñaPortal) {
                $longitud = 8;
                $password = Str::random($longitud);
                $passwordCryt = Crypt::encrypt($password);
                $cliente->contraseñaPortal = $passwordCryt;
                $cliente->password = bcrypt($password);
                $cliente->save();
                $correo = new EnviarCorreo($cliente->correoElectronico1, $password,session('company')->nombreCorto,env('URL_PORTAL'));
                Mail::to($cliente->correoElectronico1)->send($correo);
                // enviar corrreos a copropietarios
            }

            $copropietarios = $this->venta->getCopropietarios($venta);
            // dd($copropietarios);
            if (count($copropietarios) > 0) {
                foreach ($copropietarios as $key => $value) {
                    if(!$value->contraseñaPortal){
                        $longitud = 8;
                        $password = Str::random($longitud);
                        $passwordCryt = Crypt::encrypt($password);
                        $value->contraseñaPortal = $passwordCryt;
                        $value->password = bcrypt($password);
                        $value->save();
                        $correo = new EnviarCorreo($value->correoElectronico1, $password,session('company')->nombreCorto,env('URL_PORTAL'));
                        Mail::to($value->correoElectronico1)->send($correo);
                    }
                }
            }
    
            $this->generarMovimientos($data, $venta);
            $this->venderModulo($venta);
        }
    }
    public function agregarFolio($venta)
    {
        $proyectos = new CAT_PROYECTOS();
        if (($venta != null && $venta->estatus == "POR CONFIRMAR") || $venta->estatus == 'CONCLUIDO') {
            $proyectoID = $venta->proyecto;
            $folioAsignado = $venta->folioMov;
            // dd($folioAsignado,$proyectoID);
            $proyectData = $proyectos->find($proyectoID);
            // dd($proyectData);
            if ($proyectData != null) {
                if ($proyectData->clave == 'TS') {
                    $folio = $proyectData->clave . '-' . $proyectData->añoFinProyecto . '-' . $folioAsignado;
                } else {
                    $folio = $proyectData->clave . '-' . date('Y') . '-' . $folioAsignado;
                }
            } else {
                $folio = date('Y') . '-' . $folioAsignado;
            }

            // dd($folio);
            return $folio;
        } else {
            $folio = $venta->claveProyecto;
            return $folio;
        }
    }
    public function generarMovimientos($data, $venta)
    {

        foreach ($venta->getCorrida as $corrida) {
            if (str_replace(['$', ','], '', $corrida->totalCorrida) != 0) {
                $ventaNew = new Ventas();
                $ventaNew->movimiento = $corrida->mensualidad;
                $ventaNew->folioMov = $corrida->noMensualidad;
                $ventaNew->asesor = $venta->asesor;
                $ventaNew->claveProyecto = $venta->claveProyecto;
                $ventaNew->moneda = $venta->moneda;
                $ventaNew->tipoCambio = $venta->tipoCambio;
                $ventaNew->fechaEmision = $corrida->fechaPagoCorrida;
                $ventaNew->proyecto = $venta->proyecto;
                $ventaNew->modulo = $venta->modulo;
                $ventaNew->propietarioPrincipal = $venta->propietarioPrincipal;
                $ventaNew->importeAsignado = $venta->importeAsignado;
                $ventaNew->coPropietario = $venta->coPropietario;
                $ventaNew->importeAsignadoCo = $venta->importeAsignadoCo;
                $ventaNew->condicionPago = $venta->condicionPago;
                $ventaNew->tipoModulo = $venta->tipoModulo;
                $ventaNew->fechaVencimiento = $corrida->fechaPagoCorrida;
                $ventaNew->estatus = $this->estatus[1];
                $ventaNew->user_id = $venta->user_id;
                $ventaNew->idEmpresa = $venta->idEmpresa;
                $ventaNew->idSucursal = $venta->idSucursal;
                $ventaNew->valorOperacion = $venta->valorOperacion;
                $ventaNew->nivelPiso = $venta->nivelPiso;
                $ventaNew->MT2 = $venta->MT2;
                $ventaNew->numCajones = $venta->numCajones;
                $ventaNew->tipoContrato = $venta->tipoContrato;
                $ventaNew->esquemaPago = $venta->esquemaPago;
                $ventaNew->importeMensualidad = str_replace(['$', ','], '', $corrida->totalCorrida);
                $ventaNew->subTotal = str_replace(['$', ','], '', $corrida->montoCorrida);
                $ventaNew->subTotalOld = str_replace(['$', ','], '', $corrida->montoCorrida);
                $ventaNew->impuestos = str_replace(['$', ','], '', $corrida->ivaCorrida);
                $ventaNew->impuestosOld = str_replace(['$', ','], '', $corrida->ivaCorrida);
                $ventaNew->total = str_replace(['$', ','], '', $corrida->totalCorrida);
                $ventaNew->totalOld = str_replace(['$', ','], '', $corrida->totalCorrida);
                $ventaNew->saldo = str_replace(['$', ','], '', $corrida->totalCorrida);
                $ventaNew->renglones = $venta->renglones;
                $ventaNew->fechaContrato = $venta->fechaContrato;
                $ventaNew->promocion = $venta->promocion;
                $ventaNew->periocidad = $venta->periocidad;
                $ventaNew->fechaInicioMensualidad = $venta->fechaInicioMensualidad;
                $ventaNew->mantenimientoAnual = $venta->mantenimientoAnual;
                $ventaNew->financiamientoMeses = $venta->financiamientoMeses;
                $ventaNew->enganche = $venta->enganche;
                $ventaNew->fechaFinContrato = $venta->fechaFinContrato;
                $ventaNew->subTotalIva = $venta->subTotalIva;
                $ventaNew->totalCorrida = $venta->totalCorrida;
                $ventaNew->tipoComision = $venta->tipoComision;
                $ventaNew->etiqueta = $venta->etiqueta;
                $ventaNew->vendedor = $venta->vendedor;
                $ventaNew->valorOperacionComision = $venta->valorOperacionComision;
                $ventaNew->porcentajeEnganche = $venta->porcentajeEnganche;
                $ventaNew->importeEnganche = $venta->importeEnganche;
                $ventaNew->porcentajeComisionable = $venta->porcentajeComisionable;
                $ventaNew->montoComisionable = $venta->montoComisionable;
                $ventaNew->asesor = $venta->asesor;
                $ventaNew->porcentajeAsesor = $venta->porcentajeAsesor;
                $ventaNew->netoAsesor = $venta->netoAsesor;
                $ventaNew->facturaAsesor = $venta->facturaAsesor;
                $ventaNew->formaPagoAsesor = $venta->formaPagoAsesor;
                $ventaNew->referido = $venta->referido;
                $ventaNew->porcentajeReferido = $venta->porcentajeReferido;
                $ventaNew->netoReferido = $venta->netoReferido;
                $ventaNew->facturaReferido = $venta->facturaReferido;
                $ventaNew->formaPagoReferido = $venta->formaPagoReferido;
                $ventaNew->broker = $venta->broker;
                $ventaNew->porcentajeBroker = $venta->porcentajeBroker;
                $ventaNew->netoBroker = $venta->netoBroker;
                $ventaNew->facturaBroker = $venta->facturaBroker;
                $ventaNew->formaPagoBroker = $venta->formaPagoBroker;
                $ventaNew->totalNeto = $venta->totalNeto;
                $ventaNew->totalFactura = $venta->totalFactura;
                $ventaNew->retencion = $venta->retencion;
                $ventaNew->totalPago = $venta->totalPago;
                $ventaNew->fechaAlta = Carbon::now();
                $ventaNew->origenId = $venta->idVenta;

                $ventaNew->save();
                $lastID = $ventaNew::latest('idVenta')->first();

                $this->agregarFlujo($venta, $lastID);
                $this->guardarCoprops($data,$lastID);
                if ($ventaNew->movimiento != 'Inversión Inicial' && $ventaNew->movimiento != 'Finiquito') {
                    $this->guardarArticulos($data, $lastID, true, null, null, true);
                }
            }
        }

    }
    public function venderModulo($venta)
    {
        $modulo = $venta->getModulo;
        $modulo->estatus = 'Vendido';
        $modulo->save();
    }
    public function concluirFactura($venta)
    {
        $venta = $this->venta->find($venta->idVenta);

        if ($venta->movimiento == 'Factura' && $venta->estatus == $this->estatus[3]) {
            $origen = $venta->getOrigen;
            if ($origen != null) {
                $this->agregarFlujo($origen, $venta);
            }

            if ($venta->getCondition->tipoCondicion == 'Crédito') {
                $auxiliares = new AuxiliaresController();
                $auxiliares->agregarAuxiliarVentas($venta);
                $auxiliares->agregarSaldoCliente($venta);

                $cxc = new CxController(new CxC());
                $cxc->agregarCxC($venta);

                if ($origen != null) {
                    if ($venta->total >= $origen->total) {
                        $origen->saldo = 0;
                        $origen->estatus = $this->estatus[3];
                        $origen->save();
                    }
                }

            } else {
                $tesoreria = new TesoreriaController(new Tesoreria());
                $tesoreria->agregarTesoreria($venta, 'Ventas');

                if ($origen != null) {
                    if ($venta->getCobro->importe >= $origen->total) {
                        $origen->saldo = 0;
                        $origen->estatus = $this->estatus[3];
                        $origen->save();
                    } else {
                        $origen->saldo = $origen->saldo - $venta->getCobro->importe;
                        $subtotal = $origen->saldo / 1.16;
                        $impuestos = $subtotal * 0.16;
                        $origen->subTotal = $subtotal;
                        $origen->impuestos = $impuestos;
                        $origen->total = $origen->total - $venta->getCobro->importe;
                        $origen->save();
                    }
                }
            }


        }
    }
    public function agregarFlujo($origen, $destino)
    {
        $flujo = new PROC_FLUJO();
        $flujo->idEmpresa = $origen->idEmpresa;
        $flujo->idSucursal = $origen->idSucursal;
        $flujo->origenModulo = 'Ventas';
        $flujo->origenId = $origen->idVenta;
        $flujo->origenMovimiento = $origen->movimiento;
        $flujo->origenFolio = $origen->folioMov;
        $flujo->destinoModulo = 'Ventas';
        $flujo->destinoId = $destino->idVenta;
        $flujo->destinoMovimiento = $destino->movimiento;
        $flujo->destinoFolio = $destino->folioMov;
        $flujo->cancelado = 0;
        $flujo->save();
    }
    public function cancelar(Request $request)
    {
        try {
            $venta = $this->venta->find($request->idVenta);

            $validar = $this->validarCancelacion($venta);
            // dd($validar->original['status']);
            if ($validar->original['status']) {
                return response()->json(['status' => false, 'mensaje' => $validar->original['mensaje']]);
            }

            if ($venta->movimiento == 'Contrato') {
                $cancelado = $this->cancelarContrato($venta);

            } else {
                $cancelado = $this->cancelarFactura($venta);
            }
            if ($cancelado) {
                $this->mensaje = 'Se ha cancelado correctamente la venta';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido cancelar la venta';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al cancelar la venta' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'mensaje' => $this->mensaje]);
        }
        return response()->json(['status' => $this->status, 'mensaje' => $this->mensaje]);
    }
    public function cancelarContrato($venta)
    {
        $cancelado = false;
        if ($venta->estatus == $this->estatus[2]) {
            $venta->estatus = $this->estatus[4];
            $venta->save();
            $cancelado = true;
        }

        if ($venta->estatus == $this->estatus[3]) {
            $flujo = new PROC_FLUJO();
            $movimientosGenerados = $flujo->getMovientosPosteriores($venta->idEmpresa, $venta->idSucursal, 'Ventas', $venta->idVenta);

            if (count($movimientosGenerados) > 0) {
                foreach ($movimientosGenerados as $movimiento) {

                    $movimiento->cancelado = 1;
                    $movimiento->save();

                    $movimientoVenta = $this->venta->find($movimiento->destinoId);
                    $movimientoVenta->estatus = $this->estatus[4];
                    $movimientoVenta->save();

                    $destinoVenta = Ventas::where('origenId', $movimientoVenta->idVenta)->get();
                    if (count($destinoVenta) > 0) {
                        foreach ($destinoVenta as $destino) {
                            if ($destino->estatus != $this->estatus[3]) {
                                $destino->estatus = $this->estatus[4];
                                $destino->save();
                            }

                        }
                    }
                }
            }

            $venta->getModulo->estatus = 'Disponible';
            $venta->getModulo->save();

            $venta->estatus = $this->estatus[4];
            $venta->save();
            $cancelado = true;
        }

        return $cancelado;

    }
    function validarCancelacion($venta)
    {

        $movimientos = false;

        if ($venta->estatus == $this->estatus[3] && $venta->movimiento == 'Contrato') {
            $flujo = new PROC_FLUJO();
            $movimientosGenerados = $flujo->getMovientosPosteriores($venta->idEmpresa, $venta->idSucursal, 'Ventas', $venta->idVenta);

            if (count($movimientosGenerados) > 0) {
                // dd($movimientosGenerados);
                foreach ($movimientosGenerados as $movimiento) {
                    $movimientoVenta = $this->venta->find($movimiento->destinoId);

                    if ($movimientoVenta->totalOld != $movimientoVenta->saldo) {
                        $movimientos = true;
                        break;
                    }
                }
            }

            return response()->json(['status' => $movimientos, 'mensaje' => 'No se puede cancelar el contrato, ya que se han generado movimientos posteriores.']);

        }

        if ($venta->estatus == $this->estatus[3] && $venta->movimiento == 'Factura' && $venta->getCondition->tipoCondicion == 'Crédito') {
            $flujo = new PROC_FLUJO();
            $movimientoGenerado = $flujo->getMovientoPosterior($venta->idEmpresa, $venta->idSucursal, 'Ventas', $venta->idVenta);

            if ($movimientoGenerado != null) {
                $cxcVenta = CxC::where('idCXC', $movimientoGenerado->destinoId)->first();
                if ($cxcVenta->totalOld != $cxcVenta->saldo) {
                    $movimientos = true;
                }
            }

            return response()->json(['status' => $movimientos, 'mensaje' => 'No se puede cancelar la factura, ya que se han generado movimientos posteriores.']);

        }

        return response()->json(['status' => $movimientos, 'mensaje' => '']);
    }
    public function cancelarFactura($venta)
    {
        $cancelado = false;

        if ($venta->movimiento == 'Factura' && $venta->estatus == $this->estatus[3]) {
            $flujo = new PROC_FLUJO();
            $origen = $venta->getOrigen;

            if ($origen != null) {
                $movimientoVenta = $flujo->getMovientoPosterior($origen->idEmpresa, $origen->idSucursal, 'Ventas', $origen->idVenta);
                $movimientoVenta->cancelado = 1;
                $movimientoVenta->save();
            }

            if ($venta->getCondition->tipoCondicion == 'Crédito') {
                $auxiliares = new AuxiliaresController();
                $auxiliares->quitarAuxiliarVentas($venta);
                $auxiliares->quitarSaldoCliente($venta);
                $movimientoGenerado = $flujo->getMovientoPosterior($venta->idEmpresa, $venta->idSucursal, 'Ventas', $venta->idVenta);

                $cxcVenta = CxC::where('idCXC', $movimientoGenerado->destinoId)->first();

                $movimientoGenerado->cancelado = 1;
                $movimientoGenerado->save();
                $cxcVenta->estatus = $this->estatus[4];
                $cxcVenta->save();
                if ($origen != null) {
                    $origen->saldo = $origen->saldo + $venta->total;
                    $origen->estatus = $this->estatus[1];
                    $origen->save();
                }

                $venta->estatus = $this->estatus[4];
                $venta->save();
                $cancelado = true;
            } else {
                $auxiliares = new AuxiliaresController();
                $movimientoGenerado = $flujo->getMovientoPosterior($venta->idEmpresa, $venta->idSucursal, 'Ventas', $venta->idVenta);
                $solicitud = Tesoreria::where('idTesoreria', $movimientoGenerado->destinoId)->first();
                $movimientoGenerado->cancelado = 1;
                $movimientoGenerado->save();
                
                $solicitud->estatus = $this->estatus[4];
                $solicitud->save();
                
                $movimientoGeneradoTesoreria = $flujo->getMovientoPosterior($solicitud->idEmpresa, $solicitud->idSucursal, 'Tesoreria', $solicitud->idTesoreria);
                
                $movimientoGeneradoTesoreria->cancelado = 1;
                $movimientoGeneradoTesoreria->save();
                
                $deposito = Tesoreria::where('idTesoreria', $movimientoGeneradoTesoreria->destinoId)->first();
                
                $deposito->estatus = $this->estatus[4];
                $deposito->save();
                $auxiliares->quitarSaldoCuenta($deposito);
                $auxiliares->cancelarAuxiliarTesoreria($deposito);

                if ($origen != null) {
                    $origen->saldo = $origen->saldo + $venta->getCobro->importe;
                    $subtotal = $origen->saldo / 1.16;
                    $impuestos = $subtotal * 0.16;
                    $origen->subtotal = $subtotal;
                    $origen->impuestos = $impuestos;
                    $origen->total = $origen->saldo;
                    $origen->estatus = $this->estatus[1];
                    $origen->save();
                }

                $venta->estatus = $this->estatus[4];
                $venta->save();
                $cancelado = true;
            }

        }
        return $cancelado;
    }
    public function validarPermisosConsulta($venta){
        
         //Validamos si el usuario tiene permiso de ver los movimientos ya creados
         if($venta->movimiento == 'Contrato' || $venta->movimiento == 'Factura' || $venta->movimiento == 'Inversión Inicial' || $venta->movimiento == 'Finiquito'){
            if (!Auth::user()->can($venta->movimiento . ' C')) {
                return response()->json(['status' => false,'message' =>'No tiene permisos para visualizar este movimiento']);
            }
        }
        //validamos si el usuario tiene el permiso de ver los movimientos Mensualidad
        if (strpos($venta->movimiento,'Mensualidad') !== false) {
            if (!Auth::user()->can('Mensualidades C')) {
                return response()->json(['status' => false,'message' =>'No tiene permisos para visualizar este movimiento']);
            }
        }
        return response()->json(['status' => true,'message' =>'']);
    }
    public function obtenerMovimientos($venta){
        $movimientos = [];
        $permisos = Auth::user()->getAllPermissions()->where('categoria', '=', 'Ventas')->pluck('name')->toArray();
        if (count($permisos) > 0) {
            foreach ($permisos as $movimeinto ) {
                $mov = substr($movimeinto, 0, -2);
                $letra = substr($movimeinto, -1);

                if($venta->movimiento == null){
                    if ($letra == 'E' && $mov != 'Mensualidades' && $mov != 'Inversión Inicial' && $mov != 'Finiquito') {
                        if (!array_key_exists($mov, $movimientos)) {
                            $movimientos[$mov] = $mov;
                        }
                    } 
                }else{
                    if ($letra == 'C' && $mov != 'Mensualidades' && $mov != 'Inversión Inicial' && $mov != 'Finiquito') {
                        if (!array_key_exists($mov, $movimientos)) {
                            $movimientos[$mov] = $mov;
                        }
                    } 
                }
                
                
            }
        }
        return $movimientos;
    }
}