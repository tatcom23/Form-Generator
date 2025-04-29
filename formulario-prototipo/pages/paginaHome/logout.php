<?php
session_start();
session_destroy();
header("Location: /form-generator/formulario-prototipo/pages/login/login.php");
exit();
?>
