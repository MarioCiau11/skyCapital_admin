<?php

namespace App\Http\Controllers\config;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\catalogos\CAT_EMPRESAS;
use App\Models\config\CONF_ROLES;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class UsuariosController extends Controller
{
    private $user;
    public $pagesize = 25;
    public $mensaje;
    public $status;


    //constructor de la clase
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = new User();
        $users = $user->getUsers();
        $rol = new CONF_ROLES();
        $roles = $rol->selectRoles();
        // dd($roles);

        return view('page.config.usuarios.index', [
            'users' => $users,
            'columns' => $this->user->columns,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = new CAT_EMPRESAS();
        $rol = new CONF_ROLES();
        $roles = $rol->selectRolesStatus();
        return view(
            'page.config.usuarios.create',
            [
                'user' => new User(),
                'companies' => $companies->getCompanies(),
                'roles' => $roles,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $user = $this->user;
        $rol_id = Conf_roles::where('identifier', '=', $data['selectRol'] )->first();


        $user->user_name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->user_status = (int) $data['selectEstatus'];
        $user->user_rol = $data['selectRol'];

        if (!empty($data['empresas'])) {
            $user->empresas_data = array('old' => [], 'new' => $data['empresas']);
          }

        // dd($data, $user);
        try {
            $isCreate = $user->save();
            $userLast = $user::latest('user_id')->first();
            $userLast->roles()->sync($rol_id->id);

            if (!empty($data['empresas'])) {
                $userLast->company()->sync($data['empresas']);
              }
     
            if ($isCreate) {
                $this->mensaje = 'Usuario creado correctamente';
                $this->status = true;
            } else {
                $this->mensaje = 'Error al crear el usuario';
                $this->status = false;
            }
        } catch (\Exception $e) {
            $this->mensaje = 'Error al crear el usuario, ' . $e->getMessage();
            $this->status = false;

            return redirect()->route('config.usuarios.create')->with('status', $this->status)->with('message', $this->mensaje);
        }
        return redirect()->route('config.usuarios.index')->with('status', $this->status)->with('message', $this->mensaje);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $user = $this->user->find($id);

            return view('page.config.usuarios.show', [
                'user' => $user,
            ]);
        } catch (\exception $e) {
            return redirect()->route('config.usuarios.index')->with('message', 'No se ha podido mostrar el usuario')->with('status', false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $user = $this->user->find($id);
            $companies = new CAT_EMPRESAS();
            $rol = new CONF_ROLES();
            $roles = $rol->selectRolesStatus();
            // dd($user);
            return view('page.config.usuarios.edit', [
                'user' => $user,
                'companies' => $companies->getCompanies(),
                'roles' => $roles,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('config.usuarios.index')->with('message', 'No se ha podido encontrar el usuario')->with('status', false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            // dd($data);
            $id = Crypt::decrypt($id);
            $user = $this->user->find($id);
            $rol_id = Conf_roles::where('identifier', '=', $data['selectRol'] )->first();

            $user->user_name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->user_status = (int) $data['selectEstatus'];
            $user->user_rol = $data['selectRol'];

            if (!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }

            if (!empty($data['empresas'])) {
                $company_attr = $user->getCompanyIdsAttribute();
                sort($company_attr);
                sort($data['empresas']);
                $user->empresas_data['old'] = $company_attr;
                $user->empresas_data['new'] = array_map(function($value) {
                  return intval($value);
                }, $data['empresas']);
                $user->company()->sync($data['empresas']);
              }

            try {
                $isUpdate = $user->update();
                $last_idUser = $user->user_id;
                $user->roles()->sync($rol_id->id);

                if ($isUpdate) {
                    $message = "El usuario: " . $data['name'] . " se ha actualizado correctamente";
                    $status = true;
                } else {
                    $message = "No se ha podido actualizar al usuario: " . $data['name'];
                    $status = false;
                }
            } catch (\Throwable $th) {
            
                $message = "Por favor, contáctese con el administrador de sistemas ya que no se ha podido actualizar al usuario: " . $data['name'];
                return redirect()->route('config.usuarios.index')->with('message', $message)->with('status', false);
            }

            return redirect()->route('config.usuarios.index')->with('message', $message)->with('status', $status);
        } catch (\Throwable $th) {
            return redirect()->route('config.usuarios.index')->with('message', 'No se ha podido encontrar el usuario')->with('status', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = crypt::decrypt($id);
            $user_delete = User::where('user_id', $id)->first();
            $user_delete->user_status = 0;

            $isRemoved = $user_delete->update();
            $status = false;
            if ($isRemoved) {
                $message = "El usuario se ha dado de baja correctamente";
                $status = true;
            } else {
                $message = "No se ha podido dar de baja al usuario";
                $status = false;
            }
            if ($id == auth()->user()->user_id) {
                auth()->logout();
            }
            return redirect()->route('config.usuarios.index')->with('message', $message)->with('status', $status);
        } catch (\Throwable $th) {

            return redirect()->route('config.usuarios.index')->with('message', 'No se ha podido encontrar el usuario')->with('status', false);
        }
    }

    public function userAction(Request $request)
    {
        $nombre = $request->inputName;
        $user = $request->inputUser;
        $rol =  $request->selectRol;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        switch ($request->input('action')) {
            case 'Búsqueda':

                $user_filtro = User::whereUserName($nombre)
                                   ->whereUserNames($user)
                                   ->whereUserRoles($rol)
                                   ->whereUserStatus($status)->get();

            return redirect()->route('config.usuarios.index')->with('user_filtro', $user_filtro)->with('nombre', $nombre)->with('user', $user)->with('rol', $rol)->with('estatus', $status);


            case 'Exportar excel':
                $usuario = new UsersExport($nombre, $user, $rol, $status);
            return Excel::download($usuario, 'usuarios.xlsx');

        }
    }
}