<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cachito de Cielo</title>
    <link rel="icon" href="img/coffe.ico.crdownload" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+TAS:wght@100..400&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>


<body>
    <?php include __DIR__ . '/header.php'; ?>

    
    <section class="hero">
        <div class="contenido-hero">
            <h2>Cachito de Cielo</h2>
            <div class="ubicacion">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#8ea2c8" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 11a3 3 0 1 0 -3.973 2.839" />
                    <path d="M11.76 21.47a1.991 1.991 0 0 1 -1.173 -.57l-4.244 -4.243a8 8 0 1 1 13.657 -5.588" />
                    <path d="M18 22l3.35 -3.284a2.143 2.143 0 0 0 .005 -3.071a2.242 2.242 0 0 0 -3.129 -.006l-.224 .22l-.223 -.22a2.242 2.242 0 0 0 -3.128 -.006a2.143 2.143 0 0 0 -.006 3.071l3.355 3.296z" />
                </svg>
                <p>Torreón, Coahuila</p>
            </div>
            <a class="boton" href="Contacto.html">Contactar</a>
        </div>
    </section>

     <!-- CARRUSEL DE BANNERS PROMOCIONALES -->
<div class="banner-carousel">
    
    <!-- SLIDE 1: CON CONTADOR DE OFERTA -->
    <div class="banner-item">
        <img src="img/banner1.png" alt="Martes Galletas 3x2">
        <div class="banner-text">
            <h2>¡Martes de Galletas!</h2>
            <p>3x2 en todas las galletas</p>
            
            <!-- CONTADOR DE OFERTA -->
            <div class="contador-oferta" style="
                margin-top: 15px;
                padding: 15px;
                background: linear-gradient(135deg, #685c69ff 0%, #b08389ff 100%);
                border-radius: 8px;
            ">
                <div style="font-size: 14px; margin-bottom: 8px; font-weight: 600;">
                    Oferta empieza en:
                </div>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <div style="background: rgba(255,255,255,0.3); padding: 8px 12px; border-radius: 6px; min-width: 60px; text-align: center;">
                        <div id="carousel-horas" style="font-size: 24px; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 10px; opacity: 0.9; margin-top: 2px;">HORAS</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.3); padding: 8px 12px; border-radius: 6px; min-width: 60px; text-align: center;">
                        <div id="carousel-minutos" style="font-size: 24px; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 10px; opacity: 0.9; margin-top: 2px;">MIN</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.3); padding: 8px 12px; border-radius: 6px; min-width: 60px; text-align: center;">
                        <div id="carousel-segundos" style="font-size: 24px; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 10px; opacity: 0.9; margin-top: 2px;">SEG</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SLIDE 2 -->
    <div class="banner-item">
        <img src="img/banner2.png" alt="Bebidas en descuento">
        <div class="banner-text">
            <h2>Refréscate</h2>
            <p>20% OFF en bebidas todos los jueves</p>
        </div>
    </div>
    
    <!-- SLIDE 3 -->
    <div class="banner-item">
        <img src="img/banner3.png" alt="Combo Dulce">
        <div class="banner-text">
            <h2>Combo Dulce</h2>
            <p>Lleva 2 y paga 1 ¡Solo los lunes!</p>
        </div>
    </div>
    
</div>
    <!-- FIN CARRUSEL DE BANNERS PROMOCIONALES -->

    <main class="contenedor sombra">  
        <h2>Productos</h2>
        <div class="productos">

            <section class="Producto">
                <a class="pr_roles" href="roles.php"><h3>Roles de canela</h3></a>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#765e56"><g fill="none" stroke="#765e56" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M22 9c0 3.314-4.477 6-10 6S2 12.314 2 9s4.477-6 10-6s10 2.686 10 6"/><path d="M12 6.854c3-1.413 5 .863 5 1.928c0 3.17-10 3.005-10-.9C7 5.498 9 3 12 3"/><path d="M22 9v6c0 3.314-4.477 6-10 6S2 18.314 2 15V9"/></g></svg>
                </div>
                <p>Suaves, esponjosos y con el toque perfecto de canela. Cubiertos con glaseado cremoso que se derrite en tu boca y te invita a disfrutar cada mordida.</p>
            </section>

            <section class="Producto">
                <a class="pr_pasteles" href="pastel.php"><h3>Pasteles</h3></a>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#735b54"><g fill="none" stroke="#735b54" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="9" cy="7" r="2"/><path d="M7.2 7.9L3 11v9c0 .6.4 1 1 1h16c.6 0 1-.4 1-1v-9c0-2-3-6-7-8l-3.6 2.6M16 13H3m13 4H3"/></g></svg>
                </div>
                <p>El detalle ideal para celebrar cualquier ocasión. Esponjosos, decorados con amor y elaborados con ingredientes frescos para un sabor inolvidable.</p>
            </section>

            <section class="Producto">
                <a class="pr_galletas" href="galleta.php"><h3>Galletas</h3></a>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><circle cx="10" cy="21" r="2" fill="#735b54"/><circle cx="23" cy="20" r="2" fill="#735b54"/><circle cx="13" cy="10" r="2" fill="#735b54"/><circle cx="14" cy="15" r="1" fill="#735b54"/><circle cx="23" cy="5" r="2" fill="#735b54"/><circle cx="29" cy="3" r="1" fill="#735b54"/><circle cx="16" cy="23" r="1" fill="#735b54"/><path fill="#735b54" d="M16 30C8.3 30 2 23.7 2 16S8.3 2 16 2h.3l1.4.1l-.3 1.2c-.1.4-.2.9-.2 1.3c0 2.8 2.2 5 5 5c1 0 2-.3 2.9-.9l1.3 1.5c-.4.4-.6.9-.6 1.4c0 1.3 1.3 2.4 2.7 1.9l1.2-.5l.2 1.3c.1.6.1 1.2.1 1.7c0 7.7-6.3 14-14 14zm-.7-26C9 4.4 4 9.6 4 16c0 6.6 5.4 12 12 12s12-5.4 12-12v-.4c-2.3.1-4.2-1.7-4.2-4v-.2c-.5.1-1 .2-1.6.2c-3.9 0-7-3.1-7-7c0-.2 0-.4.1-.6z"/></svg>
                </div>
                <p>Crujientes por fuera, suaves por dentro y con ese toque casero que alegra el corazón. Perfectas para compartir o para consentirte a ti mismo.</p>
            </section>

            <section class="Producto">
                <a class="pr_bebidas" href="bebida.php"><h3>Bebidas</h3></a>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="#735b54"><path fill="#735b54" d="M5.5 2.75a.75.75 0 0 0-1.5 0c0 1.27.928 1.917 1.506 2.32l.064.044c.68.476.93.705.93 1.136a.75.75 0 0 0 1.5 0c0-1.27-.928-1.917-1.506-2.32l-.064-.044C5.75 3.41 5.5 3.18 5.5 2.75ZM3 9.821A1.82 1.82 0 0 1 4.821 8H17.18A1.82 1.82 0 0 1 19 9.821v.679h.75a3.25 3.25 0 0 1 0 6.5h-1.331A8.003 8.003 0 0 1 3 14V9.821Zm14.5 0a.321.321 0 0 0-.321-.321H4.82a.321.321 0 0 0-.321.321V14a6.5 6.5 0 1 0 13 0V9.821ZM19.75 12H19v2a8.04 8.04 0 0 1-.14 1.5h.89a1.75 1.75 0 1 0 0-3.5Z"/></svg>
                </div>
                <p>Desde cafés artesanales hasta tés reconfortantes y chocolates calientes cremosos. Perfectas para acompañar tus momentos dulces.</p>
            </section>

        </div>
    </main>

    <footer class="footer">
        <p>Todos los derechos reservados. Cachito de Cielo</p>
    </footer>

