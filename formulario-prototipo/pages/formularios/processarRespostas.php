<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para enviar respostas.</p>";
    exit;
}

// Coleta os dados do formulário
$id_formulario = $_POST['id_formulario'];
$respostas = $_POST['resposta'];
$id_usuario = $_SESSION['id_usuario'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

try {
    // Inicia uma transação para garantir consistência
    $conn->begin_transaction();

    // Insere as respostas na tabela RESPOSTA
    $sql_resposta = "INSERT INTO RESPOSTA (ds_resposta, id_pergunta) VALUES (?, ?)";
    $stmt_resposta = $conn->prepare($sql_resposta);

    // Insere a associação na tabela RESPOSTA_USUARIO
    $sql_resposta_usuario = "INSERT INTO RESPOSTA_USUARIO (USUARIO_id_usuario, RESPOSTA_id_resposta) VALUES (?, ?)";
    $stmt_resposta_usuario = $conn->prepare($sql_resposta_usuario);

    foreach ($respostas as $id_pergunta => $ds_resposta) {
        // Insere a resposta na tabela RESPOSTA
        $stmt_resposta->bind_param("si", $ds_resposta, $id_pergunta);
        $stmt_resposta->execute();
        $id_resposta = $stmt_resposta->insert_id; // Obtém o ID da resposta inserida

        // Insere a associação na tabela RESPOSTA_USUARIO
        $stmt_resposta_usuario->bind_param("ii", $id_usuario, $id_resposta);
        $stmt_resposta_usuario->execute();
    }

    // Confirma a transação
    $conn->commit();

    echo "<h2>Respostas enviadas com sucesso!</h2>";
    echo "<p><a href='listarFormulario.php'>Voltar para Meus Formulários</a></p>";
} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    $conn->rollback();
    echo "<p>Ocorreu um erro ao enviar as respostas: " . $e->getMessage() . "</p>";
}

$stmt_resposta->close();
$stmt_resposta_usuario->close();
$conn->close();
?>