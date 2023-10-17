<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\utils\PROC_FLUJO;
use Illuminate\Http\Request;

class FlujoController extends Controller
{
    public function getMovimientos($idMovimiento) { 
        $query = PROC_FLUJO::where('origenId', '=', $idMovimiento)->get();
        return $query;
    }

    public function getMovimientosPosteriores(Request $request) { 
       try {
        $data = $request->all();
        // dd($data);
        if (isset($data['PRINCIPAL']) && $data['PRINCIPAL'] == true) {
            $queryBuilder = PROC_FLUJO::where('origenId', '=', $data['idMovimiento'])
            ->where('origenModulo', '=', $data['modulo'])
            ->get();
            if ($queryBuilder->count() == 0) {
                $queryBuilder = PROC_FLUJO::where('destinoId', '=', $data['idMovimiento'])
                ->where('destinoModulo', '=', $data['modulo'])
                ->get();
            }
        }else {
            if ($data['search'] == 'NEXT' && isset($data['idEmpresa'])  && isset($data['idSucursal']) && isset($data['idMovimiento']) && isset($data['modulo']) ) {
                $queryBuilder = PROC_FLUJO::whereEmpresa((int)$data["idEmpresa"])
                ->whereSucursal((int)$data["idSucursal"])
                ->whereOrigenModulo($data["modulo"])
                ->whereOrigenId((int)$data["idMovimiento"])
                ->get();
            }
            elseif ($data['search'] == 'PREV' && isset($data['idEmpresa'])  && isset($data['idSucursal']) && isset($data['idMovimiento']) && isset($data['modulo'])) {
                $queryBuilder = PROC_FLUJO::whereEmpresa((int)$data["idEmpresa"])
                ->whereSucursal((int)$data["idSucursal"])
                ->whereDestinoModulo($data["modulo"])
                ->whereDestinoId((int)$data["idMovimiento"])
                ->get();
            }else {
                $queryBuilder = [];
            }
        }
        
        
        // dd($queryBuilder);
        // $query = FLUJO::where('origenId', '=', $data["idMovimiento"])->get();
        return $queryBuilder;
       } catch (\Throwable $th) {
        throw $th;
        // dd($th);
        
       }
    }
}

