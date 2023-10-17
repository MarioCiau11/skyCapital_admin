<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\config\CONF_COSECUTIVOS;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\utils\conf_plantillas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametrosGeneralesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monedas = new CONF_MONEDA();

       
        $parametro = CONF_PARAMETROS_GENERALES::where('idEmpresa', session('company')->idEmpresa)->first();
        $consecutivos = CONF_COSECUTIVOS::where('idEmpresa', session('company')->idEmpresa)->where('idSucursal', session('sucursal')->idSucursal)->first();
        // $plantilla = conf_plantillas::where('idEmpresa', session('company')->idEmpresa)->first();
        // dd($plantillas);
        // dd($parametro, session('company')->idEmpresa);

        return view('page.config.parametrosGen.index',[
            'parametro' => $parametro,
            'consecutivos' => $consecutivos, 
            'monedas' => $monedas->getMonedas(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $configEmpresa = CONF_PARAMETROS_GENERALES::where('idEmpresa', session('company')->idEmpresa)->first();
        $consecutivos = CONF_COSECUTIVOS::where('idEmpresa', session('company')->idEmpresa)->where('idSucursal', session('sucursal')->idSucursal)->first();
        // $plantilla = conf_plantillas::where('idEmpresa', session('company')->idEmpresa)->first();
        // dd($configEmpresa, $plantilla, $request->all());

        $configEmpresa == null ? $configEmpresa = new CONF_PARAMETROS_GENERALES() : $configEmpresa;
        $consecutivos == null ? $consecutivos = new CONF_COSECUTIVOS() : $consecutivos;
        // $plantilla == null ? $plantilla = new conf_plantillas() : $plantilla;

        // dd($configEmpresa, $request->all(), session('company'));
        
        $configEmpresa->idEmpresa = session('company')->idEmpresa;
        $configEmpresa->user_id = Auth::user()->user_id;
        $configEmpresa->monedaDefault = $request->selectmoneda;
        $configEmpresa->diasHabiles = $request->selectdias;
        $configEmpresa->inicioEjerecicio = $request->inicioFecha;
        $configEmpresa->finEjercicio = $request->finFecha;
        $configEmpresa->morosidad = $request->inputMorosidad;
        $configEmpresa->aviso1 = $request->inputAviso;
        $configEmpresa->aviso2 = $request->inputAviso2;
        $configEmpresa->docsProyectos = $request->inputDocsProyectos;
        $configEmpresa->docsModulos = $request->inputDocsMódulos;
        $configEmpresa->docsClientes = $request->inputDocsClientes;
        $configEmpresa->docsMovimientos = $request->inputDocsMovimientos;
        $configEmpresa->plantillaAviso1 = $request->aviso1;
        $configEmpresa->plantillaAviso2 = $request->aviso2;
        $configEmpresa->contrato = $request->contrato;
        $configEmpresa->pagare = $request->pagares;
        $configEmpresa->convenio = $request->convenio;
        $configEmpresa->notificacion = $request->notificacion;


        $consecutivos->idEmpresa = session('company')->idEmpresa;
        $consecutivos->idSucursal = session('sucursal')->idSucursal;
        $consecutivos->consCotizador = $request->consecutivoCotizador;
        $consecutivos->consContrato = $request->consecutivoContrato;
        $consecutivos->consFactura = $request->consecutivoFactura;
        $consecutivos->consAnticipo = $request->consecutivoAnticipo;
        $consecutivos->consAplicacion = $request->consecutivoAplicacion;
        $consecutivos->consDevolucion = $request->consecutivoDevolucion;
        $consecutivos->consCobro = $request->consecutivoCobro;
        $consecutivos->consFactura2 = $request->consecutivoFactura2;
        $consecutivos->consTransferencia = $request->consecutivoTransferencia;
        $consecutivos->consEgreso = $request->consecutivoEgreso;
        $consecutivos->consIngreso = $request->consecutivoIngreso;

        

              
        try {
            $isSave = $configEmpresa->save();
            $consecutivos->save();
            if($isSave){
                $message = "Se guardó correctamente el registro";
                $status = true;
                session(['parametrosEmpresa' => $configEmpresa]);
                
            }else{
    
                $message = "No se guardó correctamente el registro";
                $status = false;
            }
    
       } catch (\Exception $e) {
            // dd($e);
            $message = "No se guardó correctamente el registro" . $e->getMessage();
           return redirect()->route('config.parametros-generales.index')->with('message', $message)->with('status', false);
       }

       return redirect()->route('config.parametros-generales.index')->with('message', $message)->with('status', $status);  


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
