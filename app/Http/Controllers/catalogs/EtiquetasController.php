<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\EtiquetasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\EtiquetaRequest;
use App\Models\catalogos\CAT_ETIQUETAS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class EtiquetasController extends Controller
{
    private $etiqueta;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_ETIQUETAS $etiqueta)
    {
        $this->middleware('auth');
        $this->etiqueta = $etiqueta;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $etiquetas = CAT_ETIQUETAS::get();
        return view('page.catalogs.etiquetas.index', [
            'Etiquetas' => $etiquetas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.catalogs.etiquetas.create', [
            'Etiqueta' => new CAT_ETIQUETAS(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EtiquetaRequest $request)
    {
        $data = $request->validated();

        $etiqueta = new CAT_ETIQUETAS();
        $etiqueta->clave = $data['inputClave'];
        $etiqueta->user_id = Auth::user()->user_id;
        $etiqueta->nombre = $data['inputName'];
        $etiqueta->estatus = (int) $data['selectEstatus'];

        $isSave = $etiqueta->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente la etiqueta';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar la etiqueta';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la etiqueta, ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.etiquetas.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.etiquetas.index')
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
            $etiqueta = $this->etiqueta->find($id);

            return view('page.catalogs.etiquetas.show', [
                'Etiqueta' => $etiqueta,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.etiquetas.index')
                ->with('message', 'No se ha podido encontrar la etiqueta')
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
            $etiqueta = $this->etiqueta->find($id);

            return view('page.catalogs.etiquetas.edit', [
                'Etiqueta' => $etiqueta,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.etiquetas.index')
                ->with('message', 'No se ha podido encontrar la etiqueta')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EtiquetaRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $id = Crypt::decrypt($id);
            $etiqueta = $this->etiqueta->find($id);
            $etiqueta->user_id = Auth::user()->user_id;
            $etiqueta->nombre = $data['inputName'];
            $etiqueta->estatus = (int) $data['selectEstatus'];
            $etiqueta->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? ($etiqueta->fechaBaja = null) : ($etiqueta->fechaBaja = Carbon::now()->toDateTime());
            $isSave = $etiqueta->save();

            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente la etiqueta';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar la etiqueta';
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->mensaje = 'Error al actualizar la etiqueta, ' . $th->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.etiquetas.edit', ['id' => Crypt::encrypt($id)])
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.etiquetas.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $etiqueta = $this->etiqueta->find($id);
            $etiqueta->estatus = 0;
            $etiqueta->user_id = Auth::user()->user_id;
            $etiqueta->fechaBaja = Carbon::now()->toDateTime();
            $isSave = $etiqueta->save();

            if ($isSave) {
                $this->mensaje = 'Se ha eliminado correctamente la etiqueta';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido eliminar la etiqueta';
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->mensaje = 'Error al eliminar la etiqueta, ' . $th->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.etiquetas.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.etiquetas.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    public function etiquetaAction(Request $request)
    {
        $keyEtiqueta = $request->inputClave;
        $nameEtiqueta = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $etiqueta_filtro = CAT_ETIQUETAS::whereEtiquetaKey($keyEtiqueta)
                    ->whereEtiquetaName($nameEtiqueta)
                    ->whereEtiquetaStatus($status)
                    ->get();

                // dd($etiqueta_filtro);
                return redirect()
                    ->route('cat.etiquetas.index')
                    ->with('etiquetas_filtro', $etiqueta_filtro)
                    ->with('nombre', $nameEtiqueta)
                    ->with('clave', $keyEtiqueta)
                    ->with('Estatus', $status);
                break;

            case 'Exportar excel':
                $sucursal = new EtiquetasExport($keyEtiqueta, $nameEtiqueta, $status);
                return Excel::download($sucursal, 'catalogo_etiquetas.xlsx');
                break;

            default:
                # code...
                break;
        }
    }
}
