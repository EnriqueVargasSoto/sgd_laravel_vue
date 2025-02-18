<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Modules\Seguridad\Models\Modulo;
use Illuminate\Support\Facades\Auth;


class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Obtener los parámetros de paginación de la solicitud
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page');
            $search = $request->get('search');

            $query = Modulo::with('submodulos', 'moduloPadre', 'permisos')->orderBy('id', 'asc');

            // Aplicar la búsqueda si se proporciona un término
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                        /* ->orWhereHas('roles', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        }); */
                });
            }

            // Si se proporciona perPage, aplicar paginación, de lo contrario traer todo
            if ($perPage) {
                $modulos = $query->paginate($perPage, '*', 'page', $page);
                return response()->json([
                    'data' => $modulos->items(),
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $modulos->total(),
                    'recordsFiltered' => $modulos->total(),
                ]);
            } else {
                $modulos = $query->get();
                return response()->json([
                    'data' => $modulos,
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $modulos->count(),
                    'recordsFiltered' => $modulos->count(),
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
            $modulo = Modulo::create($request->all());
            return response()->json(['data' => $modulo]);
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
            $modulo = Modulo::find($id);
            $modulo->parent_id = $request->parent_id;
            $modulo->name = $request->name;
            $modulo->slug = $request->slug;
            $modulo->url = $request->url;
            $modulo->description = $request->description;
            $modulo->save();

            return response()->json(['data' => $modulo]);
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
            $modulo = Modulo::findOrFail($id);
            $modulo->delete();

            return response()->json(['data' => 'registro '.$modulo->name.' eliminado']);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    public function incializaTabla(){

        /* $user = Auth::user(); // Obtiene el usuario autenticado

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        $role = $user->roles->first(); // Tomamos el primer rol del usuario
// Obtener permisos con módulo
        $permissions = $role->permissions;//->where('slug', 'crear', );
        $permissions = $role->permissions->filter(function ($permiso) {
            return $permiso->modulo->slug === 'modulos';
        })->values();
        return response()->json($permissions); */

        $headers = [
            ['title' => 'Nombre', 'key'=> 'nombre'],
            ['title' => 'Slug', 'key'=> 'slug', 'sortable' => false],
            ['title' => 'Descripcion', 'key'=> 'descripcion', 'sortable' => false],
            ['title' => 'Ruta', 'key'=> 'ruta', 'sortable' => false],
            ['title' => 'Modulo Padre', 'key'=> 'padre_id', 'sortable' => false],
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
                'label' => 'Agregar Modulo',
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
            'title' => 'Modulos',
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
