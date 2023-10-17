<?php

namespace App\Http\Controllers\proc\finanzas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helpers\AuxiliaresController;
use App\Http\Controllers\helpers\ProcSaldosController;
use App\Http\Requests\proc\CxCRequest;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\catalogos\CAT_MODULOS;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\finanzas\CxC;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\proc\finanzas\CxCDetalle;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_SUCURSALES;
use App\Models\config\CONF_MONEDA;


use App\Models\proc\comercial\Ventas;
use App\Exports\CxCExport;

use App\Models\User;
use App\Models\utils\PROC_FLUJO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CxController extends Controller
{
    public $cxc, $status, $mensaje;

    public function __construct(CxC $cxc ) {
        $this->cxc = $cxc;
    }
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'POR CONFIRMAR',
        3 => 'CONCLUIDO',
        4 => 'CANCELADO',
    ];

    public function index()
    {

        $cxc = $this->cxc->cxcParametros();
        $clientes = CAT_CLIENTES::where('estatus', 1)->get()->pluck('razonSocial', 'idCliente');
        $usuarios = User::where('user_status', 1)->get()->pluck('user_name', 'user_id');
        $sucursales = new CAT_SUCURSALES;
        $monedas = CONF_MONEDA::where('estatus', 1)->get()->pluck('clave', 'idMoneda');
        
        $parametro = new CONF_PARAMETROS_GENERALES();
        $parametroGeneral = $parametro->byCompany(session('company')->idEmpresa)->first();
        if($parametroGeneral == null || $parametroGeneral->monedaDefault == null){
            return redirect()->route('config.parametros-generales.index')->with('status', false)->with('message', 'No se ha configurado los parametros generales de la empresa');
        }

        
        
        return view('page.proc.cxc.index', [
            'cxc' => $cxc,
            'clientes' => $clientes,
            'usuarios' => $usuarios->toArray(),
            'sucursales' => $sucursales->getSucursal(session('sucursal')->idSucursal)->toArray(),
            'monedas' => $monedas->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $monedas = new CONF_MONEDA();
            $parametro = new CONF_PARAMETROS_GENERALES();
            $formasPago = new CONF_FORMAS_PAGO();
            $clientes = CAT_CLIENTES::where('estatus', 1)->get();
            // $cuentasDinero = CAT_CUENTAS_DINERO::where('idEmpresa','=',session('company')->idEmpresa)->get();
            $cuentasDinero = CAT_CUENTAS_DINERO::where('idEmpresa', '=', session('company')->idEmpresa)->where('estatus', 1)->get();
            $proyectos = CAT_PROYECTOS::where('estatus', '1')->get();
            $modulos = CAT_MODULOS::all();

            if ($request->has('cxc')) {
                $cxc = $this->cxc->find( Crypt::decrypt($request->cxc));
                // dd($cxc->idCXC);
            } else {
                $cxc = new CxC();
            }

            $validacionConsulta = $this->validarPermisosConsultar($cxc);
            if (!$validacionConsulta->original['status']) {
                return redirect()->route('proc.cxc.index')->with('status', false)->with('message', $validacionConsulta->original['message']);
            }
            $movimeintos = $this->obtenerMovimientos($cxc);

            $saldos = new ProcSaldosController();
            return view('page.proc.cxc.create', [
                'cxc' => $cxc,
                'monedas' => $monedas->getMonedas(),
                'parametro' => $parametro->monedaByCompany(session('company')->idEmpresa)->first(),
                'formasPago' => $formasPago->getFormasPago(),
                'clientes' => $clientes,
                'cuentas' => $cuentasDinero,
                'proyectos' => $proyectos,
                'modulos' => $modulos,
                'saldoGlobalCliente' => $saldos->getSaldosCliente($cxc->cliente, 'CXC'),
                'movimientos' => $movimeintos,
            ]);
        } catch (\Exception $e) {

            return redirect()->route('proc.cxc.index')->with('status', false)->with('message', 'No se ha podido cargar el CxC: ' . $e->getMessage() . ' ' . $e->getLine());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CxCRequest $request)
    {
        try {
            $data = $request->validated();
            //  dd($data);
            $data["inputID"] != null ? $cxc = $this->cxc->find($data["inputID"]) : $cxc = $this->cxc;
            $cxc->estatus = $this->estatus[0];
            $this->guardarMovimiento($cxc, $data);
            $this->eliminarMovimientos($data);
            $create = $cxc->save();

            if ($data['inputID'] == null) {
                $lastCxC = $cxc::latest('idCXC')->first();
                $mensaje = 'Se ha creado correctamente el movimiento';
            } else {
                $mensaje = 'Se ha actualizado correctamente el movimiento';
                $lastCxC = $cxc;
            }
            if ($create) {

                $this->guardarDetalle($data, $lastCxC);

                $this->mensaje = $mensaje;
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear el movimiento';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido crear el movimiento: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return redirect()->route('proc.cxc.index')->with('status', $this->status)->with('message', $this->mensaje);
        }
        return redirect()->route('proc.cxc.create', ['cxc' => $lastCxC->idCXC])->with('status', $this->status)->with('message', $this->mensaje);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            // dd($request->id);
            $cxc = $this->cxc->find($request->id);
            $cxc->getDetalle->each->delete();

            $cxc->getCxC->delete();

            return response()->json([
                'status' => true,
                'message' => 'Se ha eliminado correctamente el movimiento',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'No se ha podido eliminar el movimiento: ' . $e->getMessage() . ' ' . $e->getLine(),
            ]);
        }
    }

    public function copy(Request $request)
    {
        // CxC
        $cxc = $this->cxc->find($request->id);
        if ($cxc != null) {
            $newCXC = $cxc->replicate();
            $newCXC->folioMov = null;
            $newCXC->estatus = $this->estatus[0];
            $newCXC->fechaEmision = Carbon::now();
            $newCXC->save();
        }
        // CxC detalle
        $cxcDetalle = $cxc->getDetalle;
        // dd($cxcDetalle);
        if ($cxcDetalle->count() > 0) {
            foreach ($cxcDetalle as $key => $value) {
                $newCXCDetalle = $value->replicate();
                $newCXCDetalle->idCXC = $newCXC->idCXC;
                $newCXCDetalle->save();
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Se ha copiado correctamente el movimiento',
            'id' => Crypt::encrypt($newCXC->idCXC),
        ]);
    }

    public function cxcAction(Request $request)
    {
        $data = $request->all();
        $folio = $data['inputFolio'];
        $cliente = $data['selectCliente'];
        $movimiento = $data['selectMovimiento'] == 'Todos' ? $data['selectMovimiento'] : $data['selectMovimiento'];
        $estatus = $data['selectEstatus'] == 'Todos' ? $data['selectEstatus'] : $data['selectEstatus'];
        $fecha = $data['selectFecha'] == 'Todos' ? $data['selectFecha'] : $data['selectFecha'];
        $fechaInicio = $data['inputFechaInicio'] == null ? $data['inputFechaInicio'] : $data['inputFechaInicio'];
        $fechaFinal = $data['inputFechaFinal'] == null ? $data['inputFechaFinal'] : $data['inputFechaFinal'];
        $fechaV = $data['selectFechaV'] == 'Todos' ? $data['selectFechaV'] : $data['selectFechaV'];
        $fechaInicioV = $data['inputFechaInicioV'] == null ? $data['inputFechaInicioV'] : $data['inputFechaInicioV'];
        $fechaFinalV = $data['inputFechaFinalV'] == null ? $data['inputFechaFinalV'] : $data['inputFechaFinalV'];
        $usuario = $data['selectUsuario'] == 'Todos' ? $data['selectUsuario'] : (int) $data['selectUsuario'];
        $sucursal = $data['selectSucursal'] == 'Todos' ? $data['selectSucursal'] : (int) $data['selectSucursal'];
        $moneda = $data['selectMoneda'] == 'Todos' ? $data['selectMoneda'] : (int) $data['selectMoneda'];

        // dd($data);
        switch ($request->input('action')) {
            case 'Búsqueda':
                $cxc_filtro = CxC::whereCxCFolio($folio)
                    ->whereCxCCliente($cliente)
                    ->whereCxCMovimiento($movimiento)
                    ->whereCxCEstatus($estatus)
                    ->whereCxCFecha($fecha)
                    ->whereCxCFechaV($fechaV)
                    ->whereCxCUsuario($usuario)
                    ->whereCxCSucursal($sucursal)
                    ->whereCxCMoneda($moneda)
                    ->orderBy('idCXC', 'DESC')
                    ->get();
                // dd($cxc_filtro);
                return redirect()
                    ->route('proc.cxc.index')
                    ->with('cxc_filtro', $cxc_filtro)
                    ->with('inputFolio', $folio)
                    ->with('selectCliente', $cliente)
                    ->with('selectMovimiento', $movimiento)
                    ->with('selectEstatus', $estatus)
                    ->with('selectFecha', $fecha)
                    ->with('inputFechaInicio', $fechaInicio)
                    ->with('inputFechaFinal', $fechaFinal)
                    ->with('selectFechaV', $fechaV)
                    ->with('inputFechaInicioV', $fechaInicioV)
                    ->with('inputFechaFinalV', $fechaFinalV)
                    ->with('selectUsuario', $usuario)
                    ->with('selectSucursal', $sucursal)
                    ->with('selectMoneda', $moneda);

            case 'Exportar excel':
                $cxc = new   CxCExport($folio, $cliente, $movimiento, $estatus, $fecha, $fechaV, $usuario, $sucursal, $moneda);
                return Excel::download($cxc, 'CuentasXCobrar.xlsx');

        }
    }
    public function afectar(Request $request)
    {
        try {
            $data = $request->all();
            //  dd($data);
            $validar = $this->validarAfectar($data);
            // dd($validar);
            if ($validar->original['status']) {
                return response()->json(['status' => false, 'message' => $validar->original['mensaje']]);
            }


            $data['inputID'] != null ? $cxc = $this->cxc->find($data['inputID']) : $cxc = $this->cxc;

            $this->guardarMovimiento($cxc, $data);
            $this->eliminarMovimientos($data);
            $cxc->estatus = $this->asignarEstatus($data['selectMovimiento'], $data['inputEstatus']);
            $this->updateFechaCambio($cxc);
            $cxc->folioMov = $cxc->getFolio($cxc);
            $cxc->user_id = $data['inputUser'];

            // dd($cxc);
            $create = $cxc->save();

            if ($data['inputID'] == null) {
                $lastCxC = $cxc::latest('idCXC')->first();
            } else {
                $lastCxC = $cxc;
            }
            if ($create) {
                
                $this->guardarDetalle($data, $lastCxC);

                $this->concluirCobro($lastCxC);
                $this->concluirAnticipo($lastCxC);
                $this->concluirAplicacion($lastCxC);

                $this->mensaje = 'Se ha afectado correctamente el movimiento';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido afectar el movimiento';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido afectar el movimiento: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }

        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastCxC->idCXC)]);
    }

    public function validarAfectar($data)
    {

        $validar = false;

        if ($data['selectMovimiento'] == 'Cobro' || $data['selectMovimiento'] == 'Aplicación') {
            $movimientos = $data['inputMovimientos'];
            $movimientos = json_decode($movimientos);

            if ($movimientos) {
                foreach ($movimientos as $movimiento) {
                    if ($movimiento->aplicaConsecutivo != "") {
                        if ($movimiento->referencia != null) {
                            $movimientoCxC = CxC::find($movimiento->referencia);
                            if ($movimientoCxC->moneda != $data['selectMoneda']) {
                                $validar = true;

                                return response()->json(['status' => $validar, 'mensaje' => 'Los movimientos no pueden ser de diferente moneda, favor de verificar']);
                            }
                        }
                    }
                }
            }
        } 

        if ($data['selectMovimiento'] == 'Aplicación') {

            $anticipo = CxC::find($data['inputIdAnticipo']);

            if ($anticipo->moneda != $data['selectMoneda']) {
                $validar = true;

                return response()->json(['status' => $validar, 'mensaje' => 'El anticipo no puede ser de diferente moneda, favor de verificar']);
            }
        }

        return response()->json(['status' => $validar, 'mensaje' => '']);

    }

    public function agregarCxC($data)
    {
        $cxc = new CxC();
        $cxc->movimiento = $data->movimiento;
        $cxc->folioMov = $data->folioMov;
        $cxc->moneda = $data->moneda;
        $cxc->tipoCambio = $data->tipoCambio;
        $cxc->cliente = $data->propietarioPrincipal;
        $cxc->condicion = $data->condicionPago;
        $cxc->fechaVencimiento = $data->fechaVencimiento;
        $emision = Carbon::parse($data->fechaAlta)->format('Y-m-d');
        $currentDate = Carbon::createFromFormat('Y-m-d', $emision);
        $vencimiento = Carbon::parse($data->fechaVencimiento)->format('Y-m-d');
        $shippingDate = Carbon::createFromFormat('Y-m-d', $vencimiento);
        $diasMoratorio = $shippingDate->diffInDays($currentDate);
        $cxc->diasMoratorios = '-' . $diasMoratorio;
        $cxc->importe = $data->subTotal;
        $cxc->impuestos = $data->impuestos;
        $cxc->total = $data->total;
        $cxc->totalOld = $data->total;
        $cxc->saldo = $data->total;
        $cxc->referencia = $data->movimiento . ' ' . $data->folioMov;
        $cxc->proyecto = $data->proyecto;
        $cxc->modulo = $data->modulo;
        $cxc->idEmpresa = $data->idEmpresa;
        $cxc->idSucursal = $data->idSucursal;
        $cxc->user_id = $data->user_id;
        $cxc->estatus = $this->estatus[1];
        $cxc->folioMov = $cxc->getFolio($cxc);
        $cxc->origenTipo = 'Ventas';
        $cxc->origen = $data->movimiento;
        $cxc->origenId = $data->idVenta;
        $create = $cxc->save();
        if ($create) {
            $this->agregarFlujoVentas($data, $cxc);
        }
    }
    public function agregarFlujoVentas($origen, $destino)
    {
        $flujo = new PROC_FLUJO();
        $flujo->idEmpresa = $origen->idEmpresa;
        $flujo->idSucursal = $origen->idSucursal;
        $flujo->origenModulo = 'Ventas';
        $flujo->origenId = $origen->idVenta;
        $flujo->origenMovimiento = $origen->movimiento;
        $flujo->origenFolio = $origen->folioMov;
        $flujo->destinoModulo = 'CxC';
        $flujo->destinoId = $destino->idCXC;
        $flujo->destinoMovimiento = $destino->movimiento;
        $flujo->destinoFolio = $destino->folioMov;
        $flujo->cancelado = 0;
        $flujo->save();
    }

    public function agregarFlujoCxC($origen, $destino)
    {
        $flujo = new PROC_FLUJO();
        $flujo->idEmpresa = $origen->idEmpresa;
        $flujo->idSucursal = $origen->idSucursal;
        $flujo->origenModulo = 'CxC';
        $flujo->origenId = $origen->idCXC;
        $flujo->origenMovimiento = $origen->movimiento;
        $flujo->origenFolio = $origen->folioMov;
        $flujo->destinoModulo = 'CxC';
        $flujo->destinoId = $destino->idCXC;
        $flujo->destinoMovimiento = $destino->movimiento;
        $flujo->destinoFolio = $destino->folioMov;
        $flujo->cancelado = 0;
        $flujo->save();
    }

    public function guardarMovimiento($movimiento, $data){
        // dd($data);
        $movimiento->movimiento = $data["selectMovimiento"];
        $movimiento->folioMov = $data["inputFolioMov"];
        $movimiento->moneda = $data["selectMoneda"];
        $movimiento->tipoCambio = $data['inputTipoCambio'] != null ? str_replace(['$', ','], '', $data['inputTipoCambio']) : 0;
        $movimiento->cliente = $data["inputCliente"];
        $movimiento->formaPago = $data["selectFormaPago"];
        $movimiento->cuentaDinero = $data["inputCuenta"];
        $movimiento->cuentaDineroMoneda = $data["inputCuentaMoneda"];
        $movimiento->importe = $data["inputImporte"] != null ? str_replace(['$', ','], '', $data['inputImporte']) : 0;
        $movimiento->impuestos = $data["inputImpuesto"] != null ? str_replace(['$', ','], '', $data['inputImpuesto']) : 0;
        $movimiento->total = $data["inputImporteTotal"] != null ? str_replace(['$', ','], '', $data['inputImporteTotal']) : 0;
        $movimiento->observaciones = $data["inputObservaciones"];
        $movimiento->referencia = $data["inputReferencia"];
        $movimiento->saldo = $data["inputSaldo"] != null ? str_replace(['$', ','], '', $data['inputSaldo']) : 0;
        $movimiento->proyecto = $data["inputProyecto"];
        $movimiento->modulo = $data["inputModulo"];
        $movimiento->anticipo = $data["inputAnticipo"];
        $movimiento->anticipoImporte = $data["inputAnticipoImporte"] != null ? str_replace(['$', ','], '', $data['inputAnticipoImporte']) : 0;
        $movimiento->idAnticipo = $data["inputIdAnticipo"];
        $movimiento->monedaAnticipo = $data["inputMonedaAnticipo"];
        $movimiento->idEmpresa = session('company')->idEmpresa;
        $movimiento->idSucursal = session('sucursal')->idSucursal;
        $movimiento->user_id = Auth::user()->user_id;
        // dd($movimiento);    

    }

    public function guardarDetalle($data, $cxc) {
        
        $movimientos = $data['inputMovimientos'];
        $movimientos = json_decode($movimientos);
        if ($movimientos) {
            foreach ($movimientos as $movimiento) {
                if ($movimiento->aplicaConsecutivo != "") {
                    if ($movimiento->id != null) {
                        $cxcDetalle = CxCDetalle::find($movimiento->id);
                    } else {
                        $cxcDetalle = new CxCDetalle();
                    }

                    $cxcDetalle->aplica = $movimiento->aplica;
                    $cxcDetalle->idCXC = $cxc->idCXC;
                    $cxcDetalle->aplicaConsecutivo = $movimiento->aplicaConsecutivo;
                    $cxcDetalle->importe = str_replace(['$', ','], '', $movimiento->importe);
                    $cxcDetalle->diferencia = str_replace(['$', ','], '', $movimiento->diferencia);
                    $cxcDetalle->porcentaje = str_replace(['%', ','], '', $movimiento->porcentaje);
                    $cxcDetalle->user_id = Auth::user()->user_id;
                    $cxcDetalle->idEmpresa = session('company')->idEmpresa;
                    $cxcDetalle->idSucursal = session('sucursal')->idSucursal;
                    $cxcDetalle->referencia = $movimiento->referencia;
                    $cxcDetalle->save();
                }
            }
        }
    }
    public function eliminarMovimientos($data)
    {
        $movimientosDelete = $data['inputMovimientosDelete'];
        $movimientosDelete = json_decode($movimientosDelete);

        if ($movimientosDelete) {
            foreach ($movimientosDelete as $movimiento) {
                if ($movimiento->id != null) {
                    $cxcDetalle = CxCDetalle::find($movimiento->id);
                    $cxcDetalle->delete();
                }
            }
        }
    }
    public function generarCobro(Request $request)
    {
        try {
            $data = $request->all();
            //   dd($data);
            $cxc = $this->cxc;

            $this->guardarMovimiento($cxc, $data);
            $cxc->movimiento = 'Cobro';
            $cxc->folioMov = null;

            $cxc->importe = $cxc->saldo;
            $cxc->impuestos = 0;
            $cxc->total = $cxc->importe;
            
            $cxc->origenTipo = 'CxC';
            $cxc->origen = $data['selectMovimiento'];
            $cxc->origenId = $data['inputID'];
            $cxc->estatus = $this->estatus[0];

            $create = $cxc->save();
            

            $lastCxC = $cxc::latest('idCxC')->first();

            if ($create) {

                $this->armarDetalle($data, $lastCxC);
                $this->mensaje = 'Se ha generado correctamente el cobro';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido generar el cobro';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido generar el cobro: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }

        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastCxC->idCXC)]);
    }

    public function generarAplicacion(Request $request)
    {
        try {
            // dd($request->all());
            $data = $request->all();
            // dd($data);
            $cxc = $this->cxc;

            $this->guardarMovimiento($cxc, $data);
            $cxc->movimiento = 'Aplicación';
            $cxc->folioMov = null;

            $cxc->importe = $cxc->saldo;
            $cxc->impuestos = 0;
            $cxc->total = $cxc->importe;
            
            $cxc->anticipo = $data['selectMovimiento'].'-'. $data['inputFolioMov'];
            $cxc->anticipoImporte = $cxc->total;
            $cxc->idAnticipo = $data['inputID'];
            $cxc->origenTipo = 'CxC';
            $cxc->origen = $data['selectMovimiento'];
            $cxc->monedaAnticipo = $data['selectMoneda'];
            $cxc->origenId = $data['inputID'];
            $cxc->estatus = $this->estatus[0];

            $create = $cxc->save();
            $lastCxC = $cxc::latest('idCXC')->first();
        

            if ($create) {
                $this->mensaje = 'Se ha generado correctamente la aplicación';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido generar la aplicación';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido generar la aplicación: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }

        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastCxC->idCXC)]);
    }
    public function updateFechaCambio ($cxc) {
        $cxc->fechaCambio = Carbon::now()->toDateTime();
        $cxc->save();
    }
    public function armarDetalle($data, $cxc)
    {
        // dd($data);
        $cxcDetalle = new CxCDetalle();
        $cxcDetalle->aplica = $data['selectMovimiento'];
        $cxcDetalle->idCXC = $cxc->idCXC;
        $cxcDetalle->aplicaConsecutivo = $data['inputFolioMov'];
        $cxcDetalle->importe = $data["inputSaldo"] != null ? str_replace(['$', ','], '', $data['inputSaldo']) : 0;
        $cxcDetalle->diferencia = null;
        $cxcDetalle->porcentaje = null;
        $cxcDetalle->user_id = Auth::user()->id;
        $cxcDetalle->idEmpresa = $cxc->idEmpresa;
        $cxcDetalle->idSucursal = $cxc->idSucursal;
        $cxcDetalle->referencia = $data['inputID'];
        
        $cxcDetalle->save();

        $cxc->save();
    }
    public function asignarEstatus($movimiento, $estado)
    {
        $estatus = '';

        if ($movimiento == 'Cobro' && $estado == 'SIN AFECTAR') {
            $estatus = $this->estatus[3];
        } else if ($movimiento == 'Aplicación' && $estado == 'SIN AFECTAR') {
            $estatus = $this->estatus[3];
        } else if ($movimiento == 'Anticipo' && $estado == 'SIN AFECTAR') {
            $estatus = $this->estatus[1];
        } 

        return $estatus;
    }

    public function concluirCobro($cxc) {
        $cxc = $this->cxc->find($cxc->idCXC);

        
        if ($cxc->movimiento == 'Cobro' && $cxc->estatus == $this->estatus[3]) {
            $this->concluirMovimientos($cxc);
            $auxiliares = new AuxiliaresController();
            $auxiliares->agregarAuxiliarCxC($cxc);
            $auxiliares->quitarSaldoCliente($cxc);
            $tesoreria = new TesoreriaController(new Tesoreria());
            $tesoreria->agregarTesoreria($cxc, 'CxC');           

        }
    }

    
    public function concluirAnticipo($cxc) {
        $cxc = $this->cxc->find($cxc->idCXC);

        if ($cxc->movimiento == 'Anticipo' && $cxc->estatus == $this->estatus[1]) {
            $modulo = $cxc->getModulo;
            $modulo->estatus = 'Apartado';
            $modulo->save();
            
            $auxiliares = new AuxiliaresController();
            $auxiliares->agregarAuxiliarCxC($cxc);
            $auxiliares->quitarSaldoCliente($cxc);
            $tesoreria = new TesoreriaController(new Tesoreria());
            $tesoreria->agregarTesoreria($cxc, 'CxC'); 

        }
    }

    public function concluirAplicacion($cxc) {
        $cxc = $this->cxc->find($cxc->idCXC);
        if ($cxc->movimiento == 'Aplicación' && $cxc->estatus == $this->estatus[3]) {
            $auxiliares = new AuxiliaresController();
            $auxiliares->agregarAuxiliarCxCAplicacion($cxc);
            $this->concluirMovimientos($cxc);


            $anticipo = $cxc->getAnticipo;
            $anticipo->saldo = $anticipo->saldo - $cxc->total;
            $this->agregarFlujoCxC($anticipo, $cxc);
            if($anticipo->saldo == 0) {
                $anticipo->estatus = $this->estatus[3];
            }
            $anticipo->save();
            
        }
    }

    public function concluirMovimientos($cxc) {
        $movimientos = $cxc->getDetalle;
        if($movimientos) {
            foreach ($movimientos as $movimiento) {
                $movimiento->getMovimiento->saldo = $movimiento->getMovimiento->saldo - $movimiento->importe;
                $movimiento->getMovimiento->save();

                if($movimiento->getMovimiento->saldo == 0) {
                    $movimiento->getMovimiento->estatus = $this->estatus[3];
                    $movimiento->getMovimiento->save();
                }

                $this->agregarFlujoCxC($movimiento->getMovimiento, $cxc);
            }
        }

    }

    public function resetearMovimientos($cxc) {
        $movimientos = $cxc->getDetalle;
        if($movimientos) {
            foreach ($movimientos as $movimiento) {
                $movimiento->getMovimiento->saldo = $movimiento->getMovimiento->saldo + $movimiento->importe;
                $movimiento->getMovimiento->estatus = $this->estatus[1];
                $movimiento->getMovimiento->save();
                

                $flujo = new PROC_FLUJO();
                $cobro = $flujo->getMovientosPosteriores($movimiento->getMovimiento->idEmpresa, $movimiento->getMovimiento->idSucursal, 'CxC', $movimiento->getMovimiento->idCXC);
                if (count($cobro) > 0) {
                    // dd($cobro);
                    foreach ($cobro as $movimiento) {
                        
                        if($movimiento->destinoId == $cxc->idCXC && $movimiento->cancelado == 0){
                            $movimiento->cancelado = 1;
                            $movimiento->save();
                        }
                    }
                }
            }
        }

    }

    public function cancelar(Request $request)
    {
        try {
            $cxc = $this->cxc->find($request->idCxC);

            $validar = $this->validarCancelacion($cxc);
            // dd($validar->original['status']);
            if ($validar->original['status']) {
                return response()->json(['status' => false, 'mensaje' => $validar->original['mensaje']]);
            }

            
            if($cxc->movimiento == 'Anticipo'){
                $cancelado = $this->cancelarAnticipo($cxc);
            }
            
            if($cxc->movimiento == 'Cobro'){
                // dd($cxc->movimiento);
                $cancelado = $this->cancelarCobro($cxc);
            }

            if($cxc->movimiento == 'Aplicación'){
                $cancelado = $this->cancelarAplicacion($cxc);
            }
       
            // dd($cancelado);
            if ($cancelado) {
                $this->mensaje = 'Se ha cancelado correctamente el movimiento';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido cancelar el movimiento';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al cancelar el movimiento' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;

            return response()->json(['status' => $this->status, 'mensaje' => $this->mensaje]);
        }
        return response()->json(['status' => $this->status, 'mensaje' => $this->mensaje]);
    }

    function validarCancelacion($cxc)
    {

        $movimientos = false;

        if ($cxc->estatus == $this->estatus[3] && $cxc->movimiento == 'Anticipo') {
            $flujo = new PROC_FLUJO();
            $movimientosGenerados = $flujo->getMovientosPosteriores($cxc->idEmpresa, $cxc->idSucursal, 'CxC', $cxc->idCXC);

            if (count($movimientosGenerados) > 0) {
                // dd($movimientosGenerados);
                foreach ($movimientosGenerados as $movimiento) {
                    
                    if($movimiento->destinoMovimiento == 'Aplicación' && $movimiento->cancelado == 0){
                        $movimientos = true;
                    }
                }
            }

            // dd($movimientosGenerados);

            return response()->json(['status' => $movimientos, 'mensaje' => 'No se puede cancelar el anticipo porque ya se ha generado una aplicación']);

        }

        return response()->json(['status' => $movimientos, 'mensaje' => '']);
    }

    public function cancelarAnticipo($cxc) {

        $cxc = $this->cxc->find($cxc->idCXC);

        $cancelado = false;

        if ($cxc->movimiento == 'Anticipo' && $cxc->estatus == $this->estatus[3]) {
            $modulo = $cxc->getModulo;

            if($modulo->estatus == 'Apartado'){
                $modulo->estatus = 'Disponible';
                $modulo->save();
            }

            
            $auxiliares = new AuxiliaresController();
            $auxiliares->quitarAuxiliarCxC($cxc);
            $auxiliares->agregarSaldoCliente($cxc);

            $flujo = new PROC_FLUJO();
            $solicitud = $flujo->getMovientoPosterior($cxc->idEmpresa, $cxc->idSucursal, 'CxC', $cxc->idCXC);
            $solicitud->cancelado = 1;
            $solicitud->save();

            $solicitud = Tesoreria::find($solicitud->destinoId);
            $solicitud->estatus = $this->estatus[4];
            $solicitud->save();
            
            $auxiliares->quitarSaldoCuenta($solicitud);
            $deposito = $flujo->getMovientoPosterior($cxc->idEmpresa, $cxc->idSucursal, 'Tesoreria', $solicitud->idTesoreria);
            $deposito->cancelado = 1;
            $deposito->save();

            $deposito = Tesoreria::find($deposito->destinoId);
            $auxiliares->cancelarAuxiliarTesoreria($deposito);

            $deposito->estatus = $this->estatus[4];
            $deposito->save();

            $cxc->estatus = $this->estatus[4];
            $cxc->save();
            $cancelado = true;

    
            }


        return $cancelado;
        
    }

    public function cancelarCobro($cxc) {
        $cxc = $this->cxc->find($cxc->idCXC);

        $cancelado = false;
        
        if ($cxc->movimiento == 'Cobro' && $cxc->estatus == $this->estatus[3]) {
            $this->resetearMovimientos($cxc);
            $auxiliares = new AuxiliaresController();
            $auxiliares->quitarAuxiliarCxC($cxc);
            $auxiliares->agregarSaldoCliente($cxc);
            
            $flujo = new PROC_FLUJO();
            $solicitud = $flujo->getMovientoPosterior($cxc->idEmpresa, $cxc->idSucursal, 'CxC', $cxc->idCXC);
            $solicitud->cancelado = 1;
            $solicitud->save();

            $solicitud = Tesoreria::find($solicitud->destinoId);
            $solicitud->estatus = $this->estatus[4];
            $solicitud->save();
            
            $auxiliares->quitarSaldoCuenta($solicitud);
            $deposito = $flujo->getMovientoPosterior($cxc->idEmpresa, $cxc->idSucursal, 'Tesoreria', $solicitud->idTesoreria);
            $deposito->cancelado = 1;
            $deposito->save();

            $deposito = Tesoreria::find($deposito->destinoId);
            $auxiliares->cancelarAuxiliarTesoreria($deposito);

            $deposito->estatus = $this->estatus[4];
            $deposito->save();

            $cxc->estatus = $this->estatus[4];
            $cxc->save();
            $cancelado = true;

            // dd($cxc);
           

        }

        return $cancelado;
    }

    public function cancelarAplicacion($cxc) {
        $cxc = $this->cxc->find($cxc->idCXC);

        $cancelado = false;
        if ($cxc->movimiento == 'Aplicación' && $cxc->estatus == $this->estatus[3]) {
            $auxiliares = new AuxiliaresController();
            $auxiliares->quitarAuxiliarCxCAplicacion($cxc);
            $this->resetearMovimientos($cxc);


            $anticipo = $cxc->getAnticipo;
            $anticipo->saldo = $anticipo->saldo + $cxc->total;
            $flujo = new PROC_FLUJO();
            $movimientosGenerados = $flujo->getMovientosPosteriores($cxc->idEmpresa, $cxc->idSucursal, 'CxC', $anticipo->idCXC);
            if (count($movimientosGenerados) > 0) {
                // dd($movimientosGenerados);
                foreach ($movimientosGenerados as $movimiento) {
                    
                    if($movimiento->destinoMovimiento == 'Aplicación' && $movimiento->cancelado == 0){
                        $movimiento->cancelado = 1;
                        $movimiento->save();
                    }
                }
            }
            if($anticipo->saldo > 0) {
                $anticipo->estatus = $this->estatus[1];
            }

            $anticipo->save();
            
            $cxc->estatus = $this->estatus[4];
            $cxc->save();
            $cancelado = true;
        }

        return $cancelado;
    }
    public function validarPermisosConsultar($cxc){

        if ($cxc->movimiento != null && $cxc->movimiento != 'Factura') {
            if (!Auth::user()->can($cxc->movimiento . ' C')) {
                return response()->json(['status' => false,'message' => 'No tiene permisos para consultar los movimientos']);
            }
        }
        return response()->json(['status' => true,'message' => '']);
    }
    public function obtenerMovimientos($cxc){
        $movimientos = [];
        $permisos = Auth::user()->getAllPermissions()->where('categoria','Cuentas por cobrar')->pluck('name')->toArray();
        if (count($permisos) > 0) {
            foreach ($permisos as $permiso) {
                $mov = substr($permiso, 0, -2);
                $letra = substr($permiso, -1);
                if($cxc->movimiento == null){
                    if ($letra == 'E' && $mov != 'Factura') {
                        if (!array_key_exists($mov, $movimientos)) {
                            $movimientos[$mov] = $mov;
                        }
                    } 
                }else{
                    if ($letra == 'C' && $mov != 'Factura' ) {
                        if (!array_key_exists($mov, $movimientos)) {
                            $movimientos[$mov] = $mov;
                        }
                    } 
                }
            }
        }
        // dd($movimientos);
        return $movimientos;
    }
}

