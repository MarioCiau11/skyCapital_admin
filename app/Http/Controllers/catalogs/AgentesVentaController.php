<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\AgentesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentesRequest;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\catalogos\CAT_AGENTES_VENTA;
use Carbon\Carbon;
use Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AgentesVentaController extends Controller
{

    private $AgentesBD;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    private $catalogo = 'Agentes de venta';


    public function __construct(CAT_AGENTES_VENTA $agente)
    {
        $this->middleware('auth');
        $this->AgentesBD = $agente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentes = CAT_AGENTES_VENTA::all();
        return view('page.catalogs.agentes-venta.index', [
            'Agentes' => $agentes,
            'columns' => $this->AgentesBD->columns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = new AGRUP_GRUPO();
        $categoria = new AGRUP_CATEGORIA();
        return view('page.catalogs.agentes-venta.create', [
            'Agentes' => new CAT_AGENTES_VENTA(),
            'Grupos' => $grupo->getGrupo($this->catalogo),
            'Categorias' => $categoria->getCategoria($this->catalogo),
            'nextId' => $this->AgentesBD->getNextID()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgentesRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $agente = new CAT_AGENTES_VENTA();
        $user = auth()->user()->user_id;

        $agente->clave = $data['inputClave'];
        $agente->nombre = $data['inputName'];
        $agente->tipo = $data['selectTipo'];
        $agente->categoria = $data['selectCategoria'];
        $agente->grupo = $data['selectGrupo'];
        $agente->estatus = (int) $data['selectEstatus'];
        $agente->user_id = $user;

        try {
            $isCreated = $agente->save();
            if ($isCreated) {
                $this->mensaje = 'Se ha creado el asesor correctamente';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear el asesor';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear el asesor ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.agentes-venta.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }
        return redirect()
            ->route('cat.agentes-venta.index')
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
            $agente = $this->AgentesBD->find($id);
            return view('page.catalogs.agentes-venta.show', [
                'Agentes' => $agente
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', 'No se ha podido encontrar el asesor')
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
            $agente = $this->AgentesBD->find($id);
            $grupo = new AGRUP_GRUPO();
            $categoria = new AGRUP_CATEGORIA();
            return view('page.catalogs.agentes-venta.edit', [
                'Agentes' => $agente,
                'Grupos' => $grupo->getGrupo($this->catalogo),
                'Categorias' => $categoria->getCategoria($this->catalogo),
                'nextId' => $this->AgentesBD->getNextID()
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', 'No se ha podido encontrar el asesor')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgentesRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            // dd($data);
            $id = Crypt::decrypt($id);
            $agente = $this->AgentesBD->find($id);

            $agente->clave = $data['inputClave'];
            $agente->nombre = $data['inputName'];
            $agente->tipo = $data['selectTipo'];
            $agente->categoria = $data['selectCategoria'];
            $agente->grupo = $data['selectGrupo'];
            $agente->estatus = (int) $data['selectEstatus'];
            $data['selectEstatus'] == 1 ? $agente->fechaBaja = null : $agente->fechaBaja = Carbon::now()->toDateTime();

            $agente->user_id = auth()->user()->user_id;
            $agente->fechaCambio = Carbon::now()->toDateTime();

            try {
                $isUpdate = $agente->update();

                if ($isUpdate) {
                    $message = 'El agente ' . $data['inputName'] . ' se ha actualizado correctamente';
                    $status = true;
                } else {
                    $message = 'No se ha podido actualizar el agente: ' . $data['inputName'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar el agente: ' . $data['inputName'];
                return redirect()
                    ->route('cat.agentes-venta.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', 'No se ha podido encontrar el asesor')
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
            $agente = $this->AgentesBD->find($id);
            $agente->estatus = 0;
            $agente->fechaBaja = Carbon::now()->toDateTime();

            $isRemoved = $agente->update();
            if ($isRemoved) {
                $message = 'El agente se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente el agente';
                $status = false;
            }
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.agentes-venta.index')
                ->with('message', 'No se ha podido encontrar el asesor')
                ->with('status', false);
        }
    }

    public function agentesAction(Request $request)
    {
        $clave = $request->inputClave;
        $nombre = $request->inputName;
        $estatus = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $agente_filtro = CAT_AGENTES_VENTA::whereAgenteClave($clave)
                    ->whereAgenteEstatus($estatus)
                    ->whereAgenteName($nombre)
                    ->get();

                return redirect()
                    ->route('cat.agentes-venta.index')
                    ->with('agente_filtro', $agente_filtro)
                    ->with('inputName', $nombre)
                    ->with('selectEstatus', $estatus)
                    ->with('inputClave', $clave);

            case 'Exportar excel':
                $agentes = new AgentesExport($clave, $estatus, $nombre);
                return Excel::download($agentes, 'Asesores_Comerciales.xlsx');

        }

    }

}