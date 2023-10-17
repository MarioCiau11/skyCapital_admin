<?php

namespace App\Http\Controllers\agrup;

use App\Http\Controllers\Controller;
use App\Models\agrup\AGRUP_GRUPO;
use Crypt;
use Illuminate\Http\Request;

class ClientesGrupoController extends Controller
{
    private $grupoBD;
    public $mensaje;
    public $status;
    
    public function __construct(AGRUP_GRUPO $grupo)
    {
        $this->middleware('auth');
        $this->grupoBD = $grupo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupo = AGRUP_GRUPO::where('catalogo','Clientes')->get();
        return view('page.agrup.Clientes.grupo.index',[
            'Grupo' => $grupo
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.agrup.Clientes.grupo.create',[
            'Grupo' => new AGRUP_GRUPO()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $grupo = new AGRUP_GRUPO();
        $grupo->nombre = $data['inputName'];
        $grupo->estatus = (int)$data['selectEstatus'];
        $grupo->catalogo = 'Clientes';
        try {
            $isCreated = $grupo->save();
            if ($isCreated) {
                $this->mensaje = 'Se ha creado el grupo correctamente';
                $this->status = true;
            }
            else{
                $this->mensaje = 'No se ha podido crear el grupo';
                $this->status = false;
            }
            return redirect()
            ->route('agrup.clientes.grupo.index')
            ->with('message',$this->mensaje)
            ->with('status',$this->status);
        } catch (\Exception $e) {
            $this->mensaje = 'Error en crear el grupo '.$e;
            $this->status = false;
            return redirect()
            ->route('agrup.clientes.grupo.index')
            ->with('message',$this->mensaje)
            ->with('status',$this->status);
        }
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
        $id = Crypt::decrypt($id);
        $grupo = $this->grupoBD->find($id);
        return view('page.agrup.Clientes.grupo.edit',[
            'Grupo' => $grupo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->all();
            $id = Crypt::decrypt($id);
            $grupo = $this->grupoBD->find($id);

            $grupo->nombre = $data['inputName'];
            $grupo->estatus = (int) $data['selectEstatus'];

            try {
                $isUpdate = $grupo->update();
                
                if ($isUpdate) {
                    $message = 'El grupo '.$data['inputName'].' ha sido actualizado correctamente';
                    $status = true;
                 }
                 else {
                    $message = 'No se ha podido actualizar el grupo '.$data['inputName'];
                    $status = false;
                 }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar el grupo: ' . $data['inputName'];
                return redirect()
                ->route('agrup.clientes.grupo.index')
                ->with('message',$message)
                ->with('status',false);
            }
            return redirect()
                ->route('agrup.clientes.grupo.index')
                ->with('message',$message)
                ->with('status',$status);

        } catch (\Throwable $th) {
            return redirect()
                ->route('agrup.clientes.grupo.index')
                ->with('message','No se ha podido encontrar el grupo')
                ->with('status',false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $grupo = $this->grupoBD->find($id);
            $grupo->estatus = 0;

            $isRemoved = $grupo->update();

            if ($isRemoved) {
                $message = 'El grupo se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente el grupo';
                $status = false;
            }

            return redirect()->route('agrup.clientes.grupo.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()->route('agrup.clientes.grupo.index')
                ->with('message', 'No se ha podido encontrar el grupo')
                ->with('status', false);
        }
    }
}
