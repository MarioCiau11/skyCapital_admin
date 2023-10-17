<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\catalogos\CAT_AGENTES_VENTA;
use App\Models\catalogos\CAT_ARTICULOS;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_CUENTAS_DINERO;
use App\Models\catalogos\CAT_MODULOS;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_MONEDA;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\config\CONF_FORMAS_PAGO;
use App\Models\proc\comercial\VentaPlan;
use App\Models\proc\finanzas\CxC;
use App\Models\utils\CAT_FILES_ARCHIVOS;
use App\Models\utils\CAT_FILES_IMAGENES;
use App\Models\utils\PROC_FILES_ARCHIVOS;
use App\Models\UTILS\PROC_SALDOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProcesosController extends Controller
{
    public $mensaje, $estatus, $data;
    public $estatusMov = [
        0 => 'SIN AFECTAR',
        1 => 'PENDIENTE',
        2 => 'POR CONFIRMAR',
        3 => 'CONCLUIDO',
        4 => 'CANCELADO',
    ];

    public function respuesta($mensaje = null, $estatus = null, $data = null)
    {
        $this->mensaje = $mensaje;
        $this->estatus = $estatus;
        $this->data = $data;

        return response()->json([
            'mensaje' => $this->mensaje,
            'estatus' => $this->estatus,
            'data' => $this->data,
        ], 200);
    }
    
    public function returnFolioProy($modulo = null ,$movimiento = null,$claveProyecto = null) {
        switch ($modulo) {
            case 'VTAS':
                if ($movimiento == 1) {
                    return null;
                }
                else{
                    $proyecto = CAT_PROYECTOS::where('clave',$claveProyecto)->first();
        
                    if (!is_null($proyecto)) {
                        $proyecto['clave'] == 'TheSky' ? $folio = $proyecto['añoFinProyecto'] : $folio = date('Y');
        
                        return $folio;
                    }
                    
                    return "la clave recibida está vacia";
                }
            
            case 'CXC':
                return 'se inicio un proceso de retornar folio en el modulo de CXC';
                // break;

            case 'TES':
                return 'se inicio un proceso de retornar folio en el modulo de Tesoreria';
                // break;

            default:
                return'no se paso nigún argumento válido'; 
                // break;
        
        }            
    }
    public function obtenerModulos(Request $request){
        // dd($request->all());
        $idProyecto = $request->proyecto;
        $modulo = new CAT_MODULOS();
        if ($idProyecto != null) {
            $queryBuilder = $modulo->where('proyecto',$idProyecto)
            ->whereIn('estatus', ['Disponible', 'Apartado'])
            // ->where('estatus','Apartado')
            ->get();
        }else{
            $queryBuilder = ["status" => "error", "message" => "No se recibieron los datos necesarios para realizar la consulta"];
        }
        return response()->json($queryBuilder);
    }

    public function getMovCxC(Request $request) {
        //  dd($request->all());
        $cxc = new CxC();
        $data = $request->all();
        // dd($data);
        if (isset($data['cliente']) && isset($data['moneda']) && isset($data['movimiento'])){
            $movimientos = $cxc->getMovCxC($data['cliente'], (int)$data['moneda'], $data['movimiento'], isset($data['modulo']) ? $data['modulo'] : null);

            if (count($movimientos) > 0) {
                $this->mensaje = 'Se encontraron movimientos';
                $this->estatus = true;
            }
            else{
                $this->mensaje = 'No se encontraron movimientos';
                $this->estatus = false;
            }
        }

        $this->data = $movimientos;

        return $this->respuesta($this->mensaje, $this->estatus, $this->data);

    }

    public function tipoCambio(Request $request)
    {
        $id = $request->idMoneda;
        $moneda = new CONF_MONEDA();
        $moneda = $moneda->getMoneda($id);

        if($moneda != null){
            $this->mensaje = 'Se encontró la moneda';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró la moneda';
            $this->estatus = false;
        }
        $this->data = $moneda;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);

    }

    public function getModulos(Request $request)
    {
        $id = $request->idProyecto;
        $status = $request->estatus;
        $aplica = $request->aplica;
        // dd($aplica);
        $modulo = new CAT_MODULOS();
        $modulo = $modulo->getModuloByProject($id, $status);
        // dd($modulo);

        if($aplica === 'true'){
            $modulo = new CAT_MODULOS();
            $modulo = $modulo->getModuloByProject2($id);
        }

        // dd($modulo);
        if(count($modulo) > 0){
            $this->mensaje = 'Se encontró el modulo';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el modulo';
            $this->estatus = false;
        }
        $this->data = $modulo;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    public function getModulo(Request $request)
    {
        $id = $request->idModulo;
        $modulo = new CAT_MODULOS();
        $modulo = $modulo->getModulo($id);
        // dd($modulo);

        if($modulo != null){
            $this->mensaje = 'Se encontró el modulo';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el modulo';
            $this->estatus = false;
        }
        $this->data = $modulo;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    public function getCondicion(Request $request)
    {
        $id = $request->idCondicionesc;
        $condicion = new CONF_CONDICIONES_CRED();
        $condicion = $condicion->getCondicion($id);
        // dd($modulo);

        if($condicion != null){
            $this->mensaje = 'Se encontró la condicion';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró la condicion';
            $this->estatus = false;
        }
        $this->data = $condicion;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    
    public function getFormaPago(Request $request)
    {
        $id = $request->idFormaPago;
        $formaPago = new CONF_FORMAS_PAGO();
        $formaPago = $formaPago->getFormaPago($id);
        // dd($modulo);

        if($formaPago != null){
            $this->mensaje = 'Se encontró la forma de pago';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró la forma de pago';
            $this->estatus = false;
        }
        $this->data = $formaPago;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    public function getArticulo(Request $request)
    {
        $id = $request->clave;
        $articulo = new CAT_ARTICULOS();
        $articulo = $articulo->getArticulo($id);
        // dd($articulo);

        if($articulo != null){
            $this->mensaje = 'Se encontró el articulo';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el articulo';
            $this->estatus = false;
        }
        $this->data = $articulo;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }
    public function retornarAgentes(Request $request) {
        $data = $request->all();
        // dd((int)$data['agente']);
        $agentes = CAT_AGENTES_VENTA::where('idAgentes','=',$data['agente'])->first();
        return response()->json($agentes);
        // return $agentes;

    }
    public function getCliente(Request $request)
    {
        $id = $request->idCliente;
        $cliente = new CAT_CLIENTES();
        $cliente = $cliente->getCliente($id);
        // dd($cliente);

        if($cliente != null){
            $this->mensaje = 'Se encontró el cliente';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el cliente';
            $this->estatus = false;
        }
        $this->data = $cliente;
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }
    public function getPlanVenta(Request $request) {
        $id = $request->idPlanVenta;
        $planVenta = new VentaPlan();
        $planes = $planVenta->getPlan($id);
        return response()->json($planes);
    }

    public function getCuenta(Request $request)
    {
        $id = $request->idCuenta;
        $cuenta = new CAT_CUENTAS_DINERO();
        $cuenta = $cuenta->getCuenta($id);
        $cuentaMoneda = $cuenta->getCuentaMoneda;
        //  dd($cuenta, $cuentaMoneda);

        if($cuenta != null){
            $this->mensaje = 'Se encontró la cuenta';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró la cuenta';
            $this->estatus = false;
        }
        $this->data = [
            'cuenta' => $cuenta,
            'cuentaMoneda' => $cuentaMoneda
        ];
        
        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    public function getSaldoCliente(Request $request) {
        $idCliente = $request->idCliente;
        $idMoneda = $request->idMoneda;

        //  dd($idCliente, $idMoneda);
        $saldo = new PROC_SALDOS();
        $moneda = new CONF_MONEDA();
        $moneda = $moneda->getMoneda($idMoneda);
        // dd($moneda);
        $saldo = $saldo->getSaldoCliente($idCliente, $moneda->clave);

        if($saldo != null){
            $this->mensaje = 'Se encontró el saldo';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el saldo';
            $this->estatus = false;
        }

        $this->data = $saldo;

        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }
    public function getSaldoCuenta(Request $request){
        $idCuenta = $request->cuenta;       
        $idMoneda = $request->idMoneda;

        //  dd($idCliente, $idMoneda);
        $saldo = new PROC_SALDOS();
        $moneda = new CONF_MONEDA();
        $moneda = $moneda->getMoneda($idMoneda);
        // dd($moneda);
        $saldo = $saldo->getSaldos($idCuenta, session('company')->idEmpresa, session('sucursal')->idSucursal, 'Tesoreria', $moneda->clave);

        if($saldo != null){
            $this->mensaje = 'Se encontró el saldo';
            $this->estatus = true;
        }
        else{
            $this->mensaje = 'No se encontró el saldo';
            $this->estatus = false;
        }

        $this->data = $saldo;

        return $this->respuesta($this->mensaje, $this->estatus, $this->data);
    }

    public function getImagenesModulo(Request $request) {
        $idModulo = $request->idModulo;
        $modulo = CAT_MODULOS::where('clave', $idModulo)->first();
    
        if (!$modulo) {
            return response()->json(['error' => 'Modulo no encontrado'], 404);
        }
    
        $articulosImg = new CAT_FILES_IMAGENES();
        $imagenes = $articulosImg->getTipo('Modulos', $modulo->idModulo);
    
        $images_array = [];
        foreach ($imagenes as $imagen) {
            $rutaFin = str_replace(['//', '///', '////'], '/', $imagen['path']);
    
            if (Storage::disk('public')->exists($rutaFin)) {
                $contents = Storage::disk('public')->get($rutaFin);
    
                // Redimensionar la imagen a un tamaño específico usando Intervention Image
                $image = Image::make($contents);
                $width = 1000; // Definir el ancho deseado
                $height = 800; // Definir el alto deseado
                $image->resize($width, $height);
                
                $contents = $image->encode('jpg'); // Puedes cambiar el formato si lo prefieres
                $contents = base64_encode($contents);
                $mime = 'image/jpeg'; // Cambia esto según el formato que elijas
    
                $src = 'data: '.$mime.';base64,'.$contents;
    
                $images_array[] = [
                    'src' => $src,
                    'path' => $imagen['path'],
                ];
            }
        }
    
        return response()->json($images_array);
    }

    public function anexarDocumentosPortal(Request $request)
    {
        $lastID = $request->lastID;
        
        if ($request->hasFile('files')) {
            $archivos = $request->file('files');
            $rutaAlmacenamiento = 'ventas/' . $lastID . '/';
            
            $nombresArchivos = [];
            
            foreach ($archivos as $archivo) {
                $nombreArchivo = $archivo->getClientOriginalName();
                $archivo->storeAs($rutaAlmacenamiento, $nombreArchivo, 'public');
                $nombresArchivos[] = $nombreArchivo;

                $docs = new PROC_FILES_ARCHIVOS();
                $docs->idProceso = $lastID;
                $docs->proceso = 'Ventas';
                $docs->path = str_replace(['//', '///', '////'], '/', 'ventas/' . $lastID . '-' . $nombreArchivo);
                $docs->file = $nombreArchivo;
                $docs->save();
                
            }
            
            
            // Realiza cualquier otra lógica que necesites aquí
            
            return response()->json(['status' => true, 'message' => 'Archivos subidos correctamente'], 200);
        }
        
        return response()->json(['status' => false, 'message' => 'No se recibieron archivos'], 400);
    }
    

    public function getDocumentosModulo(Request $request) {
        $idModulo = $request->idModulo;
        $modulo = CAT_MODULOS::where('clave', $idModulo)->first();
    
        if (!$modulo) {
            return response()->json(['error' => 'Modulo no encontrado'], 404);
        }
    
        $docsModule = new CAT_FILES_ARCHIVOS();
        $documentos = $docsModule->getTipo('Modulos', $modulo->idModulo);
        // dd($documentos);
        $docs_array = [];
        foreach ($documentos as $document) {
            $pathFileArray = explode('/', $document['path']);
                $patch = explode('-', $document['path'])[0];
                $longitudPath = count($pathFileArray);
                $nameFileArray = explode('-', $pathFileArray[$longitudPath - 1]);
                $nameFile = $nameFileArray[count($nameFileArray) - 1];
                
                //nameFiles de los documentos digitales
                $FileArray = explode('/', $document['file']);
                $longitudFile = count($FileArray);
                $file = $FileArray[$longitudFile - 1];

                $rutaFin = str_replace(['//', '///', '////'], '/', $document['path']);

                $docs_array[] = [
                    'file' => $file,
                    'path' => $rutaFin,
                    'nameFile' => $nameFile,
                    'patch' => $patch,
                    'id' => $document['idFile'],
                ];
        }
    
        return response()->json($docs_array);
    }
    
}
