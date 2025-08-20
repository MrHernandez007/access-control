@extends('layout.admins')

@section('titulo','Editar Residente')
@section('meta-descripcion','Formulario para editar residente')

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
  background-color: #38a169; /* verde */
  color: white;
  box-shadow: 0 6px 12px rgba(56, 161, 105, 0.5);
}

.confirm-btn:hover,
.confirm-btn:focus {
  background-color: #2f855a;
  outline: none;
  box-shadow: 0 8px 16px rgba(56, 161, 105, 0.7);
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2" style="font-size: 30px">
            <i class="bi bi-person-lines-fill text-sky-700 text-3xl"></i>
            Editar residente
        </h1>

        <a href="/residentes"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg shadow">
            <i class="bi bi-arrow-left-circle-fill"></i> Regresar
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
        <form id="formEditarResidente" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <input type="hidden" id="_id" name="_id">

                @foreach (['nombre', 'apellido', 'usuario', 'correo', 'telefono'] as $campo)
                <div>
                    <label for="{{ $campo }}" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-person-circle mr-2"></i>{{ ucfirst($campo) }}
                    </label>
                    <input type="{{ $campo === 'correo' ? 'email' : 'text' }}" 
                           name="{{ $campo }}" id="{{ $campo }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" required>
                </div>
                @endforeach

                <div class="md:col-span-2">
                    <label for="contrasena" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-shield-lock-fill mr-2"></i>Contraseña <small class="text-gray-500">(dejar vacía para no cambiar)</small>
                    </label>
                    <input type="password" name="contrasena" id="contrasena"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" >
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-sky-700 mb-1">Imagen actual</label>
                    <div class="mb-3">
                        <img id="imagenActual" src="" alt="Imagen actual" class="w-24 rounded-md shadow hidden">
                        <span id="noImagen" class="text-gray-500">No hay imagen</span>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="imagen" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-image-fill mr-2"></i>Nueva Imagen
                    </label>
                    <input type="file" name="imagen" id="imagen"
                           class="w-full text-gray-700">
                </div>

                <div class="md:col-span-2">
                    <label for="estado" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-toggle-on mr-2"></i>Estado
                    </label>
                    <select name="estado" id="estado" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <a href="/residentes"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow">
                    <i class="bi bi-x-circle-fill"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg shadow">
                    <i class="bi bi-save-fill"></i> Actualizar residente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal alerta éxito estilo personalizado -->
<div id="alertaExito" class="modal hidden" role="alert" aria-modal="true" aria-labelledby="alertaExitoTitulo" aria-describedby="alertaExitoMensaje">
  <div class="modal-content">
    <i class="bi bi-check-circle-fill text-green-600 text-7xl mb-4"></i>
    <h2 id="alertaExitoTitulo" class="text-2xl font-semibold mb-2 text-green-700">Residente actualizado correctamente</h2>
    <p id="alertaExitoMensaje" class="mb-4">Los cambios han sido guardados exitosamente.</p>
    <div class="modal-buttons">
      <button id="cerrarAlerta" class="btn confirm-btn">Aceptar</button>
    </div>
  </div>
</div>

<script>
    const form = document.getElementById('formEditarResidente');
    const pathParts = window.location.pathname.split('/');
    const id = pathParts[pathParts.length - 2]; // ID desde URL
    const token = localStorage.getItem('token');

    const alertaExito = document.getElementById('alertaExito');
    const cerrarAlertaBtn = document.getElementById('cerrarAlerta');

    // Función para cargar datos del residente y llenar el formulario
    async function cargarResidente() {
        try {
            const res = await fetch(`http://localhost:3000/api/residentes/${id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (!res.ok) {
                throw new Error('No se pudo obtener la información del residente');
            }

            const residente = await res.json();

            // Si tu API responde con un objeto dentro de `data`, usa: const residente = data.data;

            document.getElementById('_id').value = residente._id || '';
            document.getElementById('nombre').value = residente.nombre || '';
            document.getElementById('apellido').value = residente.apellido || '';
            document.getElementById('usuario').value = residente.usuario || '';
            document.getElementById('correo').value = residente.correo || '';
            document.getElementById('telefono').value = residente.telefono || '';
            document.getElementById('estado').value = residente.estado || 'activo';

            if (residente.imagen) {
                document.getElementById('imagenActual').src = `http://localhost:3000/uploads/${residente.imagen}`;
                document.getElementById('imagenActual').classList.remove('hidden');
                document.getElementById('noImagen').classList.add('hidden');
            } else {
                document.getElementById('imagenActual').classList.add('hidden');
                document.getElementById('noImagen').classList.remove('hidden');
            }
        } catch (error) {
            alert(error.message);
        }
    }

    cargarResidente();

    // Manejar envío del formulario para actualizar residente
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch(`http://localhost:3000/api/residentes/${id}`, {
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${token}` },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                // Mostrar modal en lugar de alert
                alertaExito.classList.remove('hidden');
            } else {
                alert('Error al actualizar: ' + (result.message || 'Verifica los campos'));
            }
        } catch (error) {
            alert('Error de red: ' + error.message);
        }
    });

    cerrarAlertaBtn.addEventListener('click', () => {
        alertaExito.classList.add('hidden');
        window.location.href = "/residentes";
    });
</script>

@endsection
