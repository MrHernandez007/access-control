@extends('layout.admins')

@section('titulo', 'Detalle del Administrador')
@section('meta-descripcion', 'Detalle del administrador del sistema')

@section('contenido')
<br><br>

<div class="container py-4" id="admin-detalle">
    <h2>Detalle del administrador</h2>

    <div class="card shadow-sm">
        <div class="row g-0">
            <div id="imagen-col" class="col-md-4 d-none">
                <img id="admin-imagen" src="" class="img-fluid rounded-start" alt="Imagen del administrador">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title" id="admin-nombre"></h5>
                    <p class="card-text"><strong>Usuario:</strong> <span id="admin-usuario"></span></p>
                    <p class="card-text"><strong>Correo:</strong> <span id="admin-correo"></span></p>
                    <p class="card-text"><strong>Teléfono:</strong> <span id="admin-telefono"></span></p>
                    <p class="card-text"><strong>Tipo:</strong> <span id="admin-tipo"></span></p>
                    <p class="card-text">
                        <strong>Estado:</strong> 
                        <span id="admin-estado" class="badge"></span>
                    </p>
                    <a id="btn-editar" class="btn btn-warning">Editar</a>
                    <a href="/administradores" class="btn btn-secondary">Volver a la lista</a>
                    <button onclick="logout()" class="btn btn-danger mt-3">Cerrar sesión</button>
                </div>
            </div>
        </div> 
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id'); // ?id=...

        if (!id) {
            alert("ID no proporcionado");
            return;
        }

        try {
            const token = localStorage.getItem('token'); // si usas JWT
            const res = await fetch(`http://localhost:3000/api/administradores/${id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            if (!res.ok) throw new Error('No se pudo obtener el administrador');

            const data = await res.json();
            const admin = data.data;

            document.getElementById('admin-nombre').textContent = `${admin.nombre} ${admin.apellido}`;
            document.getElementById('admin-usuario').textContent = admin.usuario;
            document.getElementById('admin-correo').textContent = admin.correo;
            document.getElementById('admin-telefono').textContent = admin.telefono || '';
            document.getElementById('admin-tipo').textContent = admin.tipo.charAt(0).toUpperCase() + admin.tipo.slice(1);

            const estadoBadge = document.getElementById('admin-estado');
            estadoBadge.textContent = admin.estado.charAt(0).toUpperCase() + admin.estado.slice(1);
            estadoBadge.classList.add('bg-' + (admin.estado === 'activo' ? 'success' : 'secondary'));

            if (admin.imagen) {
                const ruta = `http://localhost:3000/uploads/${admin.imagen}`;
                document.getElementById('admin-imagen').src = ruta;
                document.getElementById('imagen-col').classList.remove('d-none');
            }

            document.getElementById('btn-editar').href = `/administradores/edit?id=${admin._id}`;
        } catch (error) {
            console.error(error);
            alert("Error al obtener los datos del administrador.");
        }
    });

    function logout() {
        // Elimina el token del localStorage (o cualquier otra sesión que uses)
        localStorage.removeItem('token');
        // Redirige a la página de login (ajusta según ruta real)
        window.location.href = '/login';
    }
</script>
@endsection
