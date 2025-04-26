<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Responder Formulário</title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>
<section class="login-container">
    <h1>Responder Formulário</h1>

    <!-- Simulando carregamento de perguntas -->
    <form action="../../formulario_controller.php" method="POST">
        <input type="hidden" name="id_formulario" value="123">

        <p><strong>1. Como você avalia nosso serviço?</strong></p>
        <input type="text" name="resposta_1" required>

        <p><strong>2. O que podemos melhorar?</strong></p>
        <textarea name="resposta_2"></textarea>

        <button type="submit" name="acao" value="responder">Enviar Respostas</button>
    </form>
</section>
</body>
</html>
