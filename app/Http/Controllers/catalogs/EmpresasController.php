<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\EmpresasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaRequest;
use App\Models\catalogos\CAT_EMPRESAS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmpresasController extends Controller
{
    private $empresasbd;
    public $pagesize = 10;
    public $mensaje;
    public $status;

    /**
     * Display a listing of the resource.
     */

    //constructor de la clase
    public function __construct(CAT_EMPRESAS $empresa)
    {
        $this->middleware('auth');
        $this->empresasbd = $empresa;
    }
    public function index()
    {
        $empresas = CAT_EMPRESAS::get();
        return view('page.catalogs.empresas.index', [
            'Empresas' => $empresas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.catalogs.empresas.create', [
            'Empresa' => new CAT_EMPRESAS(),
            'logo' => '',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmpresaRequest $request)
    {
        $data = $request->validated();

        // dd($data);
        $empresa = new CAT_EMPRESAS();
        $empresa->clave = $data['inputClave'];
        $empresa->user_id = Auth::user()->user_id;
        $empresa->nombreEmpresa = $data['inputName'];
        $empresa->nombreCorto = $data['inputNameShort'];
        $empresa->descripcion = $data['inputDescripcion'];
        $empresa->estatus = (int) $data['selectEstatus'];
        $empresa->direccion = $data['inputDireccion'];
        $empresa->colonia = $data['inputColonia'];
        $empresa->codigoPostal = $data['inputCP'];
        $empresa->ciudad = $data['inputCiudad'];
        $empresa->estado = $data['inputEstado'];
        $empresa->pais = $data['inputPais'];
        $empresa->telefono1 = $data['inputTelefono1'];
        $empresa->telefono2 = $data['inputTelefono2'];
        $empresa->correoElectronico = $data['inputCorreo'];
        $empresa->RFC = $data['inputRFC'];
        $empresa->regimenFiscal = $data['inputRegimen'];
        $empresa->registroPatronal = $data['inputRegistro'];
        $empresa->representante = $data['inputRepresentante'];

        if (!empty($data['passwordKey'])) {
            $empresa->passwordSat = Crypt::encrypt($data['passwordKey']);
        }

        $empresa->rutaDocumentos = $data['inputDocumentos'];

        if (!empty($data['logoEmpresa'])) {
            $file = $data['logoEmpresa'];
            $extension = $file->getClientOriginalExtension();
            $filename = 'logo.png';
            $empresa->logo = $filename;
            $file->storeAs('empresas/' . $empresa->getNextID(), $filename, 'public');
            $empresa->save();
        }

        if ((int) $data['selectEstatus'] == 0) {
            $empresa->fechaBaja = Carbon::now()->toDateTime();
        }
        $empresa->save();

        $rutaPrincipal = $data['inputDocumentos'];

        // if(!empty($request['certificadoKey']) || !empty($request['certificadoCer']) ) {

        // $rutaKey = $request->file('certificadoKey');
        // $rutaCer = $request->file('certificadoCer');

        // if (isset($rutaKey) or isset($rutaCer)) {

        //     if ($rutaKey && $rutaCer
        //     ) {
        //       $rutaKey->storeAs('empresas/'. $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi' . '/', $rutaKey->getClientOriginalName(), 'public');
        //       $rutaCer->storeAs('empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi' . '/', $rutaCer->getClientOriginalName(), 'public');
        //       $nombreRutaKey = 'empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi/' . $rutaKey->getClientOriginalName();
        //       $nombreRutaCer = 'empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi/' . $rutaCer->getClientOriginalName();
        //     } else {
        //       if ($rutaKey) {
        //         $rutaKey->storeAs('empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi' . '/', $rutaKey->getClientOriginalName(), 'public');
        //         $nombreRutaKey = 'empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi/' . $rutaKey->getClientOriginalName();
        //         $nombreRutaCer = $request['certificadoCer'];
        //       } else {
        //         $rutaCer->storeAs('empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi' . '/', $rutaCer->getClientOriginalName(), 'public');
        //         $nombreRutaCer = 'empresas/' . $empresa->idEmpresa.'/'.$rutaPrincipal. '/cfdi/' . $rutaCer->getClientOriginalName();
        //         $nombreRutaKey = $request['certificadoKey'];
        //       }
        //     }
        //   } else {
        //     $nombreRutaKey = $request['certificadoKey'];
        //     $nombreRutaCer = $request['certificadoCer'];
        //   }

        //     $empresa->rutaLlave = $nombreRutaKey;
        //     $empresa->rutaCertificado = $nombreRutaCer;
        // }
        $isSave = $empresa->save();

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente la empresa';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar la empresa';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la empresa, ' . $e->getMessage();
            $this->status = false;

            return redirect()
                ->route('cat.empresas.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()
            ->route('cat.empresas.index')
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
            $empresa = $this->empresasbd->find($id);
            // dd($empresa->getUser);

            return view('page.catalogs.empresas.show', [
                'Empresa' => $empresa,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', 'No se ha podido encontrar la empresa')
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
            $empresa = $this->empresasbd->find($id);

            $path = 'empresas/' . $empresa->idEmpresa . '/logo.png';
            $src = '';
            if (Storage::disk('public')->exists($path)) {
                // dd($src);
                $contents = Storage::disk('public')->get($path);
                $contents = base64_encode($contents);
                $src = 'data: ' . mime_content_type('../storage/app/public/' . $path) . ';base64,' . $contents;
            }

            return view('page.catalogs.empresas.edit', [
                'Empresa' => $empresa,
                'logo' => $src,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', 'No se ha podido encontrar la empresa')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpresaRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            //  dd($data);
            $id = Crypt::decrypt($id);
            $empresa = $this->empresasbd->find($id);

            $empresa->user_id = Auth::user()->user_id;
            $empresa->nombreEmpresa = $data['inputName'];
            $empresa->nombreCorto = $data['inputNameShort'];
            $empresa->descripcion = $data['inputDescripcion'];
            $empresa->estatus = (int) $data['selectEstatus'];
            // $empresa->logo = $data['inputClave'];
            $empresa->direccion = $data['inputDireccion'];
            $empresa->colonia = $data['inputColonia'];
            $empresa->codigoPostal = $data['inputCP'];
            $empresa->ciudad = $data['inputCiudad'];
            $empresa->estado = $data['inputEstado'];
            $empresa->pais = $data['inputPais'];
            $empresa->telefono1 = $data['inputTelefono1'];
            $empresa->telefono2 = $data['inputTelefono2'];
            $empresa->correoElectronico = $data['inputCorreo'];
            $empresa->RFC = $data['inputRFC'];
            $empresa->regimenFiscal = $data['inputRegimen'];
            $empresa->registroPatronal = $data['inputRegistro'];
            $empresa->representante = $data['inputRepresentante'];

            if (!empty($data['passwordKey'])) {
                $empresa->passwordSat = Crypt::encrypt($data['passwordKey']);
            }

            $empresa->rutaDocumentos = $data['inputDocumentos'];
            $empresa->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? ($empresa->fechaBaja = null) : ($empresa->fechaBaja = Carbon::now()->toDateTime());

            if (!empty($data['logoEmpresa'])) {
                $file = $data['logoEmpresa'];
                $extension = $file->getClientOriginalExtension();
                $filename = 'logo.png';
                $empresa->logo = $filename;
                $file->storeAs('empresas/' . $empresa->idEmpresa, $filename, 'public');
                $empresa->save();
            }

            $rutaPrincipal = $data['inputDocumentos'];

            //   if(!empty($request['certificadoKey']) || !empty($request['certificadoCer']) ) {

            //   $rutaKey = $request->file('certificadoKey');
            //   $rutaCer = $request->file('certificadoCer');

            //   if (isset($rutaKey) or isset($rutaCer)) {

            //       if ($rutaKey && $rutaCer
            //       ) {
            //         $rutaKey->storeAs('empresas/'. $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi' . '/', $rutaKey->getClientOriginalName(), 'public');
            //         $rutaCer->storeAs('empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi' . '/', $rutaCer->getClientOriginalName(), 'public');
            //         $nombreRutaKey = 'empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi/' . $rutaKey->getClientOriginalName();
            //         $nombreRutaCer = 'empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi/' . $rutaCer->getClientOriginalName();

            //         $empresa->rutaLlave = $nombreRutaKey;
            //         $empresa->rutaCertificado = $nombreRutaCer;
            //       } else {
            //         if ($rutaKey) {
            //           $rutaKey->storeAs('empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi' . '/', $rutaKey->getClientOriginalName(), 'public');
            //           $nombreRutaKey = 'empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi/' . $rutaKey->getClientOriginalName();
            //           $empresa->rutaLlave = $nombreRutaKey;
            //         } else {
            //           $rutaCer->storeAs('empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi' . '/', $rutaCer->getClientOriginalName(), 'public');
            //           $nombreRutaCer = 'empresas/' . $empresa->idEmpresa .'/'.$rutaPrincipal. '/cfdi/' . $rutaCer->getClientOriginalName();
            //           $empresa->rutaCertificado = $nombreRutaCer;
            //         }
            //       }
            //     }

            // }

            try {
                $isUpdate = $empresa->update();

                if ($isUpdate) {
                    $this->mensaje = 'La Empresa ' . $data['inputName'] . ' se ha actualizado correctamente';
                    $this->status = true;
                } else {
                    $this->mensaje = 'No se ha podido actualizar la empresa: ' . $data['inputName'];
                    $this->status = false;
                }
            } catch (\Exception $e) {
                $this->mensaje = 'No se guardó correctamente el registro' . $e->getMessage();
                return redirect()
                    ->route('cat.empresas.index')
                    ->with('message', $this->mensaje)
                    ->with('status', false);
            }
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        } catch (\Exception $e) {
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', 'No se ha podido encontrar la empresa' . $e->getMessage())
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
            $empresa = $this->empresasbd->find($id);
            $empresa->fechaBaja = Carbon::now()->toDateTime();
            $empresa->estatus = 0;

            $isRemoved = $empresa->update();
            if ($isRemoved) {
                $this->mensaje = 'La empresa se ha dado de baja correctamente';
                $this->status = true;
            } else {
                $this->mensaje = 'La empresa no se ha podido dar de baja';
                $this->status = false;
            }
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.empresas.index')
                ->with('message', 'No se ha podido encontrar la empresa')
                ->with('status', false);
        }
    }

    public function empresaAction(Request $request)
    {
        // dd($request->all());
        $keyEmpresa = $request->inputClave;
        $nameEmpresa = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $empresa_filtro = CAT_EMPRESAS::whereCompaniesKey($keyEmpresa)
                    ->whereCompaniesName($nameEmpresa)
                    ->whereCompaniesStatus($status)
                    ->get();

                // dd($empresa_filtro);
                return redirect()
                    ->route('cat.empresas.index')
                    ->with('empresas_filtro', $empresa_filtro)
                    ->with('nombre', $nameEmpresa)
                    ->with('clave', $keyEmpresa)
                    ->with('estatus', $status);
                break;

            case 'Exportar excel':
                $empresa = new EmpresasExport($keyEmpresa, $nameEmpresa, $status);
                return Excel::download($empresa, 'catalogo_empresas.xlsx');
                break;

            default:
                # code...
                break;
        }
    }
}
