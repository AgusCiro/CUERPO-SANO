<?php 
session_start();
session_destroy();
header("Location: https://intranet.prefecturanaval.gob.ar/webcenter/portal/IntranetPNA/");
?>