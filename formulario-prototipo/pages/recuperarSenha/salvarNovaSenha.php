<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $novaSenha = $_POST['nova_senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Verificar se as senhas coincidem
    if ($novaSenha !== $confirmarSenha) {
        echo "<p>As senhas não coincidem. Tente novamente.</p>";
        exit;
    }

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

    // Verifica se o token é válido e não foi utilizado
    $sql = "SELECT id_usuario FROM recuperacao_senha 
            WHERE token = ? AND data_expiracao > NOW() AND utilizado = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario);
        $stmt->fetch();

        // Criptografa a nova senha
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Atualiza a senha do usuário
        $sql_update = "UPDATE USUARIO SET cd_senha_usuario = ? WHERE id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $senhaCriptografada, $id_usuario);
        $stmt_update->execute();

        // Marca o token como utilizado
        $sql_invalidar = "UPDATE recuperacao_senha SET utilizado = 1 WHERE token = ?";
        $stmt_invalidar = $conn->prepare($sql_invalidar);
        $stmt_invalidar->bind_param("s", $token);
        $stmt_invalidar->execute();

        echo "<h2>Senha redefinida com sucesso!</h2>";
        echo "<p><a href='/formulario-prototipo/pages/login/login.php'>Voltar para o login</a></p>";

        $stmt_update->close();
        $stmt_invalidar->close();
    } else {
        echo "<p>Token inválido ou expirado. Solicite a recuperação novamente.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Requisição inválida.</p>";
}
?>