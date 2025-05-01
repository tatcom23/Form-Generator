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

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

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

$stmt_perguntas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Responder Formulário - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Responder Formulário</h1>

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
                    <select name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                <?php elseif ($pergunta['nm_tipo_pergunta'] === 'Única Escolha'): ?>
                    <input type="radio" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" value="Sim" required> Sim
                    <input type="radio" name="resposta[<?php echo $pergunta['id_pergunta']; ?>]" value="Não"> Não
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

    <p><a href="listarFormulario.php" class="cta-btn">Voltar para Meus Formulários</a></p>
</section>

</body>
</html>