<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/ico" href="static/img/Einsphi/EINSPHI_logo_icono/logo_einsphi.png">
    <title>Einsphi</title>

    <!-- Estilos Paginas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="static/css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Scripts Paginas -->
    <script type="text/javascript">
        dplan = {
            "10h": 55,
            "20h": 100,
            "30h": 150,
            "12h": 65,
            "10b": 45,
            "20b": 85,
            "30b": 120,
            "12b": 50,
        }   
    </script>  
    <script src="https://smtpjs.com/v3/smtp.js"></script> <!-- correo -->

</head>
<body class="esconder">

    <div class="centrado" id="onload"><div class="lds-roller">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div></div>
    </div>

    <img src="static/img/SVG imagen/menu.svg" alt="" class="menu-btn">

    <div class="contenido_menu_principal">

        <?php session_start(); ?>

        <img src='static/img/Fisico/FM_logo_icono/logo_fm.png' class='imagen_logo_fm '>

        <div class="login-container">
            <?php if(isset($_SESSION['nombre'])): ?>
                <form action="cerrar_secion" method="post">
                    <button type="submit" class="logout-button">Cerrar sesión</button>
                </form>
            <?php elseif( (isset($_GET['pg']) == false) &&  (isset($_SESSION['nombre']) == false) ): ?>
                <button onclick="location.href='login'" type="button" class="login-button">Iniciar sesión</button>
            <?php elseif( isset($_SESSION['nombre']) == false ): ?>
                <button onclick="location.href='login'" type="button" class="login-button">Iniciar sesión</button>
            <?php endif; ?>
        </div>

        <nav class='nav-main'>
            <ul class='menu-horizontal'>
                <li><a href='Inicio'> ☞ Inicio </a></li>
                <li><a>☞ Instalaciones Eléctricas</a>
                    <ul class='menu-vertical'>
                        <li><a href='einsphi_CercoElectrico'> ☞ Cerco Eléctrico </a></li>
                        <li><a href='einsphi_Camaras'> ☞ Instalación de Cámaras </a></li>
                        <li><a href='einsphi_Domotica'> ☞ Domótica </a></li>
                    </ul>
                </li>
                <li><a>☞ El Físico Matemático</a>
                    <ul class='menu-vertical'>
                        <li><a href='ElFisicoMatematico'> ☞ Información </a></li>
                        <li><a href='inicio'> ☞ Cursos </a></li>
                        <li><a href='formulario'> ☞ Formularios </a></li>
                        <li><a href='locales_fisicos'> ☞ Registros </a></li>
                    </ul>
                </li>
                <li><a href='contactos'> ☞ Contactos </a></li>
            </ul>
        </nav>
    </div>

    <div class="contenido-cuerpo">
        <?php
        $datos = array(
            'what1' => "https://wa.me/message/KSWV35VXQQZMF1",
            'what2' => "https://wa.me/message/QMU4GWMYONCVH1",
            'what3' => "https://wa.me/message/KSWV35VXQQZMF1",
            'face' => "https://www.facebook.com/elfisicomatematico",
            'insta' => "https://www.instagram.com/elfisicomatematico91/",
            'direc_fm_1' => "https://goo.gl/maps/M7sYpmaxebBVzyGx8",
            'direc_fm_2' => "https://goo.gl/maps/M7sYpmaxebBVzyGx8",
            'telegram' => "https://t.me/elfisicomatematico",
        );
        if (isset($_GET["pg"])) {
            $pagina = $_GET["pg"];
            if (($pagina == "ElFisicoMatematico") || ($pagina == "login") || ($pagina == "inicio") || ($pagina == "contactos") || ($pagina == "formulario") || ($pagina == "pagos") || ($pagina == "convu")  || ($pagina == "canvas_2")) {
                include "templates/".$pagina.".html";
            } elseif (($pagina == "locales_fisicos") ) {
                include "templates/".$pagina.".php";
            } elseif (($pagina == "inicio_secion") || ($pagina == "cerrar_secion") || ($pagina == "enviar_email")) {
                include "static/php/".$pagina.".php";
            } elseif (($pagina == "registro_1") || ($pagina == "dinero_1") || ($pagina == "registro_base_datos_1")) {
                include "templates/fisico_1/".$pagina.".php";
            } elseif (($pagina == "direc_fm_1") || ($pagina == "direc_fm_2") || ($pagina == "what1") || ($pagina == "what2") || ($pagina == "what3") || ($pagina == "face") || ($pagina == "insta") || ($pagina == "telegram")) {
                header("Location: $datos[$pagina]"); // Enviar la cabecera HTTP para redirigir a la URL
            } elseif (($pagina == "registro") ) {
                include "templates/locales_fisicos.php";
            } else {
                include "templates/inicio.html";
            } 
        } else {
            include "templates/inicio.html";
        }

        ?> 
    </div>



    <footer class="pie">
        <h3> EL FISICO MATEMÁTICO </h3>
        <div class='base_pie'>
            <div><a href='contactos'><h4>Contactos</h4></a></div>
            <div><a href='contactos'><h4>Dirección</h4></a></div>
        </div>              
    </footer>


    <!-- ----------------------------------------------------------------------------------------- -->
    <!-- ------------------------------- javascritps final --------------------------------------- -->
    <!-- ----------------------------------------------------------------------------------------- -->

    <!-- LATEX -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script type="text/javascript">
        window.onload = function() {
            $('#onload').fadeOut();
            $('body').removeClass('esconder');
        }
    </script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3.0.1/es5/tex-mml-chtml.js"></script>
    <script>
        MathJax={
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']]
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>   
    <script>
        var menu_boton = document.querySelector(".menu-btn");
        const navMenu = document.querySelector('.menu-horizontal');
        if (typeof menu_boton !== 'undefined' && menu_boton !== null) {
            menu_boton.addEventListener("click", () => {
                navMenu.classList.toggle("show");
            });            
            // Agregar un evento de escucha de teclado y clic al documento
            document.addEventListener("keydown", function(evento) {
                if (evento.key === "Escape") {
                    navMenu.classList.remove("show");
                }
            });
            document.addEventListener('click', (e) => {
                if (!menu_boton.contains(e.target) && !navMenu.contains(e.target)) {
                    navMenu.classList.remove('show');
                }
            });
        }
    </script>
    
</body>
</html>