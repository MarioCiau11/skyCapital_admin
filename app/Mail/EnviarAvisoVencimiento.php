<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\catalogos\CAT_EMPRESAS;

class EnviarAvisoVencimiento extends Mailable
{
    use Queueable, SerializesModels;
    protected $html1, $cliente, $fecha, $movimiento, $empresa;
    /**
     * Create a new message instance.
     */
    public function __construct($html1, $cliente, $fecha, $movimiento, $empresa)
    {
        $this->html1 = $html1;
        $this->cliente = $cliente;
        $this->fecha = $fecha;
        $this->movimiento = $movimiento;
        $this->empresa = $empresa;

    }

    public function build()
    {
        $empresa = CAT_EMPRESAS::where('idEmpresa', $this->empresa)->first();
        $html1 = $this->html1;
        
        $nameEmpresaImg = isset($empresa) ? $empresa->logo : null;
        // dd($empresa, $nameEmpresaImg);
        if ($nameEmpresaImg != null && $nameEmpresaImg != '') {
            $idEmp = isset($empresa) ? $empresa->idEmpresa : null;
            $nameImageE = $nameEmpresaImg;
            if (file_exists(storage_path('app/public/empresas/'.$idEmp.'/'.$nameImageE))) {
                $pathImageE = storage_path('app/public/empresas/'.$idEmp.'/'.$nameImageE);
                $typeImageE = pathinfo($pathImageE, PATHINFO_EXTENSION);
                $dataImageE = file_get_contents($pathImageE);
                $base64ImageE = 'data:image/' . $typeImageE . ';base64,' . base64_encode($dataImageE);
            } else{ $base64ImageE = null; 
                    $pathImageE = null;}
        } else{ $base64ImageE = null; 
                $pathImageE = null;}
        // dd($html);
        return $this->view('page.proc.ventas.mail.avisoPorVencer', compact('pathImageE', 'base64ImageE', 'html1'))
        ->subject('Aviso de vencimiento de '.$this->movimiento)
        ->with([
            'cliente' => $this->cliente,
            'fecha' => $this->fecha,
        ]);
    }
}
