<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //generar sesiones de empresa y sucursal respecto al usuario
        if(isset($_COOKIE['idEmpresa']) && isset($_COOKIE['idSucursal'])){
            // dd(Auth::user()->idUsuario);
            $company = User::find(Auth::user()->user_id)->company()->where('CAT_EMPRESAS.idEmpresa', $_COOKIE['idEmpresa'])->first();
            $sucursal = $company->sucursales()->where('idSucursal', $_COOKIE['idSucursal'])->first();

            session(['company' => $company]);
            session(['sucursal' => $sucursal]);
        }

        // $company = $user->company()->where('CAT_EMPRESAS.idEmpresa', $request->selectEmpresas)->first();
        return view('page.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
