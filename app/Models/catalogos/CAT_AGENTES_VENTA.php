<?php

namespace App\Models\catalogos;

use App\Models\agrup\AGRUP_CATEGORIA;
use App\Models\agrup\AGRUP_GRUPO;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAT_AGENTES_VENTA extends Model
{
    use HasFactory;

    protected $table = 'cat_agentes';
    protected $primaryKey = 'idAgentes';

    public $timestamps = false;

    public $columns = [
        0 => array('name' => ['Clave', 'default' => true]),
        1 => array('name' => ['Nombre', 'default' => true]),
        2 => array('name' => ['Estatus', 'default' => true]),
        3 => array('name' => ['Tipo', 'default' => true]),
        4 => array('name' => ['Fecha Creación', 'default' => true]),
        5 => array('name' => ['Última actualización', 'default' => true]),
        6 => array('name' => ['Fecha de Baja', 'default' => true]),
    ];

    public function getAgentes() {
        $agente = $this->where('estatus',1)->pluck('nombre','idAgentes')->toArray();
        // dd($agente);
        return $agente;
    }

    public function getNextID()
    {
        $table = (new self())->getTable();
        $nextId = DB::select("SHOW TABLE STATUS LIKE '{$table}'")[0]->Auto_increment;
        return $nextId;
    }
    public function scopewhereAgenteClave($query, $clave)
    {
        if (!is_null($clave)) {
            return $query->where('clave', $clave);
        }

        return $query;

    }

    public function scopewhereAgenteName($query, $nombre)
    {
        if (!is_null($nombre)) {
            return $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        return $query;

    }
    public function scopewhereAgenteEstatus($query, $status)
    {
        if (!is_null($status)) {
            if ($status == 'Todos') {
                return $query;
            }
            return $query->where('estatus', $status);
        }

        return $query;

    }
    public function relationCat()
    {
        return $this->belongsTo(AGRUP_CATEGORIA::class, 'categoria', 'idCategoria');
    }

    public function relationGrupo()
    {
        return $this->belongsTo(AGRUP_GRUPO::class, 'grupo', 'idGrupo');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}