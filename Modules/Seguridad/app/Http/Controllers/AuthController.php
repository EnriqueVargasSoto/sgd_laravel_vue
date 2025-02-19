<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Usamos el facade de Auth para autenticar
use App\Models\User;
use Modules\Seguridad\Models\Modulo;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('seguridad::index');
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

    public function login_old(Request $request){
        // Validar las credenciales
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Obtener el usuario autenticado
            $user = Auth::user();


            // Obtener los módulos (roles) a los que el usuario tiene acceso con permisos 'listar'
            /* $modulos = $user->getPermissionsViaRoles()->filter(function ($permiso) {
                return $permiso->slug === 'listar'; // Filtrar permisos de tipo 'listar'
            }); */

            // Filtrar permisos que no tengan parent_id
            $modulosSinPadre = $user->roles[0]->permissions->filter(function ($permiso) {
                return ($permiso->modulo->parent_id === null || $permiso->modulo->parent_id === 0) && $permiso->slug === 'listar';
            })->values();

            // Filtrar permisos que tienen parent_id (módulos hijos)
            $modulosConPadre = $user->roles[0]->permissions->filter(function ($permiso) {
                return $permiso->modulo->parent_id !== null && $permiso->modulo->parent_id !== 0 && $permiso->slug === 'listar';
            })->values();

            // Agrupar módulos hijos bajo su módulo padre
            $modulosAgrupados = $modulosSinPadre->map(function ($modulo) use ($modulosConPadre) {
                // Filtrar los hijos para este módulo padre
                $hijos = $modulosConPadre->filter(function ($permiso) use ($modulo) {
                    return $permiso->modulo->parent_id === $modulo->modulo->id;
                });

                // Devolver el módulo padre con sus hijos
                $modulo->hijos = $hijos;

                return $modulo;
            })->values();


            // Cargar roles, permisos y módulos asociados
            $user->load('roles.permissions.modulo');

            // Generar el token de Sanctum
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Responder con el usuario, sus roles, permisos y el token
            return response()->json([
                'user' => $user,
                'menu' => $user->getModules($user->roles[0]->id)->values(),
                'token' => $token,
            ]);
        }

        // Si las credenciales son incorrectas
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function login(Request $request)
    {
        // Validar las credenciales
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email o contraseña no válidos'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        $user->load('persona','roles');//$user->load('roles.permissions.modulo'); // Cargar relaciones necesarias

        // Verificar que el usuario tiene roles asignados
        if ($user->roles->isEmpty()) {
            return response()->json(['message' => 'El usuario no tiene roles asignados'], 403);
        }
        // Generar token con Sanctum
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Responder con datos formateados
        return response()->json([
            'user' => $user,
            'menu' =>  $user->getModules($user->roles[0]->id)->values(),//$modulosAgrupados,
            'token' => $token,
        ]);
    }


    // Método para hacer logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
