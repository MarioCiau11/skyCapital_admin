<?php

namespace App\Models\proc\comercial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use Carbon\Carbon;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'PROC_VENTASD';

    protected $primaryKey = 'idVentaD';

    protected $guarded = ['idVentaD'];

    public $timestamps = false;

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
    public function getCliente()
    {
        return $this->hasOne('App\Models\catalogos\CAT_CLIENTES', 'idCliente', 'propietarioPrincipal');
    }
    public function getArticulo()
    {
        return $this->hasOne('App\Models\catalogos\CAT_ARTICULOS', 'idArticulos', 'articulo');
    }
    public function getSucursal()
    {
        return $this->hasOne('App\Models\catalogos\CAT_SUCURSALES', 'idSucursal', 'idSucursal');
    }
    public function getCategoria()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class,'categoria','idCategoria');
    }
    public function getGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class,'grupo','idGrupo');
    }
    
}
