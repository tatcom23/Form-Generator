<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para adicionar uma pergunta.</p>";
    exit;
}

require_once 'Pergunta.php';

// Coleta os dados do formulário
$id_formulario = $_POST['id_formulario'];
$ds_pergunta = $_POST['ds_pergunta'];
$id_categoria = $_POST['id_categoria'] ?? null; // Categoria pode ser nula
$id_tipo_pergunta = $_POST['id_tipo_pergunta'];

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

    // Insere a pergunta na tabela PERGUNTA
    $sql = "INSERT INTO PERGUNTA (ds_pergunta, FORMULARIO_id_formulario, CATEGORIA_id_categoria, TIPO_PERGUNTA_id_tipo_pergunta) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $ds_pergunta, $id_formulario, $id_categoria, $id_tipo_pergunta);
    $stmt->execute();
    $id_pergunta = $stmt->insert_id;

    // Insere as opções de resposta, se houver
    if (isset($_POST['opcoes']) && is_array($_POST['opcoes'])) {
        $sql_opcoes = "INSERT INTO RESPOSTA (ds_resposta, id_pergunta) VALUES (?, ?)";
        $stmt_opcoes = $conn->prepare($sql_opcoes);

        foreach ($_POST['opcoes'] as $index => $opcao) {
            if (!empty(trim($opcao))) { // Ignora opções vazias
                $stmt_opcoes->bind_param("si", $opcao, $id_pergunta);
                $stmt_opcoes->execute();
                $id_resposta = $stmt_opcoes->insert_id;

                // Verifica se há uma pergunta encadeada para esta resposta
                $nova_pergunta = $_POST['nova_pergunta'][$index] ?? null;
                if (!empty(trim($nova_pergunta))) {
                    // Insere a nova pergunta associada à resposta
                    $sql_nova_pergunta = "INSERT INTO PERGUNTA (ds_pergunta, FORMULARIO_id_formulario, CATEGORIA_id_categoria, TIPO_PERGUNTA_id_tipo_pergunta, id_resposta_origen)
                                          VALUES (?, ?, ?, 1, ?)"; // Tipo padrão para perguntas encadeadas é "Texto"
                    $stmt_nova_pergunta = $conn->prepare($sql_nova_pergunta);
                    $stmt_nova_pergunta->bind_param("siii", $nova_pergunta, $id_formulario, $id_categoria, $id_resposta);
                    $stmt_nova_pergunta->execute();
                    $stmt_nova_pergunta->close();
                }
            }
        }

        $stmt_opcoes->close();
    }

    // Confirma a transação
    $conn->commit();

    echo "<h2>Pergunta adicionada com sucesso!</h2>";
    echo "<p><a href='detalhesFormulario.php?id=$id_formulario'>Voltar para o formulário</a></p>";
} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    $conn->rollback();
    echo "<p>Ocorreu um erro ao adicionar a pergunta: " . $e->getMessage() . "</p>";
}

$stmt->close();
$conn->close();
?>