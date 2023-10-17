<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\ModulosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuloRequest;
use App\Models\catalogos\CAT_INSTITUCIONES_FINANCIERAS;
use App\Models\catalogos\CAT_MODULOS;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\utils\CAT_FILES_ARCHIVOS;
use App\Models\utils\CAT_FILES_IMAGENES;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use Illuminate\Support\Facades\File;

class ModulosController extends Controller
{
    private $modulo;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_MODULOS $modulo)
    {
        $this->middleware('auth');
        $this->modulo = $modulo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modulos = CAT_MODULOS::all();
        return view('page.catalogs.modulos.index',[
            'Modulos' => $modulos,
        ]
    );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proyectos = new CAT_PROYECTOS();
        $bancos = new CAT_INSTITUCIONES_FINANCIERAS();
        return view('page.catalogs.modulos.create', [
            'Modulo' => new CAT_MODULOS(),
            'imgPrincipal' => '',
            'proyectos' => $proyectos->getProjects(),
            'bancos' => $bancos->getBanks(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuloRequest $request)
    {
        // dd($request->all());
        $data = $request->validated();

        $modulo = new CAT_MODULOS();
        $modulo->clave = $data['inputModulo'];
        $modulo->user_id = Auth::user()->user_id;
        $modulo->descripcion = $data['inputDescripción'];
        $modulo->tipo = $data['selectTipo'];
        $modulo->proyecto = $data['selectProyecto'];
        $modulo->mt2 = $data['inputMT2'];
        $modulo->valorOperacion = $data['inputValor'];
        $modulo->nivelPiso = $data['inputNivel'];
        $modulo->numCajones = $data['inputCajones'];
        $modulo->estatus = $data['selectEstatus'];
        $modulo->banco = $data['selectBanco'];
        $modulo->cuenta = $data['inputCuenta'];
        $modulo->clabe = $data['inputClabe'];

        if (!empty($data['inputImgPrincipal'])) {
            $file = $data['inputImgPrincipal'];
            $filename = $file->getClientOriginalName();
            $modulo->imagenPrincipal = $filename;
            $file->storeAs('modulos/'.$modulo->getNextID(), $filename, 'public');
            $modulo->save();
          }

        $isSave = $modulo->save();
        $lastID = $modulo::latest('idModulo')->first()->idModulo;
        $fileImg = $request->file('files');
        $nameDocs=$request['nombreDocumento'];
        $fileDocs=$request->file('field_name');

        $this->subirImagenes($fileImg, $lastID);
        $this->subirDocumentos($nameDocs, $fileDocs, $lastID);

        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente el módulo';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar el módulo';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear el módulo, ' . $e->getMessage();
            $this->status = false;

            return redirect()->route('cat.modulos.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }
        return redirect()->route('cat.modulos.index')
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
            $modulo = $this->modulo->find($id);
            return view('page.catalogs.modulos.show', [
                'Modulo' => $modulo,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('cat.modulos.index')
                ->with('message', 'No se ha podido encontrar el módulo')
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
            $modulo = $this->modulo->find($id);
            $path = 'modulos/'.$modulo->idModulo.'/'.$modulo->imagenPrincipal;
            $src = "";
            if (Storage::disk('public')->exists($path)) {
                $contents = Storage::disk('public')->get($path);
                $contents = base64_encode($contents);
                $src = 'data: '.mime_content_type('../storage/app/public/'.$path).';base64,'.$contents;
            }
            $articulosImg = new CAT_FILES_IMAGENES();
            $docsProyectos = new CAT_FILES_ARCHIVOS();
            $proyectos = new CAT_PROYECTOS();
            $bancos = new CAT_INSTITUCIONES_FINANCIERAS();

            return view('page.catalogs.modulos.edit', [
                'Modulo' => $modulo,
                'imgPrincipal' => $src,
                'articulosImg' => $articulosImg->getTipo('Modulos', $modulo->idModulo),
                'docsProyectos' => $docsProyectos->getTipo('Modulos', $modulo->idModulo),
                'proyectos' => $proyectos->getProjects(),
                'bancos' => $bancos->getBanks(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('cat.modulos.index')
                ->with('message', 'No se ha podido encontrar el módulo')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuloRequest $request, string $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        $data = $request->validated();
        try {
            $id = Crypt::decrypt($id);
            $modulo = $this->modulo->find($id);
            $modulo->clave = $data['inputModulo'];
            $modulo->user_id = Auth::user()->user_id;
            $modulo->descripcion = $data['inputDescripción'];
            $modulo->tipo = $data['selectTipo'];
            $modulo->proyecto = $data['selectProyecto'];
            $modulo->mt2 = $data['inputMT2'];
            $modulo->valorOperacion = $data['inputValor'];
            $modulo->nivelPiso = $data['inputNivel'];
            $modulo->numCajones = $data['inputCajones'];
            $modulo->estatus = $data['selectEstatus'];
            $modulo->banco = $data['selectBanco'];
            $modulo->cuenta = $data['inputCuenta'];
            $modulo->clabe = $data['inputClabe'];
            $modulo->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] != 'Baja' ? $modulo->fechaBaja = null : $modulo->fechaBaja = Carbon::now()->toDateTime();
       
            if (!empty($data['inputImgPrincipal'])) {
                $file = $data['inputImgPrincipal'];
                $filename = $file->getClientOriginalName();
                $modulo->imagenPrincipal = $filename;
                $file->storeAs('modulos/'.$modulo->idModulo, $filename, 'public');
                $modulo->save();
              }

            $isSave = $modulo->save();
            $fileImg = $request->file('files');

            $this->subirImagenes($fileImg, $modulo->idModulo);

            $nameDocs= isset($request['nombreDocumento']) ? $request['nombreDocumento'] : null;
            $fileDocs= isset($request['field_name']) ? $request->file('field_name') : null;

            if (!isset($request['docsEdit'])) {
                if($nameDocs != null && $fileDocs != null){
                    $this->subirDocumentos($nameDocs, $fileDocs, $modulo->idModulo);
                }

            } else {
                $this->editarDocumentos($request['docsEdit'], $modulo, $paramGen);
            }
            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente el módulo';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar el módulo';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al actualizar el módulo, ' . $e->getMessage();
            $this->status = false;
            return redirect()->route('cat.modulos.edit', ['modulo' => Crypt::encrypt($modulo->idModulo)])
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }
        return redirect()->route('cat.modulos.index')
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
            $modulo = $this->modulo->find($id);
            $modulo->fechaBaja = Carbon::now()->toDateTime();
            $modulo->estatus = 'Baja';

            $isRemoved = $modulo->update();
            if ($isRemoved) {
                $this->mensaje = "El módulo se ha dado de baja correctamente";
                $this->status = true;
            } else {
                $this->mensaje = "El módulo no se ha podido dar de baja";
                $this->status = false;
            }
            return redirect()->route('cat.modulos.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);

        } catch (\Throwable $th) {
            return redirect()->route('cat.modulos.index')
                ->with('message', 'No se ha podido encontrar el módulo')
                ->with('status', false);
        }
    }

    public function moduloAction(Request $request)
    {
        $keyModulo = $request->inputClave;
        $nameModulo = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $modulos_filtro = CAT_MODULOS::whereModuloKey($keyModulo)
                ->whereModuloName($nameModulo)
                ->whereModuloStatus($status)
                ->get();
                return redirect()->route('cat.modulos.index')
                ->with('modulos_filtro', $modulos_filtro)
                ->with('nombre', $nameModulo)
                ->with('clave', $keyModulo)
                ->with('status', $status);
                break;

            case 'Exportar excel':
                $modulo = new ModulosExport($keyModulo, $nameModulo, $status);
                return Excel::download($modulo, 'catalogo_modulos.xlsx');
                break;
        }
    }

    public function subirDocumentos($names, $files, $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        $nameDocs = $names;
        $fileDocs = $files;
        $lastID = $id;

        if ($nameDocs[0] != null) {
            foreach ($nameDocs as $key => $value) {
                $nameDocs[$key] == null ? $nameDocs[$key] = 'Doc-' . time() : $nameDocs[$key] = $nameDocs[$key];
            }
        }

        if($nameDocs[0] != null){
            foreach ($nameDocs as $key => $value) {
                $docs = new CAT_FILES_ARCHIVOS();
                $docs->idCatalogo = $lastID;
                $docs->catalogo = 'Modulos';
                
                if ($paramGen->docsModulos != null) {
                    $docs->path = str_replace(['//', '///', '////'], '/', 'modulos/'.$paramGen->docsModulos.'-'.$nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'modulos/'.$paramGen->docsModulos.'/'.$fileDocs[$key]->getClientOriginalName());
                }else{
                    $docs->path= str_replace(['//', '///', '////'], '/', 'modulos/'.$lastID.'-'.$nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'modulos/'.$lastID.'/'.$fileDocs[$key]->getClientOriginalName());
                }
                $docs->file = $fileDocs[$key]->getClientOriginalName();
                Storage::disk('public')->put($rutaFinal, File::get($fileDocs[$key]));
                $docs->save();
            }
        }
    }

    public function subirImagenes($files, $id)
    {
        $lastID = $id;
        $fileImg = $files;
        
        if ($fileImg != null) {
            foreach ($fileImg as $key => $imagen) {
                if ($_FILES['files']['type'][$key] === 'image/png' || $_FILES['files']['type'][$key] === 'image/jpg' || $_FILES['files']['type'][$key] === 'image/jpeg') {
                    $tipoImagen = $imagen->getMimeType();
                    $formato = explode('/', $tipoImagen)[1];
                    $nombreImagen = $imagen->getClientOriginalName();
                    $image = new CAT_FILES_IMAGENES();
                    $image->idCatalogo = $lastID;
                    $image->catalogo = 'Modulos';
                    $image->path = str_replace(['//', '///', '////'], '/', 'modulos/' . $lastID . '/' . $nombreImagen);
                    $image->file = $nombreImagen;
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'modulos/' . $lastID . '/' . $nombreImagen);
                    $image->save();

                    $image_resize = Image::make($imagen);
                    $image_resize->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image_resize->orientate();
                    $image_resize->encode($formato);
                    Storage::disk('public')->put($rutaFinal, $image_resize);
                }
            }
        }
    }
    public function editarDocumentos($documentos, $modulo , $paramGen){
        foreach ($documentos as $key => $id) {

            $docs_edit = CAT_FILES_ARCHIVOS::find($id);
            $fieldName = isset($request[$id . '-nombre']) ? $request[$id . '-nombre'] : null;
            $fieldFile = isset($request[$id . '-file']) ? $request[$id . '-file'] : null;
            $rutaFile = explode('-', $docs_edit->path)[0];
            $name =$docs_edit->file;
            $rutaFinal = storage_path('app/public/'.$rutaFile.'/'.$name);
            $archivo = File::get($rutaFinal);

            if ($fieldName != null) {
                if ($paramGen->docsModulos != null) {
                    $docs_edit->path = str_replace(['//', '///', '////'], '/', 'modulos/' . $paramGen->docsModulos . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/modulos/' . $paramGen->docsModulos . '/' . $name);
                    $rutaBoveda = str_replace(['//', '///', '////'], '/', 'modulos/' . $paramGen->docsModulos . '/' . $name);

                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaBoveda, $archivo);
                    }
                }else{
                    $docs_edit->path = str_replace(['//', '///', '////'], '/', 'modulos/' . $modulo->idModulo . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/clientes/' . $modulo->idModulo . '/' . $name);
                    $rutaDefault = str_replace(['//', '///', '////'], '/', 'clientes/' . $modulo->idModulo . '/' . $name);
                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaDefault, $archivo);
                    }
                }
            }
            if ($fieldFile != null) {
                $docs_edit->file = $fieldFile->getClientOriginalName();
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'modulos/' . $modulo->idModulo . '/' . $fieldFile->getClientOriginalName());
                Storage::disk('public')->put($rutaFinal, File::get($fieldFile));
            }
            $docs_edit->update();
        }
    }
}
