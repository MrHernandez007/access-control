@extends('layout.residentes')

@section('titulo','Editar Visitante')
@section('meta-descripcion','Formulario para editar visitante')

@section('contenido')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-sky-700 mb-6 flex items-center gap-2">
        <i class="bi bi-person-badge-fill text-3xl"></i> Editar visitante
    </h2>

    <form id="form-editar-visitante" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" id="visitanteId" name="visitanteId" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach (['nombre', 'apellido', 'telefono'] as $campo)
            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-lines-fill"></i> {{ ucfirst($campo) }}
                </label>
                <input type="text" id="{{ $campo }}" name="{{ $campo }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                       required>
            </div>
            @endforeach

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-fill"></i> Tipo
                </label>
                <select id="tipo" name="tipo"
                        class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                        required>
                    <option value="visita">Visita</option>
                    <option value="proveedor">Proveedor</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-toggle-on"></i> Estado
                </label>
                <select id="estado" name="estado"
                        class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <hr class="my-6 border-gray-300">

        <h3 class="text-lg font-semibold text-gray-800 mb-3">Datos del vehículo (opcional)</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach (['placa', 'color', 'modelo', 'tipo'] as $vCampo)
            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    {{ ucfirst($vCampo) }}
                </label>
                <input type="text" id="vehiculo_{{ $vCampo }}" name="vehiculo[{{ $vCampo }}]"
                       class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
            </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="/visitantes"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded shadow transition">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded shadow transition">
                <i class="bi bi-save"></i> Guardar cambios
            </button>
        </div>


        <!-- Modal de éxito -->
<div id="successModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="successTitle" aria-describedby="successDesc">
  <div class="modal-content animate-scale-fade">
    <i class="bi bi-check-circle-fill text-green-500 text-6xl mb-4"></i>
    <h2 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">¡Éxito!</h2>
    <p id="successDesc" class="text-gray-600 mb-6">Visitante actualizado correctamente.</p>
    <div class="modal-buttons" style="justify-content: center;">
<button id="successBtn" type="button" class="btn confirm-btn">Aceptar</button>
    </div>
  </div>
</div>
    </form>
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

/* Opcional: si quieres una clase para agregar animación a modal-content dinámicamente */
.animate-scale-fade {
  animation: scaleFadeIn 0.3s forwards cubic-bezier(0.4, 0, 0.2, 1);
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
  box-shadow: 0 8px 16px rgba(47, 133, 90, 0.7);
}

</style>









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
  box-shadow: 0 8px 16px rgba(47, 133, 90, 0.7);
}
</style>





<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Sesión expirada. Inicia sesión nuevamente.');
        window.location.href = '/login/residente';
        return;
    }

    const pathParts = window.location.pathname.split('/');
    const visitanteId = pathParts[pathParts.length - 2];
    document.getElementById('visitanteId').value = visitanteId;

    // Obtener datos del visitante
    fetch(`http://localhost:3000/api/visitantes/${visitanteId}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => {
        if (!res.ok) throw new Error('Error al obtener datos del visitante');
        return res.json();
    })
    .then(data => {
        const visitante = data;

        document.getElementById('nombre').value = visitante.nombre || '';
        document.getElementById('apellido').value = visitante.apellido || '';
        document.getElementById('telefono').value = visitante.telefono || '';
        document.getElementById('tipo').value = visitante.tipo || 'visita';
        document.getElementById('estado').value = visitante.estado || 'activo';

        if (visitante.vehiculo) {
            document.getElementById('vehiculo_placa').value = visitante.vehiculo.placa || '';
            document.getElementById('vehiculo_color').value = visitante.vehiculo.color || '';
            document.getElementById('vehiculo_modelo').value = visitante.vehiculo.modelo || '';
            document.getElementById('vehiculo_tipo').value = visitante.vehiculo.tipo || '';
        }
    })
    .catch(err => {
        alert(err.message);
        window.location.href = '/visitantes';
    });

    const successModal = document.getElementById('successModal');
    const successBtn = document.getElementById('successBtn');

    function showSuccessModal(message) {
      document.getElementById('successDesc').textContent = message;
      successModal.classList.remove('hidden');
      successBtn.focus();
    }

    successBtn.addEventListener('click', () => {
      successModal.classList.add('hidden');
      window.location.href = "/visitantes";
    });

    document.getElementById('form-editar-visitante').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const jsonData = {
            nombre: formData.get('nombre'),
            apellido: formData.get('apellido'),
            telefono: formData.get('telefono'),
            tipo: formData.get('tipo'),
            estado: formData.get('estado'),
            vehiculo: {
                placa: formData.get('vehiculo[placa]'),
                color: formData.get('vehiculo[color]'),
                modelo: formData.get('vehiculo[modelo]'),
                tipo: formData.get('vehiculo[tipo]')
            }
        };

        try {
            const response = await fetch(`http://localhost:3000/api/visitantes/${visitanteId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(jsonData)
            });

            const result = await response.json();

            if (response.ok) {
                showSuccessModal('Visitante actualizado correctamente');
            } else {
                alert(result.error || 'Error al actualizar visitante');
            }
        } catch (error) {
            alert('Error de red al actualizar visitante');
            console.error(error);
        }
    });
});
</script>
@endsection
