<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\ClientesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientesRequest;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\utils\CAT_FILES_ARCHIVOS;
use Carbon\Carbon;
use Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class ClientesController extends Controller
{
    private $clienteBD ;
    public $message ;
    public $status;
    private $catalogo = 'Clientes';

    public function __construct(CAT_CLIENTES $cliente)
    {
        $this->middleware('auth');
        $this->clienteBD = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cliente = $this->clienteBD->all();
        $grupo = new AGRUP_GRUPO();
        $categoria = new AGRUP_CATEGORIA();
        return view('page.catalogs.clientes.index',[
            'Clientes' => $cliente,
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
        $condiciones = new CONF_CONDICIONES_CRED();
        $categoria = new AGRUP_CATEGORIA();
        return view('page.catalogs.clientes.create',[
            'Clientes' => new CAT_CLIENTES(),
            'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
            'categoria' => $categoria->getCategoria($this->catalogo),
            'Clave' => $this->clienteBD->getNextID(),
            'condiciones' => $condiciones->getCondicionies(),
            'contraseñaPortal' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientesRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $cliente  = new CAT_CLIENTES();

        $cliente->clave = $data['inputClave'];
        $data['checkPersona'] == 'personaMoral' ? $cliente->tipoPersona = 0 : $cliente->tipoPersona = 1;
        // $cliente->tipoPersona = (int)$data['checkPersona'];
        $cliente->razonSocial = $data['inputRazon'];
        $cliente->RFC = $data['inputRFC'];
        $cliente->CURP = $data['inputCURP'];
        $cliente->nombres = $data['inputNombre'];
        // $cliente->razonSocial = $data[''];
        $cliente->apellidoMaterno = $data['inputApellidoM'];
        $cliente->apellidoPaterno = $data['inputApellidoP'];
        $cliente->telefonCelular = $data['inputTelefonoC'];
        $cliente->correoElectronico = $data['inputCorreo'];
        $cliente->direccion = $data['inputDireccion'];
        $cliente->vialidades = $data['inputVialidades'];
        $cliente->noExterior = $data['inputNoExterior'];
        $cliente->noInterior = $data['inputNoInterior'];
        $cliente->coloniaFracc = $data['inputColonia'];
        $cliente->localidadMunicipio = $data['inputLocalidad'];
        $cliente->estado = $data['inputEstado'];
        $cliente->pais = $data['inputPais'];
        $cliente->codigoPostal = $data['inputCodigoP'];
        $cliente->telefono1 = $data['inputTelOf1'];
        $cliente->telefono2 = $data['inputTelOf2'];
        $cliente->telCelular = $data['inputRepTelCel'];
        $cliente->contacto1 = $data['inputContacto1'];
        $cliente->correoElectronico1 = $data['inputCorreoElct1'];
        $cliente->contacto2 = $data['inputContacto2'];
        $cliente->correoElectronico2 = $data['inputCorreoElct2'];
        // $cliente->contraseñaPortal = $data['inputcontraseñaPortal'];
        $cliente->observaciones = $data['textareaObservaciones'];
        $cliente->ocupacion = $data['inputOcupacion'];
        $cliente->grupo = $data['selectGrupo'];
        $cliente->categoria = $data['selectCategoria'];
        $cliente->estatus = (int)$data['selectEstatus'];
        $cliente->condicionPago = $data['selectCondicion'];
        $cliente->regimenFisc = $data['inputRegimen'];
        $cliente->user_id = auth()->user()->user_id;

        if (!empty($data['inputcontraseñaPortal'])) {
            $cliente->contraseñaPortal = Crypt::encrypt($data['inputcontraseñaPortal']);   
        }
        $isCreated = $cliente->save();

        $nombreDocs = $data['inputDocumento'];
        $fileDocs = $request->file('archivos');
        $lastId = $cliente::latest('idCliente')->first()->idCliente;

        $this->subirDocumentos($nombreDocs, $fileDocs, $lastId);


        // dd($cliente);

        try {

            // dd($isCreated = $cliente->save());
            if ($isCreated) {
                $this->message = 'Se ha guardado correctamente el cliente';
                $this->status = true;
            } else {
                $this->message = 'No se ha podido guardar el cliente';
                $this->status = false;
            }
        } catch (\Exception $e) {
            // dd($e);
            $this->message = 'Error al crear el cliente, ' . $e->getMessage();
            $this->status = false;

            return redirect()->route('cat.clientes.create')
                ->with('message', $this->message)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.clientes.index')
            ->with('message', $this->message)
            ->with('status', $this->status);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Id = Crypt::decrypt($id);
            $cliente = $this->clienteBD->find($Id);
            $documentos = new CAT_FILES_ARCHIVOS();
            if ($cliente->contraseñaPortal != null) {
                $contraseña = Crypt::decrypt($cliente->contraseñaPortal);
            }
            $contraseña = null;
            // dd($cliente);

            return view('page.catalogs.clientes.show', [
                'Clientes' => $cliente,
                'contraseñaPortal' => $contraseña,
                'documentos' => $documentos->getTipo('Clientes', $cliente->idCliente),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.clientes.index')
                ->with('message', 'No se ha podido encontrar el cliente')
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
            $cliente = $this->clienteBD->find($id);
            // dd($cliente);
            if ($cliente->contraseñaPortal != null) {
                $contraseña = Crypt::decrypt($cliente->contraseñaPortal);
            } else {
                $contraseña = null;
            }
            $grupo = new AGRUP_GRUPO();
            $condiciones = new CONF_CONDICIONES_CRED();
            $categoria = new AGRUP_CATEGORIA();
            $documentos = new CAT_FILES_ARCHIVOS();
            // dd($documentos->getTipo('Clientes', $cliente->idCliente));
            return view('page.catalogs.clientes.edit', [
                'Clientes' => $cliente,
                'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
                'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
                'Clave' => $this->clienteBD->getNextID(),
                'condiciones' => $condiciones->getCondicionies()->toArray(),
                'contraseñaPortal' => $contraseña,
                'documentos' => $documentos->getTipo('Clientes', $cliente->idCliente),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.clientes.index')
                ->with('message', 'No se ha podido encontrar el cliente')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientesRequest $request, string $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $cliente = $this->clienteBD->find($id);
            $cliente->clave = $data['inputClave'];
            $data['checkPersona'] == 'personaMoral' ? $cliente->tipoPersona = 0 : $cliente->tipoPersona = 1;
            $cliente->razonSocial = $data['inputRazon'];
            $cliente->RFC = $data['inputRFC'];
            $cliente->CURP = $data['inputCURP'];
            $cliente->nombres = $data['inputNombre'];
            $cliente->apellidoMaterno = $data['inputApellidoM'];
            $cliente->apellidoPaterno = $data['inputApellidoP'];
            $cliente->telefonCelular = $data['inputTelefonoC'];
            $cliente->correoElectronico = $data['inputCorreo'];
            $cliente->direccion = $data['inputDireccion'];
            $cliente->vialidades = $data['inputVialidades'];
            $cliente->noExterior = $data['inputNoExterior'];
            $cliente->noInterior = $data['inputNoInterior'];
            $cliente->coloniaFracc = $data['inputColonia'];
            $cliente->localidadMunicipio = $data['inputLocalidad'];
            $cliente->estado = $data['inputEstado'];
            $cliente->pais = $data['inputPais'];
            $cliente->codigoPostal = $data['inputCodigoP'];
            $cliente->telefono1 = $data['inputTelOf1'];
            $cliente->telefono2 = $data['inputTelOf2'];
            $cliente->telCelular = $data['inputRepTelCel'];
            $cliente->contacto1 = $data['inputContacto1'];
            $cliente->correoElectronico1 = $data['inputCorreoElct1'];
            $cliente->contacto2 = $data['inputContacto2'];
            $cliente->correoElectronico2 = $data['inputCorreoElct2'];
            $cliente->observaciones = $data['textareaObservaciones'];
            $cliente->ocupacion = $data['inputOcupacion'];
            $cliente->grupo = $data['selectGrupo'];
            $cliente->categoria = $data['selectCategoria'];
            $cliente->estatus = (int)$data['selectEstatus'];
            $cliente->condicionPago = $data['selectCondicion'];
            $cliente->regimenFisc = $data['inputRegimen'];
            $cliente->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $cliente->fechaBaja = null : $cliente->fechaBaja = Carbon::now()->toDateTime();
            
            if (!empty($data['inputcontraseñaPortal'])) {
                $cliente->contraseñaPortal = Crypt::encrypt($data['inputcontraseñaPortal']);   
            }
            $isUpdated = $cliente->update();
            $nameDocs= isset($request['inputDocumento']) ? $request['inputDocumento'] : null;
            $fileDocs= isset($request['archivos']) ? $request->file('archivos') : null;

            if (!isset($request['docsEdit'])) {
                if($nameDocs != null && $fileDocs != null)
                {
                    $this->subirDocumentos($nameDocs, $fileDocs, $cliente->idCliente);
                }
            }
            else{
                $this->uploadDocumentsParam($request['docsEdit'],$paramGen,$cliente);
            }

            if (($isUpdated) ) {
                $message = 'El cliente ' . $data['inputRazon'] . ' ha sido actualizado correctamente';
                $status = true;
            } else {
                $message = ' No se ha podido actualizar el cliente ' . $data['inputRazon'];
                $status = false;
            }

        } catch (\Throwable $th) {
            return redirect()
            ->route('cat.clientes.index')
            ->with('message', 'No se ha podido encontrar el cliente')
            ->with('status', false);
        }

        return redirect()
                ->route('cat.clientes.index')
                ->with('message', $message)
                ->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $cliente = $this->clienteBD->find($id);
            $cliente->estatus = 0;
            $cliente->fechaBaja = Carbon::now();
        
            $isRemomved = $cliente->update();
            if ($isRemomved) {
                $message = 'El cliente se ha dado de baja correctamente';
                $status = true;
            } else {
                $message = 'No se ha podido dar de baja correctamente el cliente';
                $status = false;
            }
            return redirect()
                ->route('cat.clientes.index')
                ->with('message', $message)
                ->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()
                ->route('cat.clientes.index')
                ->with('message', 'No se ha podido encontrar el cliente')
                ->with('status', false);
        }

    }
    public function clientesAction(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $clave = $data['inputClave'];
        $nombre = $data['inputName'];
        $razonSocial =$data['inputRazon'];
        $categoria = $data['selectCategoria'];
        $grupo = $data['selectGrupo'];
        $estatus = $data['selectEstatus'] == 'Todos' ? $data['selectEstatus'] : (int) $data['selectEstatus'];
        switch ($request->input('action')) {
            case 'Búsqueda':
                $cliente_filtro = CAT_CLIENTES::whereClientesClave($clave)
                ->whereClientesNombre($nombre)
                ->whereClientesRazon($razonSocial)
                ->whereClientesCategoria($categoria)
                ->whereClientesGrupo($grupo)
                ->whereClientesEstatus($estatus)
                ->get();
            return redirect()
            ->route('cat.clientes.index')
            ->with('clientes_filtro',$cliente_filtro)
            ->with('inputClave',$clave)
            ->with('inputName',$nombre)
            ->with('inputRazon',$razonSocial)
            ->with('selectCategoria',$categoria)
            ->with('selectGrupo',$grupo)
            ->with('selectEstatus',$estatus);
            
            case 'Exportar excel':
             $clientes = new ClientesExport($clave, $nombre, $razonSocial,$categoria,$grupo,$estatus);
                return Excel::download($clientes, 'Clientes.xlsx');

        }
    }

    public function subirDocumentos($names,$files,$id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();

        $nameDocs = $names;
        $fileDocs = $files;
        $lastId = $id;

        if ($nameDocs[0] != null) {
            foreach ($nameDocs as $key => $value) {
                $nameDocs[$key] == null ? $nameDocs[$key] = 'Doc-' . time() : $nameDocs[$key] = $nameDocs[$key];
            }
        }
        if ($nameDocs[0] != null) {
            foreach ($nameDocs as $key => $value) {
                $docs = new CAT_FILES_ARCHIVOS();
                $docs->idCatalogo = $lastId;
                $docs->catalogo = 'Clientes';

                if ($paramGen->docsClientes != null) {
                    $docs->path = str_replace(['//','///','////'],'/','clientes/'.$paramGen->docsClientes.'-'.$nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'clientes/'.$paramGen->docsClientes.'/'.$fileDocs[$key]->getClientOriginalName());
                }else{
                    $docs->path = str_replace(['//','///','////'],'/','clientes/'.$lastId.'-'.$nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'clientes/'.$lastId.'/'.$fileDocs[$key]->getClientOriginalName());
                }
                
                $docs->file = $fileDocs[$key]->getClientOriginalName();
                Storage::disk('public')->put($rutaFinal, File::get($fileDocs[$key]));
                $docs->save();
            }
        }

    }
    public function uploadDocumentsParam($documentos,$paramGen,$cliente){
        foreach ($documentos as $key => $id) {
            $docs_edit = CAT_FILES_ARCHIVOS::find($id);
            $fieldName = isset($request[$id . '-nombre']) ? $request[$id . '-nombre'] : null;
            $fieldFile = isset($request[$id . '-file']) ? $request[$id . '-file'] : null;
            $rutaFile = explode('-', $docs_edit->path)[0];
            $name = $docs_edit->file;
            $rutaFinal = storage_path('app/public/' . $rutaFile.'/'.$name);
            $archivo = file_get_contents($rutaFinal);
            if ($fieldName != null) {
                if ($paramGen->docsClientes != null) {
                    $docs_edit->path = str_replace(['//', '///', '////'], '/', 'clientes/' . $paramGen->docsClientes . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/clientes/' . $paramGen->docsClientes . '/' . $name); 
                    $rutaBoveda = str_replace(['//', '///', '////'], '/', 'clientes/' . $paramGen->docsClientes . '/' . $name);
                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaBoveda, $archivo);
                    }
                }else{
                    $docs_edit->path = str_replace(['//', '///', '////'], '/', 'clientes/' . $cliente->idCliente . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/clientes/' . $cliente->idCliente . '/' . $name);
                    $rutaDefault = str_replace(['//', '///', '////'], '/', 'clientes/' . $cliente->idCliente . '/' . $name);
                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaDefault, $archivo);
                    }
                }
            }
            if ($fieldFile != null) {
                $docs_edit->file = $fieldFile->getClientOriginalName();
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'clientes/' . $cliente->idCliente . '/' . $fieldFile->getClientOriginalName());
                Storage::disk('public')->put($rutaFinal, File::get($fieldFile));
            }
            $docs_edit->update();
        }
    }
}
