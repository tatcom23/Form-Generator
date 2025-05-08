<?php 
session_start();

// Garante que o usuário seja do tipo 'usuario'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'usuario') {
    header("Location: /Form-Generator/formulario-prototipo/pages/login/login.php");
    exit();
}

// Verifica se o id do usuário existe
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Form-Generator/formulario-prototipo/pages/login/login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
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
    <title>Home - Formulários Disponíveis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Formulários Disponíveis</title>
    <!-- Link para o arquivo CSS -->
    <link rel="stylesheet" href="../../css/homeUsuario.css">
</head>
<body>

<header>        
    <div class="container">
            <a href="homeUsuario.php" class="logo">Painel Usuário</a>
            <nav>
                <ul>
                    <li><a href="../../">Página Inicial</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
    </div>
</header>

<div class="hero">
    <h1>Bem-vindo, <?php echo ucwords(strtolower($user_name)); ?>!</h1>
</div>

<div class="container">
    <div id="form-list">
        <h2>Formulários Disponíveis</h2>

        <?php if (!empty($formularios)) : ?>
            <?php foreach ($formularios as $form) : ?>
                <div class="form-item">
                    <strong>Formulário: <?php echo htmlspecialchars($form['nm_formulario']); ?></strong><br>
                    <?php 
                        $data_formatada = date('d/m/Y H:i', strtotime($form['dt_criacao_formulario']));
                        echo "Criado em: " . htmlspecialchars($data_formatada);
                    ?>
                    <div class="form-actions">
                    <a href="../formularios/responderFormulario.php?id=<?php echo $form['id_formulario']; ?>">Responder Formulário</a>|
                    <a href="../formularios/visualizarRespostas.php?id=<?php echo $form['id_formulario']; ?>">Resposta</a>
                    </div>
                </div><br>
                    
        <?php endforeach; ?>
        <?php else : ?>
            <p>Não há formulários disponíveis no momento.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Meu Sistema. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

