@extends('layout.admins')

@section('titulo','Editar Administrador')
@section('meta-descripcion','Formulario para editar administrador')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2" style="font-size: 30px">
            <i class="bi bi-person-check-fill text-sky-700 text-3xl"></i>
            Editar administrador
        </h1>

        <a href="/administradores" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg shadow">
            <i class="bi bi-arrow-left-circle-fill"></i> Regresar
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
        <form id="form-editar-admin" enctype="multipart/form-data">
            <input type="hidden" id="adminId" name="adminId" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @foreach (['nombre', 'apellido', 'usuario', 'correo', 'telefono'] as $campo)
                <div>
                    <label for="{{ $campo }}" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-person-circle mr-2"></i>{{ ucfirst($campo) }}
                    </label>
                    <input type="text" name="{{ $campo }}" id="{{ $campo }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                           required>
                </div>
                @endforeach

                <div>
                    <label for="contrasena" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-shield-lock-fill mr-2"></i>Contraseña (opcional)
                    </label>
                    <input type="password" name="contrasena" id="contrasena"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" >
                </div>

                <div>
                    <label class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-image-fill mr-2"></i>Imagen actual
                    </label>
                    <img id="imagenActual" src="" width="100" class="mb-2 rounded hidden" alt="Imagen actual">
                    <p id="noImagen" class="text-gray-500 italic">No hay imagen</p>
                </div>

                <div>
                    <label for="imagen" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-upload mr-2"></i>Nueva Imagen
                    </label>
                    <input type="file" name="imagen" id="imagen"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-person-gear mr-2"></i>Tipo
                    </label>
                    <select name="tipo" id="tipo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" required>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-toggle-on mr-2"></i>Estado
                    </label>
                    <select name="estado" id="estado"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6">
    <a href="/administradores" 
       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow">
        <i class="bi bi-x-circle-fill"></i> Cancelar
    </a>
    <button type="button" id="updateBtn"
            class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg shadow">
        <i class="bi bi-pencil-square"></i> Actualizar administrador
    </button>
</div>

<!-- Modal de éxito -->
<div id="successModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="successTitle" aria-describedby="successDesc">
  <div class="modal-content animate-scale-fade">
    <i class="bi bi-check-circle-fill text-green-500 text-6xl mb-4"></i>
    <h2 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">¡Éxito!</h2>
    <p id="successDesc" class="text-gray-600 mb-6">Administrador actualizado correctamente.</p>
    <div class="modal-buttons" style="justify-content: center;">
<button id="successBtn" type="button" class="btn confirm-btn">Aceptar</button>
    </div>
  </div>
</div>

        </form>
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
  from { background-color: rgba(0, 0, 0, 0); }
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
  background-color: #38a169; /* verde */
  color: white;
  box-shadow: 0 6px 12px rgba(56, 161, 105, 0.5);
}

.confirm-btn:hover,
.confirm-btn:focus {
  background-color: #2f855a;
  outline: none;
  box-shadow: 0 8px 16px rgba(254, 255, 254, 0.7);
}
</style>


<script>

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('No tienes sesión activa. Por favor inicia sesión.');
        window.location.href = '/login/admin';
        return;
    }

    const pathParts = window.location.pathname.split('/');
    const adminId = pathParts[pathParts.length - 2];
    if (!adminId) {
        alert('ID de administrador no válido.');
        window.location.href = '/administradores';
        return;
    }
    document.getElementById('adminId').value = adminId;

    fetch(`http://localhost:3000/api/administradores/${adminId}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => {
        if (!res.ok) throw new Error('Error al obtener datos del administrador');
        return res.json();
    })
    .then(data => {
        const admin = data.data || data;
        document.getElementById('nombre').value = admin.nombre || '';
        document.getElementById('apellido').value = admin.apellido || '';
        document.getElementById('usuario').value = admin.usuario || '';
        document.getElementById('correo').value = admin.correo || '';
        document.getElementById('telefono').value = admin.telefono || '';
        document.getElementById('tipo').value = admin.tipo || 'admin';
        document.getElementById('estado').value = admin.estado || 'activo';

        if (admin.imagen) {
            const rutaLimpia = admin.imagen.replace(/^public[\\/]/, '');
            const imagenURL = `http://localhost:3000/${rutaLimpia.replace(/\\/g, '/')}`;
            document.getElementById('imagenActual').src = imagenURL;
            document.getElementById('imagenActual').classList.remove('hidden');
            document.getElementById('noImagen').classList.add('hidden');
        }
    })
    .catch(err => {
        console.error(err);
        alert('No se pudo cargar el administrador.');
        window.location.href = '/administradores';
    });

    // Actualizar admin
    document.getElementById('updateBtn').addEventListener('click', async () => {
        const form = document.getElementById('form-editar-admin');
        const formData = new FormData(form);

        try {
            const response = await fetch(`http://localhost:3000/api/administradores/${adminId}`, {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + token },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                document.getElementById('successModal').classList.remove('hidden');
            } else {
                alert(result.error || result.message || 'Error al actualizar administrador');
            }
        } catch (error) {
            console.error(error);
            alert('Ocurrió un error al enviar la solicitud');
        }
    });

    // Redirección al aceptar
    const successBtn = document.getElementById('successBtn');
    if (successBtn) {
        successBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Botón aceptar clicado, redirigiendo...");
            window.location.href = "/administradores";
        });
    } else {
        console.error("No se encontró el botón successBtn en el DOM");
    }
});



</script>

@endsection
