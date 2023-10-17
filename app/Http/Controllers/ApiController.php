<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\catalogosSat\CAT_SAT_PROD_SERV;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $json;
    public function getMovimientos(Request $request)
    {
        
        $data = $request->all();
        // return json_encode($data['Modulo']);
        $data['Modulo'] == "VTAS" ? $this->json=[
            'datos'=>[
                0 => array('value'=>'Cotizador','name' => 'Cotizador'),
                1 => array('value'=>'Contrato','name' => 'Contrato'),
                2 => array('value'=>'Mensualidad','name' => 'Mensualidad'),
                3 => array('value'=>'Factura','name' => 'Factura')
            ],
            'estatus' => 200
            ]:null;
        $data['Modulo'] == "CXC" ? $this->json=[
            'datos'=>[
                0 => array('value'=>'Anticipo','name' => 'Anticipo'),
                1 => array('value'=>'Aplicación','name' => 'Aplicación'),
                2 => array('value'=>'Devolución','name' => 'Devolución'),
                3 => array('value'=>'Cobro','name' => 'Cobro')
            ],
            'estatus' => 200
            ]:null;

        $data['Modulo'] == "TES" ? $this->json=[
            'datos'=>[
                0 => array('value'=>'Transferencia','name' => 'Transferencia'),
                1 => array('value'=>'Ingreso','name' => 'Ingreso'),
                2 => array('value'=>'Egreso','name' => 'Egreso'),
            ],
            'estatus' => 200
            ]:null;
        return response()->json($this->json);
    }

    public function cat_getClaveProd(Request $request)
    {
        $options = CAT_SAT_PROD_SERV::select('c_ClaveProdServ as clave','descripcion as value')
        ->where('descripcion','LIKE', $request->get('search') .'%')
        ->orWhere('c_ClaveProdServ','LIKE', $request->get('search') .'%')->pluck('value','clave')->map(function ($value, $clave) {
            return $clave . ' - ' . $value;
        });
        return response()->json($options);
    }
}
