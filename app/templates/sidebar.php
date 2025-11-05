<aside class="sidebar">
    <h2>CUERPO SANO</h2>
    <ul>
        <li><a href="../templates/dashboard.php">🏠 Inicio</a></li>
        <li><a href="../controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
        <li><a href="../controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
        <li><a href="../controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
        <li><a href="../controllers/MembresiaController.php?accion=listar">🎟️ Membresías</a></li>
        <li><a href="../controllers/ClaseController.php?accion=listar">📅 Clases</a></li>
        <li><a href="../controllers/ClaseHorarioController.php?accion=listar">📅 Horarios</a></li>
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