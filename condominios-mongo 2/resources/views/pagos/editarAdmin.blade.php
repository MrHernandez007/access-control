@extends('layout.admins')

@section('titulo', 'Editar Pago')
@section('meta-descripcion', 'Editar pago registrado')

@section('contenido')
<br><br>
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-sky-700 flex items-center gap-2">
        <i class="bi bi-pencil-square text-4xl"></i> Editar Pago
    </h1>

    <div id="mensaje" class="mb-4"></div>

    <form id="form-editar-pago" class="space-y-6">
        <div>
            <label for="residente_id" class="block mb-1 font-semibold text-gray-700">Residente</label>
            <select id="residente_id" name="residente_id" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">Selecciona un residente</option>
            </select>
        </div>

        <div>
            <label for="vivienda_id" class="block mb-1 font-semibold text-gray-700">Vivienda</label>
            <select id="vivienda_id" name="vivienda_id" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">Selecciona una vivienda</option>
            </select>
        </div>

        <div>
            <label for="concepto" class="block mb-1 font-semibold text-gray-700">Concepto</label>
            <input type="text" id="concepto" name="concepto" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500"
                placeholder="Ejemplo: Mantenimiento mensual" />
        </div>

        <div>
            <label for="monto" class="block mb-1 font-semibold text-gray-700">Monto</label>
            <input type="number" id="monto" name="monto" min="0" step="0.01" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500"
                placeholder="0.00" />
        </div>

        <div>
            <label for="fecha_pago" class="block mb-1 font-semibold text-gray-700">Fecha de pago</label>
            <input type="date" id="fecha_pago" name="fecha_pago" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500" />
        </div>

        <div>
            <label for="estado" class="block mb-1 font-semibold text-gray-700">Estado</label>
            <select id="estado" name="estado" required
                class="w-full rounded border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">Selecciona un estado</option>
                <option value="pendiente">Pendiente</option>
                <option value="pagado">Pagado</option>
                <option value="vencido">Vencido</option>
                <option value="anulado">Anulado</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t">
            <a href="{{ url('/pagos') }}" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 font-semibold">Cancelar</a>
            <button type="submit" class="px-6 py-2 rounded bg-sky-600 hover:bg-sky-700 text-white font-semibold">Guardar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const pagoId = urlParams.get('id'); // Se espera URL: /pagos/edit?id=xxx

    if (!pagoId) {
        document.getElementById('mensaje').innerHTML = '<p class="text-red-600 font-semibold">ID de pago no proporcionado.</p>';
        return;
    }

    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('mensaje').innerHTML = '<p class="text-red-600 font-semibold">No tienes sesión activa.</p>';
        return;
    }

    const residenteSelect = document.getElementById('residente_id');
    const viviendaSelect = document.getElementById('vivienda_id');
    const conceptoInput = document.getElementById('concepto');
    const montoInput = document.getElementById('monto');
    const fechaPagoInput = document.getElementById('fecha_pago');
    const estadoSelect = document.getElementById('estado');
    const mensajeDiv = document.getElementById('mensaje');
    const form = document.getElementById('form-editar-pago');

    // Función para cargar opciones de residentes
    async function cargarResidentes() {
        try {
            const res = await fetch('http://localhost:3000/api/residentes', {
                headers: { 'Authorization': 'Bearer ' + token }
            });
            const data = await res.json();
            residenteSelect.innerHTML = '<option value="">Selecciona un residente</option>';
            data.forEach(r => {
                residenteSelect.innerHTML += `<option value="${r._id}">${r.nombre}</option>`;
            });
        } catch {
            residenteSelect.innerHTML = '<option value="">Error al cargar residentes</option>';
        }
    }

    // Función para cargar opciones de viviendas
    async function cargarViviendas() {
        try {
            const res = await fetch('http://localhost:3000/api/viviendas', {
                headers: { 'Authorization': 'Bearer ' + token }
            });
            const data = await res.json();
            viviendaSelect.innerHTML = '<option value="">Selecciona una vivienda</option>';
            data.forEach(v => {
                viviendaSelect.innerHTML += `<option value="${v._id}">${v.numero || v.descripcion || v._id}</option>`;
            });
        } catch {
            viviendaSelect.innerHTML = '<option value="">Error al cargar viviendas</option>';
        }
    }

    // Carga inicial de selects
    await Promise.all([cargarResidentes(), cargarViviendas()]);

    // Cargar datos del pago a editar
    try {
        const res = await fetch(`http://localhost:3000/api/pagos/${pagoId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (res.status === 404) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">Pago no encontrado.</p>';
            return;
        }

        if (!res.ok) {
            throw new Error('Error al obtener el pago.');
        }

        const pago = await res.json();

        residenteSelect.value = pago.residente_id?._id || '';
        viviendaSelect.value = pago.vivienda_id || '';
        conceptoInput.value = pago.concepto || '';
        montoInput.value = pago.monto || '';
        fechaPagoInput.value = pago.fecha_pago ? new Date(pago.fecha_pago).toISOString().split('T')[0] : '';
        estadoSelect.value = pago.estado || '';

    } catch (error) {
        mensajeDiv.innerHTML = `<p class="text-red-600 font-semibold">${error.message}</p>`;
    }

    // Validación simple del formulario
    form.addEventListener('submit', async e => {
        e.preventDefault();
        mensajeDiv.innerHTML = '';

        const formData = {
            residente_id: residenteSelect.value.trim(),
            vivienda_id: viviendaSelect.value.trim(),
            concepto: conceptoInput.value.trim(),
            monto: parseFloat(montoInput.value),
            fecha_pago: fechaPagoInput.value,
            estado: estadoSelect.value.trim()
        };

        // Validaciones básicas
        if (!formData.residente_id) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">Selecciona un residente.</p>';
            return;
        }
        if (!formData.vivienda_id) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">Selecciona una vivienda.</p>';
            return;
        }
        if (!formData.concepto) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">El concepto es obligatorio.</p>';
            return;
        }
        if (isNaN(formData.monto) || formData.monto < 0) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">El monto debe ser un número positivo.</p>';
            return;
        }
        if (!formData.fecha_pago) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">La fecha de pago es obligatoria.</p>';
            return;
        }
        if (!['pendiente', 'pagado', 'vencido', 'anulado'].includes(formData.estado)) {
            mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">Estado inválido.</p>';
            return;
        }

        try {
            const res = await fetch(`http://localhost:3000/api/pagos/${pagoId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            if (res.status === 404) {
                mensajeDiv.innerHTML = '<p class="text-red-600 font-semibold">Pago no encontrado.</p>';
                return;
            }

            if (!res.ok) {
                const data = await res.json();
                throw new Error(data.mensaje || 'Error al actualizar el pago');
            }

            mensajeDiv.innerHTML = '<p class="text-green-600 font-semibold">Pago actualizado correctamente.</p>';
            setTimeout(() => {
                window.location.href = '/pagos';
            }, 1500);

        } catch (error) {
            mensajeDiv.innerHTML = `<p class="text-red-600 font-semibold">${error.message}</p>`;
        }
    });
});
</script>
@endsection
