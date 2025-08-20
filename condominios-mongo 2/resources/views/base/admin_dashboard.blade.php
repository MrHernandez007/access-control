@extends('layout.admins')

@section('titulo', 'Dashboard Administrador')
@section('meta-descripcion', 'Panel de administración para el administrador autenticado')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-sky-800 mb-4">Bienvenido, administrador</h1>

    <!-- Perfil del administrador -->
    <div id="perfil" class="bg-sky-50 border border-sky-200 p-6 rounded-xl shadow-md mt-6 hidden">
        <div class="flex items-center space-x-6">
            <img id="imagenPerfil" src="" alt="Foto de perfil" class="w-32 h-32 object-cover rounded-full border-4 border-sky-400 shadow-lg">
            <div>
                <h3 class="text-2xl font-bold text-sky-800" id="nombreCompleto">Cargando...</h3>
                <p class="text-sm text-gray-600 mt-1" id="usuarioCorreo"></p>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                <h4 class="text-sky-700 font-semibold mb-2">Administrar residentes</h4>
                <p class="text-gray-600 text-sm">Crea, edita o elimina cuentas de residentes del sistema.</p>
                <a href="/residentes" class="text-sky-600 hover:underline text-sm font-medium mt-2 inline-block">Ir a residentes →</a>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                <h4 class="text-sky-700 font-semibold mb-2">Ver pagos</h4>
                <p class="text-gray-600 text-sm">Consulta el estado de pagos de mantenimiento de cada vivienda.</p>
                <a href="/pagos/indexAdmin" class="text-sky-600 hover:underline text-sm font-medium mt-2 inline-block">Ver pagos →</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Sesión no válida. Redirigiendo al login...');
        window.location.href = '/login/admin';
        return;
    }

    fetch('http://localhost:3000/api/administradores/auth/perfil', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(async response => {
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Error ${response.status}: ${errorText}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data.estado || !data.data) throw new Error("Datos inválidos");

        const admin = data.data;

        // Limpieza y corrección de ruta
        let rutaImg = admin.imagen || '';
        rutaImg = rutaImg.replace(/^public[\/\\]/, '').replace(/\\/g, '/');  // quita "public/" y reemplaza \ por /

        const imagenPath = rutaImg
            ? `http://localhost:3000/${rutaImg}`
            : 'https://via.placeholder.com/150';

        document.getElementById('imagenPerfil').src = imagenPath;
        document.getElementById('nombreCompleto').textContent = `${admin.nombre} ${admin.apellido}`;
        document.getElementById('usuarioCorreo').textContent = `${admin.usuario} | ${admin.correo}`;

        document.getElementById('perfil').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error al cargar perfil:', error);
        alert('No se pudo cargar la información del administrador.');
    });
});
</script>
@endsection 