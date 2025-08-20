@extends('layout.residentes')

@section('titulo', 'Pagos - Residente')
@section('meta-descripcion', 'Listado de pagos del residente autenticado')

@section('contenido')
<br><br>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <h1 class="text-2xl font-bold text-sky-700 flex items-center gap-2 mb-6" style="font-size: 30px">
    <i class="bi bi-wallet2 text-sky-700 text-3xl"></i>
    Mis Pagos
  </h1>

  <div id="errorMsg" class="mb-4 text-red-600 font-semibold hidden"></div>

  <div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200" id="tabla-pagos">
      <thead class="bg-sky-100 text-sky-800 text-base">
        <tr>
          <th class="px-4 py-3 text-left font-medium" style="font-weight: bold;"><i class="bi bi-journal-text"></i> Concepto</th>
          <th class="px-4 py-3 text-left font-medium" style="font-weight: bold;"><i class="bi bi-currency-dollar"></i> Monto</th>
          <th class="px-4 py-3 text-left font-medium" style="font-weight: bold;"><i class="bi bi-calendar-check"></i> Fecha de Pago</th>
          <th class="px-4 py-3 text-left font-medium" style="font-weight: bold;"><i class="bi bi-power"></i> Estado</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-base" id="pagosBody">
        <!-- Aquí tu lógica inserta filas -->
      </tbody>
    </table>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    const errorMsg = document.getElementById('errorMsg');
    const pagosBody = document.getElementById('pagosBody');

    errorMsg.classList.add('hidden');
    errorMsg.textContent = '';

    // Obtener el token guardado en localStorage
    const token = localStorage.getItem('token');
    if (!token) {
      errorMsg.textContent = 'No estás autenticado. Por favor, inicia sesión.';
      errorMsg.classList.remove('hidden');
      return;
    }

    try {
      const response = await fetch('http://localhost:3000/api/pagos/residente', {
        method: 'GET',
        headers: {
          'Authorization': 'Bearer ' + token,
          'Content-Type': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error('Error al obtener los pagos. Código: ' + response.status);
      }

      const pagos = await response.json();

      if (!Array.isArray(pagos) || pagos.length === 0) {
        pagosBody.innerHTML = `<tr><td colspan="4" class="text-center py-4">No tienes pagos registrados.</td></tr>`;
        return;
      }

      pagosBody.innerHTML = pagos.map(pago => {
        const fecha = new Date(pago.fecha_pago).toLocaleDateString('es-MX', {
          year: 'numeric', month: '2-digit', day: '2-digit'
        });

        const montoFormateado = pago.monto.toLocaleString('es-MX', {
          style: 'currency',
          currency: 'MXN'
        });

        // Estilos pendientes con icono de borde (sin relleno)
        let estadoColor = 'text-gray-700';
        let estadoExtra = '';

        if (pago.estado === 'pagado') estadoColor = 'text-green-600 font-semibold';
        else if (pago.estado === 'pendiente') {
          estadoColor = 'inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 font-semibold rounded-full px-3 py-1';
          estadoExtra = `<i class="bi bi-clock"></i>`;
        }
        else if (pago.estado === 'vencido') estadoColor = 'text-red-600 font-semibold';
        else if (pago.estado === 'anulado') estadoColor = 'text-gray-400 italic';

        return `
          <tr class="hover:bg-gray-50 cursor-default">
            <td class="py-3 px-6">${pago.concepto}</td>
            <td class="py-3 px-6">${montoFormateado}</td>
            <td class="py-3 px-6">${fecha}</td>
            <td class="py-3 px-6">
              <span class="${estadoColor}">
                ${pago.estado === 'pendiente' ? estadoExtra + ' ' : ''}
                ${pago.estado.charAt(0).toUpperCase() + pago.estado.slice(1)}
              </span>
            </td>
          </tr>
        `;
      }).join('');

    } catch (error) {
      errorMsg.textContent = 'Error al cargar pagos: ' + error.message;
      errorMsg.classList.remove('hidden');
    }
  });
</script>

@endsection
