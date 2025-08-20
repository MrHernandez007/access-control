<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdministradoresController extends Controller
{
    public function index()
    {
        $administradores = Administrador::all(); // usa el modelo correcto
        //dd($administradores);
        return view('administradores.index', compact('administradores'));
    }

    public function create()
    {
        return view('administradores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'usuario' =>  'required|string|max:50', //'required|string|max:50|unique:administradores,usuario',
            'correo' => 'required|email',//'required|email|unique:administradores,correo',
            'telefono' => 'nullable|string|max:50',
            'contrasena' => 'required|string|min:6',
            'imagen' => 'nullable|image|max:2048',
            'tipo' => 'required|in:admin,superadmin',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // Validaciones manuales de unicidad
        if (Administrador::where('usuario', $request->usuario)->exists()) {
            return back()->withErrors(['usuario' => 'El usuario ya está registrado.'])->withInput();
        }

        if (Administrador::where('correo', $request->correo)->exists()) {
            return back()->withErrors(['correo' => 'El correo ya está registrado.'])->withInput();
        }

        $administrador = new Administrador($validated);
        $administrador->contrasena = Hash::make($validated['contrasena']); 

        if ($request->hasFile('imagen')) {
            $administrador->imagen = $request->file('imagen')->store('administradores', 'public');
        }

        $administrador->save();

        return redirect()->route('administradores.index')->with('success', 'Administrador creado correctamente.');
    }

    public function show(string $id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            abort(404, 'Administrador no encontrado');
        }

        return view('administradores.show', compact('administrador'));
    }

    public function edit(string $id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            abort(404);
        }

        return view('administradores.edit', ['admin' => $administrador]);  //equivalente al compact
    }

    public function update(Request $request, string $id)
{
    $administrador = Administrador::find($id);

    if (!$administrador) {
        abort(404);
    }

    $validated = $request->validate([
        'nombre' => 'required|string|max:50',
        'apellido' => 'required|string|max:50',
        'usuario' => 'required|string|max:50',
        'correo' => 'required|email',
        'telefono' => 'nullable|string|max:50',
        'contrasena' => 'nullable|string|min:6',
        'imagen' => 'nullable|image|max:2048',
        'tipo' => 'required|in:admin,superadmin',
        'estado' => 'required|in:activo,inactivo',
    ]);

    // Validaciones de unicidad excluyendo el mismo ID
    if (
        Administrador::where('usuario', $request->usuario)
            ->where('_id', '!=', $id)
            ->exists()
    ) {
        return back()->withErrors(['usuario' => 'El usuario ya está registrado.'])->withInput();
    }

    if (
        Administrador::where('correo', $request->correo)
            ->where('_id', '!=', $id)
            ->exists()
    ) {
        return back()->withErrors(['correo' => 'El correo ya está registrado.'])->withInput();
    }

    // Asignar valores
    $administrador->fill($validated);

    // Si se proporciona contraseña nueva, la encripta
    if ($request->filled('contrasena')) {
        $administrador->contrasena = Hash::make($request->contrasena);
    }

    // Si se sube una nueva imagen
    if ($request->hasFile('imagen')) {
        // Elimina imagen anterior
        if ($administrador->imagen) {
            Storage::disk('public')->delete($administrador->imagen);
        }

        // Guarda la nueva imagen
        $administrador->imagen = $request->file('imagen')->store('administradores', 'public');
    }

    $administrador->save();

    return redirect()->route('administradores.index')->with('success', 'Administrador actualizado correctamente.');
}

    public function destroy(string $id)
    {
        $administrador = Administrador::find($id);

        if ($administrador) {
            $administrador->estado = 'inactivo';
            $administrador->save();
        }

        return redirect()->route('administradores.index')->with('success', 'Administrador desactivado.');
    }

    public function dashboard(Request $request)
    {
        $user = $request->get('user_jwt');
    $id = $user['sub'] ?? null;

    if (!$id) {
        // Si no hay id, redirige o muestra error
        return redirect()->route('login.admin')->withErrors(['error' => 'Usuario no identificado']);
    }

    // Busca al admin por su ID (suponiendo que usas MongoDB y el ID es string)
    $admin = Administrador::find($id);

    if (!$admin) {
        return redirect()->route('login.admin')->withErrors(['error' => 'Administrador no encontrado']);
    }

    $nombre = $admin->nombre; // o el campo que quieras mostrar

        return view('base.admin_dashboard', compact('nombre')); // o lo que quieras retornar
        // return "Dashboard funcionando";
        // return redirect()->route('admin.dashboard');
        
    }
}
