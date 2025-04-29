<?php

// Importa o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carrega o autoload do Composer
require '../../vendor/autoload.php';

// Código de recuperação (como no seu exemplo)
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

        // Envio de e-mail real com PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Exemplo para Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'seu_email@gmail.com';  // Seu e-mail
            $mail->Password = 'sua_senha_de_app';  // Senha de app do Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remetente e destinatário
            $mail->setFrom('seu_email@gmail.com', 'Form Generator');
            $mail->addAddress($nm_email_usuario);  // E-mail do usuário

            // Conteúdo do e-mail
            $link = "http://localhost/formulario-prototipo/pages/recuperarSenha/novaSenha.php?token=$token";
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body    = "Clique no link para redefinir sua senha: <a href='$link'>$link</a>";

            // Envia o e-mail
            $mail->send();
            echo "<h2>Link de recuperação enviado para o seu e-mail!</h2>";
        } catch (Exception $e) {
            echo "Erro ao enviar o e-mail. Mailer Error: {$mail->ErrorInfo}";
        }

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
