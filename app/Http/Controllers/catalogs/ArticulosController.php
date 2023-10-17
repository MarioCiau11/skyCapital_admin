<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\ArticulosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticulosRequest;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\HISTORIC_ARTICULOS;
use App\Models\config\CONF_UNIDADES;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ArticulosController extends Controller
{
    private $articuloBD;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    private $catalogo = 'Articulos';

    public function __construct(CAT_ARTICULOS $articulo)
    {
        $this->middleware('auth');
        $this->articuloBD = $articulo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articulo = $this->articuloBD->all();
        $grupo = new AGRUP_GRUPO();
        $categoria = new AGRUP_CATEGORIA;
        return view('page.catalogs.articulos.index', [
            'Articulos' => $articulo,
            'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
            'categoria' => $categoria->getCategoria($this->catalogo)->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = new AGRUP_GRUPO();
        $categoria = new AGRUP_CATEGORIA();
        $unidades = new CONF_UNIDADES();
        // dd($grupo->getGrupo($this->catalogo),$categoria->getCategoria($this->catalogo));
        return view('page.catalogs.articulos.create', [
            'Articulos' => new CAT_ARTICULOS(),
            'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
            'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
            'nextId' => $this->articuloBD->getNextID(),
            'Unidades' => $unidades->getUnidades()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticulosRequest $request)
    {
        $data = $request->validated();
        $articulo = new CAT_ARTICULOS();

        $articulo->clave = $data['inputClave'];
        $articulo->tipo = $data['selectTipo'];
        $articulo->estatus = (int) $data['selectEstatus'];
        $articulo->descripcion = $data['inputDescripcion'];
        $articulo->unidadVenta = $data['selectUnidad'];
        $articulo->categoria = $data['selectCategoria'];
        $articulo->grupo = $data['selectGrupo'];
        $articulo->IVA = $data['inputIVA'];
        $articulo->precio = $data['inputPrecio'];
        $articulo->user_id = auth()->user()->user_id;

        try {
            $isCreated = $articulo->save();

            if ($isCreated) {
                $this->mensaje = 'Se ha creado el artículo con éxito';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido crear el artículo';
                $this->status = false;
            }

        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear el artículo ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.articulos.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.articulos.index')
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
            $articulo = $this->articuloBD->find($id);
            $grupo = new AGRUP_GRUPO();
            $precios = HISTORIC_ARTICULOS::where('articulo', $articulo->clave)->get();
            $categoria = new AGRUP_CATEGORIA();
            $unidades = new CONF_UNIDADES();
            // dd($grupo->getGrupo($this->catalogo),$categoria->getCategoria($this->catalogo));
            return view('page.catalogs.articulos.show', [
                'Articulos' => $articulo,
                'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
                'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
                'nextId' => $this->articuloBD->getNextID(),
                'Unidades' => $unidades->getUnidades()->toArray(),
                'histPrecio' => $precios
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', 'No se ha podido encontrar el artículo')
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
            $articulo = $this->articuloBD->find($id);
            $grupo = new AGRUP_GRUPO();
            $categoria = new AGRUP_CATEGORIA();
            $unidades = new CONF_UNIDADES();
            // dd($grupo->getGrupo($this->catalogo),$categoria->getCategoria($this->catalogo));
            return view('page.catalogs.articulos.edit', [
                'Articulos' => $articulo,
                'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
                'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
                'nextId' => $this->articuloBD->getNextID(),
                'Unidades' => $unidades->getUnidades()->toArray()
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', 'No se ha podido encontrar el artículo')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticulosRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $articulo = $this->articuloBD->find($id);
            $historico = new HISTORIC_ARTICULOS();
            

            if ($data['inputPrecio'] != $articulo->precio) {
                $historico->articulo = $data['inputClave'];
                $historico->listaPrecio = 1;
                $historico->fechaCambio = Carbon::now();
                $articulo->precio == null ? $historico->precio_anterior = null: $historico->precio_anterior = $articulo->precio;
                $historico->precio_nuevo = $data['inputPrecio'];
                $historico->user_id = auth()->user()->username; 

                try{
                $historicCreated = $historico->save();
                }
                catch(\Throwable $th){
                    return redirect()
                    ->route('cat.articulos.index')
                    ->with('message', 'No se ha podido guardar el histórico del precio de la Lista 1')
                    ->with('status', false);
                }

            }

            $articulo->tipo = $data['selectTipo'];
            $articulo->estatus = (int) $data['selectEstatus'];
            $articulo->descripcion = $data['inputDescripcion'];
            $articulo->unidadVenta = $data['selectUnidad'];
            $articulo->categoria = $data['selectCategoria'];
            $articulo->grupo = $data['selectGrupo'];
            $articulo->IVA = $data['inputIVA'];
            $articulo->precio = $data['inputPrecio'];
            $articulo->fechaCambio = Carbon::now();
            $data['selectEstatus'] == 1 ? $articulo->fechaBaja = null : $articulo->fechaBaja = Carbon::now();

           
            try {
                $isUpdated = $articulo->update();
                
                if (($isUpdated) ) {
                    $message = 'El artículo ' . $data['inputClave'] . ' ha sido actualizado correctamente';
                    $status = true;
                } else {
                    $message = ' No se ha podido actualizar el artículo ' . $data['inputClave'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar el artículo: ' . $data['inputClave'];
                return redirect()
                    ->route('cat.articulos.index')
                    ->with('message', $message)
                    ->with('status', false);
            }
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', 'No se ha podido encontrar el artículo')
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
            $articulo = $this->articuloBD->find($id);
            $articulo->estatus = 0;
            $articulo->fechaBaja = Carbon::now();

            $isRemomved = $articulo->update();
            if ($isRemomved) {
                $message = 'El artículo se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente el artículo';
                $status = false;
            }
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.articulos.index')
                ->with('message', 'No se ha podido encontrar el artículo')
                ->with('status', false);
        }
    }

    public function articulosAction(Request $request)
    {
        $data = $request->all();
        $nombre = $data['inputName'];
        $categoria = $data['selectCategoria'];
        $grupo = $data['selectGrupo'];
        $estatus = $data['selectEstatus'] == 'Todos' ? $data['selectEstatus'] : (int) $data['selectEstatus'];
        switch ($request->input('action')) {
            case 'Búsqueda':
                $articulo_filtro = CAT_ARTICULOS::whereArticuloNombre($nombre)
                    ->whereArticuloGrupo($grupo)
                    ->whereArticuloCategoria($categoria)
                    ->whereArticuloEstatus($estatus)
                    ->get();
                // dd($articulo_filtro);
                return redirect()
                    ->route('cat.articulos.index')
                    ->with('articulos_filtro', $articulo_filtro)
                    ->with('inputName', $nombre)
                    ->with('selectCategoria', $categoria)
                    ->with('selectGrupo', $grupo)
                    ->with('selectEstatus', $estatus);

            case 'Exportar excel':
                $articulos = new ArticulosExport($nombre, $categoria, $grupo,$estatus);
                return Excel::download($articulos, 'Articulos.xlsx');
        }
    }

}