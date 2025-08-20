@extends('layout.admins')

@section('titulo', 'Crear Vivienda')
@section('meta-descripcion', 'Formulario para crear nueva vivienda')

@section('contenido')
<br><br>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
    <i class="bi bi-person-plus-fill text-sky-700 text-3xl"></i>
    Crear Nueva Vivienda
  </h1>

  <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
    <form id="formVivienda">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
          <label for="numero" class="block text-sm font-medium text-sky-700 mb-1">Número</label>
          <input type="text" name="numero" id="numero" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                 required>
        </div>

        <div>
          <label for="tipo" class="block text-sm font-medium text-sky-700 mb-1">Tipo</label>
          <input type="text" name="tipo" id="tipo" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                 required>
        </div>

        <div>
          <label for="calle" class="block text-sm font-medium text-sky-700 mb-1">Calle</label>
          <input type="text" name="calle" id="calle" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                 required>
        </div>
        <!-- Dentro del div grid, por ejemplo al final, agrega: -->
        <div>
        <label for="residente_id" class="block text-sm font-medium text-sky-700 mb-1">Residente</label>
          <select name="residente_id" id="residente_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">
    <option value="">Seleccione un residente</option>
    <!-- Opciones cargadas dinámicamente -->
  </select>
</div>

      </div>

      <div class="flex justify-end gap-4 mt-6">
        <button type="button" id="btnCancelar" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow">
          <i class="bi bi-x-circle-fill"></i> Cancelar
        </button>
        <button type="submit" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg shadow">
          <i class="bi bi-save-fill"></i> Guardar vivienda
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('btnCancelar').addEventListener('click', () => {
    window.location.href = '/viviendas';
  });

  document.getElementById('formVivienda').addEventListener('submit', async (e) => {
    e.preventDefault();

    const token = localStorage.getItem('token');

    if (!token) {
      alert('No autenticado. Inicia sesión nuevamente.');
      return;
    }

    const data = {
      numero: document.getElementById('numero').value.trim(),
      tipo: document.getElementById('tipo').value.trim(),
      calle: document.getElementById('calle').value.trim(),
      residente_id: document.getElementById('residente_id').value
    };

    try {
      const response = await fetch('http://localhost:3000/api/viviendas', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(data)
      });

      const text = await response.text();
      let result;
      try {
        result = JSON.parse(text);
      } catch {
        console.error('Respuesta inválida del servidor:', text);
        alert('Error inesperado. Revisa la consola.');
        return;
      }

      if (!response.ok) {
        alert('Error: ' + (result.message || 'Error desconocido'));
        return;
      }

      alert('Vivienda creada correctamente');
      window.location.href = '/viviendas';

    } catch (error) {
      alert('Error de conexión con el servidor');
      console.error(error);
    }
  });
  async function cargarResidentes() {
  const select = document.getElementById('residente_id');
  const token = localStorage.getItem('token');

  if (!token) {
    alert('No tienes sesión activa. Por favor inicia sesión.');
    return;
  }

  try {
    const res = await fetch('http://localhost:3000/api/residentes', {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    if (!res.ok) {
      alert('Error al cargar residentes');
      return;
    }

    const residentes = await res.json();

    residentes.forEach(r => {
      const nombreCompleto = `${r.nombre || ''} ${r.apellido || ''}`.trim();
      const option = document.createElement('option');
      option.value = r._id || r.id;
      option.textContent = nombreCompleto || 'Sin nombre';
      select.appendChild(option);
    });

  } catch (error) {
    console.error('Error al obtener residentes:', error);
    alert('Error al cargar residentes');
  }
}

// Llamamos la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarResidentes);

</script>
@endsection
