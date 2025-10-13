<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
header("Content-Type: text/html; charset=utf-8");
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CUERPO SANO - Gimnasio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/landing.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">🏋️‍♂️ Cuerpo Sano</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#membresias">Membresías</a></li>
                    <li class="nav-item"><a class="nav-link" href="#clases">Clases</a></li>
                    <li class="nav-item"><a class="nav-link" href="#entrenadores">Entrenadores</a></li>
                    <li class="nav-item">
                        <a href="app/templates/usuario/login.php" class="btn btn-warning ms-3 fw-bold">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="inicio" class="hero d-flex align-items-center text-center text-white">
        <div class="container">
            <h1 class="display-3 fw-bold">Transformá tu cuerpo, transformá tu vida</h1>
            <p class="lead mt-3">En <strong>Cuerpo Sano</strong> te ayudamos a alcanzar tus metas con entrenamientos personalizados y clases únicas.</p>
            <a href="app/templates/usuario/login.php" class="btn btn-lg btn-warning mt-4">Comenzar Ahora</a>
        </div>
    </section>

    <!-- MEMBRESÍAS -->
    <section id="membresias" class="py-5">
        <div class="container text-center">
            <h2 class="mb-4 fw-bold">Planes y Membresías</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Plan Básico</h5>
                            <p class="card-text">Acceso libre a sala de musculación.</p>
                            <p class="display-6 fw-bold text-warning">$8.000/mes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Plan Premium</h5>
                            <p class="card-text">Incluye musculación + clases grupales.</p>
                            <p class="display-6 fw-bold text-warning">$12.000/mes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Plan Full</h5>
                            <p class="card-text">Acceso total + asesoramiento nutricional.</p>
                            <p class="display-6 fw-bold text-warning">$15.000/mes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLASES -->
    <section id="clases" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4 fw-bold">Nuestras Clases</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/crosffit.jpg" class="card-img-top" alt="Crossfit">
                        <div class="card-body">
                            <h5 class="card-title">CrossFit</h5>
                            <p class="card-text">Entrenamiento de fuerza e intensidad.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/yoga.jpg" class="card-img-top" alt="Yoga">
                        <div class="card-body">
                            <h5 class="card-title">Yoga</h5>
                            <p class="card-text">Equilibrio, fuerza y serenidad interior.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/funcional.jpg" class="card-img-top" alt="Funcional">
                        <div class="card-body">
                            <h5 class="card-title">Funcional</h5>
                            <p class="card-text">Entrenamiento dinámico con peso corporal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/spinning.jpg" class="card-img-top" alt="Spinning">
                        <div class="card-body">
                            <h5 class="card-title">Spinning</h5>
                            <p class="card-text">Cardio intenso sobre ruedas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ENTRENADORES -->
    <section id="entrenadores" class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Nuestros Entrenadores</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/trainer1.jpg" class="card-img-top" alt="Entrenador 1">
                        <div class="card-body">
                            <h5 class="card-title">Juan Pérez</h5>
                            <p class="card-text">Especialista en CrossFit y fuerza.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/entrenadora.jpg" class="card-img-top" alt="Entrenador 2">
                        <div class="card-body">
                            <h5 class="card-title">María López</h5>
                            <p class="card-text">Profesora de Yoga y Pilates.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="public/images/entrenador.jpg" class="card-img-top" alt="Entrenador 3">
                        <div class="card-body">
                            <h5 class="card-title">Carlos Gómez</h5>
                            <p class="card-text">Coach en entrenamiento funcional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Información de Contacto -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">🏋️‍♂️ Cuerpo Sano</h5>
                    <p class="mb-2">📍 <strong>Dirección:</strong><br>
                    Av. Corrientes 1234, CABA<br>
                    Buenos Aires, Argentina</p>
                    <p class="mb-2">📞 <strong>Teléfono:</strong><br>
                    (011) 4567-8900</p>
                    <p class="mb-0">📧 <strong>Email:</strong><br>
                    info@cuerposano.com.ar</p>
                </div>
                
                <!-- Horarios -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">🕒 Horarios de Atención</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Lunes a Viernes:</strong></p>
                            <p class="mb-1">6:00 - 23:00</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Fines de Semana:</strong></p>
                            <p class="mb-1">8:00 - 21:00</p>
                        </div>
                    </div>
                    <p class="mt-3 mb-0"><strong>Clases Grupales:</strong><br>
                    Consultar horarios en recepción</p>
                </div>
                
                <!-- Redes Sociales y Links -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">🌐 Síguenos</h5>
                    <div class="mb-3">
                        <a href="#" class="text-warning me-3 fs-4">📘</a>
                        <a href="#" class="text-warning me-3 fs-4">📷</a>
                        <a href="#" class="text-warning me-3 fs-4">🐦</a>
                        <a href="#" class="text-warning fs-4">📱</a>
                    </div>
                    <p class="mb-2"><strong>💪 ¡Únete a nuestra comunidad!</strong></p>
                    <p class="mb-0">Recibe tips de entrenamiento y promociones exclusivas</p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <!-- Copyright -->
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Cuerpo Sano - Todos los derechos reservados | 
                    <a href="#" class="text-warning text-decoration-none">Política de Privacidad</a> | 
                    <a href="#" class="text-warning text-decoration-none">Términos y Condiciones</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
