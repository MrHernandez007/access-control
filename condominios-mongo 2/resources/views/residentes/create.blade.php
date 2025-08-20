@extends('layout.admins')

@section('titulo','Crear Residente')
@section('meta-descripcion','Formulario para crear nuevo residente')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2" style="font-size: 30px">
            <i class="bi bi-person-plus-fill text-sky-700 text-3xl"></i>
            Crear nuevo residente
        </h1>
        
        <a href="/residentes" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg shadow">
            <i class="bi bi-arrow-left-circle-fill"></i> Regresar
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
        <form id="residenteForm" enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @foreach (['nombre', 'apellido', 'usuario', 'correo', 'telefono'] as $campo)
                <div>
                    <label for="{{ $campo }}" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-person-circle mr-2"></i>{{ ucfirst($campo) }}
                    </label>
                    <input type="{{ $campo === 'correo' ? 'email' : 'text' }}" name="{{ $campo }}" id="{{ $campo }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                           required>
                </div>
                @endforeach

                <div>
                    <label for="contrasena" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-shield-lock-fill mr-2"></i>Contraseña
                    </label>
                    <input type="password" name="contrasena" id="contrasena"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" required>
                </div>

                <div>
                    <label for="imagen" class="block text-sm font-medium text-sky-700 mb-1">
                        <i class="bi bi-image-fill mr-2"></i>Imagen
                    </label>
                    <input type="file" name="imagen" id="imagen"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500" accept="image/*">
                    <!-- Aquí irá la vista previa -->
                    <img id="previewImagen" src="" alt="Vista previa" class="mt-2 rounded-md shadow" style="max-width: 150px; display: none;">
                </div>

                <div>
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
                    <i class="bi bi-save-fill"></i> Guardar residente
                </button>
            </div>
        </form>
    </div>
</div>

<div id="alertaExito" class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none hidden">
  <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full text-center relative pointer-events-auto">
    <i class="bi bi-check-circle-fill text-green-600 text-7xl mb-4"></i>
    <h2 class="text-2xl font-semibold mb-2 text-green-700">Residente creado exitosamente</h2>
    <button id="cerrarAlerta" class="mt-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow">
      Aceptar
    </button>
  </div>
</div>



<script>
document.getElementById('imagen').addEventListener('change', function() {
    const preview = document.getElementById('previewImagen');
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});

document.getElementById('residenteForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const token = localStorage.getItem('token');
  if (!token) {
    alert('No tienes sesión activa. Por favor inicia sesión.');
    return;
  }

  const formData = new FormData(this);

  try {
    const response = await fetch('http://localhost:3000/api/residentes', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + token
        // NO poner Content-Type aquí con FormData
      },
      body: formData
    });

    const data = await response.json();

    if (response.ok) {
      // Mostrar alerta con estilo
      const alerta = document.getElementById('alertaExito');
      alerta.classList.remove('hidden');

      // Botón cerrar alerta
      document.getElementById('cerrarAlerta').onclick = () => {
        alerta.classList.add('hidden');
        window.location.href = "/residentes";
      }
    } else {
      alert('Error: ' + (data.error || JSON.stringify(data)));
    }
  } catch (error) {
    alert('Error en la solicitud: ' + error.message);
    console.error('Error en fetch:', error);
  }
});
</script>
@endsection
