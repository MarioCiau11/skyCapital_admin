<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\SucursalesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\SucursalRequest;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\catalogos\CAT_SUCURSALES;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class SucursalesController extends Controller
{
    private $sucusal;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_SUCURSALES $sucusal)
    {
        $this->middleware('auth');
        $this->sucusal = $sucusal;
    }
    public function index()
    {
        $sucursales = CAT_SUCURSALES::get();
        return view('page.catalogs.sucursales.index', [
            'Sucursales' => $sucursales,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresa = new CAT_EMPRESAS();
        return view('page.catalogs.sucursales.create', [
            'Sucursal' => new CAT_SUCURSALES(),
            'Empresas' => $empresa->getCompanies(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SucursalRequest $request)
    {
        $data = $request->validated();

        $sucursal = new CAT_SUCURSALES();
        $sucursal->clave = $data['inputClave'];
        $sucursal->user_id = Auth::user()->user_id;
        $sucursal->nombre = $data['inputName'];
        $sucursal->idEmpresa = $data['empresa'];
        $sucursal->estatus = (int) $data['selectEstatus'];
        $sucursal->direccion = $data['inputDireccion'];
        $sucursal->colonia = $data['inputColonia'];
        $sucursal->codigoPostal = $data['inputCP'];
        $sucursal->ciudad = $data['inputCiudad'];
        $sucursal->estado = $data['inputEstado'];
        $sucursal->pais = $data['inputPais'];

        $isSave = $sucursal->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente la sucursal';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar la sucursal';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la sucursal, ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.sucursales.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.sucursales.index')
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
            $sucursal = $this->sucusal->find($id);
            $empresa = new CAT_EMPRESAS();

            return view('page.catalogs.sucursales.show', [
                'Sucursal' => $sucursal,
                'Empresas' => $empresa->getCompanies(),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.sucursales.index')
                ->with('message', 'No se ha podido encontrar la sucursal')
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
            $sucursal = $this->sucusal->find($id);
            $empresa = new CAT_EMPRESAS();

            return view('page.catalogs.sucursales.edit', [
                'Sucursal' => $sucursal,
                'Empresas' => $empresa->getCompanies(),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.sucursales.index')
                ->with('message', 'No se ha podido encontrar la sucursal')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SucursalRequest $request, string $id)
    {
        $data = $request->validated();
        $id = Crypt::decrypt($id);
        $sucursal = $this->sucusal->find($id);
        $sucursal->user_id = Auth::user()->user_id;
        $sucursal->nombre = $data['inputName'];
        $sucursal->idEmpresa = $data['empresa'];
        $sucursal->estatus = (int) $data['selectEstatus'];
        $sucursal->direccion = $data['inputDireccion'];
        $sucursal->colonia = $data['inputColonia'];
        $sucursal->codigoPostal = $data['inputCP'];
        $sucursal->ciudad = $data['inputCiudad'];
        $sucursal->estado = $data['inputEstado'];
        $sucursal->pais = $data['inputPais'];
        $sucursal->fechaCambio = Carbon::now()->toDateTime();
        $data['selectEstatus'] == 1 ? ($sucursal->fechaBaja = null) : ($sucursal->fechaBaja = Carbon::now()->toDateTime());
        $isSave = $sucursal->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente la sucursal';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar la sucursal';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al actualizar la sucursal, ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.sucursales.edit', ['id' => Crypt::encrypt($id)])
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.sucursales.index')
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
            $sucursal = $this->sucusal->find($id);
            $sucursal->estatus = 0;
            $sucursal->user_id = Auth::user()->user_id;
            $sucursal->fechaBaja = Carbon::now()->toDateTime();
            $isSave = $sucursal->save();

            if ($isSave) {
                $this->mensaje = 'Se ha dado de baja correctamente la sucursal';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido dar de baja la sucursal';
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->mensaje = 'Error al dar de baja la sucursal, ' . $th->getMessage();
            $this->status = false;
        }

        return redirect()
            ->route('cat.sucursales.index')
            ->with('message', $this->mensaje)
            ->with('status', $this->status);
    }

    public function sucursalAction(Request $request)
    {
        $keySucursal = $request->inputClave;
        $nameSucursal = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $sucursal_filtro = CAT_SUCURSALES::whereSucursalKey($keySucursal)
                    ->whereSucursalName($nameSucursal)
                    ->whereSucursalStatus($status)
                    ->get();

                // dd($sucursal_filtro);
                return redirect()
                    ->route('cat.sucursales.index')
                    ->with('sucursales_filtro', $sucursal_filtro)
                    ->with('nombre', $nameSucursal)
                    ->with('clave', $keySucursal)
                    ->with('estatus', $status);
                

            case 'Exportar excel':
                $sucursal = new SucursalesExport($keySucursal, $nameSucursal, $status);
                return Excel::download($sucursal, 'catalogo_sucursales.xlsx');

            default:
                # code...
                break;
        }
    }
}
