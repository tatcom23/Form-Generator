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

// Busca as perguntas do formulário
$sql_perguntas = "SELECT p.id_pergunta, p.ds_pergunta, tp.nm_tipo_pergunta 
                  FROM PERGUNTA p
                  JOIN TIPO_PERGUNTA tp ON p.TIPO_PERGUNTA_id_tipo_pergunta = tp.id_tipo_pergunta
                  WHERE p.FORMULARIO_id_formulario = ?";
$stmt_perguntas = $conn->prepare($sql_perguntas);
$stmt_perguntas->bind_param("i", $id_formulario);
$stmt_perguntas->execute();
$result_perguntas = $stmt_perguntas->get_result();
$perguntas = $result_perguntas->fetch_all(MYSQLI_ASSOC);

$stmt_formulario->close();
$stmt_perguntas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Formulário - <?php echo htmlspecialchars($formulario['nm_formulario']); ?></title>
    <link rel="stylesheet" href="../../css/listarFormulario.css">
</head>
<body>
<section class="container">
    <h1><?php echo htmlspecialchars($formulario['nm_formulario']); ?></h1>

    <form class="formulario" action="confirmarFormulario.php" method="POST">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($formulario['id_formulario']); ?>">

        <?php foreach ($perguntas as $pergunta): ?>
            <div class="pergunta">
                <label><strong><?php echo htmlspecialchars($pergunta['ds_pergunta']); ?></strong></label>
                <p>Tipo: <?php echo htmlspecialchars($pergunta['nm_tipo_pergunta']); ?></p>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-confirmar">Confirmar Formulário</button>
    </form>
</section>
</body>
</html>