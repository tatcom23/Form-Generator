<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_formulario = $_POST['id_formulario'];

    // Redireciona para a página de compartilhamento
    header("Location: compartilharFormulario.php?id=" . $id_formulario);
    exit;
} else {
    die("Método inválido.");
}
?>