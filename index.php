<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
header("Content-Type: text/html; charset=utf-8");
header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');

session_start();

include_once "./app/models/Usuario.php";
$user = new Usuario();
$aute=$user->autenticar($_SERVER['HTTP_OAM_REMOTE_USER']);
if ($aute==0) { 
    include_once __DIR__ . "/app/templates/header.php";
    include_once __DIR__ . "/app/templates/menu.php";
    include_once __DIR__ . "/app/templates/cuerpo.php";
    include_once __DIR__ . "/app/templates/footer.php";
} 
if ($aute==1) {
    include_once __DIR__ . "/app/templates/header.php";
    include_once __DIR__ . "/app/templates/menu-visita.php";
    include_once __DIR__ . "/app/templates/cuerpo.php";
    include_once __DIR__ . "/app/templates/footer-visita.php";
}
if ($aute==2) {
    include_once __DIR__ . "/app/templates/header.php";
    include_once __DIR__ . "/app/templates/menu-visita.php";
    include_once __DIR__ . "/app/templates/cuerpo.php";
    include_once __DIR__ . "/app/templates/footer-error.php";
}
?>