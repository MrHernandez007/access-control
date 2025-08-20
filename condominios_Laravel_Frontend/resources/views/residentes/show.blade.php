@extends('layout.admins')

@section('titulo','DETALLE RESIDENTE')
@section('meta-descripcion','Vista de detalle del residente')

@section('contenido')
<br><br>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3">
            @if ($residente->imagen)
            <div class="md:col-span-1">
                <img src="/storage/{{ $residente->imagen }}" alt="Imagen del residente" class="w-full h-auto rounded-l-lg object-cover">
            </div>
            @endif

            <div class="md:col-span-2 p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">
                    {{ $residente->nombre }} {{ $residente->apellido }}
                </h2>

                <p class="mb-2"><strong class="text-gray-600">Usuario:</strong> {{ $residente->usuario }}</p>
                <p class="mb-2"><strong class="text-gray-600">Correo:</strong> {{ $residente->correo }}</p>
                <p class="mb-2"><strong class="text-gray-600">Teléfono:</strong> {{ $residente->telefono }}</p>
                <p class="mb-4">
                    <strong class="text-gray-600">Estado:</strong>
                    <span class="inline-block px-2 py-1 rounded text-white text-sm font-semibold 
                        {{ $residente->estado === 'activo' ? 'bg-green-500' : 'bg-gray-500' }}">
                        {{ ucfirst($residente->estado) }}
                    </span>
                </p>

                <div class="flex space-x-3">
                    <a href="/residentes/{{ $residente->_id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    <a href="/residentes" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
