<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            //return view('seguridad::index');
            // Obtener los parámetros de paginación de la solicitud
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page');
            $search = $request->get('search');

            $query = Role::with('permissions')->orderBy('id', 'asc');

            // Aplicar la búsqueda si se proporciona un término
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                        /* ->orWhereHas('roles', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        }); */
                });
            }

            if ($perPage) {
                $roles = $query->paginate($perPage, '*', 'page', $page);

                return response()->json([
                    'data' => $roles->items(),
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $roles->total(),
                    'recordsFiltered' => $roles->total(), // Si tienes filtrado
                ]);
            } else {
                $roles = $query->get();

                return response()->json([
                    'data' => $roles,
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $roles->count(),
                    'recordsFiltered' => $roles->count(), // Si tienes filtrado
                ]);
            }
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seguridad::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            //code...

            $modulos = $request->permissions; // Lista de módulos recibida

            $selectedPermissionIds = collect($modulos)
                ->flatMap(function ($modulo) {
                    return isset($modulo['permisos'])
                        ? collect($modulo['permisos'])->where('selected', true)->pluck('id')
                        : [];
                })
                ->unique()
                ->values()
                ->all();

            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($selectedPermissionIds);

            return response()->json(['data' => $role]);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('seguridad::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('seguridad::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            //code...
            $modulos = $request->permissions; // Lista de módulos recibida

            $selectedPermissionIds = collect($modulos)
                ->flatMap(function ($modulo) {
                    return isset($modulo['permisos'])
                        ? collect($modulo['permisos'])->where('selected', true)->pluck('id')
                        : [];
                })
                ->unique()
                ->values()
                ->all();

            $role = Role::findById($id);
            $role->name = $request->name;
            $role->save();
            //$role = Role::create(['name' => $request->name]);
            $role->syncPermissions($selectedPermissionIds);
            //$role->syncPermissions($selectedPermissionIds); // Actualizar permisos del rol

            return response()->json(['data' => $role]);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $rol = Role::findOrFail($id);
            $rol->delete();

            return response()->json(['data' => 'registro '.$rol->name.' eliminado']);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    public function incializaTabla(){
        $headers = [
            ['title' => 'Rol', 'key'=> 'name'],
            ['title' => 'Guard', 'key'=> 'guard_name', 'sortable' => false],
            ['title' => 'Fecha', 'key'=> 'created_at', 'sortable' => false],
            ['title' => 'Acciones', 'key'=> 'actions', 'sortable' => false],
        ];

        $colors = [
            'Editor' => ['color' => 'info','text' => 'Editor'],
            'users' => ['color' => 'success','text' => 'Users'],
            'manager' => ['color' => 'warning','text' => 'Manager'],
            'Admin' => ['color' => 'primary','text' => 'Admin'],
            'restricted-user' => ['color' => 'error','text' => 'Restricted User'],
        ];

        $buttons = [
            [
                'label' => 'Agregar Rol',
                'color' => 'info',
                'icon' => 'tabler-plus',
                'density' => 'default',
                'action' => 'create'
            ]
        ];

        $itemSelects = [
            ['title' => '5', 'value'=> 5],
            ['title' => '10', 'value'=> 10],
            ['title' => '25', 'value'=> 25],
            ['title' => '50', 'value'=> 50],
            ['title' => '100', 'value'=> 100],
        ];

        $data = [
            'headers' => $headers,
            'par_page' => 10,
            'page' => 1,
            'title' => 'Roles',
            'buttons' => $buttons,
            'filters' => [],
            'check' => true,
            'colors' => $colors,
            'search' => true,
            'item_selects' => $itemSelects
        ];
        return response()->json(['data'=>$data]);
    }

    public function permisosGroup(){
        $permissions = Permission::orderBy('id', 'asc')->get();

        $agrupados = [];

        foreach ($permissions as $permiso) {
            // Extraemos el tipo (usuario, rol, permiso) y la acción (listar, crear, etc.)
            list($categoria, $accion) = explode('-', $permiso['name']);

            // Crear un array por cada categoría (usuarios, roles, permisos)
            // Agrupar los permisos por categoría
            $agrupados[$categoria][] = [
                'id' => $permiso['id'],
                'accion' => $accion
            ];

        }

        return response()->json(['data' => $agrupados]);
    }

}
