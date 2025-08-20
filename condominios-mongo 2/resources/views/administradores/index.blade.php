@extends('layout.admins')

@section('titulo', 'Lista de Administradores')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-shield-lock-fill text-sky-700 text-3xl"></i>
        Lista de Administradores
    </h1>

    <a href="/administradores/create" style="font-size: large"
       class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg shadow mb-4">
        <i class="bi bi-person-plus-fill"></i> Crear nuevo administrador
    </a>

    <div id="mensaje" class="mb-4"></div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="tabla-admins">
            <thead class="bg-sky-100 text-sky-800 text-base">
                <tr>
                    <th class="px-4 py-3 text-left font-medium"><i class="bi bi-hash"></i></th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Nombre</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-fill"></i> Apellido</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-person-badge-fill"></i> Usuario</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-envelope-fill"></i> Correo</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-telephone-fill"></i> Teléfono</th>
                    <th class="px-4 py-3 text-left"><i class="bi bi-power"></i> Estado</th>
                    <th class="px-4 py-3 text-center"><i class="bi bi-tools"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-base" id="body-admins">
                <tr><td colspan="8" class="text-center text-gray-500 py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de confirmación -->
<div id="confirmModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="confirmTitle" aria-describedby="confirmDesc">
  <div class="modal-content animate-scale-fade">
    <h2 id="confirmTitle" class="text-2xl font-bold text-gray-800 mb-4">Confirmar eliminación</h2>
    <p id="confirmDesc" class="text-gray-600 mb-6">¿Seguro que deseas eliminar este administrador?</p>
    <div class="modal-buttons" style="justify-content: center; gap: 12px;">
      <button id="confirmCancelBtn" class="btn cancel-btn">Cancelar</button>
      <button id="confirmOkBtn" class="btn confirm-btn">Eliminar</button>
    </div>
  </div>
</div>

<!-- Modal de éxito -->
<div id="successModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="successTitle" aria-describedby="successDesc">
  <div class="modal-content animate-scale-fade">
    <i class="bi bi-check-circle-fill text-green-500 text-6xl mb-4"></i>
    <h2 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">¡Éxito!</h2>
    <p id="successDesc" class="text-gray-600 mb-6">Administrador eliminado correctamente.</p>
    <div class="modal-buttons" style="justify-content: center;">
      <button id="successBtn" class="btn confirm-btn">Aceptar</button>
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
    const tablaBody = document.getElementById('body-admins');
    const mensajeDiv = document.getElementById('mensaje');

    const token = localStorage.getItem('token');
    if (!token) {
        tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">No tienes sesión activa.</td></tr>`;
        return;
    }

    try {
        const res = await fetch('http://localhost:3000/api/administradores', {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (res.status === 401 || res.status === 403) {
            tablaBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Acceso no autorizado.</td></tr>`;
            return;
        }

        const admins = await res.json();

        if (!Array.isArray(admins)) throw new Error("Respuesta inválida");

        if (admins.length === 0) {
            tablaBody.innerHTML = `
                <tr><td colspan="8" class="text-center text-gray-500 py-4">
                    <i class="bi bi-info-circle"></i> No hay administradores registrados.
                </td></tr>
            `;
            return;
        }

        tablaBody.innerHTML = '';
        admins.forEach((admin, index) => {
            const id = admin._id || admin.id || '';

            // Limpieza y corrección de ruta igual que en dashboard
            let rutaImg = admin.imagen || admin.img || '';
            rutaImg = rutaImg.replace(/^public[\/\\]/, '').replace(/\\/g, '/');

            const imagenPath = rutaImg
                ? `http://localhost:3000/${rutaImg}`
                : 'https://via.placeholder.com/150';

            tablaBody.innerHTML += `
                <tr>
                    <td class="px-4 py-3 text-gray-700">${index + 1}</td>
                    <td class="px-4 py-3 text-gray-800 flex items-center gap-2">
                        <img src="${imagenPath}" alt="Foto de ${admin.nombre || ''}" class="w-8 h-8 rounded-full object-cover border border-gray-300" />
                        <span>${admin.nombre || ''}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-800">${admin.apellido || ''}</td>
                    <td class="px-4 py-3 text-gray-700">${admin.usuario || ''}</td>
                    <td class="px-4 py-3 text-gray-700">${admin.correo || ''}</td>
                    <td class="px-4 py-3 text-gray-700">${admin.telefono || ''}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full ${
                            admin.estado === 'activo'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-200 text-gray-700'
                        }">
                            <i class="bi ${
                                admin.estado === 'activo'
                                    ? 'bi-toggle-on'
                                    : 'bi-toggle-off'
                            }"></i> ${admin.estado || ''}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        ${id ? `
                        <a href="/administradores/${id}/edit"
                           class="inline-flex items-center gap-1 text-sm text-yellow-600 hover:text-yellow-800 font-medium mb-1">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        ` : ''}
                        <button onclick="eliminarAdmin('${id}')"
                                class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium mt-1">
                            <i class="bi bi-trash3-fill"></i> Eliminar
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

let adminIdToDelete = null;

function eliminarAdmin(id) {
    adminIdToDelete = id;
    document.getElementById('confirmModal').classList.remove('hidden');
}

document.getElementById('confirmCancelBtn').addEventListener('click', () => {
    adminIdToDelete = null;
    document.getElementById('confirmModal').classList.add('hidden');
});

document.getElementById('confirmOkBtn').addEventListener('click', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('No tienes sesión activa');
        document.getElementById('confirmModal').classList.add('hidden');
        return;
    }
    if (!adminIdToDelete) return;

    try {
        const res = await fetch(`http://localhost:3000/api/administradores/${adminIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        const result = await res.json();
        document.getElementById('confirmModal').classList.add('hidden');

        if (res.ok) {
            document.getElementById('successModal').classList.remove('hidden');
            adminIdToDelete = null;
        } else {
            alert('Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (err) {
        alert('Error al eliminar');
        console.error(err);
        document.getElementById('confirmModal').classList.add('hidden');
    }
});

document.getElementById('successBtn').addEventListener('click', () => {
    document.getElementById('successModal').classList.add('hidden');
    location.reload();
});
</script>
@endsection
