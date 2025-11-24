<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<header>
    <h1>Cafetería hecha con amor y un pedacito de cielo</h1>
</header>

<!-- botón fijo login/carrito -->
<div class="fixed-login">
    <?php if(!empty($_SESSION['username'])): ?>
        <a class="boton-login-fixed" href="logout.php" title="Cerrar sesión (<?php echo htmlspecialchars($_SESSION['username']); ?>)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>
    <?php else: ?>
        <a class="boton-login-fixed" href="login.php" title="Iniciar sesión">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>
    <?php endif; ?>
</div>

<?php if (!empty($_SESSION['flash_cart'])): ?>
    <div class="flash-cart" style="display:flex;align-items:center;gap:12px;background:#e8f7e8;border:1px solid #c6eac6;padding:8px;margin:12px auto;border-radius:6px;color:#2a7f2a;font-weight:600;max-width:1000px;">
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

<div class="Nav-bg">
    <nav class="NavPrincipal contenedor">
        <a class="navegacion_enlace navegacion_enlace--activo" href="index.php">Inicio</a>
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

<?php if (!empty($_SESSION['cart'])): ?>
    <!-- <div class="cart-preview" style="position:relative;max-width:1000px;margin:8px auto;padding:8px;border-radius:6px;background:#fff8f0;border:1px solid #f0e0d6;">
        <strong style="display:block;margin-bottom:6px;color:#6b4a3a">Carrito — <?php echo array_sum(array_column($_SESSION['cart'], 'qty')); ?> artículo(s)</strong>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <?php foreach ($_SESSION['cart'] as $cid => $it): ?>
                <div style="display:flex;align-items:center;gap:8px;border-radius:6px;padding:6px;background:#fff;box-shadow:0 0 0 1px rgba(0,0,0,0.02);">
                    <?php if (!empty($it['image'])): ?>
                        <img src="<?php echo htmlspecialchars($it['image']); ?>" alt="<?php echo htmlspecialchars($it['name']); ?>" style="width:40px;height:40px;object-fit:cover;border-radius:4px">
                    <?php endif; ?>
                    <div style="font-size:0.92rem;color:#333">
                        <div style="font-weight:600"><?php echo htmlspecialchars($it['name']); ?></div>
                        <div style="color:#666;font-size:0.82rem">x<?php echo (int)$it['qty']; ?> • $<?php echo number_format($it['price'],2); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div style="margin-left:auto">
                <a class="form_submit" href="cart.php">Ver carrito y pagar</a>
            </div>
        </div>
    </div> -->
<?php endif; ?>
