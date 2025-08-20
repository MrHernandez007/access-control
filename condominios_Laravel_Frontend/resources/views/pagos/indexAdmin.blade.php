@extends('layout.admins')

@section('titulo', 'Pagos - Admin')
@section('meta-descripcion', 'Listado de pagos registrados')

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

/* Estilo especial para botón confirmar pago (verde) */
#modalConfirmarPago .confirm-btn {
  background-color: #16a34a; /* verde para confirmar pago */
  box-shadow: 0 6px 12px rgba(22, 163, 74, 0.5);
}
#modalConfirmarPago .confirm-btn:hover,
#modalConfirmarPago .confirm-btn:focus {
  background-color: #15803d;
  box-shadow: 0 8px 16px rgba(21, 128, 61, 0.7);
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-cash-stack text-sky-700 text-3xl"></i>
        Pagos registrados
    </h1>

    <div id="mensaje" class="mb-4"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-pagos">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left font-medium"><i class="bi bi-hash"></i></th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Residente</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-house-door-fill"></i> Vivienda ID</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-journal-text"></i> Concepto</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-currency-dollar"></i> Monto</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-calendar-check"></i> Fecha de pago</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-power"></i> Estado</th>
                    <th class="px-4 py-3 text-center font-medium">
                        <i class="bi bi-tools"></i> Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-pagos">
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
    <p id="mensajeEliminar" class="mb-4">¿Seguro que deseas eliminar este residente?</p>
    <div class="modal-buttons">
      <button id="btnCancelarEliminar" class="btn cancel-btn">Cancelar</button>
      <button id="btnConfirmarEliminar" class="btn confirm-btn">Eliminar</button>
    </div>
  </div>
</div>

<!-- Modal alerta éxito eliminar -->
<div id="modalExitoEliminar" class="modal hidden" role="alert" aria-modal="true" aria-labelledby="tituloExitoEliminar" aria-describedby="mensajeExitoEliminar">
  <div class="modal-content">
    <i class="bi bi-check-circle-fill text-green-600 text-7xl mb-4"></i>
    <h2 id="tituloExitoEliminar" class="text-2xl font-semibold mb-2 text-green-700">Pago eliminado correctamente</h2>
    <p id="mensajeExitoEliminar" class="mb-4">El registro ha sido eliminado.</p>
    <div class="modal-buttons">
      <button id="btnCerrarExitoEliminar" class="btn confirm-btn">Aceptar</button>
    </div>
  </div>
</div>

