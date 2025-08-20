<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Administrador</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <button onclick="window.location.href = '/';"
            class="fixed top-4 left-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
        <i class="bi bi-arrow-left-circle-fill text-3xl"></i>
    </button>

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow">

        <div class="flex justify-center mb-6">
            <img
                src="//img1.wsimg.com/isteam/ip/95c093aa-8c17-4947-8364-57f6e11c54f5/LOGO%20VIDA.png/:/rs=h:200,cg:true,m/qt=q:100/ll"
                srcset="//img1.wsimg.com/isteam/ip/95c093aa-8c17-4947-8364-57f6e11c54f5/LOGO%20VIDA.png/:/rs=w:218,h:200,cg:true,m/cr=w:218,h:200/qt=q:100/ll,
                         //img1.wsimg.com/isteam/ip/95c093aa-8c17-4947-8364-57f6e11c54f5/LOGO%20VIDA.png/:/rs=w:295,h:271,cg:true,m/cr=w:295,h:271/qt=q:100/ll 2x"
                alt="Vida Administración"
                class="max-h-28 object-contain"
            />
        </div>

        <h2 class="text-2xl font-bold text-sky-700 mb-6 text-center">Login - Administrador</h2>

        <div id="errorMsg" class="mb-4 text-red-600 font-semibold text-center hidden"></div>

        <form id="loginForm" class="space-y-6" novalidate>
            <label for="usuario" class="block font-medium text-sky-700 mb-1">
                <i class="bi bi-person-fill mr-2"></i>Usuario:
            </label>
            <input type="text" name="usuario" id="usuario" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">

            <label for="contrasena" class="block font-medium text-sky-700 mt-4 mb-1">
                <i class="bi bi-shield-lock-fill mr-2"></i>Contraseña:
            </label>
            <input type="password" name="contrasena" id="contrasena" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500">

            <button type="submit"
                    class="w-full mt-6 inline-flex items-center justify-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg shadow">
                <i class="bi bi-box-arrow-in-right"></i>Ingresar
            </button>
        </form>
    </div>

<script>
    const loginForm = document.getElementById('loginForm');
    const errorMsg = document.getElementById('errorMsg');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorMsg.classList.add('hidden');
        errorMsg.textContent = '';

        const usuario = document.getElementById('usuario').value.trim();
        const contrasena = document.getElementById('contrasena').value.trim();

        if (!usuario || !contrasena) {
            errorMsg.textContent = 'Por favor, completa todos los campos.';
            errorMsg.classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch('http://localhost:3000/admin/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ usuario, contrasena })
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem('token', data.token);
                localStorage.setItem('rol', data.rol);

                if (data.rol === 'superadmin') {
                    window.location.href = '/base/admin_dashboard';
                } else if (data.rol === 'admin') {
                    window.location.href = '/base/admin2_dashboard';
                } else {
                    errorMsg.textContent = 'Rol no reconocido.';
                    errorMsg.classList.remove('hidden');
                }
            } else {
                errorMsg.textContent = data.message || 'Usuario o contraseña incorrectos.';
                errorMsg.classList.remove('hidden');
            }

        } catch (error) {
            console.error('Error:', error);
            errorMsg.textContent = 'Error de conexión con el servidor.';
            errorMsg.classList.remove('hidden');
        }
    });
</script>

</body>
</html>