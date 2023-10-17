<?php

namespace App\Http\Controllers\agrup;

use App\Http\Controllers\Controller;
use App\Models\agrup\AGRUP_CATEGORIA;
use Crypt;
use Illuminate\Http\Request;

class AgentesCategoriaController extends Controller
{
    private $categoriaBD;
    public $mensaje;
    public $status;
    
    public function __construct(AGRUP_CATEGORIA $categoria)
    {
        $this->middleware('auth');
        $this->categoriaBD = $categoria;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoria = AGRUP_CATEGORIA::where('catalogo','Agentes de venta')->get();
        return view('page.agrup.Agentes.categoria.index',[
            'Categoria' => $categoria
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.agrup.Agentes.categoria.create',[
            'Categoria' => new AGRUP_CATEGORIA()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $categoria = new AGRUP_CATEGORIA();
        $categoria->nombre = $data['inputName'];
        $categoria->estatus = (int)$data['selectEstatus'];
        $categoria->catalogo = 'Agentes de venta';
        try {
            $isCreated = $categoria->save();
            if ($isCreated) {
                $this->mensaje = 'Se ha creado la categoría correctamente';
                $this->status = true;
            }
            else{
                $this->mensaje = 'No se ha podido crear la categoría';
                $this->status = false;
            }
            return redirect()
            ->route('agrup.categoria.index')
            ->with('message',$this->mensaje)
            ->with('status',$this->status);
        } catch (\Exception $e) {
            $this->mensaje = 'Error en crear la categoría '.$e;
            $this->status = false;
            return redirect()
            ->route('agrup.categoria.index')
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
        $categoria = $this->categoriaBD->find($id);
        return view('page.agrup.Agentes.categoria.edit',[
            'Categoria' => $categoria
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
            $categoria = $this->categoriaBD->find($id);

            $categoria->nombre = $data['inputName'];
            $categoria->estatus = (int) $data['selectEstatus'];

            try {
                $isUpdate = $categoria->update();
                
                if ($isUpdate) {
                    $message = 'La categoría '.$data['inputName'].' ha sido actualizada correctamente';
                    $status = true;
                 }
                 else {
                    $message = 'No se ha podido actualizar la categoría '.$data['inputName'];
                    $status = false;
                 }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar la categoría: ' . $data['inputName'];
                return redirect()
                ->route('agrup.categoria.index')
                ->with('message',$message)
                ->with('status',false);
            }
            return redirect()
                ->route('agrup.categoria.index')
                ->with('message',$message)
                ->with('status',$status);

        } catch (\Throwable $th) {
            return redirect()
                ->route('agrup.categoria.index')
                ->with('message','No se ha podido encontrar la categoría')
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
            $categoria = $this->categoriaBD->find($id);
            $categoria->estatus = 0;

            $isRemoved = $categoria->update();

            if ($isRemoved) {
                $message = 'La categoría se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente La categoría';
                $status = false;
            }

            return redirect()->route('agrup.categoria.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()->route('agrup.categoria.index')
                ->with('message', 'No se ha podido encontrar la categoría')
                ->with('status', false);
        }
    }
}
