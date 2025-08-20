<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitante;
use MongoDB\BSON\ObjectId;

class VisitantesController extends Controller
{
    public function index_prueba()
{
    $visitantes = Visitante::all();

    foreach ($visitantes as $v) {
        dump([
            'tipo' => gettype($v->vehiculo),
            'contenido' => $v->vehiculo
        ]);
    }

    return view('visitantes.index', compact('visitantes'));
}

    public function indexgeneral()
    {
        $visitantes = Visitante::all();
        //dd($visitantes);
        return view('visitantes.index', compact('visitantes'));
    }

    public function create()
    {
        return view('visitantes.create');
    }

    public function index(Request $request)
{
    $userJwt = $request->get('user_jwt');

    if (!$userJwt || !isset($userJwt['sub'])) {
        return redirect()->route('login.residente')->withErrors(['error' => 'Debes iniciar sesión como residente.']);
    }

    $residenteId = new ObjectId($userJwt['sub']);

    $visitantes = Visitante::where('residente_id', $residenteId)->get();

    return view('visitantes.index', compact('visitantes'));
}

    public function store_apenasreemplazado(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:50',
        'apellido' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'tipo' => 'required|in:visita,proveedor',
        'vehiculo.placa' => 'nullable|string|max:20',
        'vehiculo.color' => 'nullable|string|max:30',
        'vehiculo.modelo' => 'nullable|string|max:50',
        'vehiculo.tipo' => 'nullable|string|max:30',
        'estado' => 'required|in:activo,inactivo',
        
    ]);

    $visitante = new Visitante();

    $visitante->nombre = $validated['nombre'];
    $visitante->apellido = $validated['apellido'];
    $visitante->telefono = $validated['telefono'] ?? null;
    $visitante->tipo = $validated['tipo'];
    $visitante->estado = $validated['estado'];
    
    //'estado' => 'Activo'

    // Asignar vehículo como array directamente, o vacío si no hay datos
    $visitante->vehiculo = $request->input('vehiculo', []);

    $visitante->save();

    return redirect()->route('visitantes.index')->with('success', 'Visitante registrado correctamente.');
}

public function storesineldidelresidentequeloregistro(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:50',
        'apellido' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'tipo' => 'required|in:visita,proveedor',
        'vehiculo.placa' => 'nullable|string|max:20',
        'vehiculo.color' => 'nullable|string|max:30',
        'vehiculo.modelo' => 'nullable|string|max:50',
        'vehiculo.tipo' => 'nullable|string|max:30',
        'estado' => 'required|in:activo,inactivo',
    ]);

    $visitante = new Visitante();

    $visitante->nombre = $validated['nombre'];
    $visitante->apellido = $validated['apellido'];
    $visitante->telefono = $validated['telefono'] ?? null;
    $visitante->tipo = $validated['tipo'];
    $visitante->estado = $validated['estado'];

    $visitante->vehiculo = $request->input('vehiculo', []);

    // Aquí asignas el residente_id desde el JWT almacenado en el request (middleware)
    $userJwt = $request->get('user_jwt'); 
    if ($userJwt && isset($userJwt['sub'])) {
        $visitante->residente_id = $userJwt['sub'];  // O el campo correcto según tu base de datos
    } else {
        // Opcional: manejar caso donde no haya residente logueado
        return redirect()->route('login.residente')->withErrors(['error' => 'Debes iniciar sesión como residente para registrar un visitante']);
    }

    $visitante->save();

    return redirect()->route('visitantes.index')->with('success', 'Visitante registrado correctamente.');
}

