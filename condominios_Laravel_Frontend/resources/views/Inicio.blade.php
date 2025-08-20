<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VidaAdministracion</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
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

    <div class="header-content container">

        <h1>Vida Administracion</h1>
            <p>
            Administración de condominios
18 años de experiencia en la Zona Metropolitana de Guadalajara

60 condominios nos respaldan

70 Colaboradores en nuestro equipo
            </p>
            <a href="Servicios" class="btn-1">informacion</a>
        </div>
    </header>

    <section class="seguridad">

       
        <div class="seguridad-content container">

        <h2>Bienvenido a Vida Administración</h2>
        <p class="txt-p">
           En Vida administración ofrecemos soluciones estratégicas en administración de condominios. 
           Nuestro equipo está en constante actualización técnica y profesional con el fin de proporcionar
           soluciones eficaces que permitan tener una mejor vida en comunidad, así como aumentar la plusvalía de tu patrimonio.
        </p>

        <a href="#" class="btn-1">Informacion</a>

    </div>
</section>



<main class="services">

    <div class="services-content container">
        <h2>NOSOTROS</h2>
</div>
</main> 

<section class="general">
    <div class="general-1">
        <h2>NUESTRA MISIÓN</h2>
        <p>
            Lograr la satisfacción total de los clientes que nos brindan su confianza 
            mediante servicios integrales y estrategias eficaces y vanguardistas que 
            garanticen un correcto manejo de los recursos que harán que su patrimonio crezca en plusvalía. 
        </p>
        <a href="#" class="btn-1">inf</a>
    </div>
    <div class="general-2"></div> <!-- Imagen misión -->
</section>

<section class="general">
    <div class="general-3"></div> <!-- Imagen visión -->
    <div class="general-1">
        <h2>NUESTRA VISIÓN</h2>
        <p>
            Nuestra visión hacia 2025 es ser la mejor opción en el mercado de la administración 
            de condominios en Jalisco, con planes estratégicos de expansión a toda la República 
            Mexicana, teniendo un equipo de colaboradores profesionales comprometidos y que se 
            identifiquen con los valores y la filosofía de la empresa.
        </p>
        <a href="#" class="btn-1">inf</a>
    </div>
</section>

<section class="general">
    <div class="general-1">
        <h2>NUESTROS VALORES</h2>
        <p>
            Honestidad<br>
            Responsabilidad<br>
            Lealtad<br>
            Prudencia<br>
            Respeto<br>
            Transparencia<br>
            Cordialidad<br>
            Tolerancia<br>
            Profesionalismo
        </p>
        <a href="#" class="btn-1">inf</a>
    </div>
    <div class="general-4"></div> <!-- Imagen valores -->
</section>


<br><br>
<section class="blog container">
<center>
    <h2>Blog Vida Administración</h2>
    <p>Consejos, noticias y novedades sobre la gestión de condominios. <br> Cuidamos tu patrimonio con información útil y actualizada.</p>
</center>
    <div class="blog-content">

        <div class="blog-1">
            <img src="https://www.segurilatam.com/wp-content/uploads/sites/5/2024/02/guardia-seguridad-privada-uniforme-negro-900x600.jpg" alt="Mantenimiento condominios">
            <h3>¿Por qué es importante el mantenimiento preventivo?</h3>
            <p>
                El mantenimiento preventivo evita costos mayores a largo plazo y garantiza la seguridad 
                de todos los residentes. Descubre cómo aplicamos estos controles en los condominios que administramos.
            </p>
        </div>

        <div class="blog-1">
            <img src="https://www.vivook.com/wp-content/uploads/2021/12/mantenimiento.jpg" alt="Cuotas de mantenimiento">
            <h3>¿En qué se usan tus cuotas de mantenimiento?</h3>
            <p>
                Transparencia es uno de nuestros valores. Aquí te explicamos cómo se distribuyen las cuotas de mantenimiento
                y por qué son clave para mejorar la plusvalía de tu condominio.
            </p>
        </div>                

        <div class="blog-1">
            <img src="https://parquesanjorge.com.gt/wp-content/uploads/sites/7/2024/01/4-sconsejos-buena-convivencia-condominio.png" alt="Convivencia vecinal">
            <h3>Tips para mejorar la convivencia en tu condominio</h3>
            <p>
                Una buena administración no solo se trata de números. También promovemos la armonía entre vecinos.
                Conoce algunos consejos útiles que aplicamos en nuestros desarrollos.
            </p>
        </div>

    </div>


</section>

<br><br>


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