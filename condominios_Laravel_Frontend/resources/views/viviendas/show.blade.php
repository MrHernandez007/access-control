@extends('layout.admin')

@section('titulo', 'Detalle del Residente')
@section('meta-descripcion', 'Detalle y datos del residente')

@section('contenido')
<div class="container">
    <h2>Detalle del residente</h2>

    <div class="card mb-3" style="max-width: 540px;">
        <div class="row g-0">
            @if ($residente->imagen)
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $residente->imagen) }}" class="img-fluid rounded-start" alt="Imagen del residente">
            </div>
            @endif
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $residente->nombre }} {{ $residente->apellido }}</h5>
                    <p class="card-text"><strong>Usuario:</strong> {{ $residente->usuario }}</p>
                    <p class="card-text"><strong>Correo:</strong> {{ $residente->correo }}</p>
                    <p class="card-text"><strong>Teléfono:</strong> {{ $residente->telefono }}</p>
                    <p class="card-text"><strong>Estado:</strong> 
                        <span class="badge bg-{{ $residente->estado === 'activo' ? 'success' : 'secondary' }}">
                            {{ ucfirst($residente->estado) }}
                        </span>
                    </p>
                    <a href="{{ route('residentes.edit', $residente->_id) }}" class="btn btn-warning">Editar</a>
                    <a href="{{ route('residentes.index') }}" class="btn btn-secondary">Volver a la lista</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
