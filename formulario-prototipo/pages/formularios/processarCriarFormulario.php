<?php
session_start(); // Inicia a sessão para obter o ID do usuário logado

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para criar um formulário.";
    header("Location: listarFormulario.php");
    exit;
}

// Coleta os dados do formulário com validação
$nm_formulario = $_POST['nm_formulario'] ?? null;
$dt_inicio_formulario = $_POST['dt_inicio_formulario'] ?? null;
$dt_fim_formulario = $_POST['dt_fim_formulario'] ?? null;

// Verifica se todos os campos obrigatórios estão preenchidos
if (empty($nm_formulario) || empty($dt_inicio_formulario) || empty($dt_fim_formulario)) {
    $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
    header("Location: criarFormulario.php");
    exit;
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Altere para o seu usuário do MySQL
$password = "";     // Altere para a sua senha do MySQL
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    $_SESSION['mensagem'] = "Erro de conexão com o banco de dados.";
    header("Location: criarFormulario.php");
    exit;
}

// Insere o formulário no banco de dados
$sql = "INSERT INTO FORMULARIO (nm_formulario, dt_inicio_formulario, dt_fim_formulario, USUARIO_id_usuario)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['mensagem'] = "Ocorreu um erro ao preparar a consulta.";
    header("Location: criarFormulario.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$stmt->bind_param("sssi", $nm_formulario, $dt_inicio_formulario, $dt_fim_formulario, $id_usuario);

try {
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Formulário criado com sucesso!";
        header("Location: listarFormulario.php");
        exit;
    } else {
        throw new Exception("Ocorreu um erro ao criar o formulário.");
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header("Location: criarFormulario.php");
    exit;
}

$stmt->close();
$conn->close();
?>