<?php
session_start();

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

$nome_formulario = htmlspecialchars($formulario['nm_formulario']);

// Busca todas as perguntas do formulário
$sql_perguntas = "SELECT id_pergunta, ds_pergunta, TIPO_PERGUNTA_id_tipo_pergunta 
                  FROM PERGUNTA 
                  WHERE FORMULARIO_id_formulario = ?";
$stmt_perguntas = $conn->prepare($sql_perguntas);
$stmt_perguntas->bind_param("i", $id_formulario);
$stmt_perguntas->execute();
$result_perguntas = $stmt_perguntas->get_result();
$perguntas = $result_perguntas->fetch_all(MYSQLI_ASSOC);

// Busca apenas as respostas do usuário logado
$sql_respostas = "
    SELECT ru.dt_resposta_usuario, u.nm_usuario, p.id_pergunta, p.TIPO_PERGUNTA_id_tipo_pergunta, p.ds_pergunta, r.ds_resposta
    FROM RESPOSTA r
    JOIN resposta_usuario ru ON r.id_resposta = ru.RESPOSTA_id_resposta
    JOIN USUARIO u ON ru.USUARIO_id_usuario = u.id_usuario
    JOIN PERGUNTA p ON r.id_pergunta = p.id_pergunta
    WHERE p.FORMULARIO_id_formulario = ?
      AND ru.USUARIO_id_usuario = ?  -- Filtra pelo ID do usuário logado
    ORDER BY ru.dt_resposta_usuario ASC, p.id_pergunta ASC
";
$stmt_respostas = $conn->prepare($sql_respostas);
$stmt_respostas->bind_param("ii", $id_formulario, $_SESSION['id_usuario']); // Adiciona o ID do usuário logado
$stmt_respostas->execute();
$result_respostas = $stmt_respostas->get_result();
$respostas = $result_respostas->fetch_all(MYSQLI_ASSOC);

// Organiza as respostas por data/hora
$respostas_por_data = [];
foreach ($respostas as $resposta) {
    $data_hora = $resposta['dt_resposta_usuario'];
    if (!isset($respostas_por_data[$data_hora])) {
        $respostas_por_data[$data_hora] = [
            'usuario' => htmlspecialchars($resposta['nm_usuario']),
            'respostas' => []
        ];
    }
    $respostas_por_data[$data_hora]['respostas'][] = $resposta;
}

$stmt_nome_formulario->close();
$stmt_perguntas->close();
$stmt_respostas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Respostas - <?php echo $nome_formulario; ?></title>
    <link rel="stylesheet" href="../../css/visualizarRespostas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<section class="login-container">
    <h1>Formulário: <?php echo $nome_formulario; ?></h1>

    <?php if (empty($respostas_por_data)): ?>
        <p>Nenhuma resposta encontrada para este formulário.</p>
    <?php else: ?>
        <?php foreach ($respostas_por_data as $data_hora => $dados): ?>
            <div class="resposta-sessao">
                <h2>Respondido por: <?php echo $dados['usuario']; ?></h2>
                <p>Data e Hora: <?php echo htmlspecialchars($data_hora); ?></p>
                <hr>
                <?php foreach ($perguntas as $pergunta): ?>
                    <div class="pergunta-resposta">
                        <strong>Pergunta:</strong> <?php echo htmlspecialchars($pergunta['ds_pergunta']); ?><br>
                        <strong>Resposta:</strong>
                        <?php
                        $resposta_encontrada = false;
                        foreach ($dados['respostas'] as $resposta) {
                            if ($resposta['id_pergunta'] == $pergunta['id_pergunta']) {
                                if ($pergunta['TIPO_PERGUNTA_id_tipo_pergunta'] === 7) { // Substitua '7' pelo ID do tipo "Classificação"
                                    $valor_classificacao = intval($resposta['ds_resposta']);
                                    echo "<div class='classificacao'>";
                                    for ($i = 1; $i <= 5; $i++) {
                                        $classe_estrela = ($i <= $valor_classificacao) ? 'fas fa-star' : 'far fa-star';
                                        echo "<i class='$classe_estrela'></i>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo htmlspecialchars($resposta['ds_resposta']);
                                }
                                $resposta_encontrada = true;
                                break;
                            }
                        }
                        if (!$resposta_encontrada) {
                            echo "Sem resposta.";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
                <br>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php
    // Verifica o user_role do usuário logado
    if ($_SESSION['user_role'] === 'admin') {
        $homeUrl = "../paginaHome/homeAdmin.php";
    } else {
        $homeUrl = "../paginaHome/homeUsuario.php";
    }
    ?>

    <p><a href="<?php echo $homeUrl; ?>" class="cta-btn">Voltar para Meus Formulários</a></p>
</section>

</body>
</html>