public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'tipo' => 'required|in:visita,proveedor',
            'vehiculo.placa' => 'nullable|string|max:20',
            'vehiculo.color' => 'nullable|string|max:30',
            'vehiculo.modelo' => 'nullable|string|max:50',
            'vehiculo.tipo' => 'nullable|string|max:30',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $visitante = new Visitante();

        $visitante->nombre = $validated['nombre'];
        $visitante->apellido = $validated['apellido'];
        $visitante->telefono = $validated['telefono'] ?? null;
        $visitante->tipo = $validated['tipo'];
        $visitante->estado = $validated['estado'];
        $visitante->vehiculo = $request->input('vehiculo', []);

        $userJwt = $request->get('user_jwt'); 
        if ($userJwt && isset($userJwt['sub'])) {
            $visitante->residente_id = new ObjectId($userJwt['sub']); // ✅
        } else {
            return redirect()->route('login.residente')->withErrors(['error' => 'Debes iniciar sesión como residente para registrar un visitante']);
        }

        $visitante->save();

        return redirect()->route('visitantes.index')->with('success', 'Visitante registrado correctamente.');
    }




    public function store_viejo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'tipo' => 'required|in:visita,proveedor',
            'vehiculo.placa' => 'nullable|string|max:20',
            'vehiculo.color' => 'nullable|string|max:30',
            'vehiculo.modelo' => 'nullable|string|max:50',
            'vehiculo.tipo' => 'nullable|string|max:30',
        ]);

        $visitante = new Visitante($validated);
        $visitante->save();

        return redirect()->route('visitantes.index')->with('success', 'Visitante registrado correctamente.');
    }

    public function show(string $id)
    {
        $visitante = Visitante::find($id);

        if (!$visitante) {
            abort(404, 'Visitante no encontrado');
        }

        return view('visitantes.show', compact('visitante'));
    }

    public function edit(string $id)
    {
        $visitante = Visitante::find($id);

        if (!$visitante) {
            abort(404, 'Visitante no encontrado');
        }

        return view('visitantes.edit', compact('visitante'));
    }

    public function update_viejo(Request $request, string $id)
{
    $visitante = Visitante::find($id);

    if (!$visitante) {
        abort(404, 'Visitante no encontrado');
    }

    $validated = $request->validate([
        'nombre' => 'required|string|max:50',
        'apellido' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'tipo' => 'required|in:visita,proveedor',
        'vehiculo.placa' => 'nullable|string|max:20',
        'vehiculo.color' => 'nullable|string|max:30',
        'vehiculo.modelo' => 'nullable|string|max:50',
        'vehiculo.tipo' => 'nullable|string|max:30',
    ]);

    // Asignar campo por campo directamente
    $visitante->nombre = $validated['nombre'];
    $visitante->apellido = $validated['apellido'];
    $visitante->telefono = $validated['telefono'] ?? null;
    $visitante->tipo = $validated['tipo'];

    // Asignar vehículo como array directamente
    $visitante->vehiculo = $request->input('vehiculo', []);

    $visitante->save();

    return redirect()->route('visitantes.index')->with('success', 'Visitante actualizado correctamente.');
}

public function update(Request $request, string $id)
{
    $visitante = Visitante::find($id);

    if (!$visitante) {
        abort(404, 'Visitante no encontrado');
    }

    $validated = $request->validate([
        'nombre' => 'required|string|max:50',
        'apellido' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'tipo' => 'required|in:visita,proveedor',
        'vehiculo.placa' => 'nullable|string|max:20',
        'vehiculo.color' => 'nullable|string|max:30',
        'vehiculo.modelo' => 'nullable|string|max:50',
        'vehiculo.tipo' => 'nullable|string|max:30',
    ]);

    $visitante->nombre = $validated['nombre'];
    $visitante->apellido = $validated['apellido'];
    $visitante->telefono = $validated['telefono'] ?? null;
    $visitante->tipo = $validated['tipo'];

    // Asignar vehículo como array directamente, o vacío si no hay datos
    $visitante->vehiculo = $request->input('vehiculo', []);

    $visitante->save();

    return redirect()->route('visitantes.index')->with('success', 'Visitante actualizado correctamente.');
}



    public function update_vie(Request $request, string $id)
    {
        $visitante = Visitante::find($id);

        if (!$visitante) {
            abort(404, 'Visitante no encontrado');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'tipo' => 'required|in:visita,proveedor',
            'vehiculo.placa' => 'nullable|string|max:20',
            'vehiculo.color' => 'nullable|string|max:30',
            'vehiculo.modelo' => 'nullable|string|max:50',
            'vehiculo.tipo' => 'nullable|string|max:30',
        ]);

        $visitante->fill($validated);
        $visitante->save();

        return redirect()->route('visitantes.index')->with('success', 'Visitante actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $visitante = Visitante::find($id);

        // if ($visitante) {
        //     $visitante->delete(); // o $visitante->estado = 'inactivo'; $visitante->save();
        // } 

        $visitante->estado = 'inactivo'; $visitante->save();

        return redirect()->route('visitantes.index')->with('success', 'Visitante eliminado correctamente.');
    }
}
