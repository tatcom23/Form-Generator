<?php
ob_start(); // Garante que o header() funcione corretamente mesmo que algo tenha sido enviado antes
session_start(); // Inicia a sessão

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];
    $cd_senha_usuario = $_POST['cd_senha_usuario'];

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root"; // Altere para o seu usuário do MySQL
    $password = "";     // Altere para a sua senha do MySQL
    $dbname = "formulario_generator"; // Nome do banco de dados

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Prepara a query SQL para buscar o usuário pelo CPF
    $sql = "SELECT id_usuario, nm_usuario, cd_senha_usuario, user_role FROM USUARIO WHERE cd_cpf_usuario = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    // Vincula o parâmetro (CPF)
    $stmt->bind_param("s", $cd_cpf_usuario);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário foi encontrado
    if ($stmt->num_rows > 0) {
        // Obtém os dados do usuário
        $stmt->bind_result($id_usuario, $nm_usuario, $senha_hash, $user_role);
        $stmt->fetch();

        // Verifica se a senha está correta
        if (password_verify($cd_senha_usuario, $senha_hash)) {
            // Armazena as informações do usuário na sessão
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['user_name'] = $nm_usuario;
            $_SESSION['user_role'] = $user_role;

            // Verifica se há um formulário pendente
            if (isset($_SESSION['formulario_pendente'])) {
                $id_formulario = $_SESSION['formulario_pendente'];
                unset($_SESSION['formulario_pendente']); // Limpa a variável de sessão

                // Redireciona para o formulário pendente
                header("Location: ../formularios/responderFormulario.php?id=" . $id_formulario);
                exit();
            } else {
                // Redireciona para a página inicial com base no papel (admin ou usuário comum)
                if ($user_role === 'admin') {
                    header("Location: ../paginaHome/homeAdmin.php");
                } else {
                    header("Location: ../paginaHome/homeUsuario.php");
                }
                exit();
            }
        } else {
            header("Location: login.php?erro=1");
            exit();
        }
    }
    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>