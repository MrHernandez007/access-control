<?php

namespace App\Http\Controllers;

use App\Models\Vivienda;
use Illuminate\Http\Request;
//use Vivienda as GlobalVivienda;

class ViviendasController extends Controller
{
    public function index()
    {
        $viviendas = Vivienda::all();
        return view('viviendas.index', compact('viviendas'));
    }

    public function create()
    {
        return view('viviendas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:20',
            'tipo' => 'required|string|max:50',
            'calle' => 'required|string|max:100',
        ]);

        $vivienda = new Vivienda($validated);
        $vivienda->save();

        return redirect()->route('viviendas.index')->with('success', 'Vivienda creada correctamente.');
    }

    public function show(string $id)
    {
        $vivienda = Vivienda::find($id);

        if (!$vivienda) {
            abort(404, 'Vivienda no encontrada');
        }

        return view('viviendas.show', compact('vivienda'));
    }

    public function edit(string $id)
    {
        $vivienda = Vivienda::find($id);

        if (!$vivienda) {
            abort(404);
        }

        return view('viviendas.edit', compact('vivienda'));
    }

    public function update(Request $request, string $id)
    {
        $vivienda = Vivienda::find($id);

        if (!$vivienda) {
            abort(404);
        }

        $validated = $request->validate([
            'numero' => 'required|string|max:20',
            'tipo' => 'required|string|max:50',
            'calle' => 'required|string|max:100',
        ]);

        $vivienda->fill($validated);
        $vivienda->save();

        return redirect()->route('viviendas.index')->with('success', 'Vivienda actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $vivienda = Vivienda::find($id);

        if ($vivienda) {
            $vivienda->estado = 'inactivo'; // Si prefieres solo marcarla como inactiva, se puede adaptar
            $vivienda->save();
        }

        return redirect()->route('viviendas.index')->with('success', 'Vivienda eliminada correctamente.');
    }
}
