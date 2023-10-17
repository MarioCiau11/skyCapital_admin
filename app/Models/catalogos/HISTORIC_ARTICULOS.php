<?php

namespace App\Models\catalogos;

use App\Models\catalogos\CAT_ARTICULOS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HISTORIC_ARTICULOS extends Model
{
    use HasFactory;
    protected $table = 'hist_articulos';
    protected $primaryKey = 'idHistoricArticulo';
    public $timestamps = false;

    public function getArticulo()
    {
        return $this->BelongsTo(CAT_ARTICULOS::class,'articulos','idArticulos');
    }
}
