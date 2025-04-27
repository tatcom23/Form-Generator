<?php
// enviarEmailRecuperacao.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];

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

    // Verifica se o CPF existe no banco de dados
    $sql = "SELECT id_usuario, nm_email_usuario FROM USUARIO WHERE cd_cpf_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cd_cpf_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Obtém o e-mail do usuário
        $stmt->bind_result($id_usuario, $nm_email_usuario);
        $stmt->fetch();

        // Gera um token único
        $token = bin2hex(random_bytes(16));

        // Define a data de expiração (ex.: 1 hora a partir de agora)
        $data_expiracao = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Salva o token no banco de dados
        $sql_token = "INSERT INTO recuperacao_senha (id_usuario, token, data_expiracao) VALUES (?, ?, ?)";
        $stmt_token = $conn->prepare($sql_token);
        $stmt_token->bind_param("iss", $id_usuario, $token, $data_expiracao);
        $stmt_token->execute();

        // Simulação de envio de e-mail (substitua por um serviço real de envio de e-mail)
        $link = "http://localhost/formulario-prototipo/pages/recuperarSenha/novaSenha.php?token=$token";
        echo "<h2>Link de recuperação enviado!</h2>";
        echo "<p>Simulação: <a href='$link'>$link</a></p>";

        $stmt_token->close();
    } else {
        echo "<p>CPF não encontrado. Verifique e tente novamente.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Acesso inválido.</p>";
}
?>