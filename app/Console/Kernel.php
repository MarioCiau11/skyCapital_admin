<?php

namespace App\Console;

use App\Mail\EnviarAvisoVencimiento;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\catalogos\CAT_MODULOS;
use App\Models\catalogos\CAT_PROYECTOS;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\proc\comercial\Ventas;
use App\Models\proc\finanzas\CxC;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {
            $mensualidades = Ventas::whereNotIn('movimiento', ['Contrato', 'Factura', 'Inversión Inicial'])->where('estatus', 'PENDIENTE')->get();
            if ($mensualidades->count() > 0) {

                foreach ($mensualidades as $key => $mensualidad) {
                    $parametro = CONF_PARAMETROS_GENERALES::where('idEmpresa', $mensualidad->idEmpresa)->first();
                    $dias_permitidos = isset($parametro->aviso1) ? $parametro->aviso1 : 1;
                    $plantilla = isset($parametro->plantillaAviso1) ? $parametro->plantillaAviso1 : null;
                    // dd($parametro);

                    $fecha_actual = Carbon::now()->format('Y-m-d');
                    $fecha_mensualidad = Carbon::parse($mensualidad->fechaEmision)->format('Y-m-d');
                    // dd($fecha_actual);
                    $fecha_aviso = Carbon::parse($mensualidad->fechaEmision)->subDays($dias_permitidos)->format('Y-m-d');
                    // dd($fecha_aviso);
                    if ($fecha_actual >= $fecha_aviso && $fecha_actual <= $fecha_mensualidad) {

                        $cliente = CAT_CLIENTES::find($mensualidad->propietarioPrincipal);
                        $modulo = CAT_MODULOS::where('clave', $mensualidad->modulo)->first();
                        $proyecto = CAT_PROYECTOS::find($mensualidad->proyecto);
                        $empresa = CAT_EMPRESAS::find($mensualidad->idEmpresa);
                        if ($plantilla != null && $plantilla != '') {
                            $html = $this->armarHtml($parametro->plantillaAviso1, $parametro->getCompany, $cliente, $modulo, $proyecto);
                            $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento, $empresa->idEmpresa);
                            Mail::to($cliente->correoElectronico1)->send($aviso);
                        } else {
                            $plantillaDefault = '<h3 style="text-align:center;"><strong>RECORDATORIO DE PRÓXIMO VENCIMIENTO</strong></h3><h3>&nbsp;</h3><p style="text-align:right;">&nbsp;</p><p style="text-align:right;"><strong>MÉRIDA, YUCATÁN A ##fecha##</strong></p><h3>&nbsp;</h3>
                        <h3>ESTIMADO PROPIETARIO</h3><h4><strong>##cliente##</strong></h4><h4>&nbsp;</h4><h4>PRESENTE</h4>
                        <p style="text-align:justify;">Enviándole un cordial saludo, le informamos que su estado de cuenta con ##empresa## referente a la Compra-Venta del inmueble “<strong>##modulo##”</strong> tiene un vencimiento próximo a vencer.</p>
                        <p style="text-align:justify;">Le invitamos de la forma más atenta a realizar los pagos correspondientes de su inmueble (s) el primer día del mes.&nbsp;&nbsp;</p>
                        <p>Se extiende la presente para los fines que convengan.<br><br><br>&nbsp;</p>
                        <p style="text-align:center;">Administración General</p><p style="text-align:center;">##empresa##</p>';

                            $html = $this->armarHtml($plantillaDefault, $empresa, $cliente, $modulo, $proyecto);
                            $aviso = new EnviarAvisoVencimiento($html, $cliente->razonSocial, $fecha_aviso, $mensualidad->movimiento, $empresa->idEmpresa);
                            Mail::to($cliente->correoElectronico1)->send($aviso);
                        }

                    }
                }
            }
        })->daily()->timezone('America/Mexico_City')->onSuccess(function () {
            \Log::info('Se envio correctamente los avisos por vencer');
        })->onFailure(function () {
            \Log::info('No se envio correctamente los avisos por vencer');
        })
            ->name('EnviarAvisoPorVencer');

        $schedule->call(function () {
            $mensualidades = Ventas::whereNotIn('movimiento', ['Contrato', 'Factura', 'Inversión Inicial'])->where('estatus', 'PENDIENTE')->get();
            if ($mensualidades->count() > 0) {

                foreach ($mensualidades as $key => $mensualidad) {
                    $parametro = CONF_PARAMETROS_GENERALES::where('idEmpresa', $mensualidad->idEmpresa)->first();
                    $dias_permitidos = isset($parametro->aviso2) ? $parametro->aviso2 : 0;
                    $morosidad = isset($parametro->morosidad) ? $parametro->morosidad : 2.5;

                        $fecha_actual = Carbon::now()->format('Y-m-d');
                        $plantilla = isset($parametro->plantillaAviso2) ? $parametro->plantillaAviso2 : null;


                        $fecha_aviso = Carbon::parse($mensualidad->fechaEmision)->addDays($dias_permitidos)->format('Y-m-d');
                        if ($fecha_actual >= $fecha_aviso) {

                            
                            $mensualidad->porcentajeMorosidad = $morosidad;
                            $mensualidad->save();
                                
                            $cliente = CAT_CLIENTES::find($mensualidad->propietarioPrincipal);
                            $modulo = CAT_MODULOS::where('clave', $mensualidad->modulo)->first();
                            $proyecto = CAT_PROYECTOS::find($mensualidad->proyecto);
                            $empresa = CAT_EMPRESAS::find($mensualidad->idEmpresa);
                            if ($plantilla != null && $plantilla != '') {
                                $html = $this->armarHtml($parametro->plantillaAviso2, $parametro->getCompany, $cliente, $modulo, $proyecto);
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
            }
        })->daily()->timezone('America/Mexico_City')->onSuccess(function () {
            \Log::info('Se envio correctamente los avisos de vencimiento');
        })->onFailure(function () {
            \Log::info('No se envio correctamente los avisos de vencimiento');
        })
            ->name('EnviarAvisoVencimiento');


        $schedule->call(function () {
            $facturas = CxC::where('movimiento', 'Factura')->where('estatus', 'PENDIENTE')->get();

            if ($facturas->count() > 0) {
                foreach ($facturas as $key => $factura) {
                    $dias_moratorios = (int) $factura->diasMoratorios;
                    $factura->diasMoratorios = $dias_moratorios + 1;
                    $factura->save();
                }
            }

        })->daily()->timezone('America/Mexico_City')->onSuccess(function () {
            \Log::info('Se actualizo correctamente los dias moratorios');
        })->onFailure(function () {
            \Log::info('No se actualizo correctamente los dias moratorios');
        })
            ->name('ActualizarDiasMoratorios');


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    public function armarHtml($html1, $empresa, $cliente, $modulo, $proyecto)
    {

        $html = $html1;
        // dd($html);
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        $fechaC = Carbon::now();
        $fechaFormateada = strftime("%d DE %B DE %Y", strtotime($fechaC));

        $htmlEmpresa = str_replace('##empresa##', $empresa->nombreEmpresa, $html);
        $htmlProyecto = str_replace('##proyecto##', strtoupper($proyecto->nombre), $htmlEmpresa);
        $htmlCliente = str_replace('##cliente##', strtoupper($cliente->razonSocial), $htmlProyecto);
        $htmlModulo = str_replace('##modulo##', $modulo->clave, $htmlCliente);
        $html1 = str_replace('##fecha##', strtoupper($fechaFormateada), $htmlModulo);

        return $html1;
    }
}