<?php
// Iniciar buffer para evitar erros de saída antes do header
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];
    $cd_senha_usuario = $_POST['cd_senha_usuario'];

    // Aqui você poderia salvar no banco de dados futuramente...

    // Redirecionar para a página de login
    header("Location: ../login/login.php");
    exit(); // Muito importante para finalizar o script após o redirecionamento
}

ob_end_flush();
?>
