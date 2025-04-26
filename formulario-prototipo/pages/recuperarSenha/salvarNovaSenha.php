<?php
// salvar_nova_senha.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $novaSenha = $_POST['nova_senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Verificar se as senhas coincidem
    if ($novaSenha !== $confirmarSenha) {
        echo "<p>As senhas não coincidem. Tente novamente.</p>";
        exit;
    }

    // Aqui você faria a verificação do token no banco de dados
    // e encontraria o usuário correspondente

    // Exemplo de verificação simulada:
    $tokenValido = true; // Simule que o token está correto
    $cpfUsuario = "123.456.789-00"; // Simule que achou o CPF do usuário

    if ($tokenValido) {
        // Aqui você atualizaria a senha do usuário no banco
        // É importante criptografar a senha antes de salvar
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Exemplo simulado de atualização:
        // update usuarios set cd_senha_usuario = '$senhaCriptografada' where cd_cpf_usuario = '$cpfUsuario';

        // Também é recomendado invalidar o token após o uso

        echo "<h2>Senha redefinida com sucesso!</h2>";
        echo "<p><a href='/formulario-prototipo/pages/login/login.php'>Voltar para o login</a></p>";
    } else {
        echo "<p>Token inválido ou expirado. Solicite a recuperação novamente.</p>";
    }
} else {
    echo "<p>Requisição inválida.</p>";
}
?>
