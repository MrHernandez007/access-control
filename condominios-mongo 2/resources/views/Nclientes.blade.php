<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VidaAdministracion</title>
<link rel="stylesheet" href="{{ asset('styleS.css') }}">
<link rel="stylesheet" href="{{ asset('styleN.css') }}">

</head>
<body>

    <header class="header">

        <div class="menu container">
            <input type="checkbox" id="menu" />
<label for="menu"> <br>
<img src="{{ asset('img/logo.png') }}" class="menu-icono" alt="Logo Vida">
</label>


<nav class="navbar">
    <ul>
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{ url('/Servicios') }}">Servicios</a></li>
        <li><a href="{{ url('/Contacto') }}">Contacto</a></li>
        <li><a href="{{ url('/Nclientes') }}">Nuestros Clientes</a></li>
        <li><a href="{{ url('/login_residente') }}">Login Residente</a></li>
    </ul>
</nav>


    </div>


    </header>


 <main class="services">

    <div class="services-content container">
        <h2>Clientes en la Zona Metropolitana de Guadalajara</h2>
</div>
</main> 


<!-- Apartado 1 -->
<section class="apartado-1"> 
    <div class="apartado-1-texto">
        <h2>Condominios en la Zona Sur</h2> <br>
    <ul>
        <li>El Origen A.C.</li>
        <li>Torrenta</li>
        <li>Izvora</li>
        <li>Valencia, Nva Galicia.</li>
        <li>Alberi</li>
        <li>Sant Marti</li>
      

    </ul><br>
    </div>
    <div class="apartado-1-img"></div>
</section>

<!-- Apartado 2 --> 
<section class="apartado-2">
    <div class="apartado-2-img"></div>
    <div class="apartado-2-texto">
        <h2>Condominios en la Zona Centro</h2><br>
        <ul>
        <li>Aura Altitude, Zona Andares</li>
        <li>San Francisco, Ciudad Granja</li>
        <li>Villa Colomos</li>
        <li>Santa Rita</li>
        <li>Virreyes Coto 9</li>
        <li>Metropark</li>


    </ul><br>
    </div>
</section>

<!-- Apartado 3 -->
<section class="apartado-3"> 
    <div class="apartado-3-texto"><br>
        <h2>Condominios en la Zona Norte</h2>
         <ul>
        <li>Solares 10 Vasanta</li>
        <li>Solares coto 3</li>
        <li>Solares coto 5</li>
        <li>Soaré Acanthia</li>
        <li>Santillana 8</li>
        <li>Liva Friendly Living</li>
    </ul><br>
    </div>
    <div class="apartado-3-img"></div>
</section>

<!-- Apartado 4 -->
<section class="apartado-4">
    <div class="apartado-4-img"></div>
    <div class="apartado-4-texto"><br>
        <h2>Condominios en Capital Norte</h2>
        <ul>
        <li>Asociación de Colonos Capital Norte</li>
        <li>Cerezos</li>
        <li>Carrara</li>
        <li>Galarza Bis</li>
        <li>Grand Capital</li>
        <li>Nara</li>
        <li>Reserva Capital</li>
    </ul><br>
    </div>
</section>



 <main class="services">

    <div class="services-content container">
        <h2> Distintos estados de la República</h2>
</div>
</main> 


<!-- Apartado 5 -->
<section class="apartado-5"> 
    <div class="apartado-5-texto"><br>
        <h2>Tepic, Nayarit</h2><br>
        <ul>
        <li>Biósferea Residencial</li>
        <li>Coto Castaños, Los Robles.</li>
        <li>Coto Helechos, Los Robles.</li>
        <li>Punto Sur Este.</li>
        <li>Del Pilar.</li>
        <li>Fraccionamiento Albazur</li>
    </ul>
    
    <br>
    </div>
    <div class="apartado-5-img"></div>
</section>

<!-- Apartado 6 -->
<section class="apartado-6">
    <div class="apartado-6-img"></div>
    <div class="apartado-6-texto">
        <h2>Puerto Vallarta y Nuevo Nayarit</h2>
         <ul>
        <li>Mangle Living Home</li>
        <li>Isla Creta</li>
        <li>Torres Victoria</li>
        <li>Cititower</li>
        <li>Torres Livorno Lomas Altas</li>
        <li>Coto Américas</li>
        <li>Rinconada Guadalupe</li>
    </ul><br>
    </div>
</section>

<!-- Apartado 7 -->
<section class="apartado-7"> 
    <div class="apartado-7-texto"><br><br>
        <h2>Tijuana, Baja California</h2><br>
        <ul>
        <li>Logroño Residencial</li>
        <li>Terralta Residencial</li>
        <li>Abié Residencial</li>
        <li>Contempo 445</li>
        <li>Ederra Reserva Residencial</li>
    </ul><br>
    </div>
    <div class="apartado-7-img"></div>
</section>


 <main class="services">

    <div class="services-content container">
        <h2> Construyendo confianza, creando comunidad.</h2>
</div>
</main> 



   <footer class="footer">
    <div class="footer-content container">

        <div class="link">
            <h3>Sobre Nosotros</h3>
            <ul>
                <li><a href="#">Quiénes somos</a></li>
                <li><a href="#">Nuestra misión</a></li>
                <li><a href="#">Nuestro equipo</a></li>
                <li><a href="#">Política de privacidad</a></li>
            </ul>
        </div>

        <div class="link">
            <h3>Servicios</h3>
            <ul>
                <li><a href="#">Administración de condominios</a></li>
                <li><a href="#">Gestión de mantenimiento</a></li>
                <li><a href="#">Atención a residentes</a></li>
                <li><a href="#">Reportes financieros</a></li>
            </ul>
        </div>

        <div class="link">
            <h3>Contacto</h3>
            <ul>
                <li><a href="#">Tel: (33) 1234 5678</a></li>
                <li><a href="#">Email: contacto@admincondos.mx</a></li>
                <li><a href="#">Lunes a Viernes: 9am - 6pm</a></li>
                <li><a href="#">Guadalajara, Jalisco, México</a></li>
            </ul>
        </div>

        <div class="link">
            <h3>Login</h3>
            <ul>
        <li><a href="{{ url('/login_admin') }}">Login Admin</a></li>

            </ul>
        </div>

    </div>
</footer>


</body>
</html>

