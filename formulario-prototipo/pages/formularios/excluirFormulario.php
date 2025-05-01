<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para excluir um formulário.</p>";
    exit;
}

require_once 'Formulario.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do formulário não especificado.";
    header("Location: listarFormulario.php");
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
    $_SESSION['mensagem'] = "Erro de conexão com o banco de dados.";
    header("Location: listarFormulario.php");
    exit;
}

// Exclui o formulário do banco de dados
$sql = "DELETE FROM FORMULARIO WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_formulario, $idUsuario);

try {
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Formulário excluído com sucesso!";
    } else {
        throw new Exception("Ocorreu um erro ao excluir o formulário.");
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
}

$stmt->close();
$conn->close();

// Redireciona para listarFormulario.php
header("Location: listarFormulario.php");
exit;
?>