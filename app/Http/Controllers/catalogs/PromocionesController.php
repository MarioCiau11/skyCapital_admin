<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\PromocionesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromocionRequest;
use App\Models\catalogos\CAT_PROMOCIONES;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class PromocionesController extends Controller
{
    private $promocion;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_PROMOCIONES $promocion)
    {
        $this->middleware('auth');
        $this->promocion = $promocion;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promociones = CAT_PROMOCIONES::get();
        return view('page.catalogs.promociones.index', [
            'Promociones' => $promociones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.catalogs.promociones.create', [
            'Promocion' => new CAT_PROMOCIONES(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromocionRequest $request)
    {
        $data = $request->validated();

        $promocion = new CAT_PROMOCIONES();
        $promocion->clave = $data['inputClave'];
        $promocion->user_id = Auth::user()->user_id;
        $promocion->nombre = $data['inputName'];
        $promocion->estatus = (int) $data['selectEstatus'];
        $isSave = $promocion->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente la promoción';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar la promoción';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la promoción, ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.promociones.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.promociones.index')
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
            $promocion = $this->promocion->find($id);

            return view('page.catalogs.promociones.show', [
                'Promocion' => $promocion,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.promociones.index')
                ->with('message', 'No se ha podido encontrar la promocion')
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
            $promocion = $this->promocion->find($id);

            return view('page.catalogs.promociones.edit', [
                'Promocion' => $promocion,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.promociones.index')
                ->with('message', 'No se ha podido encontrar la promocion')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PromocionRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $id = Crypt::decrypt($id);
            $promocion = $this->promocion->find($id);
            $promocion->user_id = Auth::user()->user_id;
            $promocion->nombre = $data['inputName'];
            $promocion->estatus = (int) $data['selectEstatus'];
            $promocion->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? ($promocion->fechaBaja = null) : ($promocion->fechaBaja = Carbon::now()->toDateTime());
            $isSave = $promocion->save();

            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente la promoción';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar la promoción';
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->mensaje = 'Error al actualizar la promoción, ' . $th->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.promociones.edit', ['id' => Crypt::encrypt($id)])
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.promociones.index')
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
            $promocion = $this->promocion->find($id);
            $promocion->estatus = 0;
            $promocion->user_id = Auth::user()->user_id;
            $promocion->fechaBaja = Carbon::now()->toDateTime();
            $isSave = $promocion->save();

            if ($isSave) {
                $this->mensaje = 'Se ha dado de baja correctamente la promoción';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido dar de baja la promoción';
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->mensaje = 'Error al dar de baja la promoción, ' . $th->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.promociones.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.promociones.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    public function promocionAction(Request $request)
    {
        $keyPromocion = $request->inputClave;
        $namePromocion = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $promocion_filtro = CAT_PROMOCIONES::wherePromocionKey($keyPromocion)
                    ->wherePromocionName($namePromocion)
                    ->wherePromocionStatus($status)
                    ->get();

                // dd($promocion_filtro);
                return redirect()
                    ->route('cat.promociones.index')
                    ->with('promociones_filtro', $promocion_filtro)
                    ->with('nombre', $namePromocion)
                    ->with('clave', $keyPromocion)
                    ->with('estatus', $status);
                break;

            case 'Exportar excel':
                $sucursal = new PromocionesExport($keyPromocion, $namePromocion, $status);
                return Excel::download($sucursal, 'catalogo_promociones.xlsx');
                break;

            default:
                # code...
                break;
        }
    }
}
