<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\CuentasDineroExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CuentasDineroRequest;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\catalogos\CAT_INSTITUCIONES_FINANCIERAS;
use App\Models\config\CONF_MONEDA;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Excel;

class CuentasDineroController extends Controller
{
    private $cuentasBD;
    public $message;
    public $status;

    public function __construct(CAT_CUENTAS_DINERO $cuentas)
    {
        $this->middleware('auth');
        $this->cuentasBD = $cuentas;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuentas = $this->cuentasBD->all();
        return view('page.catalogs.cuentas-dinero.index',[
            'CuentasDinero' => $cuentas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $monedas = new CONF_MONEDA();
        $cuentas = new CAT_CUENTAS_DINERO();
        $instituciones = new CAT_INSTITUCIONES_FINANCIERAS();
        $empresa = new CAT_EMPRESAS();
        return view('page.catalogs.cuentas-dinero.create',[
            'CuentasDinero' => $cuentas,
            'monedas' => $monedas->where('estatus',1)->pluck('nombre','idMoneda')->toArray(),
            'banco' => $instituciones->where('estatus',1)->pluck('nombre','idInstitucionf')->toArray(),
            'empresas' => $empresa->getCompanies()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CuentasDineroRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $cuenta = new CAT_CUENTAS_DINERO();
        $cuenta->clave = $data['inputClave'];
        $cuenta->idInstitucionf = (int)$data['selectBanco'];
        $cuenta->noCuenta = $data['inputNoCuenta'];
        $cuenta->cuentaClave = $data['inputCuentaClave'];
        $cuenta->referenciaBanco = $data['inputReferencia'];
        $cuenta->convenioBanco = $data['inputConvenio'];
        $cuenta->tipoCuenta = $data['selectTipo'];
        $cuenta->idMoneda = (int)$data['selectMoneda'];
        $cuenta->idEmpresa = (int)$data['selectEmpresa'];
        $cuenta->estatus = (int)$data['selectEstatus'];
        $cuenta->user_id = auth()->user()->user_id;
        $data['selectEstatus'] == 1 ? $cuenta->fechaBaja = null : $cuenta->fechaBaja = Carbon::now();
        
        $isExists =  $this->cuentasBD->where('clave','=',$data["inputClave"])->exists();
        if ($isExists) {
            return redirect()
            ->route('cat.cuentas-dinero.index')
            ->with('message','La cuenta con clave'.$data["inputClave"].' ya existe')
            ->with('status' ,false);
        }
        // dd($cuenta);
        try {
            $isCreated = $cuenta->save();
            if ($isCreated) {
                $this->message = 'Se ha creado correctamente la cuenta';
                $this->status = true;
            }
            else{
                $this->message = 'No se ha podido crear la cuenta';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->message = 'Ha ocurrido un error al crear la cuenta '.$e->getMessage();
            $this->status = false;

            return redirect()
            ->route('cat.cuentas-dinero.index')
            ->with('message',$this->message)
            ->with('status' ,$this->status);
        }
        return redirect()
            ->route('cat.cuentas-dinero.index')
            ->with('message',$this->message)
            ->with('status' ,$this->status);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $cuenta = $this->cuentasBD->find($id);
            return view('page.catalogs.cuentas-dinero.show', [
                'CuentasDinero' => $cuenta
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.cuentas-dinero.index')
                ->with('message', 'No se ha podido encontrar la cuenta')
                ->with('status', false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $monedas = new CONF_MONEDA();
            $cuentas = $this->cuentasBD->find($id);
            $instituciones = new CAT_INSTITUCIONES_FINANCIERAS();
            $empresa = new CAT_EMPRESAS();
            return view('page.catalogs.cuentas-dinero.edit', [
                'CuentasDinero' => $cuentas,
                'monedas' => $monedas->where('estatus', 1)->pluck('nombre', 'idMoneda')->toArray(),
                'banco' => $instituciones->where('estatus', 1)->pluck('nombre', 'idInstitucionf')->toArray(),
                'empresas' => $empresa->getCompanies()
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.cuentas-dinero.index')
                ->with('message', 'No se ha podido encontrar la cuenta')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CuentasDineroRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $cuenta = $this->cuentasBD->find($id);

            $cuenta->clave = $data['inputClave'];
            $cuenta->idInstitucionf = (int)$data['selectBanco'];
            $cuenta->noCuenta = $data['inputNoCuenta'];
            $cuenta->cuentaClave = $data['inputCuentaClave'];
            $cuenta->referenciaBanco = $data['inputReferencia'];
            $cuenta->convenioBanco = $data['inputConvenio'];
            $cuenta->tipoCuenta = $data['selectTipo'];
            $cuenta->idMoneda = (int)$data['selectMoneda'];
            $cuenta->idEmpresa = (int)$data['selectEmpresa'];
            $cuenta->estatus = (int)$data['selectEstatus'];
            $cuenta->fechaCambio = Carbon::now();
            $data['selectEstatus'] == 1 ? $cuenta->fechaBaja = null : $cuenta->fechaBaja = Carbon::now();
            try {
                $isUpdate =$cuenta->update();
                if ($isUpdate) {
                    $message = 'La cuenta ' . $data['inputClave'] . ' se ha actualizado correctamente';
                    $status = true;
                } else {
                    $message = 'No se ha podido actualizar la cuenta: ' . $data['inputClave'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la cuenta: ' . $data['inputClave'];
                return redirect()
                    ->route('cat.cuentas-dinero.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()
            ->route('cat.cuentas-dinero.index')
            ->with('message', $message)
            ->with('status', $status);

        } catch (\Throwable $th) {
            // dd($th);
           return redirect()
                ->route('cat.cuentas-dinero.index')
                ->with('message', 'No se ha podido encontrar el agente')
                ->with('status', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $agente = $this->cuentasBD->find($id);
            $agente->estatus = 0;
            $agente->fechaBaja = Carbon::now()->toDateTime();

            $isRemoved = $agente->update();
            if ($isRemoved) {
                $message = 'La cuenta se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente la cuenta';
                $status = false;
            }
            return redirect()
                ->route('cat.cuentas-dinero.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.cuentas-dinero.index')
                ->with('message', 'No se ha podido encontrar la cuenta')
                ->with('status', false);
        }
    }
    public function cuentasDineroAction(Request $request)
    {
        // dd($request->all());
        $clave = $request->inputClave;
        $cuenta = $request->inputCuenta;
        $estatus = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $cuentas_filtro = CAT_CUENTAS_DINERO::whereCuentasClave($clave)
                    ->whereCuentasCuenta($cuenta)
                    ->whereCuentasEstatus($estatus)
                    ->get();

                return redirect()
                    ->route('cat.cuentas-dinero.index')
                    ->with('cuentas_filtro', $cuentas_filtro)
                    ->with('inputCuenta', $cuenta)
                    ->with('selectEstatus', $estatus)
                    ->with('inputClave', $clave);

            case 'Exportar excel':
                $cuentas = new CuentasDineroExport($clave,$cuenta,$estatus);
                return Excel::download($cuentas,'Cuentas_de_Dinero.xlsx');
                // $agentes = new AgentesExport($clave, $estatus, $nombre);
                // return Excel::download($agentes, 'Agentes_de_venta.xlsx');

        }
    }
}
