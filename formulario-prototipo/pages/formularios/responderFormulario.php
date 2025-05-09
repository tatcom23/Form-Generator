<?php
session_start();

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>ID do formulário não especificado.</p>";
    exit;
}

$id_formulario = $_GET['id'];

// Verificação de login
if (!isset($_SESSION['id_usuario'])) {
    // Armazena o ID do formulário na sessão para redirecionamento posterior
    $_SESSION['formulario_pendente'] = $id_formulario;

    // Redireciona para a página de login
    header("Location: ../login/login.php");
    exit;
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o usuário já respondeu o formulário
$sql_verifica_resposta = "
    SELECT COUNT(*) AS total_respostas
    FROM resposta_usuario ru
    JOIN RESPOSTA r ON ru.RESPOSTA_id_resposta = r.id_resposta
    WHERE ru.USUARIO_id_usuario = ?
      AND r.id_pergunta IN (
          SELECT id_pergunta
          FROM PERGUNTA
          WHERE FORMULARIO_id_formulario = ?
      )
";
$stmt_verifica_resposta = $conn->prepare($sql_verifica_resposta);
$stmt_verifica_resposta->bind_param("ii", $_SESSION['id_usuario'], $id_formulario);
$stmt_verifica_resposta->execute();
$result_verifica_resposta = $stmt_verifica_resposta->get_result();
$verifica_resposta = $result_verifica_resposta->fetch_assoc();

if ($verifica_resposta['total_respostas'] > 0) {
    // Verifica o papel do usuário (admin ou usuario)
    if ($_SESSION['user_role'] === 'admin') {
        $homeUrl = '../paginaHome/homeAdmin.php';
    } else {
        $homeUrl = '../paginaHome/homeUsuario.php';
    }

    // Exibe o alerta e redireciona para a página correspondente
    echo "<script>
            alert('Você já respondeu este formulário anteriormente.');
            window.location.href = '$homeUrl';
          </script>";
    exit;
}

$stmt_verifica_resposta->close();

// Busca o nome do formulário
$sql_nome_formulario = "SELECT nm_formulario FROM FORMULARIO WHERE id_formulario = ?";
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
    <title>Responder Formulário - <?php echo htmlspecialchars($nome_formulario); ?></title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>

<section class="login-container">
    <h1><?php echo htmlspecialchars($nome_formulario); ?></h1>

    <form class="login-form" action="processarRespostas.php" method="POST">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($id_formulario); ?>">

        <?php foreach ($perguntas as $pergunta): ?>
            <div>
                <label><strong><?php echo htmlspecialchars($pergunta['ds_pergunta']); ?></strong></label>
                <br>
                <?php if ($pergunta['nm_tipo_pergunta'] === 'Texto'): ?>
                    <textarea name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" placeholder="Digite sua resposta" required></textarea>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'E-mail'): ?>
                    <input type="email" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" placeholder="Digite seu e-mail" required>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Múltipla Escolha'): ?>
                    <?php foreach ($opcoes_resposta[$pergunta['id_pergunta']] ?? [] as $opcao): ?>
                        <div class="opcao">
                            <input type="checkbox" name="resposta[<?php echo $pergunta['id_pergunta']; ?>][]" 
                                   value="<?php echo htmlspecialchars($opcao['id_resposta']); ?>">
                            <?php echo htmlspecialchars($opcao['ds_resposta']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Única Escolha'): ?>
                    <?php foreach ($opcoes_resposta[$pergunta['id_pergunta']] ?? [] as $opcao): ?>
                        <div class="opcao">
                            <input type="radio" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" 
                                   value="<?php echo htmlspecialchars($opcao['id_resposta']); ?>" required>
                            <?php echo htmlspecialchars($opcao['ds_resposta']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Data'): ?>
                    <input type="date" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" required>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Número'): ?>
                    <input type="number" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" placeholder="Digite um número" required>
                <?php endif; ?>
            </div>
            <br>
        <?php endforeach; ?>

        <button type="submit" class="cta-btn">Enviar Respostas</button>
    </form>

    <p><a href="../paginaHome/homeUsuario.php" class="cta-btn">🏠 Página Inicial</a></p>
</section>

</body>
</html>