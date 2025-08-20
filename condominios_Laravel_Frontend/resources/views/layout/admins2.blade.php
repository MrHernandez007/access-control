<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin-Condominio @yield('titulo')</title>
    <meta name="descripcion" content="@yield('meta-descripcion','Default meta descripcion')" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" ... />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" ... />

</head>
<body>

<nav class="shadow-md border-b border-gray-200" style="height: 130px; padding-block-start: 6mm; background: linear-gradient(to right, #ffffff, #bae6fd);">

  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
    <div class="flex justify-between items-center h-full">
<a href="/base/admin2_dashboard">
      <div class="flex items-center space-x-3 text-sky-500 text-2xl font-bold">
        <img src="//img1.wsimg.com/isteam/ip/95c093aa-8c17-4947-8364-57f6e11c54f5/LOGO%20VIDA.png/:/rs=h:200,cg:true,m/qt=q:100/ll" 
             alt="Vida Administración" style="height: 100px;" />
        <span>Panel Administrativo</span>
      </div>
</a>
      <div class="hidden md:flex space-x-6 text-gray-700 text-sm font-medium items-center">
        <a href="/admin2/visitantes2" class="flex items-center gap-2 hover:text-sky-600 transition" style="font-size: large">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 4c-2.667 0-8 1.333-8 4v2h16v-2c0-2.667-5.333-4-8-4z" />
          </svg>
          Vistantes
        </a>
        <a href="/admin2/visitas2" class="flex items-center gap-2 hover:text-sky-600 transition" style="font-size: large">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 10.75L12 4l9 6.75V20a1 1 0 01-1 1h-5.5a1 1 0 01-1-1v-4.25a1 1 0 00-1-1h-2a1 1 0 00-1 1V20a1 1 0 01-1 1H4a1 1 0 01-1-1V10.75z" />
          </svg>
          Visitas
        </a>
        <a href="/admin2/residentes2" class="flex items-center gap-2 hover:text-sky-600 transition" style="font-size: large">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
          </svg>
          Residentes
        </a>
      </div>
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

@yield('contenido')

<script src="https://cdn.tailwindcss.com"></script>

<script>
  function logout() {
    fetch('http://localhost:3000/admin/logout', {
      method: 'GET',
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      localStorage.removeItem('token');
      window.location.href = "/login/admin";  // Cambia por la ruta de login de tu frontend
    })
    .catch(error => {
      console.error('Error al cerrar sesión:', error);
      alert('Error al cerrar sesión');
    });
  }
</script>

</body>
</html> 
