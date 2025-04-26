<!DOCTYPE html> 
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criação de Formulário</title>
  <link rel="stylesheet" href="../../css/criarFormulario.css">
</head>
<body>
  <div class="container">
    <div id="create-form" class="module active">
      <h2>Criar Formulário</h2>

      <form id="form" action="../../formulario_controller.php" method="POST">
        <label for="formName">Nome do Formulário</label>
        <input type="text" id="formName" name="titulo_formulario" placeholder="Digite o nome do formulário" required>

        <div id="categories-container">
          <h3>Categorias</h3>
          <div id="category-list">
            <!-- Categorias adicionadas dinamicamente -->
          </div>
          <button type="button" onclick="addCategory()">Adicionar Categoria</button>
        </div>

        <div id="questions-container">
          <h3>Perguntas</h3>
          <!-- Perguntas adicionadas dinamicamente aqui -->
        </div>

        <input type="hidden" name="acao" value="criar">

        <button type="button" onclick="addQuestion()">Adicionar Pergunta</button>
        <button type="submit">Salvar Formulário</button>
      </form>

      <button onclick="previewForm()">Visualizar Prévia</button>
    </div>
  </div>

  <script src="../../js/scripts.js"></script>
</body>
</html>
