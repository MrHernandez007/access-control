<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visita;
use App\Models\Visitante;
use Carbon\Carbon;

class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->get('user_jwt');
        $residenteId = $user['sub'];

        $visitas = Visita::where('residente_id', $residenteId)->get();
        return view('visitas.index', compact('visitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $residenteId = $request->user_jwt['sub'];

    $visitantes = Visitante::where('residente_id', $residenteId)->get();

    return view('visitas.create', compact('visitantes'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $residenteId = $request->user_jwt['sub'];

    $data = $request->all();
    $data['residente_id'] = $residenteId;
    $data['estado'] = 'En curso'; // O el estado inicial que quieras
    // Agrega validaciones según necesites

    Visita::create($data);

    return redirect()->route('visitas.index')->with('success', 'Visita registrada.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

// app/Http/Controllers/VisitasController.php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Visita;
// use App\Models\Visitante;
// use Carbon\Carbon;

// class VisitasController extends Controller
// {
//     public function index(Request $request)
//     {
//         $user = $request->get('user_jwt');
//         $residenteId = $user['sub'];

//         $visitas = Visita::where('residente_id', $residenteId)->get();
//         return view('visitas.index', compact('visitas'));
//     }

//     public function create(Request $request)
//     {
//         $user = $request->get('user_jwt');
//         $residenteId = $user['sub'];

//         $visitantes = Visitante::where('residente_id', $residenteId)->get();
//         return view('visitas.create', compact('visitantes'));
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'visitante_id' => 'required|string',
//             'dia_visita' => 'required|date',
//         ]);

//         $user = $request->get('user_jwt');
//         $residenteId = $user['sub'];

//         Visita::create([
//             'visitante_id' => $validated['visitante_id'],
//             'residente_id' => $residenteId,
//             'dia_visita' => $validated['dia_visita'],
//             'hora_llegada' => now()->format('H:i'),
//             'hora_salida' => null,
//             'estado' => 'En curso',
//         ]);

//         return redirect()->route('visitas.index')->with('success', 'Visita registrada');
//     }

//     public function show($id)
//     {
//         $visita = Visita::findOrFail($id);
//         return view('visitas.show', compact('visita'));
//     }

//     public function destroy($id)
//     {
//         $visita = Visita::findOrFail($id);
//         $visita->delete();

//         return redirect()->route('visitas.index')->with('success', 'Visita eliminada');
//     }
// }

