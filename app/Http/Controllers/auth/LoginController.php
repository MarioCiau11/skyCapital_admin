<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $remember = true;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'username';
    }

    public function authenticated(Request $request, $user)
    {
        $company = $user->company()->where('CAT_EMPRESAS.idEmpresa', $request->selectEmpresas)->first();
    
        if ($company) {
            $sucursal = $company->sucursales()->where('idSucursal', $request->selectSucursales)->first();
            session(['company' => $company]);
            session(['sucursal' => $sucursal]);
                    //guardar en una cookie el id de la empresa y sucursal
            setcookie('idEmpresa', $company->idEmpresa, time() + (86400 * 30), "/");
            setcookie('idSucursal', $sucursal->idSucursal, time() + (86400 * 30), "/");
        } else {
            // Si no se encuentra la empresa, manejar el caso adecuadamente (redireccionar, mostrar mensaje, etc.)
        }
    }
    

}
