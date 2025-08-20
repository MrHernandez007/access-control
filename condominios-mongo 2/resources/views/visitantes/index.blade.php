@extends('layout.residentes')

@section('titulo', 'Visitantes')
@section('meta-descripcion', 'Listado de visitantes registrados por el residente')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-person-check-fill text-sky-700 text-3xl"></i>
        Mis Visitantes
    </h1>

    <a href="/visitantes/create" style="font-size: large"
       class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg shadow-lg mb-4 transition duration-300">
        <i class="bi bi-person-plus-fill"></i> Registrar nuevo visitante
    </a>

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
                    <th class="px-4 py-3 text-center"><i class="bi bi-gear-fill"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="visitantesBody">
                <tr><td colspan="8" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Confirmación -->
<div id="confirmModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
  <div class="modal-content animate-scale">
    <i class="bi bi-exclamation-triangle-fill text-red-500 text-6xl mb-4"></i>
    <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-3">¿Eliminar visitante?</h2>
    <p id="modalDesc" class="text-gray-700 mb-6">Esta acción cambiará el estado del visitante.</p>
    <div class="modal-buttons">
      <button id="cancelBtn" class="btn cancel-btn shadow-lg">Cancelar</button>
      <button id="confirmBtn" class="btn confirm-btn shadow-lg">Eliminar</button>
    </div>
  </div>
</div>

<!-- Toast Notificación -->
<div id="toast" class="toast hidden"></div>

<style>
/* Modal */
.modal {
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease forwards;
}

.modal.hidden {
  display: none;
}

.modal-content {
  background-color: white;
  padding: 40px 50px; /* más grande */
  border-radius: 20px;
  max-width: 480px; /* modal más ancho */
  width: 90%;
  text-align: center;
  box-shadow: 0 20px 50px rgba(0,0,0,0.3); /* sombra más fuerte */
  transform: scale(0.9);
  opacity: 0;
  animation: fadeInScale 0.3s ease forwards;
}

@keyframes fadeIn {
  from { background-color: rgba(0,0,0,0); }
  to { background-color: rgba(0,0,0,0.5); }
}

@keyframes fadeInScale {
  to { transform: scale(1); opacity: 1; }
}

.modal-buttons {
  margin-top: 25px;
  display: flex;
  justify-content: space-around;
}

.btn {
  padding: 14px 30px; /* botones más grandes */
  font-weight: 700;
  font-size: 18px;
  border-radius: 10px;
  cursor: pointer;
  border: none;
  transition: all 0.25s ease;
  user-select: none;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.cancel-btn {
  background-color: #e5e7eb;
  color: #374151;
}

.cancel-btn:hover {
  background-color: #d1d5db;
  box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

.confirm-btn {
  background-color: #ef4444;
  color: white;
  box-shadow: 0 8px 20px rgba(239,68,68,0.6);
}

.confirm-btn:hover {
  background-color: #dc2626;
  box-shadow: 0 10px 30px rgba(220,38,38,0.85);
}

/* Toast */
.toast {
  position: fixed;
  top: 20px;
  right: -300px;
  background-color: #10b981; /* verde */
  color: white;
  padding: 15px 25px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  z-index: 1100;
  transition: right 0.4s ease, opacity 0.4s ease;
}

.toast.show {
  right: 20px;
  opacity: 1;
}

.toast.error {
  background-color: #ef4444; /* rojo */
}
</style>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem('token');
    const tbody = document.getElementById('visitantesBody');
    const errorDiv = document.getElementById('error');
    const baseEditUrl = "{{ url('visitantes') }}";

    if (!token) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    try {
        const response = await fetch('http://localhost:3000/api/visitantes', {
            headers: { 'Authorization': `Bearer ${token}` }
        });

        if (response.status === 401 || response.status === 403) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
            return;
        }

        const data = await response.json();
        tbody.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500 py-4">No tienes visitantes registrados.</td></tr>`;
            return;
        }

        data.forEach((visitante, index) => {
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

            const estadoIcon = visitante.estado === 'activo'
                ? 'bi-toggle-on'
                : 'bi-toggle-off';

            tbody.innerHTML += `
                <tr>
                    <td class="px-4 py-3">${index + 1}</td>
                    <td class="px-4 py-3">${visitante.nombre || ''}</td>
                    <td class="px-4 py-3">${visitante.apellido || ''}</td>
                    <td class="px-4 py-3">${visitante.telefono || ''}</td>
                    <td class="px-4 py-3">${visitante.tipo || ''}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${estadoClass}">
                            <i class="bi ${estadoIcon}"></i> ${visitante.estado || ''}
                        </span>
                    </td>
                    <td class="px-4 py-3 align-top">${vehiculo}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="${baseEditUrl}/${id}/edit"
                           class="inline-flex items-center gap-1 text-sm text-yellow-600 hover:text-yellow-800 font-medium mb-1  transition">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <button onclick="eliminarVisitante('${id}')"
                                class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium mt-1  transition">
                            <i class="bi bi-trash3-fill"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        errorDiv.classList.remove("hidden");
        errorDiv.textContent = "Error al cargar los visitantes.";
    }
});

const modal = document.getElementById('confirmModal');
const cancelBtn = document.getElementById('cancelBtn');
const confirmBtn = document.getElementById('confirmBtn');
const toast = document.getElementById('toast');

let visitanteAEliminar = null;

function showConfirmModal(id) {
  visitanteAEliminar = id;
  modal.classList.remove('hidden');
}

cancelBtn.addEventListener('click', () => {
  visitanteAEliminar = null;
  modal.classList.add('hidden');
});

function showToast(message, isError = false) {
  toast.textContent = message;
  toast.className = 'toast show';
  if (isError) toast.classList.add('error');
  setTimeout(() => { toast.classList.remove('show'); }, 3000);
}

confirmBtn.addEventListener('click', async () => {
  if (!visitanteAEliminar) return;

  modal.classList.add('hidden');
  const token = localStorage.getItem('token');

  try {
    const res = await fetch(`http://localhost:3000/api/visitantes/${visitanteAEliminar}`, {
      method: 'DELETE',
      headers: { 'Authorization': 'Bearer ' + token }
    });

    const result = await res.json();
    if (res.ok) {
      showToast('Visitante eliminado correctamente');
      setTimeout(() => location.reload(), 1000);
    } else {
      showToast('Error: ' + (result.error || 'No se pudo eliminar'), true);
    }
  } catch (err) {
    showToast('Error al eliminar', true);
  }
  visitanteAEliminar = null;
});

function eliminarVisitante(id) {
  if (!localStorage.getItem('token')) {
    showToast('No tienes sesión activa', true);
    return;
  }
  showConfirmModal(id);
}
</script>
@endsection
