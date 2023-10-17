<?php

namespace App\Models\catalogos;

use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\config\CONF_CONDICIONES_CRED;
use App\Models\User;
use App\Models\UTILS\PROC_SALDOS;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_CLIENTES extends Model
{
    use HasFactory;
    protected $table = 'cat_clientes';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;
    public $columns = [
        0 => array('name' => ['', 'default' => true])
    ];
    public function getNextID()
    {
        $table = (new self())->getTable();
        $nextId = DB::select("SHOW TABLE STATUS LIKE '{$table}'")[0]->Auto_increment;
        return $nextId;
    }
    public function getCategoria()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class,'categoria','idCategoria');
    }
    public function getGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class,'grupo','idGrupo');
    }

    public function getUser()
    {
        return $this->BelongsTo(User::class,'user_id','user_id');
    }
    public function getCondiciones()
    {
        return $this->belongsTo(CONF_CONDICIONES_CRED::class,'condicionPago','idCondicionesc');
    }

    public function scopewhereClientesClave($query,$clave)
    {
        if (!is_null($clave)) {
            return $query->where('clave',$clave);
        }
        return $query;
    }
    public function scopewhereClientesNombre($query,$nombre)
    {
        if (!is_null($nombre)) {
            return $query->where(DB::raw("CONCAT(nombres,' ',apellidoPaterno,' ',apellidoMaterno)"),'like','%'.$nombre.'%')
                         ->orWhere(DB::raw("CONCAT(apellidoPaterno,' ',apellidoMaterno,' ',nombres)"),'like','%'.$nombre.'%');         
        } 
        return $query;
    }
    public function scopewhereClientesRazon($query,$razon)
    {
        if (!is_null($razon)) {
            return $query->where('razonSocial','like','%'.$razon.'%');
        }
        return $query;
    }
    public function scopewhereClientesCategoria($query,$categoria)
    {
        if (!is_null($categoria)) {
            if ($categoria == 'Todos') {
                return $query;
            }
            return $query->where('categoria', $categoria);
        }
        return $query;
    }
    public function scopewhereClientesGrupo($query, $grupo)
    {
        if (!is_null($grupo)) {
            if ($grupo == 'Todos') {
                return $query;
            }
            return $query->where('grupo', $grupo);
        }

        return $query;
    }
    public function scopewhereClientesEstatus($query, $estatus)
    {
        if (!is_null($estatus)) {
            if ($estatus == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $estatus);
        }
        return $query;
    }

    public function getClientes()
    {
        return $this->where('estatus', 1)->get()->pluck('razonSocial', 'idCliente');
    }

    public function getClientesRazon()
    {
        return $this->where('estatus', 1)->get()->pluck('razonSocial', 'razonSocial');
    }

    public function getMonedasClave()
    {
        $array = [];
        $monedas = $this->where('estatus', 1)->select('idMoneda', 'clave', 'nombre')->get()->toArray();
        foreach ($monedas as $key => $value) {
            $array[$value['clave']] = $value['clave'];
        }
        return $array;
    }

    public function getCliente($id)
    {
        return $this->where('idCliente', $id)->first();
    }

    public function getSaldo($id, $idEmpresa, $idSucursal)
    {
        $saldo = PROC_SALDOS::where('cuenta', $id)->where('idEmpresa', $idEmpresa)->where('idSucursal', $idSucursal)->first();
        
        return $saldo;
    }


}