@extends('layout.admins')

@section('titulo', 'Visitantes')

@section('contenido')
<br><br>

<style>
.modal {
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
  animation: fadeInBg 0.25s ease forwards;
}
.modal.hidden {
  display: none;
}
.modal-content {
  background: white;
  padding: 30px 35px;
  border-radius: 14px;
  max-width: 400px;
  width: 90%;
  text-align: center;
  box-shadow: 0 12px 28px rgba(0,0,0,0.15);
  opacity: 0;
  transform: scale(0.85);
  animation: scaleFadeIn 0.3s forwards cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes fadeInBg {
  from { background-color: rgba(0,0,0,0); }
  to { background-color: rgba(0,0,0,0.4); }
}
@keyframes scaleFadeIn {
  to {
    opacity: 1;
    transform: scale(1);
  }
}
.modal-buttons {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 20px;
}
.btn {
  padding: 12px 28px;
  font-weight: 700;
  font-size: 17px;
  border-radius: 8px;
  cursor: pointer;
  border: none;
  transition: background-color 0.3s ease;
  user-select: none;
}
.confirm-btn {
  background-color: #dc2626; /* rojo para eliminar */
  color: white;
  box-shadow: 0 6px 12px rgba(220, 38, 38, 0.5);
}
.confirm-btn:hover,
.confirm-btn:focus {
  outline: none;
  box-shadow: 0 8px 16px rgba(185, 28, 28, 0.7);
}
.cancel-btn {
  background-color: #9ca3af; /* gris */
  color: white;
  box-shadow: 0 6px 12px rgba(156, 163, 175, 0.5);
}
.cancel-btn:hover,
.cancel-btn:focus {
  outline: none;
  box-shadow: 0 8px 16px rgba(107, 114, 128, 0.7);
}
</style>

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
                    <th class="px-4 py-3 text-left font-medium"><i class="bi bi-hash"></i></th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Nombre</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-badge-fill"></i> Apellido</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-telephone-fill"></i> Teléfono</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-card-list"></i> Tipo</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-toggle-on"></i> Estado</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-car-front-fill"></i> Vehículo</th>
                    <th class="px-4 py-3 text-center"><i class="bi bi-tools"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="visitantesBody">
                <tr><td colspan="8" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal confirmación eliminar -->
<div id="modalEliminar" class="modal hidden" role="alertdialog" aria-modal="true" aria-labelledby="tituloEliminar" aria-describedby="mensajeEliminar">
  <div class="modal-content">
    <i class="bi bi-exclamation-triangle-fill text-red-600 text-7xl mb-4"></i>
    <h2 id="tituloEliminar" class="text-2xl font-semibold mb-2 text-red-700">Confirmar eliminación</h2>
    <p id="mensajeEliminar" class="mb-4">¿Seguro que deseas eliminar este visitante?</p>
    <div class="modal-buttons">
      <button id="btnCancelarEliminar" class="btn cancel-btn">Cancelar</button>
      <button id="btnConfirmarEliminar" class="btn confirm-btn">Eliminar</button>
    </div>
  </div>
</div>

<!-- Modal alerta éxito -->
<div id="modalExitoEliminar" class="modal hidden" role="alert" aria-modal="true" aria-labelledby="tituloExitoEliminar" aria-describedby="mensajeExitoEliminar">
  <div class="modal-content">
    <i class="bi bi-check-circle-fill text-green-600 text-7xl mb-4"></i>
    <h2 id="tituloExitoEliminar" class="text-2xl font-semibold mb-2 text-green-700">Visitante eliminado correctamente</h2>
    <p id="mensajeExitoEliminar" class="mb-4">El registro ha sido eliminado.</p>
    <div class="modal-buttons">
      <button id="btnCerrarExitoEliminar" class="btn confirm-btn">Aceptar</button>
    </div>
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
              <td class="px-4 py-3 text-center">
                  <button onclick="confirmarEliminar('${id}')"
                          class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium mt-1">
                      <i class="bi bi-tools"></i> Eliminar
                  </button>
              </td>
          </tr>
        `;
      });

    } catch (error) {
      console.error(error);
      const errorDiv = document.getElementById('error');
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

const modalEliminar = document.getElementById('modalEliminar');
const btnConfirmarEliminar = document.getElementById('btnConfirmarEliminar');
const btnCancelarEliminar = document.getElementById('btnCancelarEliminar');
const modalExitoEliminar = document.getElementById('modalExitoEliminar');
const btnCerrarExitoEliminar = document.getElementById('btnCerrarExitoEliminar');

let idEliminar = null;

function confirmarEliminar(id) {
    idEliminar = id;
    modalEliminar.classList.remove('hidden');
}

btnCancelarEliminar.addEventListener('click', () => {
    modalEliminar.classList.add('hidden');
    idEliminar = null;
});

btnConfirmarEliminar.addEventListener('click', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('No tienes sesión activa');
        modalEliminar.classList.add('hidden');
        return;
    }
    if (!idEliminar) return;

    try {
        const res = await fetch(`http://localhost:3000/api/visitantes/${idEliminar}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        const result = await res.json();
        modalEliminar.classList.add('hidden');

        if (res.ok) {
            modalExitoEliminar.classList.remove('hidden');
        } else {
            alert('Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (err) {
        alert('Error al eliminar');
        console.error(err);
        modalEliminar.classList.add('hidden');
    }
});

btnCerrarExitoEliminar.addEventListener('click', () => {
    modalExitoEliminar.classList.add('hidden');
    location.reload();
});
</script>

@endsection
