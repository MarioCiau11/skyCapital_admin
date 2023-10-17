<?php

namespace App\Models\proc\finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxCDetalle extends Model
{
    use HasFactory;

    protected $table = 'PROC_CXCD';

    protected $primaryKey = 'idRenglon';

    protected $guarded = ['idRenglon'];

    public $timestamps = false;

    public function getMovimiento() {
        return $this->hasOne('App\Models\proc\finanzas\CxC', 'idCXC', 'referencia');
    }
}
