<?php

namespace App\Http\Controllers\config;

use App\Exports\RoleExport;
use App\Http\Controllers\Controller;
use App\Models\config\CONF_ROLES;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use stdClass;

class RolesController extends Controller
{

    private $role;
    public $pagesize = 25;
    public $mensaje;
    public $status;

    public function __construct(Role $rol)
    {
        // // $this->middleware(['permission:Roles y Usuarios']);
        $this->role = $rol;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (!auth()->user()->can('Roles y Usuarios')) {
            return redirect('/'); // Redirige al inicio
        }
        
        $role = new CONF_ROLES();
        $roles = $role->getRoles();
        //  dd($roles);
        return view(
            'page.config.roles.index',
            [
                'roles' => $roles,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriaObject = new stdClass();

        $permission_collection = Permission::all();
        $permission_array = $permission_collection->toArray();

        
        foreach ($permission_array as $key => $value) {
            if ($value['categoria'] == 'Reportes Módulos') {
                if (property_exists($categoriaObject, $value['tipoReporte'])) {
                    $categoriaObject->{$value['tipoReporte']}[] = $value;
                } else {
                    $categoriaObject->{$value['tipoReporte']} = [$value];
                }
            } else {
                if (property_exists($categoriaObject, $value['categoria'])) {
                    $categoriaObject->{$value['categoria']}[] = $value;
                } else {
                    $categoriaObject->{$value['categoria']} = [$value];
                }
            }
        }
        $categoriasPermisos = (array) $categoriaObject;
        $categorias = array_keys($categoriasPermisos);
        $rolePermissions = [];

        return view('page.config.roles.create', [
            'rol' => new Role(),
            'categorias' => $categorias,
            'categoriasPermisos' => $categoriasPermisos,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            //validar los datos
            $request->validate([
                'name' => 'required|max:100',
                'descripcion' => 'required|max:250',
                'selectEstatus' => 'required',
                'identificador' => 'required|max:10',
            ]);
        // dd($request->all());
        $conf_rol_request = $request->except('_token'); //rechazamos el token que nos pasa el formulario
        // dd($conf_rol_request);
        $isKeyRol = Role::where('name', '=', $conf_rol_request['name'])->first();

        if ($isKeyRol) {
            $this->mensaje = "El rol: " . $conf_rol_request['name'] . " ya existe en la base de datos";
            $this->status = false;
        } else {
            try {
                $role = Role::create(['name' => $conf_rol_request['name'], 'guard_name' => 'web', 'descript' => $conf_rol_request['descripcion'], 'status' => (int) $conf_rol_request['selectEstatus'], 'identifier' => $conf_rol_request['identificador']]);
                isset($conf_rol_request['permisos']) ? $role->givePermissionTo($conf_rol_request['permisos']) : null;
                $this->mensaje = "El rol: " . $conf_rol_request['name'] . " se creó correctamente";
                $this->status = true;
            } catch (\Throwable $th) {
                $this->mensaje = "Por favor, vaya con el administrador de sistemas, no se pudo crear el rol: " . $conf_rol_request['name'];
                return redirect()->route('config.roles.index')->with('message', $this->mensaje)->with('status', false);
            }
        }
        return redirect()->route('config.roles.index')->with('message', $this->mensaje)->with('status', $this->status);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $role = Role::find($id);
            $categoriaObject = new stdClass();

            $permission_collection = Permission::all();
            $permission_array = $permission_collection->toArray();

       
            foreach ($permission_array as $key => $value) {
                if ($value['categoria'] == 'Reportes Módulos') {
                    if (property_exists($categoriaObject, $value['tipoReporte'])) {
                        $categoriaObject->{$value['tipoReporte']}[] = $value;
                    } else {
                        $categoriaObject->{$value['tipoReporte']} = [$value];
                    }
                } else {
                    if (property_exists($categoriaObject, $value['categoria'])) {
                        $categoriaObject->{$value['categoria']}[] = $value;
                    } else {
                        $categoriaObject->{$value['categoria']} = [$value];
                    }
                }
            }

            $categoriasPermisos = (array) $categoriaObject;
            $categorias = array_keys($categoriasPermisos);
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();

            // dd($rolePermissions, $categoriasPermisos);
            return view('page.config.roles.show', [
                'rol' => $role,
                'categorias' => $categorias,
                'categoriasPermisos' => $categoriasPermisos,
                'rolePermissions' => $rolePermissions,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('config.roles.index')->with('message', 'No se pudo encontrar la configuración rol')->with('status', false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $role = Role::find($id);
            $categoriaObject = new stdClass();

            $permission_collection = Permission::all();
            $permission_array = $permission_collection->toArray();

          
        foreach ($permission_array as $key => $value) {
            if ($value['categoria'] == 'Reportes Módulos') {
                if (property_exists($categoriaObject, $value['tipoReporte'])) {
                    $categoriaObject->{$value['tipoReporte']}[] = $value;
                } else {
                    $categoriaObject->{$value['tipoReporte']} = [$value];
                }
            } else {
                if (property_exists($categoriaObject, $value['categoria'])) {
                    $categoriaObject->{$value['categoria']}[] = $value;
                } else {
                    $categoriaObject->{$value['categoria']} = [$value];
                }
            }
        }

            $categoriasPermisos = (array) $categoriaObject;
            $categorias = array_keys($categoriasPermisos);
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
            // dd($user);
            return view('page.config.roles.edit', [
                'rol' => $role,
                'categorias' => $categorias,
                'categoriasPermisos' => $categoriasPermisos,
                'rolePermissions' => $rolePermissions,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('config.usuarios.index')->with('message', 'No se ha podido encontrar el usuario')->with('status', false);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $request->validate([
                'name' => 'required|max:100',
                'descripcion' => 'required|max:250',
                'selectEstatus' => 'required',
            ]);

            $id = Crypt::decrypt($id);
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->descript = $request->input('descripcion');
            $role->status = (int) $request->input('selectEstatus');

            try {
                $role->update();
                $role->permissions()->sync($request->input('permisos'));

                return redirect()->route('config.roles.index')->with('message', 'El rol se actualizó correctamente')->with('status', true);
            } catch (\Exception $e) {
                return redirect()->route('config.roles.index')->with('message', 'Error al actualizar el rol'.$e->getMessage())->with('status', false);
            }
        } catch (\Exception $e) {
            return redirect()->route('config.roles.index')->with('message', 'No se pudo encontrar la configuración rol')->with('status', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = crypt::decrypt($id);
            $role_delete = Role::where('id', $id)->first();
            $role_delete->status = 0;

            $isRemoved = $role_delete->save();
            if ($isRemoved) {
                $this->mensaje = "El rol: " . $role_delete->name . " se eliminó correctamente";
                $this->status = true;
            } else {
                $this->mensaje = "No se ha podido eliminar el rol";
                $this->status = false;
            }
            return redirect()->route('config.roles.index')->with('message', $this->mensaje)->with('status', $this->status);
        } catch (\Throwable $th) {
            return redirect()->route('config.roles.index')->with('message', 'No se ha podido encontrar el usuario')->with('status', false);
        }
    }

    public function roleAction(Request $request)
    {
        // dd($request->all());
        $nombre = $request->inputName;
        $status = $request->selectEstatus == 'Todos' ? $request->selectEstatus : (int) $request->selectEstatus;

        // dd($nombre, $user, $rol, $status);
        switch ($request->input('action')) {
            case 'Búsqueda':

                $role_filtro = CONF_ROLES::whereRolName($nombre)->whereRolStatus($status)->get();

                return redirect()->route('config.roles.index')->with('rol_filtro', $role_filtro)->with('nombre', $nombre)->with('status2', $status);


            case 'Exportar excel':
                $role = new RoleExport($nombre, $status);
                // dd($role);
                return Excel::download($role, 'roles.xlsx');
        }
    }
}
