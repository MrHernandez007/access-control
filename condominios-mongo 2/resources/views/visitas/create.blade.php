@extends('layout.residentes')

@section('titulo', 'Registrar Nueva Visita')
@section('meta-descripcion', 'Formulario para registrar una nueva visita')

@section('contenido')
<br><br>
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-sky-700 mb-6 flex items-center gap-2" style="font-size: 30px">
        <i class="fas fa-user-plus"></i> Registrar Nueva Visita
    </h2>

    <form id="formulario" class="space-y-6 bg-white p-6 rounded-lg shadow-md border">
        <div>
            <label for="visitante_id" class="block text-sm font-medium text-gray-700">Visitante</label>
            <select id="visitante_id" name="visitante_id" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none">
            </select>
        </div>

        <div>
            <label for="dia_visita" class="block text-sm font-medium text-gray-700">Fecha</label>
            <input type="date" id="dia_visita" name="dia_visita" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none" />
        </div>

        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
            <select id="estado" name="estado" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none">
                <option value="pendiente">Pendiente</option>
                <option value="autorizado">Autorizado</option>
                <option value="rechazado">Rechazado</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-sky-700 text-white px-4 py-2 rounded hover:bg-sky-800 transition">Registrar</button>
        </div>
    </form>
</div>

<script>
    const token = localStorage.getItem('token');
    const residente_id = localStorage.getItem('residente_id');  // IMPORTANTÍSIMO: este valor debe estar guardado en localStorage

    // Cargar lista de visitantes del residente autenticado (filtrar backend idealmente)
    fetch('http://localhost:3000/api/visitantes', {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById('visitante_id');
        data.forEach(visitante => {
            const option = document.createElement('option');
            option.value = visitante._id;
            option.textContent = visitante.nombre + ' ' + visitante.apellido;
            select.appendChild(option);
        });
    })
    .catch(err => console.error('Error al cargar visitantes:', err));

    // Enviar formulario
    document.getElementById('formulario').addEventListener('submit', function(e) {
        e.preventDefault();

        const data = {
            visitante_id: document.getElementById('visitante_id').value,
            dia_visita: document.getElementById('dia_visita').value,
            estado: document.getElementById('estado').value,
            residente_id: residente_id  // enviar residente_id requerido por backend
        };

        fetch('http://localhost:3000/api/visitas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) throw new Error(`Error ${res.status}`);
            return res.json();
        })
        .then(response => {
            alert('Visita registrada correctamente');
            window.location.href = '/visitas';
        })
        .catch(err => {
            console.error('Error al registrar visita:', err);
            alert('Error al registrar visita');
        });
    });
</script>
@endsection
