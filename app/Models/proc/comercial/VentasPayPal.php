<?php

namespace App\Models\proc\comercial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasPayPal extends Model
{
    use HasFactory;

    protected $table = 'PROC_VENTAS_PAYPAL';
    protected $primaryKey = 'idPayPal';
    protected $guarded = ['idPayPal'];
  
    public $timestamps = false;
}
