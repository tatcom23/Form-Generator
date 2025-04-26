<?php
// enviar_email_recuperacao.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = $_POST['cd_cpf_usuario'];

    // Aqui você faria uma consulta no banco de dados para verificar se o CPF existe
    // Exemplo (simulado):
    $cpfExiste = true; // Simule como se o CPF estivesse no banco

    if ($cpfExiste) {
        // Gerar um token único (você pode usar hash + timestamp)
        $token = bin2hex(random_bytes(16));

        // Aqui você deveria salvar o token no banco junto ao CPF e uma data de expiração

        // Simular um link de recuperação (em um projeto real, isso seria enviado por e-mail)
        $link = "https://seudominio.com/formulario-prototipo/pages/recuperarSenha/novaSenha.php?token=$token";

        // Simulação de "envio" (aqui só mostramos o link)
        echo "<h2>Link de recuperação enviado!</h2>";
        echo "<p>Simulação: <a href='$link'>$link</a></p>";
    } else {
        echo "<p>CPF não encontrado. Verifique e tente novamente.</p>";
    }
} else {
    echo "<p>Acesso inválido.</p>";
}
?>
