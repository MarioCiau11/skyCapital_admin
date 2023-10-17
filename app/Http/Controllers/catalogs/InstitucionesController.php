<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\InstitucionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionRequest;
use App\Models\catalogos\CAT_INSTITUCIONES_FINANCIERAS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class InstitucionesController extends Controller
{
    private $institucion;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_INSTITUCIONES_FINANCIERAS $institucion)
    {
        $this->middleware('auth');
        $this->institucion = $institucion;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instituciones = CAT_INSTITUCIONES_FINANCIERAS::get();
        return view('page.catalogs.instituciones.index',[
            'instituciones' => $instituciones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.catalogs.instituciones.create', [
            'Institucion' => new CAT_INSTITUCIONES_FINANCIERAS(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstitucionRequest $request)
    {
        $data = $request->validated();

        $institucion = new CAT_INSTITUCIONES_FINANCIERAS();
        $institucion->clave = $data['inputClave'];
        $institucion->user_id = Auth::user()->user_id;
        $institucion->nombre = $data['inputName'];
        $institucion->ciudad = $data['inputCiudad'];
        $institucion->estado = $data['inputEstado'];
        $institucion->pais = $data['inputPais'];
        $institucion->estatus = (int) $data['selectEstatus'];

        $isSave = $institucion->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente la institución';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar la institución';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la institución, ' . $e->getMessage();
            $this->status = false;

            return redirect()->route('cat.instituciones.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.instituciones.index')
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
            $institucion = $this->institucion->find($id);

            return view('page.catalogs.instituciones.show', [
                'Institucion' => $institucion,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('cat.instituciones.index')
                ->with('message', 'No se ha podido encontrar la institución' . $e->getMessage())
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
            $institucion = $this->institucion->find($id);

            return view('page.catalogs.instituciones.edit', [
                'Institucion' => $institucion,
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('cat.instituciones.index')
                ->with('message', 'No se ha podido encontrar la institución')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InstitucionRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $id = Crypt::decrypt($id);
            $institucion = $this->institucion->find($id);

            $institucion->user_id = Auth::user()->user_id;
            $institucion->nombre = $data['inputName'];
            $institucion->ciudad = $data['inputCiudad'];
            $institucion->estado = $data['inputEstado'];
            $institucion->pais = $data['inputPais'];
            $institucion->estatus = (int) $data['selectEstatus'];
            $institucion->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $institucion->fechaBaja = null : $institucion->fechaBaja = Carbon::now()->toDateTime();

            $isSave = $institucion->save();

            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente la institución';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar la institución';
                $this->status = false;
            }

        } catch (\Throwable $th) {
            $this->mensaje = 'Error al actualizar la institución, ' . $th->getMessage();
            $this->status = false;

            return redirect()->route('cat.instituciones.edit', Crypt::encrypt($id))
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.instituciones.index')
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
            $institucion = $this->institucion->find($id);
            $institucion->estatus = 0;
            $institucion->user_id = Auth::user()->user_id;
            $institucion->fechaBaja = Carbon::now()->toDateTime();
            $isSave = $institucion->save();

            if ($isSave) {
                $this->mensaje = 'Se ha eliminado correctamente la institucíon';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido eliminar la institucíon';
                $this->status = false;
            }

        } catch (\Throwable $th) {
            $this->mensaje = 'Error al eliminar la institucíon, ' . $th->getMessage();
            $this->status = false;

            return redirect()->route('cat.instituciones.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.instituciones.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    public function institucionAction(Request $request)
    {
        $keyInstitucion = $request->inputClave;
        $nameInstitucion = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                    
        $institucion_filtro = CAT_INSTITUCIONES_FINANCIERAS::whereInstitucionKey($keyInstitucion)->whereInstitucionName($nameInstitucion)->whereInstitucionStatus($status)->get();

                return redirect()->route('cat.instituciones.index')->with('instituciones_filtro', $institucion_filtro)->with('nombre', $nameInstitucion)->with('clave', $keyInstitucion)->with('estatus', $status);
                break;

            case 'Exportar excel':
                $institucion = new InstitucionExport($keyInstitucion, $nameInstitucion, $status);
                // dd($institucion);
                return Excel::download($institucion, 'catalogo_instituciones.xlsx');
                break;
            
            default:
                # code...
                break;
        }
    }
}
