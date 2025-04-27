<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para editar um formulário.</p>";
    exit;
}

require_once 'Formulario.php';

// Coleta os dados do formulário
$id_formulario = $_POST['id_formulario'];
$nome = $_POST['nm_formulario'];
$dataInicio = $_POST['dt_inicio_formulario'];
$dataFim = $_POST['dt_fim_formulario'];
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

// Atualiza o formulário no banco de dados
$sql = "UPDATE FORMULARIO SET nm_formulario = ?, dt_inicio_formulario = ?, dt_fim_formulario = ? WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $nome, $dataInicio, $dataFim, $id_formulario, $idUsuario);

if ($stmt->execute()) {
    echo "<h2>Formulário atualizado com sucesso!</h2>";
    echo "<p><a href='listarFormulario.php'>Ver meus formulários</a></p>";
} else {
    echo "<p>Ocorreu um erro ao atualizar o formulário.</p>";
}

$stmt->close();
$conn->close();
?>