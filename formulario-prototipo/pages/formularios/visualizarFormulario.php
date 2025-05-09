<?php
session_start();
require_once 'utils.php';

// Verificação de login
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

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca o nome e status do formulário
$sql_nome_formulario = "SELECT nm_formulario, status FROM FORMULARIO WHERE id_formulario = ?";
$stmt_nome_formulario = $conn->prepare($sql_nome_formulario);
$stmt_nome_formulario->bind_param("i", $id_formulario);
$stmt_nome_formulario->execute();
$result_nome_formulario = $stmt_nome_formulario->get_result();
$formulario = $result_nome_formulario->fetch_assoc();

if (!$formulario) {
    echo "<p>Formulário não encontrado.</p>";
    exit;
}

$nome_formulario = $formulario['nm_formulario'] ?? 'Formulário';
$status_formulario = $formulario['status'];

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

// Busca as opções de resposta para perguntas de múltipla escolha e única escolha
$opcoes_resposta = [];
foreach ($perguntas as $pergunta) {
    $sql_opcoes = "SELECT id_resposta, ds_resposta 
                   FROM RESPOSTA 
                   WHERE id_pergunta = ?";
    $stmt_opcoes = $conn->prepare($sql_opcoes);
    $stmt_opcoes->bind_param("i", $pergunta['id_pergunta']);
    $stmt_opcoes->execute();
    $result_opcoes = $stmt_opcoes->get_result();
    $opcoes_resposta[$pergunta['id_pergunta']] = $result_opcoes->fetch_all(MYSQLI_ASSOC);
    $stmt_opcoes->close();
}

$stmt_nome_formulario->close();
$stmt_perguntas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Formulário - <?php echo htmlspecialchars($nome_formulario); ?></title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>

<section class="login-container">
    <h1><?php echo htmlspecialchars($nome_formulario); ?></h1>

    <!-- Botão Finalizar Formulário -->
    <?php if ($status_formulario == 0): ?>
        <form action="finalizarFormulario.php" method="POST" style="display: inline;">
            <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($id_formulario); ?>">
            <button type="submit" class="cta-btn" onclick="return confirm('Tem certeza que deseja finalizar este formulário? Após finalizar, não será possível adicionar novas perguntas.')">
                <i class="fas fa-check"></i> Finalizar Formulário
            </button>
        </form>
    <?php else: ?>
        <button class="cta-btn" disabled>
            <i class="fas fa-lock"></i> Formulário Finalizado
        </button>
    <?php endif; ?>

    <div class="login-form">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($id_formulario); ?>">

        <?php foreach ($perguntas as $pergunta): ?>
            <div>
                <label><strong><?php echo htmlspecialchars($pergunta['ds_pergunta']); ?></strong></label>
                <br>
                <?php if ($pergunta['nm_tipo_pergunta'] === 'Texto'): ?>
                    <p>Tipo de Resposta: Texto</p>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'E-mail'): ?>
                    <p>Tipo de Resposta: E-mail</p>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Múltipla Escolha'): ?>
                    <p>Tipo de Resposta: Múltipla Escolha</p>
                    <?php foreach ($opcoes_resposta[$pergunta['id_pergunta']] ?? [] as $opcao): ?>
                        <div class="opcao">
                            <span><?php echo htmlspecialchars($opcao['ds_resposta']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Única Escolha'): ?>
                    <p>Tipo de Resposta: Única Escolha</p>
                    <?php foreach ($opcoes_resposta[$pergunta['id_pergunta']] ?? [] as $opcao): ?>
                        <div class="opcao">
                            <span><?php echo htmlspecialchars($opcao['ds_resposta']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Data'): ?>
                    <p>Tipo de Resposta: Data</p>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Número'): ?>
                    <p>Tipo de Resposta: Número</p>
                <?php endif; ?>
            </div>
            <br>
        <?php endforeach; ?>
    </div>

    <p><a href="../paginaHome/homeAdmin.php" class="cta-btn">🏠 Página Inicial</a></p>
</section>

</body>
</html>