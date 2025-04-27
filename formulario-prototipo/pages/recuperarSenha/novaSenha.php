<?php
// Verifica se o token está presente na URL
if (!isset($_GET['token'])) {
    echo "<p>Token de recuperação inválido ou ausente.</p>";
    exit;
}

$token = $_GET['token'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Altere para o seu usuário do MySQL
$password = "";     // Altere para a sua senha do MySQL
$dbname = "formulario_generator"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o token é válido e não expirou
$sql = "SELECT id_usuario FROM recuperacao_senha 
        WHERE token = ? AND data_expiracao > NOW() AND utilizado = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0):
    $stmt->bind_result($id_usuario);
    $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Redefinir Senha</h1>

    <form class="login-form" action="salvarNovaSenha.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="nova_senha" placeholder="Nova Senha" required>
        <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha" required>

        <button type="submit" class="cta-btn">Redefinir Senha</button>
    </form>

</section>
</body>
</html>
<?php
else:
    echo "<p>Token inválido ou expirado.</p>";
endif;

$stmt->close();
$conn->close();
?>