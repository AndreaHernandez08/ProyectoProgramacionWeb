<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cachito de Cielo - Bebidas</title>
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
        <a class="navegacion_enlace" href="clientes.php">Clientes</a>
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
        <h2>Bebidas</h2> 

    <div class="grid">
        <?php if (!empty($_SESSION['flash_cart'])): ?>
            <div class="flash-cart" style="display:flex;align-items:center;gap:12px;background:#e8f7e8;border:1px solid #c6eac6;padding:8px;margin-bottom:12px;border-radius:6px;color:#2a7f2a;font-weight:600;">
                <?php if (!empty($_SESSION['flash_cart']['image'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['flash_cart']['image']); ?>" alt="<?php echo htmlspecialchars($_SESSION['flash_cart']['name']); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:6px">
                <?php endif; ?>
                <div>
                    <div><?php echo htmlspecialchars($_SESSION['flash_cart']['message']); ?></div>
                    <?php if (isset($_SESSION['flash_cart']['price'])): ?>
                        <div style="font-weight:500;color:#2a7f2a">Precio: $<?php echo number_format((float)$_SESSION['flash_cart']['price'],2); ?></div>
                    <?php endif; ?>
                </div>
                <div style="margin-left:auto">
                    <a class="form_submit" href="cart.php">Ver carrito</a>
                </div>
                <?php unset($_SESSION['flash_cart']); ?>
            </div>
        <?php endif; ?>
        <div class="comida"> 
            <a>
                <img class="pro_imagen" src="img/cafe.jpeg" alt="latte">
                <div class="pro_informacion">
                    <p class="pro_nombre">Latte en las Rocas</p>
                    <p class="pro_precio">$75</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_latte_rocas">
                        <input type="hidden" name="name" value="Latte en las Rocas">
                        <input type="hidden" name="price" value="75.00">
                        <input type="hidden" name="image" value="img/cafe.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida-->
       <div class="comida">
            <a>
                <img class="pro_imagen" src="img/choco.jpeg" alt="chocolate caliente">
                <div class="pro_informacion">
                    <p class="pro_nombre">Chocolate Caliente</p>
                    <p class="pro_precio">$65</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_chocolate_caliente">
                        <input type="hidden" name="name" value="Chocolate Caliente">
                        <input type="hidden" name="price" value="65.00">
                        <input type="hidden" name="image" value="img/choco.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida-->
        <div class="comida">
            <a>
                <img class="pro_imagen" src="img/latte.jpeg" alt="latte de caramelo">
                <div class="pro_informacion">
                    <p class="pro_nombre">Latte de Caramelo</p>
                    <p class="pro_precio">$75</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_latte_caramelo">
                        <input type="hidden" name="name" value="Latte de Caramelo">
                        <input type="hidden" name="price" value="75.00">
                        <input type="hidden" name="image" value="img/latte.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida-->
        <div class="comida">
            <a>
                <img class="pro_imagen" src="img/berry.jpeg" alt="matcha berry">
                <div class="pro_informacion">
                    <p class="pro_nombre">Matcha Berry</p>
                    <p class="pro_precio">$85</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_matcha_berry">
                        <input type="hidden" name="name" value="Matcha Berry">
                        <input type="hidden" name="price" value="85.00">
                        <input type="hidden" name="image" value="img/berry.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida-->
        <div class="comida">
            <a>
                <img class="pro_imagen" src="img/italiana.jpeg" alt="soda italiana">
                <div class="pro_informacion">
                    <p class="pro_nombre">Soda Italiana</p>
                    <p class="pro_precio">$70</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_soda_italiana">
                        <input type="hidden" name="name" value="Soda Italiana">
                        <input type="hidden" name="price" value="70.00">
                        <input type="hidden" name="image" value="img/italiana.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida-->
        <div class="comida">
            <a>
                <img class="pro_imagen" src="img/tisiana.jpeg" alt="tisiana">
                <div class="pro_informacion">
                    <p class="pro_nombre">Tisiana</p>
                    <p class="pro_precio">$75</p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="bebida_tisiana">
                        <input type="hidden" name="name" value="Tisiana">
                        <input type="hidden" name="price" value="75.00">
                        <input type="hidden" name="image" value="img/tisiana.jpeg">
                        <input type="hidden" name="category" value="bebidas">
                        <input type="number" name="qty" value="1" min="1"> 
                        <button class="form_submit" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </a>
        </div> <!--cierre comida--> 
    </div>
    </main>

    <footer class="footer">
        <p>Todos los derechos reservados. Cachito de Cielo</p>
    </footer> <!--cierre pie de pagina-->

</body>
</html>
</html>
