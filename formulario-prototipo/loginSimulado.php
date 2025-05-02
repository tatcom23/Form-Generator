<?php
session_start();
$_SESSION['id_usuario'] = 1; // Simula o ID de um usuário logado
$_SESSION['nm_usuario'] = "João Silva"; // Nome do usuário (opcional, para exibição)
header("Location: pages/formularios/listarFormulario.php?id=3"); // Redireciona para a página de listagem
exit;
?>