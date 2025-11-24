<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
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
<body>
    <header>
        <h1>Cafetería hecha con amor y un pedacito de cielo</h1>
    </header>
    <div class="Nav-bg">
    <nav class="NavPrincipal contenedor">
        <a class="navegacion_enlace" href="index.php">Inicio</a>
        <a class="navegacion_enlace" href="nosotros.php">Sobre Nosotros</a>
        <a class="navegacion_enlace" href="clientes.php">Clientes</a>
        <a class="navegacion_enlace navegacion_enlace--activo" href="Contacto.php">Contacto</a>

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
        <h2>Ponte en Contacto con Nosotros</h2>
        <form class="formulario">
            <fieldset>
                <legend>Contactános llenando todos los campos</legend>

                <div class="contenedor-campos">
                    <div class="campo">
                        <label>Nombre:</label>
                        <input class="input-text" type="text" placeholder="Nombre" required>
                    </div>

                    <div class="campo">
                        <label>Teléfono</label>
                        <input class="input-text" type="tel" placeholder="Teléfono" required>
                    </div>

                    <div class="campo">
                        <label>Correo</label>
                        <input class="input-text" type="email" placeholder="Correo Electrónico" required> 
                    </div>

                    <div class="campo">
                        <label>Mensaje</label>
                        <textarea class="input-text"></textarea>
                    </div>
                </div> <!--cierre contenedor campos-->

                <div class="alinear-derecha flex">
                    <input class="boton w-sm-100" type="submit" value="Enviar">
                </div>

            </fieldset>
        </form>
    </main>
    <footer class="footer">
        <p>Todos los derechos reservados. Cachito de Cielo</p>
    </footer> <!--cierre pie de pagina-->
</body>
</html>