<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cachito de Cielo </title>
    <link rel="icon" href="img/coffe.ico.crdownload" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+TAS:wght@100..400&display=swap" rel="stylesheet">
    <link rel="preload" href="css/style.css" as="style">
    <link href="css/style.css" rel="stylesheet">
    
</head>

<body>    <!--Encabezado (asi se comenta)-->
    <header>
        <h1>Cafetería hecha con amor y un pedacito de cielo</h1>
    </header>
    <div class="Nav-bg">
    <nav class="NavPrincipal contenedor">
        <a class="navegacion_enlace" href="index.php">Inicio</a>
        <a class="navegacion_enlace" href="nosotros.php">Sobre Nosotros</a>
        <a class="navegacion_enlace navegacion_enlace--activo" href="clientes.php">Clientes</a>
        <a class="navegacion_enlace" href="Contacto.php">Contacto</a>

        <div class="nav-actions">
        <?php if(!empty($_SESSION['username'])): ?>
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a class="navegacion_enlace" href="panel_admin.php">Panel Admin</a>
            <?php endif; ?>
            <a class="navegacion_enlace" href="user.php">Mi cuenta (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            <a class="navegacion_enlace" href="logout.php">Cerrar sesión</a>
            <a class="navegacion_enlace" href="cart.php" title="Ver carrito">Carrito (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>)</a>
        <?php else: ?>
            <a class="navegacion_enlace" href="login.php">Iniciar sesión</a>
            <a class="navegacion_enlace" href="register.php">Registrarse</a>
            <a class="navegacion_enlace" href="cart.php" title="Ver carrito">Carrito (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>)</a>
        <?php endif; ?>
        </div>
    </nav>
</div>

    <main class="contenedor sombra">  
        <h2>Nuestros Clientes</h2>
        <div class = "clientes">  
            <div class = "clientes__contenido">
                <p>En Cachito de Cielo, valoramos profundamente a nuestros clientes y nos enorgullece contar con una comunidad diversa y apasionada que comparte nuestro amor por el café de calidad. Cada cliente que cruza nuestras puertas es una parte esencial de nuestra historia, y nos esforzamos por ofrecerles una experiencia única y memorable en cada visita.</p>

                <p>Nuestros clientes provienen de todos los ámbitos de la vida, desde profesionales ocupados que buscan un refugio tranquilo para disfrutar de su café matutino, hasta estudiantes creativos que encuentran inspiración en nuestro ambiente acogedor. También tenemos el placer de atender a familias que disfrutan de momentos especiales juntos, así como a amigos que se reúnen para compartir risas y conversaciones alrededor de una taza humeante.</p>

                <p>Lo que une a nuestros clientes es su aprecio por la calidad, la autenticidad y el servicio excepcional. Muchos de ellos son conocedores del café, siempre interesados en explorar nuevas variedades y métodos de preparación. Otros son simplemente amantes del café que buscan un lugar donde puedan relajarse y disfrutar de una bebida bien elaborada.</p>

                <p>Nos sentimos honrados de ser parte de la rutina diaria de nuestros clientes y de celebrar con ellos ocasiones especiales. Sus historias, sus sonrisas y su lealtad son lo que nos motiva a seguir mejorando y ofreciendo lo mejor de nosotros en cada taza que servimos.</p>

                <p>En Cachito de Cielo, nuestros clientes no son solo visitantes; son parte de nuestra familia cafetera. Agradecemos su confianza y esperamos seguir compartiendo momentos inolvidables juntos, siempre con un pedacito de cielo en cada sorbo.</p>
            </div>
            <img class="clientes__imagen" src="img/clientes.jpeg" alt="Imagen clientes">
        </div>
    </main>
    <footer class="footer">
        <p>Todos los derechos reservados. Cachito de Cielo</p>
    </footer> <!--cierre pie de pagina-->

</body>
</html>