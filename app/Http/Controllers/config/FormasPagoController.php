<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\config\CONF_MONEDA;
use App\Http\Requests\FormasRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormasPagoExport;
use Crypt;
use Carbon\Carbon;




class FormasPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $formasPago;
    private $formas;
    public $pagesize = 10;
    public $mensaje;
    public $status;

    public function __construct(CONF_FORMAS_PAGO $forma)
    {
        $this->middleware('auth');
        $this->formas = $forma;
    }

    public function index()
    {
        $formaspagination = CONF_FORMAS_PAGO::all();
        // dd($formaspagination);
        return view('page.config.formas_pago.index', [
            'formasPago' => $formaspagination,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $monedas = new CONF_MONEDA();
        return view('page.config.formas_pago.create', [
            'formasPago' => new CONF_FORMAS_PAGO(),
            'monedas' => $monedas->where('estatus',1)->pluck('nombre','idMoneda')->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormasRequest $request)
    {
        $data = $request->validated();
        $formaPagonew = new CONF_FORMAS_PAGO();
        $user = auth()->user()->user_id;

        $formaPagonew->clave = $data['inputClave'];
        $formaPagonew->nombre = $data['inputNombre'];
        $formaPagonew->descripcion = $data['inputDescripcion'];
        // $formaPagonew->formaPagosat = $data['formaPago'];
        $formaPagonew->monedaSat = (int) $data['selectMoneda'];
        $formaPagonew->user_id = $user;
        $formaPagonew->estatus = (int) $data['selectEstatus'];
        $data['selectEstatus'] == 1 ? $formaPagonew->fecha_Baja = null :$formaPagonew->fecha_Baja = Carbon::now()->toDateTime(); 


        try {
            $isCreate = $formaPagonew->save();
            if ($isCreate) {
                $this->mensaje = 'Forma de pago credado correctamente';
                $this->status = true;
            } else {
                $this->mensaje = 'Error al crear la Forma de pago';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la Forma de pago ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('config.formas-pago.create')
                ->with('status', $this->status)
                ->with('message', $this->mensaje);
        }
        return redirect()
            ->route('config.formas-pago.index')
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
            $formasS = $this->formas->find($id);
            return view('page.config.formas_pago.show', [
                'formasPago' => $formasS,
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('config.formas-pago.index')
                ->with('message', 'No se ha podido mostrar la forma de pago')
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
            $formaPagoedit = $this->formas->find($id);
            $monedas = new CONF_MONEDA();
            return view('page.config.formas_pago.edit', [
                'formasPago' => $formaPagoedit,
                'monedas' => $monedas->where('estatus',1)->pluck('nombre','idMoneda')->toArray(),
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('config.formas-pago.index')
                ->with('message', 'No se ha podido mostrar la forma de pago')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormasRequest $request, string $id)
    {
        try {
            // dd($request);
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $formaPagoedit = $this->formas->find($id);


            $formaPagoedit->clave = $data['inputClave'];
            $formaPagoedit->nombre = $data['inputNombre'];
            $formaPagoedit->descripcion = $data['inputDescripcion'];
            // $formaPagoedit->formaPagosat = $data['formaPago'];
            $formaPagoedit->monedaSat = (int) $data['selectMoneda'];
            $formaPagoedit->estatus = (int) $data['selectEstatus'];
            $formaPagoedit->fecha_Cambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $formaPagoedit->fecha_Baja = null :$formaPagoedit->fecha_Baja = Carbon::now()->toDateTime(); 

            try {
                $isUpdate = $formaPagoedit->update();

                if ($isUpdate) {
                    $message = "La forma de pago " . $data['inputNombre'] . " se ha actualizado correctamente";
                    $status = true;
                } else {
                    $message = "No se ha podido actualizar la forma de pago: " . $data['inputNombre'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = "Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la forma de pago: " . $data['inputNombre'];
                return redirect()->route('config.formas-pago.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()->route('config.formas-pago.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()->route('config.formas-pago.index')
                ->with('message', 'No se ha podido encontrar la forma de pago')
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
            
            $formas_delete = CONF_FORMAS_PAGO::where('idFormaspc', $id)->first();
            $formas_delete->estatus = 0;
            $formas_delete->fecha_Baja = Carbon::now()->toDateTime();
            $isRemoved = $formas_delete->update();

            if ($isRemoved) {
                $message = 'La forma de pago se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente La forma de pago';
                $status = false;
            }
            return redirect()->route('config.formas-pago.index')
                ->with('message', $message)
                ->with('status', $status);

        } catch (\Throwable $th) {
            return redirect()->route('config.formas-pago.index')
                ->with('message', 'No se ha podido encontrar la forma de pago')
                ->with('status', false);
        }
    }

    public function formasAction(Request $request)
    {
        $nombre = $request->inputName;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $condicion_filtro = CONF_FORMAS_PAGO::whereFormaName($nombre)
                    ->whereFormaStatus($status)
                    ->get();
                return redirect()
                    ->route('config.formas-pago.index')
                    ->with('condicion_filtro', $condicion_filtro)
                    ->with('nombre', $nombre)
                    ->with('estatus', $status);

            case 'Exportar excel':
                $formasPago = new FormasPagoExport($nombre, $status);
                // dd($usuario);
                return Excel::download($formasPago, 'formasdePago.xlsx');
        }
    }
}