<!-- Modal confirmación pago -->
<div id="modalConfirmarPago" class="modal hidden" role="alertdialog" aria-modal="true" aria-labelledby="tituloConfirmarPago" aria-describedby="mensajeConfirmarPago">
  <div class="modal-content">
    <i class="bi bi-credit-card-2-front-fill text-green-600 text-7xl mb-4"></i>
    <h2 id="tituloConfirmarPago" class="text-2xl font-semibold mb-2 text-green-700">Confirmar pago</h2>
    <p id="mensajeConfirmarPago" class="mb-4">¿Seguro que deseas marcar este pago como pagado?</p>
    <div class="modal-buttons">
      <button id="btnCancelarPago" class="btn cancel-btn">Cancelar</button>
      <button id="btnConfirmarPago" class="btn confirm-btn">Confirmar</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tablaBody = document.getElementById('body-pagos');
    const mensajeDiv = document.getElementById('mensaje');
    const token = localStorage.getItem('token');

    if (!token) {
        tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    function getEstadoColor(estado) {
        switch (estado) {
            case 'pagado': return 'bg-green-100 text-green-700';
            case 'pendiente': return 'bg-yellow-100 text-yellow-700';
            case 'vencido': return 'bg-red-100 text-red-700';
            case 'anulado': return 'bg-gray-100 text-gray-700';
            default: return 'bg-gray-200 text-gray-700';
        }
    }

    function getEstadoIcon(estado) {
        switch (estado) {
            case 'pagado': return 'bi-toggle-on';
            case 'pendiente': return 'bi-clock';
            case 'vencido': return 'bi-exclamation-triangle';
            case 'anulado': return 'bi-toggle-off';
            default: return 'bi-info-circle';
        }
    }

    try {
        const res = await fetch('http://localhost:3000/api/pagos', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (res.status === 401 || res.status === 403) {
            tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
            return;
        }

        const pagos = await res.json();

        if (!Array.isArray(pagos)) throw new Error("Respuesta inválida");

        if (pagos.length === 0) {
            tablaBody.innerHTML = `
                <tr><td colspan="8" class="text-center text-gray-500 py-4">
                    <i class="bi bi-info-circle"></i> No hay pagos registrados.
                </td></tr>
            `;
            return;
        }

        tablaBody.innerHTML = '';
        pagos.forEach((pago, index) => {
            const id = pago._id || pago.id || '';
            const estadoColor = getEstadoColor(pago.estado);
            const estadoIcon = getEstadoIcon(pago.estado);

            tablaBody.innerHTML += `
                <tr>
                    <td class="px-4 py-3 text-gray-700">${index + 1}</td>
                    <td class="px-4 py-3 text-gray-800">${pago.residente_id?.nombre || 'Sin nombre'}</td>
                    <td class="px-4 py-3 text-gray-800">${pago.vivienda_id || 'N/A'}</td>
                    <td class="px-4 py-3 text-gray-700">${pago.concepto || ''}</td>
                    <td class="px-4 py-3 text-gray-700">$${pago.monto?.toFixed(2) || '0.00'}</td>
                    <td class="px-4 py-3 text-gray-700">${pago.fecha_pago ? new Date(pago.fecha_pago).toLocaleDateString() : ''}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${estadoColor}">
                            <i class="bi ${estadoIcon}"></i> ${pago.estado || ''}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center space-x-2">
                        ${pago.estado !== 'pagado' ? `
                        <button 
                            onclick="mostrarConfirmarPago('${id}')"
                            class="inline-flex items-center gap-1 text-green-600 hover:text-green-700 font-semibold cursor-pointer bg-transparent border-0 p-0"
                            title="Marcar como pagado"
                        >
                            <i class="bi bi-key"></i> Pagar
                        </button>` : `
                        <span class="text-gray-400 italic">Ya pagado</span>`}
                        <button 
                            onclick="confirmarEliminar('${id}')" 
                            class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 font-semibold cursor-pointer bg-transparent border-0 p-0"
                            title="Eliminar pago">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        console.error(error);
        tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Error al cargar datos.</td></tr>`;
    }
});

// Modal Confirmar Pago
const modalConfirmarPago = document.getElementById('modalConfirmarPago');
const btnConfirmarPago = document.getElementById('btnConfirmarPago');
const btnCancelarPago = document.getElementById('btnCancelarPago');

let idPagoMarcar = null;

function mostrarConfirmarPago(id) {
  idPagoMarcar = id;
  modalConfirmarPago.classList.remove('hidden');
}

btnCancelarPago.addEventListener('click', () => {
  modalConfirmarPago.classList.add('hidden');
  idPagoMarcar = null;
});

btnConfirmarPago.addEventListener('click', async () => {
  if (!idPagoMarcar) return;

  const token = localStorage.getItem('token');
  if (!token) {
    alert('No tienes sesión activa.');
    modalConfirmarPago.classList.add('hidden');
    return;
  }

  try {
    const res = await fetch(`http://localhost:3000/api/pagos/${idPagoMarcar}`, {
      method: 'PUT',
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ estado: 'pagado' })
    });

    if (!res.ok) {
      const errorText = await res.text();
      throw new Error('Error al marcar como pagado: ' + errorText);
    }

    modalConfirmarPago.classList.add('hidden');
    location.reload();
  } catch (error) {
    alert(error.message);
    modalConfirmarPago.classList.add('hidden');
  }
});

// Modal eliminar ya existente (no modificado)
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
        const res = await fetch(`http://localhost:3000/api/pagos/${idEliminar}`, {
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
