<?php

namespace Modules\Maestros\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Maestros\Models\TipoDocumento;

class TipoDocumentoController extends Controller
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

            $query = TipoDocumento::/* with('submodulos', 'moduloPadre', 'permisos')-> */orderBy('id', 'asc');

            // Aplicar la búsqueda si se proporciona un término
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', '%' . $search . '%');
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
        return view('maestros::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $tipo = TipoDocumento::create($request->all());
            return response()->json(['data' => $tipo, 'mensaje' => 'Tipo de Documento '.$tipo->nombre.' creado con éxito']);
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
        return view('maestros::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('maestros::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $tipo = TipoDocumento::find($id);
            $tipo->nombre = $request->nombre;
            $tipo->slug = $request->slug;
            $tipo->save();

            return response()->json(['data' => $tipo, 'mensaje' => 'Tipo de Documento '.$tipo->nombre.' actualizado con éxito']);
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
            $tipo = TipoDocumento::findOrFail($id);
            $tipo->delete();

            return response()->json(['data' => 'registro '.$tipo->nombre.' eliminado']);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    public function incializaTabla(){
        $headers = [
            ['title' => 'Tipo', 'key'=> 'nombre'],
            ['title' => 'Slug', 'key'=> 'slug', 'sortable' => false],
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
                'label' => 'Agregar Tipo de Documento',
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
            'title' => 'Tipos de Documento',
            'buttons' => $buttons,
            'filters' => [],
            'check' => true,
            'colors' => $colors,
            'search' => true,
            'item_selects' => $itemSelects
        ];
        return response()->json(['data'=>$data]);
    }
}
