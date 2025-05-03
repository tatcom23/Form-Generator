<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<p>Você precisa estar logado para criar um formulário.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Formulário - Form Generator</title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>

<section class="login-container">
    <h1>Criar Novo Formulário</h1>
    <form class="login-form" action="processarCriarFormulario.php" method="POST">
        <!-- Nome do Formulário -->
        <label for="nm_formulario">Nome do Formulário:</label>
        <input type="text" name="nm_formulario" placeholder="Digite o nome do formulário" required>

        <!-- Data de Início -->
        <label for="dt_inicio_formulario">Data de Início:</label>
        <input type="date" name="dt_inicio_formulario" required>

        <!-- Data de Fim -->
        <label for="dt_fim_formulario">Data de Fim:</label>
        <input type="date" name="dt_fim_formulario" required>

        <!-- Botão de Envio -->
        <button type="submit" class="cta-btn">Criar Formulário</button>
    </form>

    <p><a href="listarFormulario.php" class="cta-btn">Voltar para Meus Formulários</a></p>
</section>

</body>
</html>