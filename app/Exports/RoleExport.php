<?php

namespace App\Exports;

use App\Models\config\CONF_ROLES;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RoleExport implements FromView, ShouldAutoSize, WithStyles
{

    public $name;
    public $status;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($name, $status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    public function view(): View
    {
        // dd($this->name, $this->user, $this->rol, $this->status);
        
        $role_filtro =  CONF_ROLES::whereRolName($this->name)->whereRolStatus($this->status)->get();
        
        // dd($role_filtro);
       
        return view('exports.roles', [
            'roles' => $role_filtro
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

}
