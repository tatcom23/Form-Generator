<?php
// Inclui a classe Usuario
require_once 'usuario.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nm_usuario = $_POST['nm_usuario'];
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];
    $nm_email_usuario = $_POST['nm_email_usuario'];
    $cd_senha_usuario = password_hash($_POST['cd_senha_usuario'], PASSWORD_DEFAULT); // Criptografa a senha

    // Cria um objeto Usuario
    $usuario = new Usuario($nm_usuario, $cd_cpf_usuario, $nm_email_usuario, $cd_senha_usuario);

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formulario_generator";

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Prepara a query SQL
    $sql = "INSERT INTO usuario (nm_usuario, cd_cpf_usuario, nm_email_usuario, cd_senha_usuario)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    // Faz o bind dos parâmetros
    $stmt->bind_param("ssss", $usuario->nm_usuario, $usuario->cd_cpf_usuario, $usuario->nm_email_usuario, $usuario->cd_senha_usuario);

    // Executa a query
    if ($stmt->execute()) {
        // Se cadastro foi feito com sucesso, mostra o alerta e redireciona
        echo "<script>
            alert('Usuário cadastrado com sucesso!');
            window.location.href = '/formulario-prototipo/pages/login/login.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Erro ao cadastrar usuário: " . $stmt->error . "');
            window.history.back();
        </script>";
    }

    // Fecha o stmt e a conexão
    $stmt->close();
    $conn->close();
}
?>