<?php
session_start(); // Inicia a sessão para obter o ID do usuário logado

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para criar um formulário.</p>";
    exit;
}

// Coleta os dados do formulário
$nm_formulario = $_POST['nm_formulario'];
$dt_inicio_formulario = $_POST['dt_inicio_formulario'];
$dt_fim_formulario = $_POST['dt_fim_formulario'];
$id_usuario = $_SESSION['id_usuario']; // ID do usuário logado

// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Altere para o seu usuário do MySQL
$password = "";     // Altere para a sua senha do MySQL
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Insere o formulário no banco de dados
$sql = "INSERT INTO FORMULARIO (nm_formulario, dt_inicio_formulario, dt_fim_formulario, USUARIO_id_usuario)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nm_formulario, $dt_inicio_formulario, $dt_fim_formulario, $id_usuario);

if ($stmt->execute()) {
    echo "<h2>Formulário criado com sucesso!</h2>";
    echo "<p><a href='listarFormularios.php'>Ver meus formulários</a></p>";
} else {
    echo "<p>Ocorreu um erro ao criar o formulário: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>