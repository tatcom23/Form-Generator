<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para criar um formulário.</p>";
    exit;
}

require_once 'Formulario.php'; // Caminho relativo para a classe

// Coleta os dados do formulário
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

// Cria um novo objeto Formulario
$formulario = new Formulario($nome, $dataInicio, $dataFim, $idUsuario);

// Salva o formulário no banco de dados
if ($formulario->salvar($conn)) {
    echo "<h2>Formulário criado com sucesso!</h2>";
    echo "<p><a href='listarFormulario.php'>Ver meus formulários</a></p>";
} else {
    echo "<p>Ocorreu um erro ao criar o formulário.</p>";
}

$conn->close();
?>