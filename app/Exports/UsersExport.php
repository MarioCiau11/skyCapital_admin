<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromView, ShouldAutoSize, WithStyles
{
    public $name;
    public $user;
    public $rol;
    public $status;

    public function __construct($name, $user, $rol, $status)
    {
        $this->name = $name;
        $this->user = $user;
        $this->rol = $rol;
        $this->status = $status;
    }

    public function view(): View
    {
        // dd($this->name, $this->user, $this->rol, $this->status);
        
        $user_collection_filtro =  User::whereUserName($this->name)->whereUserNames($this->user)->whereUserRoles($this->rol)->whereUserStatus($this->status)->get();
        
        // dd($user_collection_filtro);
       
        return view('exports.usuarios', [
            'usuarios' => $user_collection_filtro
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
