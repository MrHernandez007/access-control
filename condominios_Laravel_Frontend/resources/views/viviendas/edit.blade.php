@extends('layout.admins')

@section('titulo', 'Editar Vivienda')
@section('meta-descripcion', 'Editar datos de la vivienda')

@section('contenido')
<br><br>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
        <i class="bi bi-pencil-square text-sky-700 text-3xl"></i>
        Editar Vivienda
    </h1>

    <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
        <form id="formEditarVivienda">
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

                <div>
                    <label for="residente" class="block text-sm font-medium text-sky-700 mb-1">Residente</label>
                    <select name="residente" id="residente"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Cargando residentes...</option>
                    </select>
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-sky-700 mb-1">Estado</label>
                    <select name="estado" id="estado"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                            required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
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
                    <i class="bi bi-save-fill"></i> Actualizar vivienda
                </button>
            </div>
        </form>
    </div>
</div>

<div id="alertaExito" class="modal hidden" role="alert" aria-live="assertive" aria-modal="true" tabindex="-1">
    <div class="modal-content">
        <i class="bi bi-check-circle-fill text-green-600 text-7xl mb-4"></i>
        <h2 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">¡Éxito!</h2>
        <p id="successDesc" class="text-gray-600 mb-6">Vivienda actualizada correctamente</p>
        <div class="modal-buttons">
            <button id="cerrarAlerta" class="btn confirm-btn" type="button">
                Aceptar
            </button>
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
    margin-top: 1.5rem;
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
    const urlSegments = window.location.pathname.split('/');
    const idVivienda = urlSegments[urlSegments.length - 2];
    const token = localStorage.getItem('token');
    const residenteSelect = document.getElementById('residente');
    const alertaExito = document.getElementById('alertaExito');
    const cerrarAlertaBtn = document.getElementById('cerrarAlerta');

    if (!token) {
        alert('No tienes sesión activa. Inicia sesión primero.');
        window.location.href = '/login_admin';
        return;
    }

    // Carga la lista de residentes y selecciona el actual si se pasa su id
    async function cargarResidentes(selectedResidenteId = null) {
        try {
            const res = await fetch('http://localhost:3000/api/residentes', {
                headers: { 'Authorization': 'Bearer ' + token }
            });

            if (!res.ok) {
                residenteSelect.innerHTML = '<option value="">Error al cargar residentes</option>';
                return;
            }

            const residentes = await res.json();

            residenteSelect.innerHTML = '<option value="">Selecciona un residente</option>';
            residentes.forEach(r => {
                const option = document.createElement('option');
                option.value = r._id;
                option.textContent = `${r.nombre} ${r.apellido}`;
                residenteSelect.appendChild(option);
            });

            if (selectedResidenteId) {
                residenteSelect.value = selectedResidenteId;
            }
        } catch (error) {
            console.error(error);
            residenteSelect.innerHTML = '<option value="">Error al cargar residentes</option>';
        }
    }

    // Carga los datos de la vivienda
    async function cargarVivienda() {
        try {
            const res = await fetch(`http://localhost:3000/api/viviendas/${idVivienda}`, {
                headers: { 'Authorization': 'Bearer ' + token }
            });

            if (res.status === 401 || res.status === 403) {
                alert('No estás autorizado o tu sesión expiró.');
                localStorage.removeItem('token');
                window.location.href = '/login_admin';
                return;
            }

            if (!res.ok) {
                alert('No se pudo cargar la vivienda.');
                window.location.href = '/viviendas';
                return;
            }

            const vivienda = await res.json();

            console.log('Estado de la vivienda recibido:', vivienda.estado);

            document.getElementById('numero').value = vivienda.numero || '';
            document.getElementById('tipo').value = vivienda.tipo || '';
            document.getElementById('calle').value = vivienda.calle || '';
            
            // CORRECCIÓN: El valor se convierte a minúsculas antes de asignarlo.
            document.getElementById('estado').value = (vivienda.estado || 'activo').toLowerCase();

            const residenteId = vivienda.residente?._id || vivienda.residente_id || vivienda.residente || null;
            await cargarResidentes(residenteId);

        } catch (error) {
            console.error(error);
            alert('Error al cargar los datos de la vivienda');
        }
    }

    cargarVivienda();

    document.getElementById('btnCancelar').addEventListener('click', () => {
        window.location.href = '/viviendas';
    });

    document.getElementById('formEditarVivienda').addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            numero: document.getElementById('numero').value.trim(),
            tipo: document.getElementById('tipo').value.trim(),
            calle: document.getElementById('calle').value.trim(),
            residente_id: document.getElementById('residente').value,
            estado: document.getElementById('estado').value
        };

        const url = `http://localhost:3000/api/viviendas/${idVivienda}`;
        console.log('Enviando datos al servidor:', data);
        console.log('URL de la petición PUT:', url);

        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(data)
            });

            if (response.status === 401 || response.status === 403) {
                alert('No estás autorizado o tu sesión expiró.');
                localStorage.removeItem('token');
                window.location.href = '/login_admin';
                return;
            }

            if (!response.ok) {
                const resError = await response.json();
                alert(resError.message || 'No se pudo actualizar la vivienda');
                return;
            }

            // Muestra el modal de éxito en lugar de alert()
            alertaExito.classList.remove('hidden');

        } catch (err) {
            console.error(err);
            alert('Error de conexión con el servidor');
        }
    });

    // Evento para cerrar el modal de éxito y redirigir
    cerrarAlertaBtn.addEventListener('click', () => {
        alertaExito.classList.add('hidden');
        window.location.href = '/viviendas';
    });

    // Opcional: cerrar el modal haciendo clic fuera de él
    alertaExito.addEventListener('click', (e) => {
        if (e.target === alertaExito) {
            alertaExito.classList.add('hidden');
            window.location.href = '/viviendas';
        }
    });
});
</script>
@endsection