<?php

namespace App\Http\Controllers\config;

use App\Exports\UnidadesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnidadesRequest;
use App\Models\config\CONF_UNIDADES;
use Illuminate\Http\Request;
use Crypt;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class UnidadesController extends Controller
{
    private $unidadesbd;
    public $pagesize = 25;
    public $mensaje;
    public $status;

    /**
     * Display a listing of the resource.
     */

    public function __construct(CONF_UNIDADES $unidad)
    {
        $this->middleware('auth');
        $this->unidadesbd = $unidad;
    }
    public function index()
    {
        $unidades = CONF_UNIDADES::all();
        return view('page.config.unidades.index', [
            'Unidades' => $unidades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.config.unidades.create', [
            'Unidades' => new CONF_UNIDADES(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnidadesRequest $request)
    {
        $data = $request->validated();
        $Unidades = new CONF_UNIDADES();
        $user = auth()->user()->user_id;

        $Unidades->unidad = $data['inputUnidad'];
        $Unidades->decimalValida = $data['inputDecimal'];
        $Unidades->user_id = $user;
        $Unidades->estatus = (int) $data['selectEstatus'];
        $data['selectEstatus'] == 1 ? $Unidades->fecha_Baja = null :$Unidades->fecha_Baja = Carbon::now()->toDateTime(); 


        try {
            $isCreated = $Unidades->save();
            if ($isCreated) {
                $this->mensaje = 'Se ha creado la unidad con éxito';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear la unidad';
                $this->status = false;
            }
        } catch (\Exception $e) {
            return redirect()->route('config.unidades.create') - with('message', $this->mensaje)->with('status', $this->status);
        }
        return redirect()
            ->route('config.unidades.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $unidad = $this->unidadesbd->find($id);
            return view('page.config.unidades.show', [
                'Unidades' => $unidad,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.unidades.index')
                ->with('message', 'No se ha podido mostrar la unidad')
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
            $unidades = $this->unidadesbd->find($id);
           

            return view('page.config.unidades.edit', [
                'Unidades' => $unidades,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.unidades.index')
                ->with('message', 'No se ha podido mostrar la unidad')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnidadesRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $unidades = $this->unidadesbd->find($id);


            $unidades->unidad = $data['inputUnidad'];
            $unidades->decimalValida = $data['inputDecimal'];
            $unidades->estatus = (int) $data['selectEstatus'];
            $unidades->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $unidades->fecha_Baja = null :$unidades->fecha_Baja = Carbon::now()->toDateTime(); 
            

            try {
                $isUpdate = $unidades->update();

                if ($isUpdate) {
                    $message = 'La unidad ' . $data['inputUnidad'] . ' se ha actualizado correctamente';
                    $status = true;
                } else {
                    $message = 'No se ha podido actualizar la unidad: ' . $data['inputUnidad'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la unidad: ' . $data['inputUnidad'];
                return redirect()
                    ->route('config.unidades.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()
                ->route('config.unidades.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.unidades.index')
                ->with('message', 'No se ha podido encontrar la unidad')
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

            $unidad = $this->unidadesbd->find($id);
            $unidad->estatus = 0;
            $unidad->fecha_Baja = Carbon::now()->toDateTime();

            $isRemoved = $unidad->update();

            if ($isRemoved) {
                $message = 'La unidad se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente La unidad';
                $status = false;
            }
            return redirect()
                ->route('config.unidades.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('config.unidades.index')
                ->with('message', 'No se ha podido encontrar la unidad')
                ->with('status', false);
        }
    }

    public function UnidadesAction(Request $request)
    {
        $unidad = $request->inputUnidad;
        $estatus = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $unidad_filtro = CONF_UNIDADES::whereUnidad($unidad)
                ->whereUnidadStatus($estatus)
                ->get();
                return redirect()
                ->route('config.unidades.index')
                ->with('unidades_filtro',$unidad_filtro)
                ->with('unidad',$unidad)
                ->with('status',$estatus);
            
            case 'Exportar excel':
                $unidades = new UnidadesExport($unidad,$estatus);
                return Excel::download($unidades,'Unidades.xlsx');
        }
    }
}