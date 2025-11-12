<?php
// app/controllers/DashboardController.php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: ../templates/usuario/login.php?error=Debe+iniciar+sesión");
    exit;
}

include_once __DIR__ . '/../models/ClaseHorario.php';

// --- Lógica del Calendario ---

// Obtener el mes y año solicitados, o usar los actuales por defecto
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');

// Validar mes y año para evitar errores
if ($mes < 1 || $mes > 12) {
    $mes = date('n');
}
if ($anio < 2000 || $anio > 2100) {
    $anio = date('Y');
}

// Crear una instancia del modelo
$claseHorarioModel = new ClaseHorario();

// Obtener los horarios del mes y año
$horarios = $claseHorarioModel->obtenerHorariosPorMesAnio($mes, $anio);

// Procesar los horarios para agruparlos por día
$clasesPorDia = [];
foreach ($horarios as $horario) {
    $dia = date('j', strtotime($horario['fecha_inicio']));
    if (!isset($clasesPorDia[$dia])) {
        $clasesPorDia[$dia] = [];
    }
    $clasesPorDia[$dia][] = $horario;
}

// --- Datos para la Paginación del Calendario ---

// Mes y año anterior
$mesAnterior = ($mes == 1) ? 12 : $mes - 1;
$anioAnterior = ($mes == 1) ? $anio - 1 : $anio;

// Mes y año siguiente
$mesSiguiente = ($mes == 12) ? 1 : $mes + 1;
$anioSiguiente = ($mes == 12) ? $anio + 1 : $anio;

// Nombre del mes actual en español
setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');
$nombreMes = strftime('%B', mktime(0, 0, 0, $mes, 1, $anio));


// --- Información para construir el grid del calendario ---
$primerDiaDelMes = date('N', mktime(0, 0, 0, $mes, 1, $anio)); // 1 (lunes) a 7 (domingo)
$diasEnMes = date('t', mktime(0, 0, 0, $mes, 1, $anio));

// Incluir la vista del dashboard
include_once __DIR__ . '/../templates/dashboard.php';

?>
