<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VidaAdministracion</title>
<link rel="stylesheet" href="{{ asset('styleS.css') }}">


<style>


    .contacto {
      max-width: 700px;
      margin: 0 auto;
      padding: 40px 20px;
      text-align: center;
    }

    .contacto h2 {
      text-align: left;
      font-size: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
      margin-bottom: 30px;
    }

    .contacto p.descripcion {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 30px;
    }

    .contacto form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .contacto input,
    .contacto textarea {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 0;
      outline: none;
    }

    .contacto textarea {
      min-height: 100px;
      resize: none;
    }

    .contacto button {
      background: #000;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
      width: 100px;
      align-self: center;
    }

    .contacto button:hover {
      background: #333;
    }

    .contacto small {
      font-size: 11px;
      color: #666;
      margin-top: 10px;
      display: block;
    }

    .contacto .info-extra {
      margin-top: 40px;
      font-size: 14px;
      line-height: 1.6;
    }

    .contacto .info-extra strong {
      font-size: 16px;
    }
  </style>

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







<section class="contacto">
  <h2>Tu opinión cuenta</h2>
  <p class="descripcion">
    Conocer la opinión de nuestros clientes es muy importante para nosotros, extendemos la invitación a llenar nuestro formulario para solicitar información sobre los productos y servicios que ofrecemos. A la brevedad posible, uno de nuestros representantes atenderá la solicitud.
  </p>

  <form>
    <input type="text" placeholder="Nombre">
    <input type="email" placeholder="Correo electrónico*">
    <textarea placeholder="Mensaje"></textarea>
    <button type="submit">Enviar</button>
    <small>Este sitio está protegido por reCAPTCHA y aplican las <a href="#">Política de privacidad</a> y los <a href="#">Términos de servicio</a> de Google.</small>
  </form>

  <div class="info-extra">
    <p><strong>O, aún mejor, ¡ven a visitarnos!</strong></p>
    <p>Av. Jorge Bizet 5171, Col La Estancia, Zapopan, Jalisco.</p>
    <p><strong>Llámanos</strong><br>(33) 1949-5366 móvil</p>
    <p><strong>Te atendemos</strong><br>Lunes a Viernes<br>9:00 am a 4:00 pm</p>
  </div>
</section>


<section style="background-color: #323337; padding: 40px; color: white;">
<h2 style="margin-bottom: 10px; padding-left: 165px;">Estamos cerca de ti</h2>
  <hr style="border: none; border-top: 2px solid #444; margin-bottom: 20px;">

  <div style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
   <center><iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3734.119160663991!2d-103.41876302575944!3d20.684335400579717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8428ae1ebd1e4c37%3A0xe4a678c3a0497c8a!2sC.%20George%20Bizet%205171%2C%20La%20Estancia%2C%2045130%20Zapopan%2C%20Jal.!5e0!3m2!1ses!2smx!4v1690000000000!5m2!1ses!2smx"
      width="80%" 
      height="450" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade">
    </iframe></center> 
  </div>
</section>





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