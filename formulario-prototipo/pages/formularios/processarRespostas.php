<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para acessar esta página.</p>";
    exit;
}

// Recupera o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<p>Método inválido.</p>";
    exit;
}

// Coleta os dados do formulário
$id_formulario = $_POST['id_formulario'];
$respostas = $_POST['resposta']; // Array de respostas

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para inserir uma nova resposta na tabela `resposta`
function inserirResposta($conn, $id_pergunta, $texto_resposta) {
    $sql_inserir_resposta = "INSERT INTO resposta (id_pergunta, ds_resposta) VALUES (?, ?)";
    $stmt_inserir_resposta = $conn->prepare($sql_inserir_resposta);
    $stmt_inserir_resposta->bind_param("is", $id_pergunta, $texto_resposta);
    $stmt_inserir_resposta->execute();
    $id_resposta = $stmt_inserir_resposta->insert_id; // Recupera o ID gerado
    $stmt_inserir_resposta->close();
    return $id_resposta;
}

// Insere as respostas na tabela resposta_usuario
foreach ($respostas as $id_pergunta => $resposta) {
    if (is_array($resposta)) { // Para múltipla escolha
        foreach ($resposta as $id_resposta) {
            // Verifica se o ID da resposta existe na tabela `resposta`
            $sql_verificar_resposta = "SELECT id_resposta FROM resposta WHERE id_resposta = ?";
            $stmt_verificar_resposta = $conn->prepare($sql_verificar_resposta);
            $stmt_verificar_resposta->bind_param("i", $id_resposta);
            $stmt_verificar_resposta->execute();
            $result_verificar_resposta = $stmt_verificar_resposta->get_result();

            if ($result_verificar_resposta->num_rows === 0) {
                echo "<p>Erro: Resposta inválida para a pergunta $id_pergunta.</p>";
                continue; // Ignora respostas inválidas
            }

            // Insere a resposta na tabela `resposta_usuario`
            $sql_inserir_resposta_usuario = "INSERT INTO resposta_usuario (RESPOSTA_id_resposta, USUARIO_id_usuario) VALUES (?, ?)";
            $stmt_inserir_resposta_usuario = $conn->prepare($sql_inserir_resposta_usuario);
            $stmt_inserir_resposta_usuario->bind_param("ii", $id_resposta, $id_usuario);
            $stmt_inserir_resposta_usuario->execute();
            $stmt_inserir_resposta_usuario->close();
        }
    } else { // Para única escolha ou outros tipos
        // Verifica se a resposta é um ID válido na tabela `resposta`
        $sql_verificar_resposta = "SELECT id_resposta FROM resposta WHERE id_resposta = ?";
        $stmt_verificar_resposta = $conn->prepare($sql_verificar_resposta);
        $stmt_verificar_resposta->bind_param("i", $resposta);
        $stmt_verificar_resposta->execute();
        $result_verificar_resposta = $stmt_verificar_resposta->get_result();

        if ($result_verificar_resposta->num_rows > 0) {
            // Resposta é um ID válido na tabela `resposta`
            $row = $result_verificar_resposta->fetch_assoc();
            $id_resposta = $row['id_resposta'];
        } else {
            // Resposta é um texto ou outro tipo de dado (Texto, Número, Data, Email)
            $id_resposta = inserirResposta($conn, $id_pergunta, $resposta); // Insere a resposta na tabela `resposta`
        }

        // Insere a resposta na tabela `resposta_usuario`
        $sql_inserir_resposta_usuario = "INSERT INTO resposta_usuario (RESPOSTA_id_resposta, USUARIO_id_usuario) VALUES (?, ?)";
        $stmt_inserir_resposta_usuario = $conn->prepare($sql_inserir_resposta_usuario);
        $stmt_inserir_resposta_usuario->bind_param("ii", $id_resposta, $id_usuario);
        $stmt_inserir_resposta_usuario->execute();
        $stmt_inserir_resposta_usuario->close();
    }
}

$conn->close();

// Retorna uma resposta HTML com o alert e o redirecionamento
echo "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <title>Confirmação</title>
    <script>
        alert('Respostas enviadas com sucesso!');
        window.location.href = '../../'; // Caminho ajustado para a home
    </script>
</head>
<body>
</body>
</html>
";
exit;
?>