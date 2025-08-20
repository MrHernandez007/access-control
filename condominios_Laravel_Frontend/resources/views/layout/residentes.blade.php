<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Residente - @yield('titulo')</title>
    <meta name="descripcion" content="@yield('meta-descripcion','Dashboard del residente')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body class="bg-gray-50 min-h-screen">

<nav class="shadow-md border-b border-gray-200" style="height: 130px; padding-block-start: 6mm; background: linear-gradient(to right, #ffffff, #bae6fd);">
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
    <div class="flex justify-between items-center">
<a href="/base/residente_dashboard">
      <!-- Logo + Título -->
      <div class="flex items-center space-x-3 text-sky-500 text-3xl font-bold">
        <img src="//img1.wsimg.com/isteam/ip/95c093aa-8c17-4947-8364-57f6e11c54f5/LOGO%20VIDA.png/:/rs=h:200,cg:true,m/qt=q:100/ll"
             alt="Vida Administración" style="height: 90px;">
        <span>Residente</span>
      </div>
</a>
      <!-- Navegación -->
      <div class="hidden md:flex space-x-8 text-gray-700 text-lg font-medium items-center">

        <a href="/visitas" class="flex items-center gap-2 hover:text-sky-600 transition">
          <i class="bi bi-house-door-fill text-xl"></i> Visitas
        </a>

        <a href="/visitantes" class="flex items-center gap-2 hover:text-sky-600 transition">
          <i class="bi bi-people-fill text-xl"></i> Visitantes
        </a>

        <a href="/pagos/indexResidente" class="flex items-center gap-2 hover:text-sky-600 transition">
          <i class="bi bi-credit-card-fill text-xl"></i> Pagos
        </a>
      </div>

      <!-- Logout -->
      <div>
        <button type="button" style="font-size: large;"
          class="flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition text-sm"
          onclick="logout()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
          </svg>
          Cerrar sesión
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Contenido -->

  @yield('contenido')

<script>
  function logout() {
    fetch('http://localhost:3000/residente/logout', {
      method: 'GET',
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      localStorage.removeItem('token');
      window.location.href = "/login/residente";  // Cambia por la ruta de login de tu frontend
    })
    .catch(error => {
      console.error('Error al cerrar sesión:', error);
      alert('Error al cerrar sesión');
    });
  }
</script>

</body>
</html>
