
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
                <li><a href="#">🏠 Inicio</a></li>
                <li><a href="#">💪 Miembros</a></li>
                <li><a href="#">🧑‍🏫 Entrenadores</a></li>
                <li><a href="#">📅 Clases</a></li>
                <li><a href="#">🕓 Asistencias</a></li>
                <li><a href="#">📘 Instructivo</a></li>
            </ul>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">
            <header>
                <div class="user-info">
                    <span><?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></span>
                    <img src="../images/logopna.jpg" alt="Foto de perfil">
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
