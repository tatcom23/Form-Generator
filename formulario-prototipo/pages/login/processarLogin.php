<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];
    $cd_senha_usuario = $_POST['cd_senha_usuario'];

    // Aqui você colocaria a verificação no banco de dados
    if ($id_usuario == "admin" && $cd_senha_usuario == "123") {
        header("Location: ../../dashboard.php"); // redireciona se o login for válido
        exit();
    } else {
        echo "Usuário ou senha inválidos";
    }
}
?>

