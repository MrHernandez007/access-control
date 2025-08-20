<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Residente;
use MongoDB\BSON\ObjectId;

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // Vista para residentes
    public function indexResidente(Request $request)
    {
        $userJwt = $request->get('user_jwt');

        if (!$userJwt || $userJwt['rol'] !== 'residente') {
            return redirect()->route('login.residente')->withErrors(['error' => 'No autorizado.']);
        }

        $pagos = Pago::where('residente_id', new ObjectId($userJwt['sub']))->get();

        return view('pagos.index_residente', compact('pagos'));
    }

    // Vista para administradores
    public function indexAdmin(Request $request)
    {
        $userJwt = $request->get('user_jwt');

        if (!$userJwt || $userJwt['rol'] !== 'admin') {
            return redirect()->route('login.admin')->withErrors(['error' => 'No autorizado.']);
        }

        $pagos = Pago::all();

        return view('pagos.index_admin', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
