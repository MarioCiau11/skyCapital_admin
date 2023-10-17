<?php

namespace App\Http\Controllers\proc\finanzas;

use App\Http\Controllers\Controller;
use App\Http\Requests\proc\TesoreriaRequest;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_SUCURSALES;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Exports\TesoreriaExport;

use App\Http\Controllers\helpers\AuxiliaresController;
use App\Models\proc\finanzas\TesoreriaDetalle;
use App\Models\User;
use App\Models\utils\PROC_FLUJO;
use App\Models\UTILS\PROC_SALDOS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TesoreriaController extends Controller
{
    public $tesoreria, $status, $mensaje;
    public $estatus = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'CONCLUIDO',
        3 => 'CANCELADO',
    ];

    public function __construct(Tesoreria $tesoreria ) {
        $this->tesoreria = $tesoreria;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd($this->tesoreria);
        $tesoreria = $this->tesoreria->tesoreriaParametros();
        // dd($tesoreria);
        $usuarios = User::where('user_status', 1)->get()->pluck('user_name', 'user_id');
        $sucursales = new CAT_SUCURSALES;
        $cuentasdinero = CAT_CUENTAS_DINERO::where('estatus', 1)->get()->pluck('clave', 'idCuentasDinero'); 
        $parametro = new CONF_PARAMETROS_GENERALES();
        $parametroGeneral = $parametro->byCompany(session('company')->idEmpresa)->first();
        if($parametroGeneral == null || $parametroGeneral->monedaDefault == null){
            return redirect()->route('config.parametros-generales.index')->with('status', false)->with('message', 'No se ha configurado los parametros generales de la empresa');
        }

        $monedas = new CONF_MONEDA();
        // dd($tesoreria);
        return view('page.proc.tesoreria.index', [
            'tesoreria' => $tesoreria,
            'usuarios' => $usuarios->toArray(),
            'sucursales' => $sucursales->getSucursal(session('sucursal')->idSucursal)->toArray(),
            'cuentasdinero' => $cuentasdinero->toArray(),
            'monedas' => $monedas->getMonedas(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try{
        // dd($request->all());
        $monedas  = new CONF_MONEDA();
        $parametro = new CONF_PARAMETROS_GENERALES();
        $formasPago = new CONF_FORMAS_PAGO();
        $clientes = CAT_CLIENTES::where('estatus', 1)->get();
        // $cuentasDinero = CAT_CUENTAS_DINERO::where('idEmpresa','=',session('company')->idEmpresa)->get();
        $cuentasDinero = CAT_CUENTAS_DINERO::where('idEmpresa','=',session('company')->idEmpresa)->where('estatus',1)->get();
        
        if ($request->has('tesoreria')) {
            $tesoreria = $this->tesoreria->find(Crypt::decrypt($request->tesoreria));
            // dd($tesoreria);
        } else {
            $tesoreria = new Tesoreria();
        }
        $validaciónConsulta = $this->validarPermisosConsultar($tesoreria);
        if (!$validaciónConsulta->original['status']) {
            return redirect()->route('proc.tesoreria.index')->with('status', false)->with('message', $validaciónConsulta->original['message']);
        }
        $movimientos = $this->obtenerMovimientos($tesoreria);
        // dd($movimientos);

        return view('page.proc.tesoreria.create', [
            'tesoreria' => $tesoreria,
            'monedas' => $monedas->getMonedas(),
            'parametro' => $parametro->monedaByCompany(session('company')->idEmpresa)->first(),
            'formasPago' => $formasPago->getFormasPago(),
            'clientes' => $clientes,
            'cuentas' => $cuentasDinero,
            'movimientos' => $movimientos,
        ]);
    } catch (\Exception $e) {
            
        return redirect()->route('proc.tesoreria.index')->with('status', false)->with('message', 'No se ha podido cargar la tesoreria: ' . $e->getMessage() . ' ' . $e->getLine());
    }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TesoreriaRequest $request)
    {
        // dd($request->validated());
        try {
            $data = $request->validated();
            $data["inputID"] != null ? $tesoreria = $this->tesoreria->find($data["inputID"]) : $tesoreria = $this->tesoreria;
            $this->guardarMovimiento($tesoreria,$data);
            $create = $tesoreria->save();
            if ($data["inputID"] != null) {
                $this->mensaje = "Se ha actualizado correctamente el movimiento";
                $this->updateFechaCambio($tesoreria);
            }else{
                $this->mensaje = "Se ha creado correctamente el movimiento";
            }
            if ($create) {
                $this->status = true;
            }else{
                $this->mensaje = "Error al guardar el movimiento";
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido crear el movimiento: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;
            return redirect()->route('proc.tesoreria.index')->with('status', $this->status)->with('message', $this->mensaje);
        }
        return redirect()->route('proc.tesoreria.create', ['tesoreria' => Crypt::encrypt($tesoreria->idTesoreria)])->with('status', $this->status)->with('message', $this->mensaje);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function destroy(Request $request)
    {
        try{
            $id = $request->id;
            $this->tesoreria->find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Se ha eliminado correctamente el movimiento',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'No se ha podido eliminar el movimiento: ' . $e->getMessage() . ' ' . $e->getLine(),
            ]);
        }
    }

    public function tesoreriaAction(Request $request)
    {
        $data = $request->all();
        $folio = $data['inputFolio'];
        $movimiento = $data['selectMovimiento'] == 'Todos' ? $data['selectMovimiento'] : $data['selectMovimiento'];
        $cuentad = $data['selectCuentaD'] == 'Todos' ? $data['selectCuentaD'] : $data['selectCuentaD'];
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
                $tesoreria_filtro = Tesoreria::whereTesoreriaFolio($folio)
                    ->whereTesoreriaMovimiento($movimiento)
                    ->whereTesoreriaCuentaD($cuentad)
                    ->whereTesoreriaEstatus($estatus)
                    ->whereTesoreriaFecha($fecha)
                    ->whereTesoreriaUsuario($usuario)
                    ->whereTesoreriaMoneda($moneda)
                    ->whereTesoreriaSucursal($sucursal)
                    ->orderBy('idTesoreria', 'DESC')
                    ->get();
                // dd($venta_filtro);
                return redirect()
                    ->route('proc.tesoreria.index')
                    ->with('tesoreria_filtro', $tesoreria_filtro)
                    ->with('inputFolio', $folio)
                    ->with('selectMovimiento', $movimiento)
                    ->with('selectCuentaD', $cuentad)
                    ->with('selectEstatus', $estatus)
                    ->with('selectFecha', $fecha)
                    ->with('inputFechaInicio', $fechaInicio)
                    ->with('inputFechaFinal', $fechaFinal)
                    ->with('selectUsuario', $usuario)
                    ->with('selectSucursal', $sucursal)->with('selectMoneda', $moneda);

            case 'Exportar excel':
                $tesoreria = new   TesoreriaExport($folio, $movimiento, $cuentad, $estatus, $fecha, $usuario, $sucursal, $moneda);
                return Excel::download($tesoreria, 'Tesorería.xlsx');

        }
    }

    public function afectar(Request $request){
        try {
            $data = $request->all();
            //  dd($data);
            $data["inputID"] != null ? $tesoreria = $this->tesoreria->find($data["inputID"]) : $tesoreria = $this->tesoreria;
            
            $this->guardarMovimiento($tesoreria,$data);
            $tesoreria->estatus = $this->asignarEstatus($data["selectMovimiento"],$data["inputEstatus"]);
            $tesoreria->folioMov = $tesoreria->getFolio($tesoreria);
            $this->updateFechaCambio($tesoreria);
            
            $create = $tesoreria->save();

            if($data['inputID'] == null){
                $lastTesoreria = $tesoreria->latest('idTesoreria')->first();
            }else{
                $lastTesoreria = $tesoreria;
            }
            
            if ($create) {

                $this->concluirIngreso($lastTesoreria);
                $this->concluirEgreso($lastTesoreria);
                $this->concluirTransferencia($lastTesoreria);

                $this->mensaje = 'Se ha afectado correctamente el movimiento';
                $this->status = true;
            }else{
                $this->mensaje = 'No se ha podido afectar el movimiento';
            }
        } catch (\Exception $e) {
            $this->mensaje = 'No se ha podido afectar el movimiento: ' . $e->getMessage() . ' ' . $e->getLine();
            $this->status = false;
            return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'error' => $e->getMessage() . ' ' . $e->getLine()]);
        }
        return response()->json(['status' => $this->status, 'message' => $this->mensaje, 'id' => Crypt::encrypt($lastTesoreria->idTesoreria)]);
    }

    public function concluirIngreso($tesoreria) {
        $tesoreria = $this->tesoreria->find($tesoreria->idTesoreria);

        if($tesoreria->movimiento == 'Ingreso' && $tesoreria->estatus == 'CONCLUIDO'){
            $auxiliares = new AuxiliaresController();
            $auxiliares->agregarSaldoInicial($tesoreria);
            $auxiliares->agregarSaldoCuenta($tesoreria);
            $auxiliares->agregarAuxiliarTesoreria($tesoreria);

            $this->actualizarSaldoCuenta($tesoreria);
        }
    }

    public function concluirEgreso($tesoreria) {
        $tesoreria = $this->tesoreria->find($tesoreria->idTesoreria);

        if($tesoreria->movimiento == 'Egreso' && $tesoreria->estatus == 'CONCLUIDO'){
   
            $auxiliares = new AuxiliaresController();
            $auxiliares->quitarSaldoCuenta($tesoreria);
            $auxiliares->quitarAuxiliarTesoreria($tesoreria);

            $this->actualizarSaldoCuenta($tesoreria);
        }
    }

    
    public function concluirTransferencia($tesoreria) {
        $tesoreria = $this->tesoreria->find($tesoreria->idTesoreria);

        if($tesoreria->movimiento == 'Transferencia' && $tesoreria->estatus == 'CONCLUIDO'){
            $auxiliares = new AuxiliaresController();
            $auxiliares->quitarSaldoCuenta($tesoreria);
            $auxiliares->quitarAuxiliarTesoreria($tesoreria);

            $auxiliares->agregarSaldoCuenta($tesoreria);
            $auxiliares->agregarAuxiliarTesoreria($tesoreria);

            $this->actualizarSaldoCuenta($tesoreria);
        }
    }

    private function actualizarSaldoCuenta($tesoreria) {
        $saldoCuenta = $tesoreria->getSaldoCuenta($tesoreria->cuentaDinero, $tesoreria->idEmpresa, $tesoreria->idSucursal, $tesoreria->getMoneda->clave);
    
        if ($saldoCuenta !== null) {
            $tesoreria->saldoCuenta = $saldoCuenta->saldo;
            $tesoreria->save();
        }
    }

    public function agregarTesoreria($data, $tipo)
    {
        // $data->getCobro;

        // dd($data->getCobro->getCuenta->tipoCuenta);

        // if($data->getCobro->getCuenta->tipoCuenta == 'Banco'){
            $this->generarSolicitudDeposito($data, $tipo);
        // }else{
        //     $this->generarIngreso($data);
        // }

        //  dd($data->getCobro->getCuenta);
    }

    public function generarSolicitudDeposito($data, $tipo)
    {

        
        $tesoreria = new Tesoreria();

        if($tipo == 'Ventas'){
            $cuenta = $data->getCobro->getCuenta->clave;
            $importe = $data->getCobro->importe;
            $formaPago = $data->getCobro->formaCobro;
            // $origenId = $data->idVenta;
        }else{
            $cuenta = $data->cuentaDinero;
            $importe = $data->total;
            $formaPago = $data->formaPago;
            // $origenId = $data->idCXC;
        }

        $saldoCuenta = $tesoreria->getSaldoCuenta($cuenta, $data->idEmpresa, $data->idSucursal, $data->getMoneda->clave) != null ? $tesoreria->getSaldoCuenta($cuenta, $data->idEmpresa, $data->idSucursal, $data->getMoneda->clave)->saldo : null;


        $tesoreria->movimiento = 'Solicitud Depósito';
        $tesoreria->moneda = $data->moneda;
        $tesoreria->tipoCambio = $data->tipoCambio;
        $tesoreria->beneficiario = $data->getCliente->idCliente;
        $tesoreria->referencia = 'Solicitud Depósito';
        $tesoreria->cuentaDinero = $cuenta;
        $tesoreria->importeTotal = $importe;
        $tesoreria->saldoCuenta = $saldoCuenta;
        $tesoreria->formaPago = $formaPago;
        $tesoreria->estatus = $this->estatus[2];
        $tesoreria->user_id = $data->user_id;
        $tesoreria->idEmpresa = $data->idEmpresa;
        $tesoreria->idSucursal = $data->idSucursal;
        $tesoreria->origenTipo = $tipo;
        $tesoreria->origen = $data->movimiento;
        $tesoreria->origenId = $data->folioMov;
        $tesoreria->folioMov = $tesoreria->getFolio($tesoreria);
        $this->updateFechaCambio($tesoreria);

        $create = $tesoreria->save();
        sleep(1);
        $lastId = $tesoreria->latest('idTesoreria')->first();
        if ($create) {
            if($tipo == 'Ventas'){
                $this->agregarFlujoVentas($data, $lastId);
            }else{
                $this->agregarFlujoCxC($data, $lastId);
            }
            
            $this->generarDeposito($lastId);
        }

    }


    public function generarDeposito($data)
    {

        $auxiliares = new AuxiliaresController();
        $auxiliares->agregarSaldoCuenta($data);

        $tesoreria = new Tesoreria();
        $tesoreria->movimiento = 'Depósito';
        $tesoreria->moneda = $data->moneda;
        $tesoreria->tipoCambio = $data->tipoCambio;
        $tesoreria->beneficiario = $data->beneficiario;
        $tesoreria->cuentaDinero = $data->cuentaDinero;
        $tesoreria->importeTotal = $data->importeTotal;
        $tesoreria->saldoCuenta = $tesoreria->getSaldoCuenta($data->cuentaDinero, $data->idEmpresa, $data->idSucursal, $data->getMoneda->clave) != null ? $tesoreria->getSaldoCuenta($data->cuentaDinero, $data->idEmpresa, $data->idSucursal, $data->getMoneda->clave)->saldo : null;
        $tesoreria->formaPago = $data->formaPago;
        $tesoreria->estatus = $this->estatus[2];
        $tesoreria->user_id = $data->user_id;
        $tesoreria->idEmpresa = $data->idEmpresa;
        $tesoreria->idSucursal = $data->idSucursal;
        $tesoreria->origenTipo = 'Tesoreria';
        $tesoreria->origen = $data->movimiento;
        $tesoreria->origenId = $data->folioMov;
        $tesoreria->folioMov = $tesoreria->getFolio($tesoreria);
        $this->updateFechaCambio($tesoreria);
        $create = $tesoreria->save();
        $lastId = $tesoreria->latest('idTesoreria')->first();
        if ($create) {
            $this->guardarDetalle($data, $lastId);
            $this->agregarFlujoTesoreria($data, $lastId);
            $auxiliares->agregarAuxiliarTesoreria($lastId);
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
        $flujo->destinoModulo = 'Tesoreria';
        $flujo->destinoId = $destino->idTesoreria;
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
        $flujo->destinoModulo = 'Tesoreria';
        $flujo->destinoId = $destino->idTesoreria;
        $flujo->destinoMovimiento = $destino->movimiento;
        $flujo->destinoFolio = $destino->folioMov;
        $flujo->cancelado = 0;
        $flujo->save();
    }

    public function agregarFlujoTesoreria($origen, $destino)
    {
        $flujo = new PROC_FLUJO();
        $flujo->idEmpresa = $origen->idEmpresa;
        $flujo->idSucursal = $origen->idSucursal;
        $flujo->origenModulo = 'Tesoreria';
        $flujo->origenId = $origen->idTesoreria;
        $flujo->origenMovimiento = $origen->movimiento;
        $flujo->origenFolio = $origen->folioMov;
        $flujo->destinoModulo = 'Tesoreria';
        $flujo->destinoId = $destino->idTesoreria;
        $flujo->destinoMovimiento = $destino->movimiento;
        $flujo->destinoFolio = $destino->folioMov;
        $flujo->cancelado = 0;
        $flujo->save();
    }

    
    public function guardarMovimiento($movimiento,$data) {
        $movimiento->movimiento = $data["selectMovimiento"];
        $movimiento->moneda = $data["selectMoneda"];
        $movimiento->tipoCambio = $data['inputTipoCambio'] != null ? str_replace(['$', ','], '', $data['inputTipoCambio']) : 0;
        $movimiento->referencia = $data["inputReferencia"];
        $movimiento->cuentaDinero = $data["inputCuenta"];
        $movimiento->cuentaDineroDestino = $data["inputCuentaDestino"];
        $movimiento->nombreCuenta = $data["inputNombreCuenta"];
        $movimiento->nombreCuentaDestino = $data["inputNombreCuentaDestino"];
        $movimiento->monedaCuenta = $data["inputMonedaCuenta"];
        $movimiento->monedaCuentaDestino = $data["inputMonedaCuentaDestino"];
        $movimiento->importeTotal = $data["inputImporte"] != null ? str_replace(['$', ','], '', $data['inputImporte']) : 0;
        $movimiento->saldoCuenta = $data["inputSaldoCuenta"] != null ? str_replace(['$', ','], '', $data['inputSaldoCuenta']) : 0;
        $movimiento->formaPago = $data["selectFormaPago"];
        $movimiento->observaciones = $data["inputObservaciones"];
        $movimiento->estatus = $this->estatus[0];
        $movimiento->user_id =  Auth::user()->user_id;
        $movimiento->idEmpresa = session('company')->idEmpresa;
        $movimiento->idSucursal = session('sucursal')->idSucursal; 
        $movimiento->origenTipo = "Tesoreria";
        $this->updateFechaCambio($movimiento);

    }

    public function guardarDetalle($data, $lastId) {
        $tesoreriaDetalle = new TesoreriaDetalle();

        $tesoreriaDetalle->idTesoreria = $lastId->idTesoreria;
        $tesoreriaDetalle->aplica = $data->movimiento;
        $tesoreriaDetalle->aplicaConsecutivo = $data->folioMov;
        $tesoreriaDetalle->importe = $data->importeTotal;
        $tesoreriaDetalle->formaPago = $data->getFormaPago->clave;
        $tesoreriaDetalle->user_id = Auth::user()->user_id;
        $tesoreriaDetalle->idEmpresa = $data->idEmpresa;
        $tesoreriaDetalle->idSucursal = $data->idSucursal;
        $tesoreriaDetalle->referencia = $data->idTesoreria; 

        $tesoreriaDetalle->save();


    }

    public function asignarEstatus($movimiento,$status){
        $estatus = '';
        if ($movimiento == 'Egreso' && $status == 'SIN AFECTAR') {
            $estatus = $this->estatus[2];
        }
        else if($movimiento == 'Ingreso' && $status == 'SIN AFECTAR'){
            $estatus = $this->estatus[2];
        }
        else if($movimiento == 'Transferencia' && $status == 'SIN AFECTAR'){
            $estatus = $this->estatus[2];
        }
        return $estatus;
    
    }
    public function updateFechaCambio ($tesoreria) {
        $tesoreria->fechaCambio = Carbon::now()->toDateTime();
        $tesoreria->save();
    }

    public function copy(Request $request){
        $tesoreria  = $this->tesoreria->find($request->id);
        // dd($tesoreria);
        if ($tesoreria != null) {
            $newTesoreria = $tesoreria->replicate();
            $newTesoreria->folioMov = null;
            $newTesoreria->estatus = $this->estatus[0];
            $newTesoreria->fechaEmision = Carbon::now();
            // dd($newTesoreria);
            $newTesoreria->save();
        }
        $tesoreriaDetalle = $tesoreria->getDetalle;
        // dd($tesoreriaDetalle);

        if ($tesoreriaDetalle ->count() > 0) {
            foreach ($tesoreriaDetalle as $key => $value) {
                $newTesoreriaDetalle = $value->replicate();
                $newTesoreriaDetalle->idTesoreria = $newTesoreria->idTesoreria;
                $newTesoreriaDetalle->save();
            }
        }
        return response()->json(['status' => true, 'message' => 'Se ha copiado correctamente el movimiento', 'id' => Crypt::encrypt($newTesoreria->idTesoreria)]);
    }

    public function cancelar(Request $request)
    {
        try {
            $tesoreria = $this->tesoreria->find($request->idTesoreria);

            $validar = $this->validarCancelacion($tesoreria);
            // dd($validar->original['status']);
            if ($validar->original['status']) {
                return response()->json(['status' => false, 'mensaje' => $validar->original['mensaje']]);
            }

            if($tesoreria->movimiento == 'Ingreso'){
                $cancelado = $this->cancelarIngreso($tesoreria);
            }
            if($tesoreria->movimiento == 'Egreso'){
                $cancelado = $this->cancelarEgreso($tesoreria);
            }
            if($tesoreria->movimiento == 'Transferencia'){
                $cancelado = $this->cancelarTransferencia($tesoreria);
            }

            if($tesoreria->movimiento == 'Egreso'){
                $cancelado = $this->cancelarEgreso($tesoreria);
            }

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

    function validarCancelacion($tesoreria)
    {

        $validar = false;

        if ($tesoreria->estatus == $this->estatus[2] && $tesoreria->movimiento == 'Ingreso') {
        
            $saldo = $tesoreria->getSaldoCuenta($tesoreria->cuentaDinero, $tesoreria->idEmpresa, $tesoreria->idSucursal, $tesoreria->getMoneda->clave) != null ? $tesoreria->getSaldoCuenta($tesoreria->cuentaDinero, $tesoreria->idEmpresa, $tesoreria->idSucursal, $tesoreria->getMoneda->clave)->saldo : null;

            if($saldo < $tesoreria->importeTotal){
                $validar = true;
            }

            return response()->json(['status' => $validar, 'mensaje' => 'No se puede cancelar el movimiento, el saldo de la cuenta es menor al importe del movimiento.']);
        
        }

        if ($tesoreria->estatus == $this->estatus[2] && $tesoreria->movimiento == 'Transferencia') {
        
            $saldo = $tesoreria->getSaldoCuenta($tesoreria->cuentaDineroDestino, $tesoreria->idEmpresa, $tesoreria->idSucursal, $tesoreria->getMoneda->clave) != null ? $tesoreria->getSaldoCuenta($tesoreria->cuentaDineroDestino, $tesoreria->idEmpresa, $tesoreria->idSucursal, $tesoreria->getMoneda->clave)->saldo : null;

            if($saldo < $tesoreria->importeTotal){
                $validar = true;
            }

            return response()->json(['status' => $validar, 'mensaje' => 'No se puede cancelar el movimiento, el saldo de la cuenta es menor al importe del movimiento.']);
        
        }

        return response()->json(['status' => $validar, 'mensaje' => '']);
    }

    public function cancelarIngreso($tesoreria) {
        
        $cancelado = false;
        if ($tesoreria->estatus == $this->estatus[2] && $tesoreria->movimiento == 'Ingreso') {
            $auxiliares = new AuxiliaresController();

            $auxiliares->quitarSaldoCuenta($tesoreria);
            $auxiliares->cancelarAuxiliarTesoreria($tesoreria);

            $tesoreria->estatus = $this->estatus[3];
            $tesoreria->save();
            $cancelado = true;
        }

        return $cancelado;
    }

    public function cancelarEgreso($tesoreria) {
        
        $cancelado = false;
        if ($tesoreria->estatus == $this->estatus[2] && $tesoreria->movimiento == 'Egreso') {
            $auxiliares = new AuxiliaresController();

            $auxiliares->agregarSaldoCuenta($tesoreria);
            $auxiliares->cancelarAuxiliarTesoreriaAbono($tesoreria);

            $tesoreria->estatus = $this->estatus[3];
            $tesoreria->save();
            $cancelado = true;
        }

        return $cancelado;
    }

    public function cancelarTransferencia($tesoreria) {
        
        $cancelado = false;
        if ($tesoreria->estatus == $this->estatus[2] && $tesoreria->movimiento == 'Transferencia') {
            $auxiliares = new AuxiliaresController();
            $auxiliares->cancelarAgregarSaldoCuenta($tesoreria);
            $auxiliares->cancelarAuxiliarTesoreriaAbono($tesoreria);

            $auxiliares->cancelarQuitarSaldoCuenta($tesoreria);
            $auxiliares->cancelarAuxiliarTesoreria($tesoreria);
            $tesoreria->estatus = $this->estatus[3];
            $tesoreria->save();
            $cancelado = true;
        }

        return $cancelado;
    }
    public function validarPermisosConsultar($tesoreria){

        if ($tesoreria->movimiento != null && $tesoreria->movimiento != 'Solicitud Depósito' && $tesoreria->movimiento != 'Depósito') {
            if (!Auth::user()->can($tesoreria->movimiento.' C')) {
                return response()->json(['status' => false, 'message' => 'No tiene permisos para consultar el movimiento.']);
            }
        }
        
        return response()->json(['status' => true, 'message' => '']);
    }
    public function obtenerMovimientos($tesoreria){
        $movimientos = [];
        $permisos = Auth::user()->getAllPermissions()->where('categoria', 'Tesorería',)->pluck('name')->toArray();
        // dd($permisos);
        if(count($permisos) > 0){
            foreach ($permisos as $permiso) {
                $mov = substr($permiso, 0, -2);
                $letra = substr($permiso, -1);
                if($tesoreria->movimiento == null){

                    if ($letra == 'E' && $mov != 'Solicitud Depósito' && $mov != 'Depósito') {
                        if (!array_key_exists($mov, $movimientos)) {
                            $movimientos[$mov] = $mov;
                        }
                    }
                }
                else{
                    if ($letra == 'C' && $mov != 'Solicitud Depósito' && $mov != 'Depósito') {
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

