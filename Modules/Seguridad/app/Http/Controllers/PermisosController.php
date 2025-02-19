<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener los parámetros de paginación de la solicitud
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page');
        $search = $request->get('search');

        $query = Permission::with('roles','modulo')->orderBy('id', 'asc');

        // Aplicar la búsqueda si se proporciona un término
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('roles', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Si se proporciona perPage, aplicar paginación, de lo contrario traer todo
        if ($perPage) {
            $permissions = $query->paginate($perPage, '*', 'page', $page);
            return response()->json([
                'data' => $permissions->items(),
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $permissions->total(),
                'recordsFiltered' => $permissions->total(),
            ]);
        } else {
            $permissions = $query->get();
            return response()->json([
                'data' => $permissions,
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $permissions->count(),
                'recordsFiltered' => $permissions->count(),
            ]);
        }

        /* $permissions = $query->paginate($perPage, '*', 'page', $page);

        return response()->json([
            'data' => $permissions->items(),
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $permissions->total(),
            'recordsFiltered' => $permissions->total(), // Si tienes filtrado
        ]); */
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
            $permission = Permission::create($request->all());
            return response()->json(['data' => $permission, 'mensaje' => 'Permiso '.$permission->name.' creado con éxito']);
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
            $permission = Permission::find($id);
            $permission->modulo_id = $request->modulo_id;
            $permission->slug = $request->slug;
            $permission->name = $request->name;
            $permission->description = $request->description;
            $permission->save();

            return response()->json(['data' => $permission, 'mensaje' => 'Permiso '.$permission->name.' actualizado con éxito']);
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
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json(['data' => 'registro '.$permission->name.' eliminado']);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    public function incializaTabla(){
        $headers = [
            ['title' => 'Modulo', 'key'=> 'modulo'],
            ['title' => 'Nombres', 'key'=> 'name'],
            ['title' => 'Roles', 'key'=> 'assignedTo', 'sortable' => false],
            ['title' => 'Descripcion', 'key'=> 'description', 'sortable' => false],
            ['title' => 'fecha', 'key'=> 'created_at', 'sortable' => false],
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
                'label' => 'Agregar Permiso',
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
            'title' => 'Permisos',
            'buttons' => $buttons,
            'filters' => [],
            'check' => false,
            'colors' => $colors,
            'search' => true,
            'item_selects' => $itemSelects
        ];
        return response()->json(['data'=>$data]);
    }
}
