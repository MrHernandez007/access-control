@extends('layout.residentes')

@section('titulo', 'Registrar visitante')
@section('meta-descripcion', 'Formulario para registrar un nuevo visitante')

@section('contenido')
<br><br>
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-sky-700 mb-6 flex items-center gap-2">
        <i class="bi bi-person-plus-fill text-3xl"></i> Registrar nuevo visitante
    </h2>

    <div id="errorMsg" class="text-red-600 font-semibold mb-4 hidden"></div>

    <form id="formVisitante" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Campos izquierda -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-lines-fill"></i> Nombre
                </label>
                <input type="text" name="nombre" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-lines-fill"></i> Apellido
                </label>
                <input type="text" name="apellido" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-telephone-fill"></i> Teléfono
                </label>
                <input type="text" name="telefono"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-fill"></i> Tipo
                </label>
                <select name="tipo" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                    <option value="visita">Visita</option>
                    <option value="proveedor">Proveedor</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-toggle-on"></i> Estado
                </label>
                <select name="estado" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Campos derecha Vehículo -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Datos del vehículo (opcional)</h3>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    Placa
                </label>
                <input type="text" name="vehiculo[placa]"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    Color
                </label>
                <input type="text" name="vehiculo[color]"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    Modelo
                </label>
                <input type="text" name="vehiculo[modelo]"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    Tipo de vehículo
                </label>
                <input type="text" name="vehiculo[tipo]"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>
        </div>

        <!-- Botones -->
        <div class="col-span-1 md:col-span-2 flex justify-end gap-4 mt-8">
            <a href="/visitantes"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded shadow">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded shadow">
                <i class="bi bi-save"></i> Guardar visitante
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById("formVisitante").addEventListener("submit", async function (e) {
    e.preventDefault();

    const token = localStorage.getItem("token");
    if (!token) {
        alert("No tienes sesión activa.");
        return;
    }

    const form = e.target;

    const visitante = {
        nombre: form.nombre.value.trim(),
        apellido: form.apellido.value.trim(),
        telefono: form.telefono.value.trim(),
        tipo: form.tipo.value,
        estado: form.estado.value,
        vehiculo: {
            placa: form["vehiculo[placa]"].value.trim(),
            color: form["vehiculo[color]"].value.trim(),
            modelo: form["vehiculo[modelo]"].value.trim(),
            tipo: form["vehiculo[tipo]"].value.trim()
        }
    };

    try {
        const response = await fetch("http://localhost:3000/api/visitantes", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            },
            body: JSON.stringify(visitante)
        });

        const data = await response.json();

        if (response.ok) {
            alert("Visitante registrado correctamente");
            window.location.href = "/visitantes";
        } else {
            document.getElementById("errorMsg").classList.remove("hidden");
            document.getElementById("errorMsg").textContent = data.error || "Error al registrar visitante.";
        }
    } catch (error) {
        console.error(error);
        document.getElementById("errorMsg").classList.remove("hidden");
        document.getElementById("errorMsg").textContent = "Error de conexión con el servidor.";
    }
});
</script>
@endsection
