<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para acessar esta página.</p>";
    exit;
}

require_once 'Formulario.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>ID do formulário não especificado.</p>";
    exit;
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
$sql = "SELECT id_formulario, nm_formulario, dt_inicio_formulario, dt_fim_formulario FROM FORMULARIO WHERE id_formulario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_formulario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Formulário não encontrado.</p>";
    exit;
}

$formulario = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Formulário - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Editar Formulário</h1>

    <form class="login-form" action="processarEditarFormulario.php" method="POST">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($formulario['id_formulario']); ?>">
        <input type="text" name="nm_formulario" placeholder="Nome do Formulário" value="<?php echo htmlspecialchars($formulario['nm_formulario']); ?>" required>
        <input type="date" name="dt_inicio_formulario" placeholder="Data de Início" value="<?php echo htmlspecialchars($formulario['dt_inicio_formulario']); ?>" required>
        <input type="date" name="dt_fim_formulario" placeholder="Data de Fim" value="<?php echo htmlspecialchars($formulario['dt_fim_formulario']); ?>" required>

        <button type="submit" class="cta-btn">Salvar Alterações</button>
    </form>

</section>

</body>
</html>