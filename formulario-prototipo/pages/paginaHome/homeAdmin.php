<?php 
session_start();

// Verifica se o usuário está autenticado como admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /Form-Generator/formulario-prototipo/pages/login/login.php");
    exit();
}

// Verifica se o id do usuário existe
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Form-Generator/formulario-prototipo/pages/login/login.php");
    exit();
}

$admin_name = $_SESSION['user_name'];
$id_usuario = $_SESSION['id_usuario'];

// Conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar formulários do usuário atual
$sql = "SELECT id_formulario, nm_formulario, dt_criacao_formulario FROM FORMULARIO";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$formularios = [];
while ($row = $result->fetch_assoc()) {
    $formularios[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Home Administrador - Meus Formulários</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/homeAdmin.css">
</head>
<body>

<header>
    <div class="container">
        <a href="homeAdmin.php" class="logo">Painel Administrador</a>
        <nav>
            <ul>
                <li><a href="../formularios/criarFormulario.php">Criar Formulário</a></li>
                <li><a href="../formularios/listarFormulario.php">Listar Formulário</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="hero">
    <h1><!-- h1 1 -->Bem-vindo, <?php echo ucwords(strtolower($admin_name)); ?>!</h1>
</div>

<div class="container">
    <div id="form-list">
        <h2>Formulários Criados</h2>

        <?php if (!empty($formularios)) : ?>
            <?php foreach ($formularios as $form) : ?>
                <div class="form-item">
                    <strong><?php echo htmlspecialchars($form['nm_formulario']); ?></strong><br>
                    <?php 
                        $data_formatada = date('d/m/Y H:i', strtotime($form['dt_criacao_formulario']));
                        echo "Criado em: " . htmlspecialchars($data_formatada);
                    ?>
                    <div class="form-actions">
                        <a href="../formularios/detalhesFormulario.php?id=<?php echo $form['id_formulario']; ?>">Detalhes</a> |
                        <a href="../formularios/visualizarRespostas.php?id=<?php echo $form['id_formulario']; ?>">Respostas</a> |
                        <a href="../formularios/editarFormulario.php?id=<?php echo $form['id_formulario']; ?>">Editar</a> |
                        <a href="../formularios/excluirFormulario.php?id=<?php echo $form['id_formulario']; ?>">Excluir</a> |
                        <a href="../formularios/adicionarCategoria.php?id=<?php echo $form['id_formulario']; ?>">+ Categoria</a>
                    </div>
                </div><br>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Você ainda não criou nenhum formulário.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2025 Sistema de Formulários. Todos os direitos reservados.</p>
</footer>

</body>
</html>
