<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para editar um formulário.";
    header("Location: listarFormulario.php");
    exit;
}

// Coleta os dados do formulário com validação
$id_formulario = $_POST['id_formulario'] ?? null;
$nm_formulario = $_POST['nm_formulario'] ?? null;
$dt_inicio_formulario = $_POST['dt_inicio_formulario'] ?? null;
$dt_fim_formulario = $_POST['dt_fim_formulario'] ?? null;

// Verifica se todos os campos obrigatórios estão preenchidos
if (empty($id_formulario) || empty($nm_formulario) || empty($dt_inicio_formulario) || empty($dt_fim_formulario)) {
    $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
    header("Location: editarFormulario.php?id=" . $id_formulario);
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
    header("Location: editarFormulario.php?id=" . $id_formulario);
    exit;
}

// Atualiza o formulário no banco de dados
$sql = "UPDATE FORMULARIO 
        SET nm_formulario = ?, dt_inicio_formulario = ?, dt_fim_formulario = ? 
        WHERE id_formulario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['mensagem'] = "Ocorreu um erro ao preparar a consulta.";
    header("Location: editarFormulario.php?id=" . $id_formulario);
    exit;
}

$stmt->bind_param("sssi", $nm_formulario, $dt_inicio_formulario, $dt_fim_formulario, $id_formulario);

try {
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Formulário atualizado com sucesso!";
        header("Location: listarFormulario.php");
        exit;
    } else {
        throw new Exception("Ocorreu um erro ao atualizar o formulário.");
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header("Location: editarFormulario.php?id=" . $id_formulario);
    exit;
}

$stmt->close();
$conn->close();
?>