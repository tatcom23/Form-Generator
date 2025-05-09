<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>
            alert('Você precisa estar logado para acessar esta página.');
            window.location.href = '../login/login.php';
          </script>";
    exit;
}

// Coleta o ID do formulário via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formulario_generator";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Atualiza o status do formulário para "finalizado"
    $sql = "UPDATE FORMULARIO SET status = 1 WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_formulario, $_SESSION['id_usuario']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redireciona para a página de compartilhamento com mensagem de sucesso
        echo "<script>
                alert('Formulário finalizado com sucesso!');
                window.location.href = 'compartilharFormulario.php?id=" . urlencode($id_formulario) . "';
              </script>";
        exit;
    } else {
        // Exibe mensagem de erro caso o formulário não seja encontrado ou o usuário não tenha permissão
        echo "<script>
                alert('Erro ao finalizar o formulário. Verifique suas permissões.');
                window.location.href = 'detalhesFormularioView.php?id=" . urlencode($id_formulario) . "';
              </script>";
        exit;
    }
} else {
    // Redireciona caso o método ou ID do formulário não esteja especificado
    echo "<script>
            alert('Método inválido ou ID do formulário não especificado.');
            window.location.href = 'listarFormularios.php';
          </script>";
    exit;
}
?>