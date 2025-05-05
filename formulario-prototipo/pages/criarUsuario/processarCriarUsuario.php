<?php 
// Inclui a classe Usuario
require_once 'usuario.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nm_usuario = $_POST['nm_usuario'];
    $cd_cpf_usuario = $_POST['cd_cpf_usuario'];
    $nm_email_usuario = $_POST['nm_email_usuario'];
    $cd_senha_usuario = $_POST['cd_senha_usuario']; // A senha é recebida sem criptografia inicialmente
    $user_role = $_POST['user_role'];

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

    // Verifica se o CPF ou e-mail já existe
    $sql_check = "SELECT 1 FROM usuario WHERE cd_cpf_usuario = ? OR nm_email_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    // Faz o bind dos parâmetros
    $stmt_check->bind_param("ss", $cd_cpf_usuario, $nm_email_usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Se CPF ou e-mail já estiverem cadastrados
        echo "<script>
            alert('Este CPF ou e-mail já está sendo utilizado. Por favor, entre em login');
            window.location.href = '../login/login.php'; 
        </script>";
    } else {
        // Validação de senha
        $senhas_fracas = ['123456', '123456789', '12345678', '12345', '1234567', 'senha', 'admin', 'qwerty', 'abcdef'];

        if (
            strlen($cd_senha_usuario) < 8 ||
            in_array($cd_senha_usuario, $senhas_fracas) ||
            !preg_match('/[A-Z]/', $cd_senha_usuario) ||
            !preg_match('/[a-z]/', $cd_senha_usuario) ||
            !preg_match('/\d/', $cd_senha_usuario) ||
            !preg_match('/[\W]/', $cd_senha_usuario)
        ) {
            echo "<script>
                alert('Senha fraca. Use no mínimo 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos. Evite senhas como \"123456\".');
                window.history.back();
            </script>";
            exit();
        }

        // Cria um objeto Usuario
        $usuario = new Usuario($nm_usuario, $cd_cpf_usuario, $nm_email_usuario, password_hash($cd_senha_usuario, PASSWORD_DEFAULT));

        // Prepara a query SQL para inserção
        $sql_insert = "INSERT INTO usuario (nm_usuario, cd_cpf_usuario, nm_email_usuario, cd_senha_usuario, user_role)
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        if (!$stmt_insert) {
            die("Erro na preparação da declaração: " . $conn->error);
        }

        // Faz o bind dos parâmetros
        $stmt_insert->bind_param("sssss", $usuario->nm_usuario, $usuario->cd_cpf_usuario, $usuario->nm_email_usuario, $usuario->cd_senha_usuario, $user_role);

        // Executa a query para inserir os dados
        if ($stmt_insert->execute()) {
            echo "<script>
                alert('Usuário cadastrado com sucesso!');
                window.location.href = '../login/login.php';
            </script>";
        } else {
            echo "<script>
                alert('Erro ao cadastrar usuário: " . $stmt_insert->error . "');
                window.history.back();
            </script>";
        }
    }

    // Fecha o stmt e a conexão
    $stmt_check->close();
    $stmt_insert->close();
    $conn->close();
}
?>
