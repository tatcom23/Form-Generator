<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

require_once 'Pergunta.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do formulário não especificado.");
}

$id_formulario = $_GET['id'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca os dados do formulário
$sql_formulario = "SELECT id_formulario, nm_formulario FROM FORMULARIO WHERE id_formulario = ?";
$stmt_formulario = $conn->prepare($sql_formulario);
$stmt_formulario->bind_param("i", $id_formulario);
$stmt_formulario->execute();
$result_formulario = $stmt_formulario->get_result();

if ($result_formulario->num_rows === 0) {
    die("Formulário não encontrado.");
}

$formulario = $result_formulario->fetch_assoc();

// Busca as categorias disponíveis
$sql_categorias = "SELECT id_categoria, nm_categoria FROM CATEGORIA WHERE FORMULARIO_id_formulario = ?";
$stmt_categorias = $conn->prepare($sql_categorias);
$stmt_categorias->bind_param("i", $id_formulario);
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

// Busca os tipos de perguntas disponíveis
$sql_tipos_pergunta = "SELECT id_tipo_pergunta, nm_tipo_pergunta FROM TIPO_PERGUNTA";
$result_tipos_pergunta = $conn->query($sql_tipos_pergunta);
$tipos_pergunta = $result_tipos_pergunta->fetch_all(MYSQLI_ASSOC);

$stmt_formulario->close();
$stmt_categorias->close();
$conn->close();

// Inclui o arquivo de visualização
require_once 'detalhesFormularioView.php';
?>