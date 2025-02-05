<?php

namespace Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Usamos el facade de Auth para autenticar
use App\Models\User;

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

    public function login(Request $request){
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Generar token para el usuario
        //$token = $user->createToken('authToken')->plainTextToken;

        // Obtener roles y permisos
        $roles = $user->getRoleNames(); // Retorna una colección de roles
        $permissions = $user->getAllPermissions()->pluck('name'); // Retorna una colección de permisos

        // Respuesta con token, roles y permisos
        return response()->json([
            'user' => $user,
            //'token' => $token,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    // Método para hacer logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
