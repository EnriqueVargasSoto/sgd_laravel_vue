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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

}
