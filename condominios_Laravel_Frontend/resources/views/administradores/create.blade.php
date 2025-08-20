@extends('layout.admins')

@section('titulo', 'Crear administrador')
@section('meta-descripcion', 'Formulario para registrar un nuevo administrador')

@section('contenido')
<br><br>
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-sky-700 mb-6 flex items-center gap-2">
        <i class="bi bi-person-plus-fill text-3xl bi-person-plus-fill"></i> Registrar nuevo administrador
    </h2>

    <div id="errorMsg" class="text-red-600 font-semibold mb-4 hidden"></div>

    <form id="formAdministrador" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <i class="bi bi-person-fill"></i> Usuario
                </label>
                <input type="text" name="usuario" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-envelope-fill"></i> Correo
                </label>
                <input type="email" name="correo" required
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
                    <i class="bi bi-shield-lock-fill"></i> Contraseña
                </label>
                <input type="password" name="contrasena" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
            </div>
        </div>

        <!-- Campos derecha -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-image-fill"></i> Imagen
                </label>
                <input type="file" name="imagen" id="imagen" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                <img id="previewImagen" src="#" alt="Vista previa" class="mt-2 h-20 rounded-full hidden object-cover">
            </div>

            <div>
                <label class="block text-sm font-medium text-sky-700 mb-1">
                    <i class="bi bi-person-gear"></i> Tipo
                </label>
                <select name="tipo" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
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

        <!-- Botones -->
        <div class="col-span-1 md:col-span-2 flex justify-end gap-4 mt-8">
            <a href="/administradores"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded shadow">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded shadow">
                <i class="bi bi-save"></i> Guardar administrador
            </button>
        </div>

        <!-- Modal de éxito -->
<div id="successModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="successTitle" aria-describedby="successDesc">
  <div class="modal-content animate-scale-fade">
    <i class="bi bi-check-circle-fill text-green-500 text-6xl mb-4"></i>
    <h2 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">¡Éxito!</h2>
    <p id="successDesc" class="text-gray-600 mb-6">Administrador registrado correctamente.</p>
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
// Vista previa de imagen
document.getElementById("formAdministrador").addEventListener("submit", async function (e) {
    e.preventDefault();

    const token = localStorage.getItem("token");
    if (!token) {
        alert("No tienes sesión activa.");
        return;
    }

    const formData = new FormData(this);

    try {
        const response = await fetch("http://localhost:3000/api/administradores", {
            method: "POST",
            headers: {
                "Authorization": "Bearer " + token
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            // Mostrar modal y ocultar mensaje error
            document.getElementById("errorMsg").classList.add("hidden");
            document.getElementById("successModal").classList.remove("hidden");
        } else {
            document.getElementById("errorMsg").classList.remove("hidden");
            document.getElementById("errorMsg").textContent = data.error || "Error al registrar administrador.";
        }
    } catch (error) {
        console.error(error);
        document.getElementById("errorMsg").classList.remove("hidden");
        document.getElementById("errorMsg").textContent = "Error de conexión con el servidor.";
    }
});

// Botón aceptar del modal: redirigir a administradores
document.getElementById("successBtn").addEventListener("click", () => {
    window.location.href = "/administradores";
});

</script>
@endsection
