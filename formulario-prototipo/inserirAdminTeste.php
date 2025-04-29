<?php
// Definir informações do banco de dados
$servername = "localhost";
$username = "root";  // Altere para o seu usuário do MySQL
$password = "";      // Altere para a sua senha do MySQL
$dbname = "formulario_generator"; // Nome do banco de dados

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Dados do usuário admin
$nm_usuario = 'Admin Teste';
$cd_cpf_usuario = '142873568';
$nm_email_usuario = 'admin@teste.com';
$senha_usuario = 'senha123'; // Senha simples para teste
$senha_usuario_hash = password_hash($senha_usuario, PASSWORD_DEFAULT); // Criptografa a senha
$user_role = 'admin'; // Role do usuário

// Prepara a query SQL para inserir o usuário admin
$sql = "INSERT INTO usuario (nm_usuario, cd_cpf_usuario, nm_email_usuario, cd_senha_usuario, user_role) 
        VALUES ('$nm_usuario', '$cd_cpf_usuario', '$nm_email_usuario', '$senha_usuario_hash', '$user_role')";

// Executa a query
if ($conn->query($sql) === TRUE) {
    echo "Usuário admin inserido com sucesso!";
} else {
    echo "Erro ao inserir usuário: " . $conn->error;
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
