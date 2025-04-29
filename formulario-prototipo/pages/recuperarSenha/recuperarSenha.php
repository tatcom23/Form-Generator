<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
</head>
<body>

<section class="login-container">
    <h1>Recuperar Senha</h1>
    <p>Informe seu CPF para redefinir a senha</p>

    <form class="login-form" action="enviarEmailRecuperacao.php" method="POST">
        <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF do Usuário" required>

        <button type="submit" class="cta-btn">Enviar link de recuperação</button>

        <p class="create-account"><a href="/Form-Generator/formulario-prototipo/pages/login/login.php">Voltar para o login</a></p>
    </form>
</section>

</body>
</html>
