
<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verificar que el usuario esté logueado
if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: usuario/login.php?error=Debe+iniciar+sesión+para+acceder+al+dashboard");
    exit;
}

// Obtener datos del usuario de la sesión
$usuario = $_SESSION['USUARIO'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Cuerpo Sano</title>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
    <script src="../../public/js/app/class/dashboard.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- MENU LATERAL -->
        <aside class="sidebar">
            <h2>CUERPO SANO</h2>
            <ul>
                <li><a href="/CUERPO-SANO/app/templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/MembresiaController.php?accion=listar">🎟️ Membresías</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClaseController.php?accion=listar">📅 Clases</a></li>
                <li><a href="#">🕓 Asistencias</a></li>
                <li><a href="#">📘 Instructivo</a></li>
            </ul>
            
            <!-- Información del usuario y logout -->
            <div class="user-sidebar">
                <div class="user-info-sidebar">
                    <span class="user-name-sidebar"><?php echo htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']); ?></span>
                    <span class="user-dni-sidebar">DNI: <?php echo htmlspecialchars($usuario['dni']); ?></span>
                    <span class="user-role-sidebar"><?php echo htmlspecialchars($usuario['rol']); ?></span>
                </div>
                <a href="../controllers/UsuarioController.php?accion=logout" class="btn-logout-sidebar">🚪 Cerrar Sesión</a>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">
            <header>
                <div class="header-content">
                    <h1>Panel de Control</h1>
                    <div class="header-actions">
                        <a href="usuario/cambiar_contrasena.php" class="btn-change-password">🔒 Cambiar Contraseña</a>
                    </div>
                </div>
            </header>

            <section class="content">
                <h1>Clases de Hoy</h1>
                <div class="cards-container">
                    <div class="card">
                        <h3>CrossFit</h3>
                        <p>08:00 - 09:00</p>
                        <span>Instructor: Juan Pérez</span>
                    </div>
                    <div class="card">
                        <h3>Yoga</h3>
                        <p>10:30 - 11:30</p>
                        <span>Instructor: María López</span>
                    </div>
                    <div class="card">
                        <h3>Funcional</h3>
                        <p>18:00 - 19:00</p>
                        <span>Instructor: Ana Torres</span>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
