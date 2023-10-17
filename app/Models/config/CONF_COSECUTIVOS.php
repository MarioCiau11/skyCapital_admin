<?php

namespace App\Models\config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CONF_COSECUTIVOS extends Model
{
    use HasFactory;

    protected $table = 'CONF_CONSECUTIVOS';

    protected $primaryKey = 'idConsecutivo';

    public function getCompany()
    {
        return $this->hasOne('App\Models\catalogos\CAT_EMPRESAS', 'idEmpresa', 'idEmpresa');
    }


    public function byCompany($id)
    {
        return $this->where('idEmpresa', $id);
    }
}
