<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css"> 
</head>
<body>

<section class="login-container">
    <h1>Crie sua Conta</h1>
    <p>Preencha os dados abaixo</p>

    <form class="login-form" action="processar_criar_usuario.php" method="POST">
        <input type="text" id="id_usuario" name="id_usuario" placeholder="ID do Usuário" required>
        <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF do Usuário" required>
        <input type="password" id="cd_senha_usuario" name="cd_senha_usuario" placeholder="Senha" required>

        <button type="submit" class="cta-btn">Criar Conta</button>

        <p class="create-account">Já tem uma conta? <a href="../login/login.php">Login</a></p>
    </form>
</section>
</body>
</html>
