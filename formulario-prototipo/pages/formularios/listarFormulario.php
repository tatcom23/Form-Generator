<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para acessar esta página.</p>";
    exit;
}

require_once 'Formulario.php'; // Caminho relativo para a classe

$idUsuario = $_SESSION['id_usuario'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Lista os formulários do usuário logado
$formularios = Formulario::listarPorUsuario($conn, $idUsuario);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Formulários - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Meus Formulários</h1>

    <?php if (count($formularios) > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data de Início</th>
                <th>Data de Fim</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($formularios as $formulario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($formulario['id_formulario']); ?></td>
                    <td><?php echo htmlspecialchars($formulario['nm_formulario']); ?></td>
                    <td><?php echo htmlspecialchars($formulario['dt_inicio_formulario']); ?></td>
                    <td><?php echo htmlspecialchars($formulario['dt_fim_formulario']); ?></td>
                    <td>
                        <a href="editarFormulario.php?id=<?php echo $formulario['id_formulario']; ?>">Editar</a> |
                        <a href="excluirFormulario.php?id=<?php echo $formulario['id_formulario']; ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum formulário encontrado.</p>
    <?php endif; ?>

    <p><a href="criarFormulario.php" class="cta-btn">Criar Novo Formulário</a></p>
</section>

</body>
</html>