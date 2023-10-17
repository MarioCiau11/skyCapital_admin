<?php

namespace App\Models\proc\finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesoreriaDetalle extends Model
{
    use HasFactory;

    protected $table = 'PROC_TESORERIAD';

    protected $primaryKey = 'idRenglon';

    protected $guarded = ['idRenglon'];

    public $timestamps = false;

    public function getMovimiento() {
        return $this->hasOne('App\Models\proc\finanzas\Tesoreria', 'idTesoreria', 'referencia');
    }
}
