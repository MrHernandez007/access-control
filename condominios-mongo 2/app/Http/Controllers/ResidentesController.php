<?php

namespace App\Http\Controllers;
use App\Models\Residente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ResidentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residentes = Residente::all();
        return view('residentes.index', compact('residentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('residentes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'usuario' => 'required|string|max:50|unique:residentes,usuario',
            'correo' => 'required|email|unique:residentes,correo',
            'telefono' => 'nullable|string|max:50',
            'contrasena' => 'required|string|min:6',
            'imagen' => 'nullable|image|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $residente = new Residente($validated);

        $residente->contrasena = Hash::make($validated['contrasena']);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('residentes', 'public');
            $residente->imagen = $path;
        }

        $residente->save();

        return redirect()->route('residentes.index')->with('success', 'Residente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $residente = Residente::find($id);

        if (!$residente) {
            abort(404, 'Residente no encontrado');
        }

        return view('residentes.show', compact('residente'));
    }

    public function edit(string $id)
    {
        $residente = Residente::find($id);
        return view('residentes.edit', compact('residente'));
    }

    public function update(Request $request, string $id)
    {
        $residente = Residente::find($id);

        if (!$residente) {
            abort(404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'usuario' => 'required|string|max:50|unique:residentes,usuario,' . $id . ',_id',
            'correo' => 'required|email|unique:residentes,correo,' . $id . ',_id',
            'telefono' => 'nullable|string|max:50',
            'contrasena' => 'nullable|string|min:6',
            'imagen' => 'nullable|image|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $residente->fill($validated);

        if ($request->filled('contrasena')) {
            $residente->contrasena = Hash::make($request->contrasena);
        }

        if ($request->hasFile('imagen')) {
            if ($residente->imagen) {
                Storage::disk('public')->delete($residente->imagen);
            }
            $residente->imagen = $request->file('imagen')->store('residentes', 'public');
        }

        $residente->save();

        return redirect()->route('residentes.index')->with('success', 'Residente actualizado.');
    }

    public function destroy(string $id)
    {
        $residente = Residente::find($id);

        $residente->estado='Inactivo';
     
        $residente->save();
        

        return redirect()->route('residentes.index')->with('success', 'Residente eliminado.');
    }


    public function dashboard(Request $request)
    {
        // Aquí puedes retornar la vista del dashboard del admin
        $user = $request->get('user_jwt');  // Datos del usuario decodificados

        //dd($user);
        $nombre = $user['nombre'] ?? $user['usuario'] ?? 'Usuario'; // según lo que tengas en JWT


         return view('base.residente_dashboard', compact('nombre')); //['nombre' => $user['nombre'] ?? $user['usuario'] ?? '']); 
        // return "Dashboard funcionando";
        // return redirect()->route('admin.dashboard');
        
    }
}
