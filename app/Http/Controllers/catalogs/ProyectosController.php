<?php

namespace App\Http\Controllers\catalogs;

use App\Exports\ProyectosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProyectoRequest;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\utils\CAT_FILES_ARCHIVOS;
use App\Models\utils\CAT_FILES_IMAGENES;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use Illuminate\Support\Facades\File;

class ProyectosController extends Controller
{

    private $proyecto;
    public $pagesize = 25;
    public $mensaje;
    public $status;
    private $catalogo = 'Proyectos';
    /**
     * Display a listing of the resource.
     */

    public function __construct(CAT_PROYECTOS $proyecto)
    {
        $this->middleware('auth');
        $this->proyecto = $proyecto;
    }
    public function index()
    {
        $proyectos = CAT_PROYECTOS::all();
        return view(
            'page.catalogs.proyectos.index',
            [
                'Proyectos' => $proyectos,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupo = new AGRUP_GRUPO();
        $categoria = new AGRUP_CATEGORIA();
        return view('page.catalogs.proyectos.create', [
            'Proyecto' => new CAT_PROYECTOS(),
            'imgPrincipal' => '',
            'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
            'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProyectoRequest $request)
    {
        ;
        $data = $request->validated();

        $proyecto = new CAT_PROYECTOS();
        $proyecto->clave = $data['inputClave'];
        $proyecto->nombre = $data['inputNombre'];
        $proyecto->descripcion = $data['inputDescripcion'];
        $proyecto->presupuesto = $data['inputPresupuesto'];
        $proyecto->numCajones = $data['inputCajones'];
        $proyecto->numCajonesRestantes = $data['inputCajonesRestantes'];
        $proyecto->fechaIniProyecto = $data['inputInicio'];
        $proyecto->fechaFinProyecto = $data['inputFin'];
        $proyecto->añoFinProyecto = $data['inputAño'];
        $proyecto->mt2 = $data['inputMT2Totales'];
        $proyecto->nivelesTotales = $data['inputNiveles'];
        $proyecto->direccion = $data['inputDireccion'];
        $proyecto->colonia = $data['inputColonia'];
        $proyecto->ciudadMunicipio = $data['inputCiudad'];
        $proyecto->estado = $data['inputEstado'];
        $proyecto->pais = $data['inputPais'];
        $proyecto->cp = $data['inputCP'];
        $proyecto->categoria = $data['selectCategoria'];
        $proyecto->grupo = $data['selectGrupo'];
        $proyecto->estatus = (int) $data['selectEstatus'];
        $proyecto->titulo1 = $data['inputTituloLink1'];
        $proyecto->link1 = $data['inputLink1'];
        $proyecto->titulo2 = $data['inputTituloLink2'];
        $proyecto->link2 = $data['inputLink2'];
        $proyecto->titulo3 = $data['inputTituloLink3'];
        $proyecto->link3 = $data['inputLink3'];
        $proyecto->user_id = auth()->user()->user_id;

        if (!empty($data['inputImgPrincipal'])) {
            $file = $data['inputImgPrincipal'];
            $filename = $file->getClientOriginalName();
            $proyecto->imagenPrincipal = $filename;
            $file->storeAs('proyectos/' . $proyecto->getNextID(), $filename, 'public');
            $proyecto->save();
        }

        $isSave = $proyecto->save();
        $lastID = $proyecto::latest('idProyecto')->first()->idProyecto;
        $fileImg = $request->file('files');
        $nameDocs = $request['nombreDocumento'];
        $fileDocs = $request->file('field_name');

        $this->subirImagenes($fileImg, $lastID);
        $this->subirDocumentos($nameDocs, $fileDocs, $lastID);


        try {
            if ($isSave) {
                $this->mensaje = 'Se ha guardado correctamente el proyecto';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido guardar el proyecto';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear el proyecto, ' . $e->getMessage();
            $this->status = false;

            return redirect()->route('cat.proyectos.create')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.proyectos.index')
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
            $proyecto = $this->proyecto->find($id);
            return view('page.catalogs.proyectos.show', [
                'Proyecto' => $proyecto,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('cat.proyectos.index')
                ->with('message', 'No se ha podido encontrar el proyecto')
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
            $proyecto = $this->proyecto->find($id);

            $path = 'proyectos/' . $proyecto->idProyecto . '/' . $proyecto->imagenPrincipal;
            $src = "";
            if (Storage::disk('public')->exists($path)) {
                $contents = Storage::disk('public')->get($path);
                $contents = base64_encode($contents);
                $src = 'data: ' . mime_content_type('../storage/app/public/' . $path) . ';base64,' . $contents;
            }

            $articulosImg = new CAT_FILES_IMAGENES();
            $docsProyectos = new CAT_FILES_ARCHIVOS();

            $grupo = new AGRUP_GRUPO();
            $categoria = new AGRUP_CATEGORIA();

            return view('page.catalogs.proyectos.edit', [
                'Proyecto' => $proyecto,
                'imgPrincipal' => $src,
                'grupo' => $grupo->getGrupo($this->catalogo)->toArray(),
                'categoria' => $categoria->getCategoria($this->catalogo)->toArray(),
                'articulosImg' => $articulosImg->getTipo('Proyectos', $proyecto->idProyecto),
                'docsProyectos' => $docsProyectos->getTipo('Proyectos', $proyecto->idProyecto),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('cat.proyectos.index')
                ->with('message', 'No se ha podido encontrar el proyecto')
                ->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProyectoRequest $request, string $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();

        $data = $request->validated();
        try {
            $id = Crypt::decrypt($id);
            $proyecto = $this->proyecto->find($id);
            $proyecto->clave = $data['inputClave'];
            $proyecto->nombre = $data['inputNombre'];
            $proyecto->descripcion = $data['inputDescripcion'];
            $proyecto->presupuesto = $data['inputPresupuesto'];
            $proyecto->numCajones = $data['inputCajones'];
            $proyecto->numCajonesRestantes = $data['inputCajonesRestantes'];
            $proyecto->fechaIniProyecto = $data['inputInicio'];
            $proyecto->fechaFinProyecto = $data['inputFin'];
            $proyecto->añoFinProyecto = $data['inputAño'];
            $proyecto->mt2 = $data['inputMT2Totales'];
            $proyecto->nivelesTotales = $data['inputNiveles'];
            $proyecto->direccion = $data['inputDireccion'];
            $proyecto->colonia = $data['inputColonia'];
            $proyecto->ciudadMunicipio = $data['inputCiudad'];
            $proyecto->estado = $data['inputEstado'];
            $proyecto->pais = $data['inputPais'];
            $proyecto->cp = $data['inputCP'];
            $proyecto->categoria = $data['selectCategoria'];
            $proyecto->grupo = $data['selectGrupo'];
            $proyecto->estatus = (int) $data['selectEstatus'];
            $proyecto->titulo1 = $data['inputTituloLink1'];
            $proyecto->link1 = $data['inputLink1'];
            $proyecto->titulo2 = $data['inputTituloLink2'];
            $proyecto->link2 = $data['inputLink2'];
            $proyecto->titulo3 = $data['inputTituloLink3'];
            $proyecto->link3 = $data['inputLink3'];
            $proyecto->fechaCambio = Carbon::now()->toDateTime();
            $data['selectEstatus'] == 1 ? $proyecto->fechaBaja = null : $proyecto->fechaBaja = Carbon::now()->toDateTime();
            if (!empty($data['inputImgPrincipal'])) {
                $file = $data['inputImgPrincipal'];
                $filename = $file->getClientOriginalName();
                $proyecto->imagenPrincipal = $filename;
                $file->storeAs('proyectos/' . $proyecto->idProyecto, $filename, 'public');
                $proyecto->save();
            }

            $isSave = $proyecto->save();
            $fileImg = $request->file('files');

            $this->subirImagenes($fileImg, $proyecto->idProyecto);

            $nameDocs = isset($request['nombreDocumento']) ? $request['nombreDocumento'] : null;
            $fileDocs = isset($request['field_name']) ? $request->file('field_name') : null;

            if (!isset($request['docsEdit'])) {
                if ($nameDocs != null && $fileDocs != null) {
                    $this->subirDocumentos($nameDocs, $fileDocs, $proyecto->idProyecto);
                }

            } else {
                $this->editarDocumento($request['docsEdit'],$proyecto,$paramGen);
            }


            if ($isSave) {
                $this->mensaje = 'Se ha actualizado correctamente el proyecto';
                $this->status = true;
            } else {
                $this->mensaje = 'No se ha podido actualizar el proyecto';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al actualizar el proyecto, ' . $e->getMessage() . ' Linea: ' . $e->getLine();
            $this->status = false;

            return redirect()->route('cat.proyectos.edit', ['proyecto' => Crypt::encrypt($proyecto->idProyecto)])
                ->with('message', $this->mensaje)
                ->with('status', $this->status);
        }

        return redirect()->route('cat.proyectos.index')
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
            $proyecto = $this->proyecto->find($id);
            $proyecto->fechaBaja = Carbon::now()->toDateTime();
            $proyecto->estatus = 0;

            $isRemoved = $proyecto->update();
            if ($isRemoved) {
                $this->mensaje = "El proyecto se ha dado de baja correctamente";
                $this->status = true;
            } else {
                $this->mensaje = "El proyecto no se ha podido dar de baja";
                $this->status = false;
            }
            return redirect()->route('cat.proyectos.index')
                ->with('message', $this->mensaje)
                ->with('status', $this->status);

        } catch (\Throwable $th) {
            return redirect()->route('cat.proyectos.index')
                ->with('message', 'No se ha podido encontrar el proyecto')
                ->with('status', false);
        }
    }

    public function proyectoAction(Request $request)
    {
        $keyProyecto = $request->inputClave;
        $nameProyecto = $request->inputName;
        $status = $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':

                $proyectos_filtro = CAT_PROYECTOS::whereProyectoKey($keyProyecto)
                ->whereProyectoName($nameProyecto)
                ->whereProyectoStatus($status)
                ->get();

                return redirect()->route('cat.proyectos.index')
                ->with('proyectos_filtro', $proyectos_filtro)
                ->with('nombre', $nameProyecto)
                ->with('clave', $keyProyecto)
                ->with('status2', $status);
                break;

            case 'Exportar excel':
                $proyecto = new ProyectosExport($keyProyecto, $nameProyecto, $status);
                return Excel::download($proyecto, 'catalogo_proyectos.xlsx');
                break;
        }
    }

    public function subirDocumentos($names, $files, $id)
    {
        $parametros = new CONF_PARAMETROS_GENERALES();
        $paramGen = $paramGen = $parametros->byCompany(session('company')->idEmpresa)->first();
        $nameDocs = $names;
        $fileDocs = $files;
        $lastID = $id;

        if ($nameDocs[0] != null) {
            foreach ($nameDocs as $key => $value) {
                $nameDocs[$key] == null ? $nameDocs[$key] = 'Doc-' . time() : $nameDocs[$key] = $nameDocs[$key];
            }
        }

        if ($nameDocs[0] != null) {
            foreach ($nameDocs as $key => $value) {
                $docs = new CAT_FILES_ARCHIVOS();
                $docs->idCatalogo = $lastID;
                $docs->catalogo = 'Proyectos';
                if ($paramGen->docsProyectos != null) {
                    $docs->path = str_replace(['//', '///', '////'], '/', 'proyectos/' . $paramGen->docsProyectos . '-' . $nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'proyectos/' . $paramGen->docsProyectos . '/' . $fileDocs[$key]->getClientOriginalName());
                } else {
                    $docs->path = str_replace(['//', '///', '////'], '/', 'proyectos/' . $lastID . '-' . $nameDocs[$key]);
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'proyectos/' . $lastID . '/' . $fileDocs[$key]->getClientOriginalName());

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
                    $image->catalogo = 'Proyectos';
                    $image->path = str_replace(['//', '///', '////'], '/', 'proyectos/' . $lastID . '/' . $nombreImagen);
                    $image->file = $nombreImagen;
                    $rutaFinal = str_replace(['//', '///', '////'], '/', 'proyectos/' . $lastID . '/' . $nombreImagen);
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
    public function editarDocumento($documentos,$proyecto,$paramGen) {
        foreach ($documentos as $key => $id) {

            $docs_edit = CAT_FILES_ARCHIVOS::find($id);
            $fieldName = isset($request[$id . '-nombre']) ? $request[$id . '-nombre'] : null;
            $fieldFile = isset($request[$id . '-file']) ? $request[$id . '-file'] : null;
            $rutaFile = explode('-', $docs_edit->path)[0];
            $name =$docs_edit->file;
            $rutaFinal = storage_path('app/public/'.$rutaFile.'/'.$name);
            $archivo = File::get($rutaFinal);

            if ($fieldName != null) {
                if ($paramGen->docsProyectos != null) {
                    $docs_edit->path = str_replace(['//', '///', '////'], '/','proyectos/' . $paramGen->docsProyectos  . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/proyectos/' . $paramGen->docsProyectos . '/' . $name); 
                    $rutaBoveda = str_replace(['//', '///', '////'], '/', 'proyectos/' . $paramGen->docsProyectos . '/' . $name);
                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaBoveda, $archivo);
                    }
                }else{
                    $docs_edit->path = str_replace(['//', '///', '////'], '/', 'proyectos/' . $proyecto->idProyecto . '-' . $fieldName);
                    $rutaComprobacion = storage_path('app/public/proyectos/' . $proyecto->idProyecto . '/' . $name); 
                    $rutaDefault = str_replace(['//', '///', '////'], '/', 'proyectos/' . $proyecto->idProyecto . '/' . $name);
                    if(!file_exists($rutaComprobacion)){
                        Storage::disk('public')->put($rutaDefault, $archivo);
                    }
                }
            }
            if ($fieldFile != null) {
                $docs_edit->file = $fieldFile->getClientOriginalName();
                $rutaFinal = str_replace(['//', '///', '////'], '/', 'proyectos/' . $proyecto->idProyecto . '/' . $fieldFile->getClientOriginalName());
                Storage::disk('public')->put($rutaFinal, File::get($fieldFile));
            }
            $docs_edit->update();
        }
    }

}