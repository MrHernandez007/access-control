@extends('layout.residentes')

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
        <option value="Finalizada">Finalizada</option>
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
                    <th class="px-4 py-3 text-center"><i class="bi bi-tools"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-visitas">
                <tr><td colspan="4" class="text-center text-gray-500 py-4">Cargando visitas...</td></tr>
            </tbody>
        </table>
    </div>
</div>

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

.modal-success {
  position: fixed;
  top: 1rem;
  right: 1rem;
  background: #d1fae5;
  border: 1px solid #34d399;
  color: #065f46;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 1.125rem;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
  z-index: 1100;
}

.modal-success.show {
  opacity: 1;
  pointer-events: auto;
}
</style>

<!-- Modal Confirmación Eliminar -->
<div id="modalConfirmar" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitulo" aria-describedby="modalDescripcion">
  <div class="modal-content">
    <i class="bi bi-exclamation-triangle-fill text-red-600 text-7xl mb-4"></i>
    <h2 id="tituloEliminar" class="text-2xl font-semibold mb-2 text-red-700">Confirmar eliminación</h2>
    <p id="modalDescripcion" class="text-lg font-semibold">¿Seguro que deseas eliminar esta visita? Esta acción no se puede deshacer.</p>
    <div class="modal-buttons">
      <button id="btnCancelar" class="btn cancel-btn" type="button">Cancelar</button>
      <button id="btnConfirmar" class="btn confirm-btn" type="button">Eliminar</button>
    </div>
  </div>
</div>

<!-- Modal Éxito -->
<div id="modalExito" class="modal-success" role="alert" aria-live="assertive" aria-atomic="true">
  <i class="bi bi-check-circle-fill text-green-600" style="font-size: 1.5rem;"></i>
  <span>Visita eliminada correctamente.</span>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const tablaBody = document.getElementById('body-visitas');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroFecha = document.getElementById('filtroFecha');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const token = localStorage.getItem('token');

    // Modales y botones
    const modalConfirmar = document.getElementById('modalConfirmar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnConfirmar = document.getElementById('btnConfirmar');
    const modalExito = document.getElementById('modalExito');

    let idEliminarActual = null;
    let visitasOriginales = [];

    if (!token) {
        tablaBody.innerHTML = `<tr><td colspan="4" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    async function cargarVisitas() {
        try {
            const response = await fetch('http://localhost:3000/api/visitas', {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (!response.ok) {
                tablaBody.innerHTML = `<tr><td colspan="4" class="text-center text-red-500 py-4">Error ${response.status}: ${await response.text()}</td></tr>`;
                return;
            }

            visitasOriginales = await response.json();

            filtrarYMostrar();
        } catch (error) {
            console.error(error);
            tablaBody.innerHTML = `<tr><td colspan="4" class="text-center text-red-500 py-4">Error al cargar visitas.</td></tr>`;
        }
    }

    function filtrarYMostrar() {
        let filtradas = visitasOriginales;

        const estadoFiltro = filtroEstado.value;
        if (estadoFiltro) {
            filtradas = filtradas.filter(v => v.estado === estadoFiltro);
        }

        const fechaFiltro = filtroFecha.value;
        if (fechaFiltro) {
            filtradas = filtradas.filter(v => {
                if (!v.dia_visita) return false;
                const visitaDate = new Date(v.dia_visita);
                if (isNaN(visitaDate)) return false;

                const yyyy = visitaDate.getFullYear();
                const mm = String(visitaDate.getMonth() + 1).padStart(2, '0');
                const dd = String(visitaDate.getDate()).padStart(2, '0');

                const visitaFechaStrLocal = `${yyyy}-${mm}-${dd}`;
                return visitaFechaStrLocal === fechaFiltro;
            });
        }

        if (filtradas.length === 0) {
            tablaBody.innerHTML = `
                <tr><td colspan="4" class="text-center text-gray-500 py-4">
                    <i class="bi bi-info-circle"></i> No hay visitas registradas.
                </td></tr>
            `;
            return;
        }

        tablaBody.innerHTML = '';

        filtradas.forEach(visita => {
            const visitante = visita.visitante_id;
            let nombreCompleto = 'Desconocido';

            if (visitante && typeof visitante === 'object') {
                nombreCompleto = `${visitante.nombre || ''} ${visitante.apellido || ''}`.trim();
                if (!nombreCompleto) nombreCompleto = 'Desconocido';
            } else if (typeof visitante === 'string') {
                nombreCompleto = visitante;
            }

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
                    <td class="px-4 py-3 text-center">
                        <button data-id="${visita._id}"
                                class="btn-eliminar inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium mt-1"
                                title="Eliminar visita">
                            <i class="bi bi-trash3-fill"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });

        // Añadir event listeners para eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                idEliminarActual = e.currentTarget.getAttribute('data-id');
                modalConfirmar.style.display = 'flex';
            });
        });
    }

    btnCancelar.addEventListener('click', () => {
        idEliminarActual = null;
        modalConfirmar.style.display = 'none';
    });

    btnConfirmar.addEventListener('click', async () => {
        if (!idEliminarActual) return;
        try {
            const res = await fetch(`http://localhost:3000/api/visitas/${idEliminarActual}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (res.ok) {
                modalConfirmar.style.display = 'none';
                idEliminarActual = null;
                mostrarExito();
                cargarVisitas();
            } else {
                const err = await res.json();
                alert('Error al eliminar: ' + (err.message || res.statusText));
                modalConfirmar.style.display = 'none';
                idEliminarActual = null;
            }
        } catch (error) {
            alert('Error de red al eliminar la visita.');
            modalConfirmar.style.display = 'none';
            idEliminarActual = null;
        }
    });

    function mostrarExito() {
        modalExito.style.display = 'block';
        setTimeout(() => {
            modalExito.style.display = 'none';
        }, 2500);
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
