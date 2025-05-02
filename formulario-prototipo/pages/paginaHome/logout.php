<?php
session_start();
session_destroy();
header("Location: /formulario-prototipo/pages/login/login.php");
exit();
?>
