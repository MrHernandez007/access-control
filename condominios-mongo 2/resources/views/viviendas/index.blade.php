@extends('layout.admins')

@section('titulo', 'Viviendas')
@section('meta-descripcion', 'Lista de Viviendas')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-house-fill text-sky-700 text-3xl"></i>
        Lista de Viviendas
    </h1>

    <a href="/viviendas/create" style="font-size: large"
       class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg shadow mb-4">
        <i class="bi bi-plus-square-fill"></i> Crear nueva vivienda
    </a>

    <div id="mensaje" class="mb-4"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-viviendas">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left font-medium"><i class="bi bi-hash"></i></th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-123"></i> Número</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-house-door-fill"></i> Tipo</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-signpost-2-fill"></i> Calle</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-power"></i> Estado</th>
                    <th class="px-4 py-3 text-center"><i class="bi bi-tools"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-viviendas">
                <tr><td colspan="6" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div id="modalEliminar" class="modal hidden" aria-hidden="true" aria-labelledby="modalEliminarLabel" role="dialog" aria-modal="true">
    <div class="modal-content">
        <i class="bi bi-exclamation-triangle-fill text-red-600 text-7xl mb-4"></i>
        <h2 id="tituloEliminar" class="text-2xl font-semibold mb-2 text-red-700">Confirmar eliminación</h2>
        <p id="modalEliminarLabel" class="text-lg mb-6">¿Estás seguro que deseas eliminar esta vivienda?</p>
        <div class="modal-buttons">
            <button id="btnCancelar" class="btn cancel-btn">Cancelar</button>
            <button id="btnConfirmar" class="btn confirm-btn">Eliminar</button>
        </div>
    </div>
</div>

<div id="modalMensaje" class="modal hidden">
    <div class="modal-content">
        <p id="mensajeTexto" class="text-lg mb-6"></p>
        <div class="modal-buttons">
            <button id="btnCerrarMensaje" class="btn confirm-btn">Cerrar</button>
        </div>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tablaBody = document.getElementById('body-viviendas');
    const modalEliminar = document.getElementById('modalEliminar');
    const btnConfirmar = document.getElementById('btnConfirmar');
    const btnCancelar = document.getElementById('btnCancelar');
    const modalMensaje = document.getElementById('modalMensaje');
    const mensajeTexto = document.getElementById('mensajeTexto');
    const btnCerrarMensaje = document.getElementById('btnCerrarMensaje');
    let viviendaIdAEliminar = null;

    const token = localStorage.getItem('token');
    if (!token) {
        tablaBody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    // Función para mostrar el modal de mensaje
    function mostrarMensaje(mensaje) {
        mensajeTexto.textContent = mensaje;
        modalMensaje.classList.remove('hidden');
    }

    // Función para recargar la tabla de viviendas
    async function cargarViviendas() {
        try {
            const res = await fetch('http://localhost:3000/api/viviendas', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (res.status === 401 || res.status === 403) {
                tablaBody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
                return;
            }

            const viviendas = await res.json();
            if (!Array.isArray(viviendas)) throw new Error("Respuesta inválida");

            if (viviendas.length === 0) {
                tablaBody.innerHTML = `
                    <tr><td colspan="6" class="text-center text-gray-500 py-4">
                        <i class="bi bi-info-circle"></i> No hay viviendas registradas.
                    </td></tr>
                `;
                return;
            }

            tablaBody.innerHTML = '';
            viviendas.forEach((vivienda, index) => {
                const esActivo = vivienda.estado && vivienda.estado.toLowerCase() === 'activo';
                
                const estadoTexto = esActivo ? 'Activo' : 'Inactivo';
                const estadoClase = esActivo ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700';
                const estadoIcono = esActivo ? 'bi-toggle-on' : 'bi-toggle-off';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-3 text-gray-700">${index + 1}</td>
                    <td class="px-4 py-3 text-gray-800">${vivienda.numero || ''}</td>
                    <td class="px-4 py-3 text-gray-800">${vivienda.tipo || ''}</td>
                    <td class="px-4 py-3 text-gray-700">${vivienda.calle || ''}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${estadoClase}">
                            <i class="bi ${estadoIcono}"></i> ${estadoTexto}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="/viviendas/${vivienda._id}/edit"
                           class="inline-flex items-center gap-1 text-sm text-yellow-600 hover:text-yellow-800 font-medium mb-1">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <button class="btn-eliminar inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium mt-1" data-id="${vivienda._id}">
                            <i class="bi bi-trash3-fill"></i> Eliminar
                        </button>
                    </td>
                `;
                tablaBody.appendChild(row);
            });
            
            // Agrega eventos de clic a los botones de eliminar
            document.querySelectorAll('.btn-eliminar').forEach(button => {
                button.addEventListener('click', (e) => {
                    viviendaIdAEliminar = e.target.closest('button').dataset.id;
                    modalEliminar.classList.remove('hidden');
                });
            });

        } catch (error) {
            console.error(error);
            tablaBody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-4">Error al cargar datos.</td></tr>`;
        }
    }
    
    // Carga las viviendas al iniciar
    cargarViviendas();

    // Evento para confirmar la eliminación
    btnConfirmar.addEventListener('click', async () => {
        modalEliminar.classList.add('hidden');
        if (!viviendaIdAEliminar) return;
        
        try {
            const res = await fetch(`http://localhost:3000/api/viviendas/${viviendaIdAEliminar}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            const result = await res.json();
            if (res.ok) {
                mostrarMensaje('Vivienda eliminada correctamente.');
                
            } else {
                mostrarMensaje('Error: ' + (result.error || 'No se pudo eliminar'));
            }
        } catch (err) {
            console.error(err);
            mostrarMensaje('Error al eliminar');
        }
    });

    // Evento para cancelar la eliminación
    btnCancelar.addEventListener('click', () => {
        modalEliminar.classList.add('hidden');
        viviendaIdAEliminar = null;
    });

    // Evento para cerrar el modal de mensaje y recargar la página
    btnCerrarMensaje.addEventListener('click', () => {
        modalMensaje.classList.add('hidden');
        location.reload();
    });
});
</script>
@endsection