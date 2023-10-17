<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\catalogos\CAT_CLIENTES;
use App\Models\proc\finanzas\CxC;
use App\Models\proc\finanzas\Tesoreria;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\config\CONF_MONEDA;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcSaldosController extends Controller
{
    private $cxc;
    private $cliente;
    private $tesoreria;
    private $param;
    private $moneda;

    public function getSaldosCliente($idCliente,$modulo) {
        // dd($idCliente,$modulo);
        $this->cxc = new CxC();
        $this->cliente = new CAT_CLIENTES();
        $this->tesoreria = new Tesoreria();
        $this->param = new CONF_PARAMETROS_GENERALES();
        $this->moneda = new CONF_MONEDA();
 
        // dd($this->param->first());
        if ($idCliente != null && $modulo != null) {
            switch ($modulo) {
                case 'CXC':
                    $movimientos = $this->cxc->where('cliente', $idCliente)
                                             ->where('estatus', 'PENDIENTE')
                                             ->whereIn('movimiento', ['Factura', 'Anticipo'])
                                             ->get();
                    $conversion = $this->param->where('user_id', Auth::user()->user_id)->first();
                    if ($conversion != null)
                        if ($conversion->monedaDefault != null)
                            $moneda = $this->moneda->where('idMoneda', $conversion->monedaDefault)->first();
                        else
                            $moneda = $this->moneda->where('idMoneda', 1)->first();
                    else
                        $moneda = $this->moneda->where('idMoneda', 1)->first();
                        
                    // dd($conversion, $moneda);
                    $saldo1 = 0;
                    $saldoTotal = 0;
                    foreach ($movimientos as $key => $value) {
                        $mov= $movimientos[$key];

                        if ($movimientos[$key]->movimiento == 'Anticipo') {
                            $saldo1 -= $movimientos[$key]->saldo * $movimientos[$key]->tipoCambio;
                        } else {
                            $saldo1 += $movimientos[$key]->saldo * $movimientos[$key]->tipoCambio;
                        }
                        $saldoTotal = $saldo1 / $moneda->tipoCambio;
                        
                    }
                    //Retonar el saldo total a la vista.

                    // dd($mov, $movimientos, $saldoTotal);
                    return [$movimientos, $saldoTotal];
                    // break;
                
                case 'TESORERIA':
                    $movimientos = $this->tesoreria->where('cliente', $idCliente)->get();
                    return $movimientos;
                    // break;

                default:
                    # code...
                    break;
            }
        }
        else {
            return [];
        }
    }
}
