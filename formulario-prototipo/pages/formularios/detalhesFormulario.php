<?php
session_start();
require_once 'utils.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("<script>
            alert('Você precisa estar logado para acessar esta página.');
            window.location.href = '../login/login.php';
         </script>");
}

require_once 'Pergunta.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>
            alert('ID do formulário não especificado.');
            window.location.href = 'listarFormulario.php';
         </script>");
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

// Verifica se o usuário é o dono do formulário
if (!verificarPropriedadeFormulario($conn, $id_formulario, $_SESSION['id_usuario'])) {
    echo "<script>
            alert('Você não tem permissão para editar este formulário.');
            window.location.href = 'listarFormulario.php';
          </script>";
    exit;
}

// Busca os dados do formulário
$sql_formulario = "SELECT id_formulario, nm_formulario, USUARIO_id_usuario, status 
                   FROM FORMULARIO 
                   WHERE id_formulario = ?";
$stmt_formulario = $conn->prepare($sql_formulario);
$stmt_formulario->bind_param("i", $id_formulario);
$stmt_formulario->execute();
$result_formulario = $stmt_formulario->get_result();

if ($result_formulario->num_rows === 0) {
    die("<script>
            alert('Formulário não encontrado.');
            window.location.href = 'listarFormulario.php';
         </script>");
}

$formulario = $result_formulario->fetch_assoc();

// Verifica se o usuário logado é o criador do formulário
if ($formulario['USUARIO_id_usuario'] != $_SESSION['id_usuario']) {
    echo "<script>
            alert('Você não tem permissão para editar este formulário.');
            window.location.href = 'listarFormulario.php';
          </script>";
    exit;
}

// Verifica se o formulário já foi finalizado
if ($formulario['status'] == 1) {
    echo "<script>
            alert('Este formulário já foi finalizado e não pode ser editado.');
            window.location.href = 'listarFormulario.php';
          </script>";
    exit;
}

// Busca as categorias disponíveis
$sql_categorias = "SELECT id_categoria, nm_categoria 
                   FROM CATEGORIA 
                   WHERE FORMULARIO_id_formulario = ?";
$stmt_categorias = $conn->prepare($sql_categorias);
$stmt_categorias->bind_param("i", $id_formulario);
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

// Busca os tipos de perguntas disponíveis
$sql_tipos_pergunta = "SELECT id_tipo_pergunta, nm_tipo_pergunta 
                       FROM TIPO_PERGUNTA";
$result_tipos_pergunta = $conn->query($sql_tipos_pergunta);
$tipos_pergunta = $result_tipos_pergunta->fetch_all(MYSQLI_ASSOC);

$stmt_formulario->close();
$stmt_categorias->close();
$conn->close();

// Inclui o arquivo de visualização
require_once 'detalhesFormularioView.php';
?>