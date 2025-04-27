<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para excluir um formulário.</p>";
    exit;
}

require_once 'Formulario.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>ID do formulário não especificado.</p>";
    exit;
}

$id_formulario = $_GET['id'];
$idUsuario = $_SESSION['id_usuario'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Exclui o formulário do banco de dados
$sql = "DELETE FROM FORMULARIO WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_formulario, $idUsuario);

if ($stmt->execute()) {
    echo "<h2>Formulário excluído com sucesso!</h2>";
    echo "<p><a href='listarFormulario.php'>Ver meus formulários</a></p>";
} else {
    echo "<p>Ocorreu um erro ao excluir o formulário.</p>";
}

$stmt->close();
$conn->close();
?>