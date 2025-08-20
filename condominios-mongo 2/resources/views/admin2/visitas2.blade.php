@extends('layout.admins2')

@section('titulo', 'Lista de Visitas')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-eye-fill text-sky-700 text-3xl"></i>
        Lista de Visitas
    </h1>

    <!-- FILTROS -->
<div class="mb-6 flex items-center gap-3">
  <label for="filtroEstado" class="font-semibold text-gray-700">Filtrar por estado:</label>
  <select id="filtroEstado" class="block w-48 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm
    focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm font-medium text-gray-700
    transition duration-150 ease-in-out">
    <option value="">Todos</option>
    <option value="En curso">En curso</option>
    <option value="Terminada">Terminada</option>
  </select>

  <label for="filtroFecha" class="font-semibold text-gray-700 ml-6">Filtrar por fecha:</label>
  <input type="date" id="filtroFecha" class="block w-48 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm
    focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm font-medium text-gray-700
    transition duration-150 ease-in-out">

  <button id="btnLimpiarFiltros" 
    class="ml-6 inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-md shadow-sm
    transition duration-150 ease-in-out">
    <i class="bi bi-x-lg"></i> Limpiar filtros
  </button>
</div>

    <div id="mensaje-visitas" class="mb-4"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-visitas">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Visitante</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-calendar-event-fill"></i> Fecha</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-info-circle-fill"></i> Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-visitas">
                <tr><td colspan="3" class="text-center text-gray-500 py-4">Cargando visitas...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tablaBody = document.getElementById('body-visitas');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroFecha = document.getElementById('filtroFecha');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const token = localStorage.getItem('token');

    if (!token) {
        tablaBody.innerHTML = `<tr><td colspan="3" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    let visitasOriginales = [];

    async function cargarVisitas() {
        try {
            const response = await fetch('http://localhost:3000/api/visitas', {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (!response.ok) {
                tablaBody.innerHTML = `<tr><td colspan="3" class="text-center text-red-500 py-4">Error ${response.status}: ${await response.text()}</td></tr>`;
                return;
            }

            visitasOriginales = await response.json();

            filtrarYMostrar();
        } catch (error) {
            console.error(error);
            tablaBody.innerHTML = `<tr><td colspan="3" class="text-center text-red-500 py-4">Error al cargar visitas.</td></tr>`;
        }
    }

    function filtrarYMostrar() {
        let filtradas = visitasOriginales;

        // Filtrar por estado si hay valor
        const estadoFiltro = filtroEstado.value;
        if (estadoFiltro) {
            if (estadoFiltro === 'Otro') {
                filtradas = filtradas.filter(v => {
                    return v.estado && v.estado !== 'En curso' && v.estado !== 'Finalizada';
                });
            } else {
                filtradas = filtradas.filter(v => v.estado === estadoFiltro);
            }
        }

        // Filtrar por fecha (fecha exacta, ignorando hora)
        const fechaFiltro = filtroFecha.value; // formato yyyy-mm-dd
        if (fechaFiltro) {
            filtradas = filtradas.filter(v => {
                if (!v.dia_visita) return false;
                const visitaDate = new Date(v.dia_visita);
                const visitaFechaStr = visitaDate.toISOString().slice(0, 10);
                return visitaFechaStr === fechaFiltro;
            });
        }

        if (filtradas.length === 0) {
            tablaBody.innerHTML = `
                <tr><td colspan="3" class="text-center text-gray-500 py-4">
                    <i class="bi bi-info-circle"></i> No hay visitas registradas.
                </td></tr>
            `;
            return;
        }

        tablaBody.innerHTML = '';

        filtradas.forEach(visita => {
            const visitante = visita.visitante_id;
            const nombreCompleto = visitante && typeof visitante === 'object'
                ? `${visitante.nombre || ''} ${visitante.apellido || ''}`.trim()
                : 'Desconocido';

            const fechaRaw = visita.dia_visita;
            const fechaValida = fechaRaw && !isNaN(Date.parse(fechaRaw));
            const fechaFormateada = fechaValida
                ? new Date(fechaRaw).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                })
                : 'Fecha inválida';

            const estadoTexto = visita.estado || 'Desconocido';
            let estadoIcono = '';
            let claseEstado = '';

            if (estadoTexto === 'En curso') {
                claseEstado = 'bg-yellow-100 text-yellow-700';
                estadoIcono = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-yellow-700 fill-none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6l4 2m4-2a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                `;
            } else if (estadoTexto === 'Finalizada') {
                claseEstado = 'bg-green-100 text-green-700';
                estadoIcono = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-green-700 fill-none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                `;
            } else {
                claseEstado = 'bg-gray-200 text-gray-700';
                estadoIcono = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-gray-600 fill-none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                `;
            }

            tablaBody.innerHTML += `
                <tr>
                    <td class="px-4 py-3 text-gray-800">${nombreCompleto}</td>
                    <td class="px-4 py-3 text-gray-700">${fechaFormateada}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-2 px-2 py-1 rounded-full ${claseEstado}">
                            ${estadoIcono}
                            ${estadoTexto}
                        </span>
                    </td>
                </tr>
            `;
        });
    }

    filtroEstado.addEventListener('change', filtrarYMostrar);
    filtroFecha.addEventListener('change', filtrarYMostrar);
    btnLimpiarFiltros.addEventListener('click', () => {
        filtroEstado.value = '';
        filtroFecha.value = '';
        filtrarYMostrar();
    });

    cargarVisitas();
});
</script>
@endsection
