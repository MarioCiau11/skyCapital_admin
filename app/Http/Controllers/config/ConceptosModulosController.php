<?php

namespace App\Http\Controllers\config;

use App\Exports\ConceptosModuloExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConceptosModRequest;
use App\Models\catalogosSat\CAT_SAT_PROD_SERV;
use App\Models\config\CONF_CONCEPTOS_MODULOS;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConceptosModulosController extends Controller
{

    private $modulosbd;
    public $mensaje;
    public $status;
    public $pagesize = 25;

    //constructor de la clase
    public function __construct(CONF_CONCEPTOS_MODULOS $concepto)
    {
        $this->middleware('auth');
        $this->modulosbd = $concepto;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conceptosMod = CONF_CONCEPTOS_MODULOS::where('estatus',1)->get();
        return view('page.config.conceptos_modulos.index',[
        'ConceptosMod' => $conceptosMod,
        'columns' => $this->modulosbd->columns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cat_sat_ProdServ = new CAT_SAT_PROD_SERV();
        return view('page.config.conceptos_modulos.create',[
            'ConceptosMod' => new CONF_CONCEPTOS_MODULOS(),
            'cat_sat_ProdServ' => $cat_sat_ProdServ->getProdServ()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConceptosModRequest $request)
    {
        $data = $request->validated();
        $concepto = new CONF_CONCEPTOS_MODULOS();
        $user = auth()->user()->user_id;

        $concepto->nombreConcepto = $data['inputConcepto'];
        $concepto->modulo = $data['selectModulo'];
        $concepto->movimiento = $data['selectMovimiento'];
        $concepto->estatus = (int)$data['selectEstatus'];
        $concepto->claveProdServ = $data['inputClave'];
        $concepto->user_id = $user;
        try {
            $isCreated = $concepto->save();
            if($isCreated){
                $this->mensaje = 'El concepto se ha creado con éxtio';
                $this->status = true;
            }
            else{
                $this->mensaje = 'No se ha podido crear el concepto';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear la condición de crédito'.$e->getMessage();
            $this->status = false;
            return redirect()
            ->route('config.conceptos-modulos')
            ->with('message',$this->mensaje)
            ->with('status', $this->status);
        }
        return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message', $this->mensaje)
            ->with('status',$this->status);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $concepto = $this->modulosbd->find($id);
            return view('page.config.conceptos_modulos.show',[
                'ConceptosMod' => $concepto
            ]);
        } catch (\Throwable $th) {
            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message','No se ha podido mostrar el concepto')
            ->with('status',false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $concepto = $this->modulosbd->find($id);
            $cat_sat_ProdServ = new CAT_SAT_PROD_SERV();
            return view('page.config.conceptos_modulos.edit',[
                'ConceptosMod' => $concepto,
                'cat_sat_ProdServ' => $cat_sat_ProdServ->getProdServ()
            ]);
        } catch (\Throwable $th) {
            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message','No se ha podido mostrar el concepto')
            ->with('status',false);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConceptosModRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $id = Crypt::decrypt($id);
            $concepto = $this->modulosbd->find($id);
            $user = auth()->user()->user_id;

            $concepto->nombreConcepto = $data['inputConcepto'];
            $concepto->modulo = $data['selectModulo'];
            $concepto->movimiento = $data['selectMovimiento'];
            $concepto->estatus = (int)$data['selectEstatus'];
            $concepto->claveProdServ = $data['inputClave'];
            $concepto->fechaCambio = Carbon::now()->toDateTime();
            $concepto->user_id = $user;

            if ((int) $data['selectEstatus'] == '1') {
                $concepto->fechaBaja = null;
            }

            try {
                $isUpdate = $concepto->update();
                if ($isUpdate) {
                    $message = 'El concepto '.$data['inputConcepto'].' se ha actualizado correctamente';
                    $status = true;
                }
                else {
                    $message = 'No se ha podido actualizar el concepto '.$data['inputConcepto'];
                    $status = false;
                }
            } catch (\Throwable $th) {
                $message = 'Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar el concepto: ' . $data['inputConcepto'];
                return redirect()
                ->route('config.conceptos-modulos.index')
                ->with('message',$message)
                ->with('status',false);
            }

            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message',$message)
            ->with('status',$status);
        } catch (\Throwable $th) {
            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message', 'No se ha podido encontrar el concepto')
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
            $user = auth()->user()->user_id;
            $concepto = $this->modulosbd->find($id);
            $concepto->estatus = 0;
            $concepto->fechaBaja = Carbon::now()->toDateTime();
            $concepto->user_id = $user;

            $isRemoved = $concepto->update();

            if ($isRemoved) {
                $message = 'se ha dado de baja el concepto correctamente';
                $status = true;
            }
            else {
                $message = 'No se ha podido dar de baja el concepto';
                $status =false;
            }
            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message',$message)
            ->with('status',$status);
        } catch (\Throwable $th) {
            return redirect()
            ->route('config.conceptos-modulos.index')
            ->with('message','No se ha podido dar de baja el concepto')
            ->with('status',false);
        }
    }

    public function conceptoAction(Request $request)
    {
        $concepto = $request->inputName;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':
                $concepto_filtro = CONF_CONCEPTOS_MODULOS ::whereConceptoName($concepto)
                    ->whereConceptoEstatus($status)
                    ->get();
                return redirect()
                    ->route('config.conceptos-modulos.index')
                    ->with('concepto_filtro', $concepto_filtro)
                    ->with('nombre', $concepto)
                    ->with('status', $status);

            case 'Exportar excel':
                $concepto = new ConceptosModuloExport($concepto,$status);
                return Excel::download($concepto,'Conceptos_de_Moduos.xlsx');
        }
    }
}
