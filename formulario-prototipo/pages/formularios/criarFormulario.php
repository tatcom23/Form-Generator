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
    <link rel="stylesheet" href="../../css/criarFormulario.css">
</head>
<body>

<section class="login-container">
    <h1>Criar Novo Formulário</h1>
        <form class="login-form" action="processarCriarFormulario.php" method="POST">
            <!-- Nome do Formulário -->
            <label for="nm_formulario">Nome do Formulário</label>
            <input type="text" id="nm_formulario" name="nm_formulario" placeholder="Digite o nome do formulário" required>

            <!-- Data de Início -->
            <label for="dt_inicio_formulario">Data de Início</label>
            <input type="date" id="dt_inicio_formulario" name="dt_inicio_formulario" required>

            <!-- Data de Fim -->
            <label for="dt_fim_formulario">Data de Término</label>
            <input type="date" id="dt_fim_formulario" name="dt_fim_formulario" required>

            <!-- Botão de Envio -->
            <button type="submit" class="cta-btn criar-formulario">
                Criar Formulário 📝
            </button>
    </form>

    <div class="botoes-voltar">
        <a href="listarFormulario.php" class="cta-btn voltar-formulario">
            ⬅ Meus Formulários
        </a>
        <a href="../paginaHome/homeAdmin.php" class="cta-btn voltar-home">
            🏠 Página Inicial
        </a>
    </div>

    </section>
</body>
</html>