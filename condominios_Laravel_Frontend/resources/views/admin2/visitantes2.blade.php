@extends('layout.admins2')

@section('titulo', 'Visitantes')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-person-check-fill text-sky-700 text-3xl"></i>
        Visitantes
    </h1>
<div class="mb-6 flex items-center gap-3">
  <label for="filtroEstado" class="font-semibold text-gray-700">Filtrar por estado:</label>
  <select id="filtroEstado" class="block w-48 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm
    focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm font-medium text-gray-700
    transition duration-150 ease-in-out">
    <option value="">Todos</option>
    <option value="activo">Activos</option>
    <option value="inactivo">Inactivos</option>
  </select>
</div>
    <div id="error" class="text-red-600 font-semibold mb-4 hidden"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-visitantes">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">#</th>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Apellido</th>
                    <th class="px-4 py-3 text-left">Teléfono</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Vehículo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="visitantesBody">
                <tr><td colspan="8" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const filtroEstado = document.getElementById('filtroEstado');

  async function cargarVisitantes() {
    const token = localStorage.getItem('token');
    const tbody = document.getElementById('visitantesBody');
    const errorDiv = document.getElementById('error');
    const baseEditUrl = "{{ url('visitantes') }}";

    if (!token) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
      return;
    }

    try {
      // Leer el filtro actual
      const estadoFiltro = filtroEstado.value;
      let url = 'http://localhost:3000/api/visitantes';
      if (estadoFiltro) {
        url += `?estado=${estadoFiltro}`;
      }

      const response = await fetch(url, {
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (response.status === 401 || response.status === 403) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
        return;
      }

      const visitantes = await response.json();

      tbody.innerHTML = '';

      if (!Array.isArray(visitantes) || visitantes.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500 py-4">No hay visitantes registrados.</td></tr>`;
        return;
      }

      visitantes.forEach((visitante, index) => {
        const id = visitante._id || visitante.id || '';

        const vehiculo = visitante.vehiculo ? `
            <div><strong>Tipo:</strong> ${visitante.vehiculo.tipo || ''}</div>
            <div><strong>Modelo:</strong> ${visitante.vehiculo.modelo || ''}</div>
            <div><strong>Color:</strong> ${visitante.vehiculo.color || ''}</div>
            <div><strong>Placa:</strong> ${visitante.vehiculo.placa || ''}</div>
        ` : '';

        const estadoClass = visitante.estado === 'activo'
          ? 'bg-green-100 text-green-700'
          : visitante.estado === 'inactivo'
            ? 'bg-red-100 text-red-700'
            : 'bg-gray-200 text-gray-700';

        const estadoIcon = visitante.estado === 'activo' ? 'bi-toggle-on' : 'bi-toggle-off';

        tbody.innerHTML += `
          <tr>
              <td class="px-4 py-3 text-gray-700">${index + 1}</td>
              <td class="px-4 py-3 text-gray-800">${visitante.nombre || ''}</td>
              <td class="px-4 py-3 text-gray-800">${visitante.apellido || ''}</td>
              <td class="px-4 py-3 text-gray-800">${visitante.telefono || ''}</td>
              <td class="px-4 py-3 text-gray-800">${visitante.tipo || ''}</td>
              <td class="px-4 py-3">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${estadoClass}">
                      <i class="bi ${estadoIcon}"></i> ${visitante.estado || ''}
                  </span>
              </td>
              <td class="px-4 py-3 text-gray-800 align-top">${vehiculo}</td>
          </tr>
        `;
      });

    } catch (error) {
      console.error(error);
      errorDiv.classList.remove("hidden");
      errorDiv.textContent = "Error al cargar los visitantes.";
    }
  }

  // Cargar visitantes la primera vez
  cargarVisitantes();

  // Recargar cuando cambie el filtro
  filtroEstado.addEventListener('change', () => {
    cargarVisitantes();
  });
});

async function eliminarVisitante(id) {
  const token = localStorage.getItem('token');
  if (!token) {
    alert('No tienes sesión activa');
    return;
  }

  if (!confirm('¿Seguro que deseas eliminar este visitante?')) return;

  try {
    const res = await fetch(`http://localhost:3000/api/visitantes/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    const result = await res.json();
    if (res.ok) {
      alert('Visitante eliminado correctamente');
      location.reload();
    } else {
      alert('Error: ' + (result.error || 'No se pudo eliminar'));
    }
  } catch (err) {
    alert('Error al eliminar');
    console.error(err);
  }
}
</script>

@endsection
