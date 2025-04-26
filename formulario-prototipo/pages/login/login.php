<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css"> 
</head>
<body>

<section class="login-container">
    <h1>Bem-vindo ao Form Generator</h1>
    <p>Faça login na sua conta</p>

    <form class="login-form" action="processar_login.php" method="POST">
        <input type="text" id="id_usuario" name="id_usuario" placeholder="ID do Usuário" required>
        <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF do Usuário" required>
        <input type="password" id="cd_senha_usuario" name="cd_senha_usuario" placeholder="Senha" required>

        <!-- Link para redefinir senha -->
        <p class="forgot-password"><a href="/formulario-prototipo/pages/recuperarSenha/recuperarSenha.php">Esqueceu a senha?</a></p>

        <button type="submit" class="cta-btn">Login</button>

        <p class="create-account">Não tem uma conta? <a href="/formulario-prototipo/pages/criarUsuario/criarUsuario.php">Criar Conta</a></p>
    </form>
</section>
</body>
</html>

