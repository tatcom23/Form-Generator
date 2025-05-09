<?php
session_start();
require_once 'utils.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

// Coleta o ID do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formulario_generator";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Atualiza o status do formulário para "finalizado"
    $sql = "UPDATE FORMULARIO SET status = 1 WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_formulario, $_SESSION['id_usuario']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redireciona para a página de visualização
        header("Location: visualizarFormulario.php?id=" . $id_formulario);
        exit;
    } else {
        die("Erro ao finalizar o formulário. Verifique suas permissões.");
    }
} else {
    die("Método inválido ou ID do formulário não especificado.");
}
?>