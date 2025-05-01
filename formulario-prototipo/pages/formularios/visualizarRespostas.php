<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para acessar esta página.</p>";
    exit;
}

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>ID do formulário não especificado.</p>";
    exit;
}

$id_formulario = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca as perguntas e respostas do formulário
$sql = "
    SELECT p.ds_pergunta, r.ds_resposta, ru.dt_resposta_usuario 
    FROM RESPOSTA r
    JOIN PERGUNTA p ON r.id_pergunta = p.id_pergunta
    JOIN RESPOSTA_USUARIO ru ON r.id_resposta = ru.RESPOSTA_id_resposta
    WHERE p.FORMULARIO_id_formulario = ? AND ru.USUARIO_id_usuario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_formulario, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$respostas = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Respostas - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Respostas Enviadas</h1>

    <?php if (count($respostas) > 0): ?>
        <table border="1">
            <tr>
                <th>Pergunta</th>
                <th>Resposta</th>
                <th>Data da Resposta</th>
            </tr>
            <?php foreach ($respostas as $resposta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($resposta['ds_pergunta']); ?></td>
                    <td><?php echo htmlspecialchars($resposta['ds_resposta']); ?></td>
                    <td><?php echo htmlspecialchars($resposta['dt_resposta_usuario']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhuma resposta encontrada.</p>
    <?php endif; ?>

    <p><a href="listarFormulario.php" class="cta-btn">Voltar para Meus Formulários</a></p>
</section>

</body>
</html>