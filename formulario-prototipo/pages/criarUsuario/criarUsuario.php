<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css"> <!-- Link para o CSS -->
</head>
<body>

<section class="login-container">
    <h1>Bem-vindo ao Form Generator</h1>
    <p>Crie sua conta para acessar o sistema</p>

    <form class="login-form" action="processarCriarUsuario.php" method="POST">
        <input type="text" id="nm_usuario" name="nm_usuario" placeholder="Nome Completo" required>
        <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF (somente números)" required>
        <input type="email" id="nm_email_usuario" name="nm_email_usuario" placeholder="E-mail" required>
        <input type="password" id="cd_senha_usuario" name="cd_senha_usuario" placeholder="Senha" required>

        <!-- Botão de Cadastro -->
        <button type="submit" class="cta-btn">Cadastrar</button>

        <p class="create-account">Já tem uma conta? <a href="/Form-Generator/formulario-prototipo/pages/login/login.php">Fazer Login</a></p>
    </form>
</section>
</body>
</html>