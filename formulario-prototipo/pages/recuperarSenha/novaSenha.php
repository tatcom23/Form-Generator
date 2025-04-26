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

    <?php
    // Verifica se o token está presente na URL
    if (!isset($_GET['token'])) {
        echo "<p>Token de recuperação inválido ou ausente.</p>";
        exit;
    }

    $token = $_GET['token'];

    // Aqui você deve verificar no banco de dados se o token é válido e ainda não expirou
    // Exemplo simulado:
    $tokenValido = true; // Suponha que é válido

    if ($tokenValido):
    ?>

    <form class="login-form" action="salvar_nova_senha.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="nova_senha" placeholder="Nova Senha" required>
        <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha" required>

        <button type="submit" class="cta-btn">Redefinir Senha</button>
    </form>

    <?php else: ?>
        <p>Token inválido ou expirado.</p>
    <?php endif; ?>

</section>
</body>
</html>
