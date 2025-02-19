<?php

namespace Modules\Maestros\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Maestros\Models\TipoDocumentoIdentidad;

class TipoDocumentoIdentidadController extends Controller
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

            $query = TipoDocumentoIdentidad::/* with('submodulos', 'moduloPadre', 'permisos')-> */orderBy('id', 'asc');

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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
