<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Mail\EnviarAvisoVencimiento;
use App\Mail\EnviarCorreo;
use App\Mail\MailTests;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\finanzas\CxC;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class HelpController extends Controller
{
    
    public function index()
    {
        $paths = [
            'Laptop' => public_path('images\mail\Laptop.jpg'),
            'LogotipoSkyBlanco' => public_path('images\mail\LogotipoSkyBlanco.png'),
            'LogotipoSkyNegro' => public_path('images\mail\LogotipoSkyNegro.png'),
            'OpcionesPortal' => public_path('images\mail\OpcionesPortal.png')
        ];
    

        Mail::to('leonelrosado2407@gmail.com')->send(new EnviarCorreo('Leonel','123456',session('company')->nombreCorto,env('URL_PORTAL')));

        return view('mail.AccesosClientes',[
            'usuario' => 'Leonel',
            'contraseña' => '123456',
            'images' => $paths
        ]);
    }

    public function avisoPorVencer() {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $ventas = new Ventas();
        $mensualidades = $ventas->getMensualidades();
        foreach ($mensualidades as $key => $mensualidad) {

            $param = $parametro->byCompany($mensualidad->idEmpresa)->first();
            // dd($param);
            $dias_permitidos = isset($param->aviso1) ? $param->aviso1 : 1;
            $plantilla = isset($param->plantillaAviso1) ? $param->plantillaAviso1 : null;
            // dd($param);

            $fecha_actual = Carbon::now()->format('Y-m-d');
            // dd($fecha_actual);
            $fecha_aviso = Carbon::parse($mensualidad->fechaEmision)->subDays($dias_permitidos)->format('Y-m-d');
            // dd($fecha_aviso);
            if($fecha_actual >= $fecha_aviso) {
                $cliente = $mensualidad->getCliente()->first();
                // DD($cliente);
                $modulo = $mensualidad->getModulo()->first();
                $proyecto = $mensualidad->getProyecto()->first();
                $empresa = CAT_EMPRESAS::find($mensualidad->idEmpresa);
                if ($plantilla != null && $plantilla != '') {
                    $html = $this->armarHtml($param->plantillaAviso1, $param->getCompany, $cliente, $modulo, $proyecto);
                    $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento , $empresa->idEmpresa);
                    Mail::to($cliente->correoElectronico1)->send($aviso);
                } else {
                    $plantillaDefault = '<h3 style="text-align:center;"><strong>RECORDATORIO DE PRÓXIMO VENCIMIENTO</strong></h3><h3>&nbsp;</h3><p style="text-align:right;">&nbsp;</p><p style="text-align:right;"><strong>MÉRIDA, YUCATÁN A ##fecha##</strong></p><h3>&nbsp;</h3>
                    <h3>ESTIMADO PROPIETARIO</h3><h4><strong>##cliente##</strong></h4><h4>&nbsp;</h4><h4>PRESENTE</h4>
                    <p style="text-align:justify;">Enviándole un cordial saludo, le informamos que su estado de cuenta con ##empresa## referente a la Compra-Venta del inmueble “<strong>##modulo##”</strong> tiene un vencimiento próximo a vencer.</p>
                    <p style="text-align:justify;">Le invitamos de la forma más atenta a realizar los pagos correspondientes de su inmueble (s) el primer día del mes.&nbsp;&nbsp;</p>
                    <p>Se extiende la presente para los fines que convengan.<br><br><br>&nbsp;</p>
                    <p style="text-align:center;">Administración General</p><p style="text-align:center;">##empresa##</p>';

                    $html = $this->armarHtml($plantillaDefault, $empresa, $cliente, $modulo, $proyecto);
                    $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento , $empresa->idEmpresa);
                    Mail::to($cliente->correoElectronico1)->send($aviso);
                }
                
            }
        }
    }

    public function avisoVencido() {
        $parametro = new CONF_PARAMETROS_GENERALES();
        $ventas = new Ventas();
        $mensualidades = $ventas->getMensualidades();


        foreach ($mensualidades as $key => $mensualidad) {
            $param = $parametro->byCompany($mensualidad->idEmpresa)->first();
            $dias_permitidos = isset($param->aviso2) ? $param->aviso2 : 0;
    
            // dd($dias_permitidos);
            $plantilla = isset($param->plantillaAviso2) ? $param->plantillaAviso2 : null;
            $fecha_mensualidad = Carbon::parse($mensualidad->fechaEmision)->format('Y-m-d');
            $fecha_actual = Carbon::now()->format('Y-m-d');
            $fecha_aviso = Carbon::parse($mensualidad->fechaEmision)->addDays($dias_permitidos)->format('Y-m-d');

         
            if($fecha_actual >= $fecha_aviso) {

                // $monthlyPaymentData[] = [
                //     'movement_id' => $mensualidad->idVenta,
                //     'due_date' => $fecha_mensualidad,
                //     'notification_date' => $fecha_aviso,
                // ];
                
                $cliente = $mensualidad->getCliente()->first();
                $modulo = $mensualidad->getModulo()->first();
                $proyecto = $mensualidad->getProyecto()->first();
                $empresa = CAT_EMPRESAS::find($mensualidad->idEmpresa);
                if ($plantilla != null && $plantilla != '') {
                    $html = $this->armarHtml($param->plantillaAviso2, $param->getCompany, $cliente, $modulo, $proyecto);
                    $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento, $empresa->idEmpresa);
                    Mail::to($cliente->correoElectronico1)->send($aviso);
                } else {
                    $plantillaDefault = '<h3 style="text-align:center;"><strong>SEGUNDA NOTIFICACIÓN</strong></h3><p style="text-align:right;">&nbsp;</p><p style="text-align:right;">&nbsp;</p><p style="text-align:right;"><strong>MÉRIDA, YUCATÁN A ##fecha##</strong></p>
                    <h3>&nbsp;</h3><h3>ESTIMADO PROPIETARIO</h3><h4><strong>##cliente##</strong></h4><p>&nbsp;</p><h4>PRESENTE</h4>
                    <p style="text-align:justify;">Enviándole un cordial saludo, le informamos que el proyecto ##proyecto##, en Mérida, Yucatán, avanza en tiempo y forma; la acelerada plusvalía en el mismo se mantiene al alza, capitalizando una vez más, una inversión rentable para nuestros clientes.&nbsp;</p>
                    <p style="text-align:justify;">Su estado de cuenta presenta un adeudo con ##empresa## con referencia a la Compra-Venta del inmueble <strong>##modulo##</strong>.</p>
                    <p style="text-align:justify;">Le invitamos de la forma más atenta a realizar los pagos correspondientes de su inmueble (s) previo al día 31 del presente.&nbsp;</p>
                    <p>Se extiende la presente para los fines que convengan.</p><p>&nbsp;</p><p>&nbsp;</p>
                    <p style="text-align:center;">Administración General</p><p style="text-align:center;">##empresa##</p>';

                    $html = $this->armarHtml($plantillaDefault, $empresa, $cliente, $modulo, $proyecto);
                    $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento, $empresa->idEmpresa);
                    Mail::to($cliente->correoElectronico1)->send($aviso);
                }
            }
        }

        // dd($monthlyPaymentData);
    }

    public function armarHtml($html1, $empresa, $cliente, $modulo, $proyecto) {
        
        $html = $html1;
        // dd($html);
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $fechaC = Carbon::now();
        $fechaFormateada = strftime("%d DE %B DE %Y", strtotime($fechaC));

        $htmlEmpresa = str_replace('##empresa##', $empresa->nombreEmpresa, $html);
        $htmlProyecto = str_replace('##proyecto##', strtoupper($proyecto->nombre), $htmlEmpresa);
        $htmlCliente = str_replace('##cliente##', strtoupper($cliente->razonSocial), $htmlProyecto);
        $htmlModulo = str_replace('##modulo##', $modulo->clave, $htmlCliente);
        $html1 = str_replace('##fecha##', strtoupper($fechaFormateada), $htmlModulo);

        return $html1;
    }

    public function diasMoratorios() {
      
        $facturas = CxC::where('movimiento', 'Factura')->where('estatus', 'PENDIENTE')->get();

        foreach ($facturas as $key => $factura) {
           $dias_moratorios = (int) $factura->diasMoratorios;
            $factura->diasMoratorios = $dias_moratorios + 1;
            $factura->save();
        }

        DD($facturas);
    }
   
}

?>
