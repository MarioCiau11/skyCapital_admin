<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{

    public function verificarUsuario(Request $request)
    {
        $user = new User();
        $user = $user->userVerificate($request->username);

        if ($user) {
            return response()->json(['status' => 200, 'data' => 'Usuario encontrado', 'user' => $user]);
        }

        return response()->json(['status' => 404, 'data' => 'Usuario no encontrado']);
    }

    public function verificarPassword(Request $request)
    {
        $user = new User();
        $key = '1234567891234567';
        $desencriptar = $request->password;

        $pass = $user->passVerificate($request->username, $desencriptar);
        // dd($pass);
        if($pass['passBoolean']){
            $companies = $user->getCompaniesUser($pass['user']->user_id);
            foreach ($companies as $key => $value) {
                $empresa = CAT_EMPRESAS::find($value->idEmpresa);
                if($empresa->estatus == 1){
                    $companies[$key]->empresas;
                }else{
                    unset($companies[$key]);
                }
            }
            return response()->json(['status' => 200, 'data' => 'Contraseña correcta', 'companies' => $companies]);
        }
        return response()->json(['status' => 404, 'data' => 'Contraseña incorrecta']);
    }

    public function getSucursales(Request $request)
    {
        $company = CAT_EMPRESAS::find($request->idEmpresa);
        $sucursales = $company->sucursales;

        return response()->json(['status' => 200, 'data' => 'Sucursales encontradas', 'sucursales' => $sucursales]);
    }
}
