@extends('layout.admins2')

@section('titulo', 'Residentes')
@section('meta-descripcion', 'Lista de Residentes')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-people-fill text-sky-700 text-3xl"></i>
        Lista de Residentes
    </h1>
<div class="mb-6 flex items-center gap-3">
  <label for="filtroEstadoResidentes" class="font-semibold text-gray-700">Filtrar residentes por estado:</label>
  <select id="filtroEstadoResidentes" class="block w-48 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm
    focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm font-medium text-gray-700
    transition duration-150 ease-in-out">
    <option value="">Todos</option>
    <option value="activo">Activos</option>
    <option value="inactivo">Inactivos</option>
  </select>
</div>
    <div id="mensaje" class="mb-4"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-residentes">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left font-medium"><i class="bi bi-hash"></i></th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Nombre</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Apellido</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-badge-fill"></i> Usuario</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-envelope-fill"></i> Correo</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-telephone-fill"></i> Teléfono</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-power"></i> Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-residentes">
                <tr><td colspan="8" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const tablaBody = document.getElementById('body-residentes');
  const filtroEstado = document.getElementById('filtroEstadoResidentes');
  const token = localStorage.getItem('token');

  if (!token) {
    tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
    return;
  }

  // Función para cargar residentes según filtro de estado
  async function cargarResidentes(estadoFiltro = '') {
    try {
      let url = 'http://localhost:3000/api/residentes';
      if (estadoFiltro) {
        url += `?estado=${encodeURIComponent(estadoFiltro)}`;
      }

      const res = await fetch(url, {
        headers: { 'Authorization': 'Bearer ' + token }
      });

      if (res.status === 401 || res.status === 403) {
        tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
        return;
      }

      const residentes = await res.json();

      if (!Array.isArray(residentes)) throw new Error("Respuesta inválida");

      if (residentes.length === 0) {
        tablaBody.innerHTML = `
          <tr><td colspan="8" class="text-center text-gray-500 py-4">
              <i class="bi bi-info-circle"></i> No hay residentes registrados.
          </td></tr>
        `;
        return;
      }

      tablaBody.innerHTML = '';
      residentes.forEach((residente, index) => {
        const id = residente._id || residente.id || '';
        tablaBody.innerHTML += `
          <tr>
              <td class="px-4 py-3 text-gray-700">${index + 1}</td>
              <td class="px-4 py-3 text-gray-800">${residente.nombre || ''}</td>
              <td class="px-4 py-3 text-gray-800">${residente.apellido || ''}</td>
              <td class="px-4 py-3 text-gray-700">${residente.usuario || ''}</td>
              <td class="px-4 py-3 text-gray-700">${residente.correo || ''}</td>
              <td class="px-4 py-3 text-gray-700">${residente.telefono || ''}</td>
              <td class="px-4 py-3">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${
                    residente.estado === 'activo'
                      ? 'bg-green-100 text-green-700'
                      : 'bg-gray-200 text-gray-700'
                  }">
                      <i class="bi ${
                        residente.estado === 'activo'
                          ? 'bi-toggle-on'
                          : 'bi-toggle-off'
                      }"></i> ${residente.estado || ''}
                  </span>
              </td>
          </tr>
        `;
      });
    } catch (error) {
      console.error(error);
      tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Error al cargar datos.</td></tr>`;
    }
  }

  // Cargar residentes inicialmente sin filtro
  cargarResidentes();

  // Escuchar cambio en el select para recargar la tabla con filtro
  filtroEstado.addEventListener('change', () => {
    cargarResidentes(filtroEstado.value);
  });
});

</script>
@endsection
