<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Seguridad\Models\Persona;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
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

            $query = User::with('roles', 'persona.tipo_documento_identidad', 'persona.unidad_organica')->orderBy('id', 'asc');

            // Aplicar la búsqueda si se proporciona un término
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('email', 'like', '%' . $search . '%')
                        ->orWhereHas('persona', function ($query) use ($search) {
                            $query->where('nombre', 'like', '%' . $search . '%')
                            ->orWhere('apellidos', 'like', '%' . $search . '%')
                            ->orWhere('numero_documento', 'like', '%' . $search . '%');
                        });
                });
            }

            // Si se proporciona perPage, aplicar paginación, de lo contrario traer todo
            if ($perPage) {
                $usuarios = $query->paginate($perPage, '*', 'page', $page);
                return response()->json([
                    'data' => $usuarios->items(),
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $usuarios->total(),
                    'recordsFiltered' => $usuarios->total(),
                ]);
            } else {
                $usuarios = $query->get();
                return response()->json([
                    'data' => $usuarios,
                    'draw' => intval($request->get('draw')),
                    'recordsTotal' => $usuarios->count(),
                    'recordsFiltered' => $usuarios->count(),
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

            $persona = Persona::create([
                'unidad_organica_id' => $request->unidad_organica_id,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'tipo_documento_identidad_id' => $request->tipo_documento_identidad_id,
                'numero_documento' => $request->numero_documento,
                'edad' => $request->edad,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            $user = User::create([
                'persona_id' => $persona->id,
                'email' => $request->email,
                'host' => $request->host,
                'mac' => $request->mac,
                'ip' => $request->ip,
                'password' => bcrypt($request->password)
            ]);

            $role = Role::findById($request->rol_id)->name;

            $user->assignRole([$role]);

            return response()->json(['data'=>$user, 'mensaje' => 'Usuario '.$persona->nombres.' '.$persona->apellidos.' creado con éxito']);
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


            $user = User::find($id);
            $user->email = $request->email;
            $user->host = $request->host;
            $user->mac = $request->mac;
            $user->ip = $request->ip;


            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            $persona = Persona::find($user->persona_id);
            $persona->unidad_organica_id = $request->unidad_organica_id;
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->tipo_documento_identidad_id = $request->tipo_documento_identidad_id;
            $persona->numero_documento = $request->numero_documento;
            $persona->edad = $request->edad;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;

            $persona->save();

            $role = Role::findById($request->rol_id)->name;

            $user->syncRoles([$role]);

            return response()->json(['data'=>$user, 'mensaje' => 'Usuario '.$persona->nombres.' '.$persona->apellidos.' actualizado con éxito']);
        } catch (\Error $e) {
            //throw $th;
            return redirect()->back()
                ->with('error', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['data' => 'registro '.$user->name.' eliminado']);
        } catch (\Error $e) {
            //throw $th;
            return response()->json(['error', $e]);
        }
    }

    public function incializaTabla(){
        $headers = [
            ['title' => 'Apellidos', 'key'=> 'apellidos'],
            ['title' => 'Nombres', 'key'=> 'nombres'],
            ['title' => 'Tipo Documento Identidad', 'key'=> 'tipo_documento_identidad'],
            ['title' => 'N° Documento', 'key'=> 'documento'],
            ['title' => 'Unidad Orgánica', 'key'=> 'unidad_organica'],
            ['title' => 'Email', 'key'=> 'email'],
            ['title' => 'Rol', 'key'=> 'roles'],
            ['title' => 'Estado', 'key'=> 'status', 'sortable' => false],
            /* ['title' => 'Descripcion', 'key'=> 'description', 'sortable' => false], */
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
                'label' => 'Agregar Usuario',
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
            'title' => 'Usuarios',
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
