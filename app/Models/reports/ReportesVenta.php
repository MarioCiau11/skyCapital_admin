<?php

namespace App\Models\reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportesVenta extends Model
{
    use HasFactory;
    public function reportesParametros()
    {
    
        // return $this->where('idEmpresa', '=', session('company')->idEmpresa)
        //             ->where('idSucursal', '=', session('sucursal')->idSucursal)
        //             ->where('estatus', '=', 'PENDIENTE')
        //             ->where('user_id', '=', Auth::user()->user_id)
        //             ->get();
    }
    public function getVenta()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'idVenta');
    }
    public function getMoneda()
    {
        return $this->hasOne('App\Models\config\CONF_MONEDA', 'idMoneda', 'moneda');
    }
    public function getProyecto()
    {
        return $this->hasOne('App\Models\catalogos\CAT_PROYECTOS', 'idProyecto', 'proyecto');
    }
    public function getEmpresa()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }
    public function getDetalle()
    {
        return $this->hasMany('App\Models\proc\comercial\VentaDetalle', 'idVenta', 'idVenta');
    }

    public function getOrigen()
    {
        return $this->hasOne('App\Models\proc\comercial\Ventas', 'idVenta', 'origenId');
    }

    public function getArticulos()
    {
        return $this->hasMany('App\Models\catalogos\CAT_ARTICULOS', 'idArticulos', 'articulo');
    }

}