<script>
    function mostrarMensajeBienvenida() {
    const hora = new Date().getHours();
    let mensaje = '';
    let emoji = '';
    
    if (hora >= 5 && hora < 12) {
        mensaje = '¡Buenos días! Perfecto para un café mañanero';
    } else if (hora >= 12 && hora < 18) {
        mensaje = '¡Buenas tardes! ¿Qué tal un postre delicioso?';
    } else {
        mensaje = '¡Buenas noches!  Relájate con algo dulce';
    }
    
    // Crear el elemento de bienvenida
    const bienvenida = document.createElement('div');
    bienvenida.id = 'mensaje-bienvenida';
    bienvenida.innerHTML = `
        <div style="
            background: linear-gradient(135deg, #9b856c 0%, #c9a388 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 1000px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideDown 0.5s ease-out;
        ">
            <h2 style="margin: 0; font-size: 1.8rem; font-weight: bold; color: #fff;">
                ${mensaje}
            </h2>
        </div>
    `;
    
    // Insertar después del header
    const header = document.querySelector('header') || document.querySelector('.Nav-bg');
    if (header) {
        header.insertAdjacentElement('afterend', bienvenida);
    }
    
    // Agregar animación
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
}
</script>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando efectos del index...');
    
    // 1. Mostrar mensaje de bienvenida
    mostrarMensajeBienvenida();
    
    // 2. Iniciar contador de ofertas
    crearContadorOfertas();
    
    console.log('Efectos del index cargados');
});
</script>

<script>
// Función para calcular tiempo restante hasta medianoche
function calcularTiempoRestante() {
    const ahora = new Date();
    const diaActual = ahora.getDay(); // 0=Domingo, 1=Lunes, 2=Martes, etc.
    
    let proximoMartes = new Date();
    
    // Si hoy es martes (día 2)
    if (diaActual === 1) {
        // Contar hasta el final del día de hoy
        proximoMartes.setHours(23, 59, 59, 999);
    } else {
        // Calcular días hasta el próximo martes
        let diasHastaMartes;
        if (diaActual < 2) {
            // Si es domingo (0) o lunes (1)
            diasHastaMartes = 2 - diaActual;
        } else {
            // Si es miércoles en adelante
            diasHastaMartes = 7 - (diaActual - 2);
        }
        
        proximoMartes.setDate(ahora.getDate() + diasHastaMartes);
        proximoMartes.setHours(23, 59, 59, 999);
    }
    
    const diferencia = proximoMartes - ahora;
    
    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
    const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
    const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
    
    return { dias, horas, minutos, segundos };
}

// Actualizar el contador del carrusel
function actualizarContadorCarrusel() {
    const tiempo = calcularTiempoRestante();
    
    const horasElement = document.getElementById('carousel-horas');
    const minutosElement = document.getElementById('carousel-minutos');
    const segundosElement = document.getElementById('carousel-segundos');
    
    if (horasElement) horasElement.textContent = String(tiempo.horas).padStart(2, '0');
    if (minutosElement) minutosElement.textContent = String(tiempo.minutos).padStart(2, '0');
    if (segundosElement) segundosElement.textContent = String(tiempo.segundos).padStart(2, '0');
}

// Iniciar el contador cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContadorCarrusel();
    setInterval(actualizarContadorCarrusel, 1000);
    console.log('Contador de carrusel iniciado');
});
</script>

<!-- Efectos del index -->
<script src="js/efectos_index.js"></script>

<!-- Carrusel -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/carrusel.js"></script>

<!-- iconos -->
<script src="js/hover_iconos.js"></script>

</body>
</html>

