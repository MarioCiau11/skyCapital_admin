<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\config\CONF_MONEDA;
use Illuminate\Http\Request;
use App\Http\Requests\MonedasRequest;
use Crypt;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonedaExport;


class MonedasController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    private $monedasbd;
    public $pagesize = 10;
    public $mensaje;
    public $status;


    //constructor de la clase
    public function __construct(CONF_MONEDA $moneda)
    {
        $this->middleware('auth');
        $this->monedasbd = $moneda;
    }
    public function index()
    {
        $monedaspagination = CONF_MONEDA::all();
        return view('page.config.monedas.index', [
            'Monedas' => $monedaspagination
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.config.monedas.create', [
            'Monedas' => new CONF_MONEDA(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MonedasRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user()->user_id;
        $monedanew = new CONF_MONEDA();

        $monedanew->clave = $data['inputClave'];
        $monedanew->nombre = $data['inputName'];
        $monedanew->tipoCambio = $data['inputTipo'];
        $monedanew->descripcion = $data['inputDescripcion'];
        $monedanew->estatus = (int) $data['selectEstatus'];
        $monedanew->user_id = $user;
        $data['selectEstatus'] == 1 ? $monedanew->fechaBaja = null :$monedanew->fechaBaja = Carbon::now()->toDateTime(); 


        try {
            $isCreated = $monedanew->save();
            if ($isCreated) {
                $this->mensaje = 'La moneda ha sido creada con éxito';
                $this->status = true;
            } else {
                $this->mensaje = 'no se ha podido crear la moneda';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la Forma de pago ' . $e->getMessage();
            $this->status = false;
            return redirect()->route('config.monedas.create')
                ->with([
                    'message' => $this->mensaje,
                    'status' => $this->status
                ]);
        }
        return redirect()->route('config.monedas.index')
            ->with([
                'message' => $this->mensaje,
                'status' => $this->status
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $moneda = $this->monedasbd->find($id);

            return view('page.config.monedas.show')->with('Monedas', $moneda);

        } catch (\Throwable $th) {
            return redirect()->route('config.monedas.index')
                ->with('message', 'no se ha podido mostrar la moneda')
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
            $moneda = $this->monedasbd->find($id);

            return view('page.config.monedas.edit')->with('Monedas', $moneda, );

        } catch (\Throwable $th) {
            return redirect()->route('config.monedas.index')
                ->with('message', 'no se ha podido mostrar la moneda')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MonedasRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            // dd($data);
            $id = Crypt::decrypt($id);
            $moneda_edit = $this->monedasbd->find($id);

            $moneda_edit->nombre = $data['inputName'];
            $moneda_edit->tipoCambio = $data['inputTipo'];
            $moneda_edit->descripcion = $data['inputDescripcion'];
            $moneda_edit->estatus = (int) $data['selectEstatus'];
            $moneda_edit->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $moneda_edit->fechaBaja = null :$moneda_edit->fechaBaja = Carbon::now()->toDateTime(); 



            try {
                $isUpdate = $moneda_edit->update();

                if ($isUpdate) {
                    $message = "La moneda " . $data['inputName'] . " se ha actualizado correctamente";
                    $status = true;
                } else {
                    $message = "No se ha podido actualizar la moneda: " . $data['inputName'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = "Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la moneda: " . $data['inputName'];
                return redirect()->route('config.monedas.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()->route('config.monedas.index')
                ->with('status', $status)
                ->with('message', $message);
        } catch (\Throwable $th) {
            return redirect()->route('config.monedas.index')
                ->with('message', 'No se ha podido encontrar la moneda')
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
            $moneda_delete = CONF_MONEDA::where('idMoneda', $id)->first();
            $moneda_delete->estatus = 0;
            $moneda_delete->fechaBaja = Carbon::now()->toDateTime();
            $isRemoved = $moneda_delete->update();

            if ($isRemoved) {
                $message = 'La moneda se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente La moneda';
                $status = false;
            }
            return redirect()->route('config.monedas.index')
                ->with('message', $message)
                ->with('status', $status);

        } catch (\Throwable $th) {
            return redirect()->route('config.monedas.index')
                ->with('message', 'No se ha podido encontrar la moneda')
                ->with('status', false);
        }
    }
    public function monedaAction(Request $request)
    {
        // dd($request);
        $nombre = $request->inputName;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;
        $clave = $request->inputClave;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $moneda_filtro = CONF_MONEDA::whereClave($clave)
                    ->whereMonedaName($nombre)
                    ->whereStatus($status)
                    ->get();
                return redirect()->route('config.monedas.index')
                    ->with('monedas_filtro', $moneda_filtro)
                    ->with('nombre', $nombre)
                    ->with('estatus', $status)
                    ->with('clave', $clave);
            case 'Exportar excel':
                $Monedas = new MonedaExport($nombre, $status, $clave);
                return Excel::download($Monedas, 'Monedas.xlsx');
        }
    }
}