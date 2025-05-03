<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para acessar esta página.";
    header("Location: detalhesFormulario.php?id=" . ($_GET['id'] ?? ''));
    exit;
}

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do formulário não especificado.";
    header("Location: listarFormulario.php");
    exit;
}

$id_formulario = $_GET['id'];

// Processa o envio do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nm_categoria = $_POST['nm_categoria'] ?? null;

    // Verifica se o campo obrigatório está preenchido
    if (empty($nm_categoria)) {
        $_SESSION['mensagem'] = "Por favor, insira o nome da categoria.";
        header("Location: adicionarCategoria.php?id=" . $id_formulario);
        exit;
    }

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formulario_generator";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $_SESSION['mensagem'] = "Erro de conexão com o banco de dados.";
        header("Location: adicionarCategoria.php?id=" . $id_formulario);
        exit;
    }

    // Insere a categoria no banco de dados
    $sql = "INSERT INTO CATEGORIA (nm_categoria, FORMULARIO_id_formulario) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['mensagem'] = "Ocorreu um erro ao preparar a consulta.";
        header("Location: adicionarCategoria.php?id=" . $id_formulario);
        exit;
    }

    $stmt->bind_param("si", $nm_categoria, $id_formulario);

    try {
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Categoria adicionada com sucesso!";
            header("Location: detalhesFormulario.php?id=" . $id_formulario);
            exit;
        } else {
            throw new Exception("Ocorreu um erro ao adicionar a categoria.");
        }
    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
        header("Location: adicionarCategoria.php?id=" . $id_formulario);
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Categoria - Form Generator</title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>

<section class="login-container">
    <h1>Adicionar Categoria</h1>

    <form class="login-form" action="adicionarCategoria.php?id=<?php echo htmlspecialchars($id_formulario); ?>" method="POST">
        <input type="text" name="nm_categoria" placeholder="Nome da Categoria" required>
        <button type="submit" class="cta-btn">Adicionar Categoria</button>
    </form>

    <p><a href="detalhesFormulario.php?id=<?php echo htmlspecialchars($id_formulario); ?>" class="cta-btn">Voltar para o Formulário</a></p>
</section>

</body>
</html>