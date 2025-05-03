<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para excluir um formulário.";
    header("Location: listarFormulario.php");
    exit;
}

require_once 'Formulario.php';

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do formulário não especificado.";
    header("Location: listarFormulario.php");
    exit;
}

$id_formulario = $_GET['id'];
$idUsuario = $_SESSION['id_usuario'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $_SESSION['mensagem'] = "Erro de conexão com o banco de dados.";
    header("Location: listarFormulario.php");
    exit;
}

try {
    // Inicia uma transação para garantir consistência
    $conn->begin_transaction();

    // Variáveis para statements
    $stmt_resposta_usuario = null;
    $stmt_resposta_encadeada = null;
    $stmt_pergunta_encadeada = null;
    $stmt_resposta = null;
    $stmt_pergunta = null;
    $stmt_categoria = null;
    $stmt_formulario = null;

    // 1. Excluir registros em RESPOSTA_USUARIO
    $sql_excluir_resposta_usuario = "DELETE ru FROM RESPOSTA_USUARIO ru
                                     JOIN RESPOSTA r ON ru.RESPOSTA_id_resposta = r.id_resposta
                                     JOIN PERGUNTA p ON r.id_pergunta = p.id_pergunta
                                     WHERE p.FORMULARIO_id_formulario = ?";
    $stmt_resposta_usuario = $conn->prepare($sql_excluir_resposta_usuario);
    if (!$stmt_resposta_usuario) {
        throw new Exception("Erro ao preparar a consulta para excluir RESPOSTA_USUARIO.");
    }
    $stmt_resposta_usuario->bind_param("i", $id_formulario);
    $stmt_resposta_usuario->execute();

    // 2. Excluir respostas relacionadas a perguntas encadeadas
    $sql_excluir_resposta_encadeada = "DELETE r FROM RESPOSTA r
                                       JOIN PERGUNTA p ON r.id_pergunta = p.id_pergunta
                                       WHERE p.id_resposta_origen IS NOT NULL AND p.FORMULARIO_id_formulario = ?";
    $stmt_resposta_encadeada = $conn->prepare($sql_excluir_resposta_encadeada);
    if (!$stmt_resposta_encadeada) {
        throw new Exception("Erro ao preparar a consulta para excluir respostas relacionadas a perguntas encadeadas.");
    }
    $stmt_resposta_encadeada->bind_param("i", $id_formulario);
    $stmt_resposta_encadeada->execute();

    // 3. Excluir perguntas encadeadas
    $sql_excluir_pergunta_encadeada = "DELETE FROM PERGUNTA 
                                      WHERE id_resposta_origen IS NOT NULL AND FORMULARIO_id_formulario = ?";
    $stmt_pergunta_encadeada = $conn->prepare($sql_excluir_pergunta_encadeada);
    if (!$stmt_pergunta_encadeada) {
        throw new Exception("Erro ao preparar a consulta para excluir perguntas encadeadas.");
    }
    $stmt_pergunta_encadeada->bind_param("i", $id_formulario);
    $stmt_pergunta_encadeada->execute();

    // 4. Excluir respostas relacionadas a perguntas principais
    $sql_excluir_respostas = "DELETE r FROM RESPOSTA r
                              JOIN PERGUNTA p ON r.id_pergunta = p.id_pergunta
                              WHERE p.id_resposta_origen IS NULL AND p.FORMULARIO_id_formulario = ?";
    $stmt_resposta = $conn->prepare($sql_excluir_respostas);
    if (!$stmt_resposta) {
        throw new Exception("Erro ao preparar a consulta para excluir respostas relacionadas a perguntas principais.");
    }
    $stmt_resposta->bind_param("i", $id_formulario);
    $stmt_resposta->execute();

    // 5. Excluir perguntas principais
    $sql_excluir_perguntas = "DELETE FROM PERGUNTA 
                             WHERE id_resposta_origen IS NULL AND FORMULARIO_id_formulario = ?";
    $stmt_pergunta = $conn->prepare($sql_excluir_perguntas);
    if (!$stmt_pergunta) {
        throw new Exception("Erro ao preparar a consulta para excluir perguntas principais.");
    }
    $stmt_pergunta->bind_param("i", $id_formulario);
    $stmt_pergunta->execute();

    // 6. Excluir categorias
    $sql_excluir_categorias = "DELETE FROM CATEGORIA WHERE FORMULARIO_id_formulario = ?";
    $stmt_categoria = $conn->prepare($sql_excluir_categorias);
    if (!$stmt_categoria) {
        throw new Exception("Erro ao preparar a consulta para excluir categorias.");
    }
    $stmt_categoria->bind_param("i", $id_formulario);
    $stmt_categoria->execute();

    // 7. Excluir o formulário
    $sql_excluir_formulario = "DELETE FROM FORMULARIO WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
    $stmt_formulario = $conn->prepare($sql_excluir_formulario);
    if (!$stmt_formulario) {
        throw new Exception("Erro ao preparar a consulta para excluir o formulário.");
    }
    $stmt_formulario->bind_param("ii", $id_formulario, $idUsuario);

    if ($stmt_formulario->execute()) {
        $_SESSION['mensagem'] = "Formulário excluído com sucesso!";
    } else {
        throw new Exception("Ocorreu um erro ao excluir o formulário.");
    }

    // Confirma a transação
    $conn->commit();
    $_SESSION['mensagem'] = "Formulário excluído com sucesso!"; // Mensagem de sucesso

} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    $conn->rollback();
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage(); // Mensagem de erro
} finally {
    // Fecha os statements, se forem inicializados
    if (isset($stmt_resposta_usuario)) {
        $stmt_resposta_usuario->close();
    }
    // Fecha os outros statements...
    $conn->close();
}

// Redireciona para listarFormulario.php
header("Location: listarFormulario.php");
exit;
?>