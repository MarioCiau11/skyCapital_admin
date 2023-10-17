<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\CondicionesRequest;
use App\Models\catalogosSat\CAT_SAT_MONEDA;
use App\Models\config\CONF_CONDICIONES_CRED;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CondicionesCreditoExport;
use App\Models\catalogosSat\CAT_SAT_METODOPAGO;

class CondicionesCreditoController extends Controller
{
    private $condicionesCred;
    private $condiciones;
    public $pagesize = 25;
    public $mensaje;
    public $status;

    //Constructo de la clase
    public function __construct(CONF_CONDICIONES_CRED $condicion)
    {
        $this->middleware('auth');
        $this->condiciones = $condicion;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $condicionesCred = CONF_CONDICIONES_CRED::all();

        return view('page.config.condiciones_credito.index', [
            'condiciones' => $condicionesCred,
            'columns' => $this->condiciones->columns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $cat_metodop = new CAT_SAT_METODOPAGO();
        return view('page.config.condiciones_credito.create', [
            'condicionesCred' => new CONF_CONDICIONES_CRED(),
            // 'c_MetodoPago' => $cat_metodop->getMetodoPago(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CondicionesRequest $request)
    {
        $data = $request->validated();
        $condicion = new CONF_CONDICIONES_CRED();
        $user = auth()->user()->user_id;

        $condicion->nombreCondicion = $data['name'];
        $condicion->tipoCondicion = $data['selectTipo'];
        isset($data['diasVenci']) ? ($condicion->diasVencimiento = $data['diasVenci']) : ($condicion->diasVencimiento = null);
        isset($data['selectTipo_dias']) ? ($condicion->tipoDias = $data['selectTipo_dias']) : ($condicion->tipoDias = null);
        isset($data['selectDias_habil']) ? ($condicion->diasHabiles = $data['selectDias_habil']) : ($condicion->diasHabiles = null);
        // $condicion->metodoPago = $data['selectMetodo'];
        $condicion->estatus = (int) $data['selectEstatus'];
        $condicion->user_id = $user;
        $data['selectEstatus'] == 1 ? $condicion->fecha_Baja = null :$condicion->fecha_Baja = Carbon::now()->toDateTime(); 

        try {
            $isCreate = $condicion->save();
            if ($isCreate) {
                $this->mensaje = 'Condición de crédito credada correctamente';
                $this->status = true;
            } else {
                $this->mensaje = 'Error al crear la condición de crédito';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la condición de crédito ' . $e->getMessage();
            $this->status = false;
            return redirect()
                ->route('config.condiciones-credito.create')
                ->with('status', $this->status)
                ->with('message', $this->mensaje);
        }
        return redirect()
            ->route('config.condiciones-credito.index')
            ->with('status', $this->status)
            ->with('message', $this->mensaje);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $condicion = $this->condiciones->find($id);

            return view('page.config.condiciones_credito.show', [
                'condicion' => $condicion,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', 'No se ha podido mostrar la condición')
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
            $condicion = $this->condiciones->find($id);

            return view('page.config.condiciones_credito.edit', [
                'condicionesCred' => $condicion,
                // 'c_MetodoPago' => $cat_metodopago->getMetodoPago(),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', 'No se ha podido mostrar la condición')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CondicionesRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $condicionCred = $this->condiciones->find($id);


            $condicionCred->nombreCondicion = $data['name'];
            $condicionCred->tipoCondicion = $data['selectTipo'];
            $condicionCred->diasVencimiento = $data['diasVenci'];
            $condicionCred->tipoDias = $data['selectTipo_dias'];
            $condicionCred->diasHabiles = $data['selectDias_habil'];
            // $condicionCred->metodoPago = $data['selectMetodo'];
            $condicionCred->estatus = (int) $data['selectEstatus'];
            $condicionCred->fecha_Cambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $condicionCred->fecha_Baja = null :$condicionCred->fecha_Baja = Carbon::now()->toDateTime(); 

            try {
                $isUpdate = $condicionCred->update();

                if ($isUpdate) {
                    $message = 'La condición ' . $data['name'] . ' se ha actualizado correctamente';
                    $status = true;
                } else {
                    $message = 'No se ha podido actualizar la condición: ' . $data['name'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la condición: ' . $data['name'];
                return redirect()
                    ->route('config.condiciones-credito.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', 'No se ha podido encontrar la condición')
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
            $condicion_delete = CONF_CONDICIONES_CRED::where('idCondicionesc', $id)->first();
            $condicion_delete->estatus = 0;
            $condicion_delete->fecha_Baja = Carbon::now()->toDateTime();
            $isRemoved = $condicion_delete->update();

            if ($isRemoved) {
                $message = 'La condición se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente La condición';
                $status = false;
            }
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.condiciones-credito.index')
                ->with('message', 'No se ha podido encontrar la condición')
                ->with('status', false);
        }
    }

    public function condicionAction(Request $request)
    {
        $nombre = $request->inputName;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $condicion_filtro = CONF_CONDICIONES_CRED::whereCondicionName($nombre)
                    ->whereCondicionStatus($status)
                    ->get();
                return redirect()
                    ->route('config.condiciones-credito.index')
                    ->with('condicion_filtro', $condicion_filtro)
                    ->with('nombre', $nombre)
                    ->with('estatus', $status);

            case 'Exportar excel':
                $condicionesCredito = new CondicionesCreditoExport($nombre, $status);
                return Excel::download($condicionesCredito, 'Condiciones_de_Credito.xlsx');
        }
    }
}