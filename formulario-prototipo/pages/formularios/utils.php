<?php
// Arquivo utils.php

// Função para verificar se o usuário é o dono do formulário
function verificarPropriedadeFormulario($conn, $id_formulario, $id_usuario) {
    $sql = "SELECT id_formulario FROM FORMULARIO WHERE id_formulario = ? AND USUARIO_id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_formulario, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>