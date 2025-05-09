<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Formulário - Form Generator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/detalhesFormulario.css">
</head>
<body>

<section class="login-container">
    <h1 class="titulo-principal">Adicionar Pergunta</h1>
    <h2 class="nome-formulario">Formulário: <?php echo htmlspecialchars($formulario['nm_formulario']); ?></h2>

    <p>
        <a href="adicionarCategoria.php?id=<?php echo $formulario['id_formulario']; ?>" class="cta-btn">
            <i class="fas fa-plus"></i> Adicionar Categoria
        </a>
        <!-- Botão Visualizar Formulário -->
        <a href="visualizarFormulario.php?id=<?php echo $formulario['id_formulario']; ?>" class="cta-btn">
            <i class="fas fa-eye"></i> Visualizar Formulário
        </a>
        <!-- Botão Finalizar Formulário -->
        <?php if ($formulario['status'] == 0): ?>
            <form action="finalizarFormulario.php" method="POST" style="display: inline;">
                <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($formulario['id_formulario']); ?>">
                <button type="submit" class="cta-btn" onclick="return confirm('Tem certeza que deseja finalizar este formulário? Após finalizar, não será possível adicionar novas perguntas.')">
                    <i class="fas fa-check"></i> Finalizar Formulário
                </button>
            </form>
        <?php else: ?>
            <button class="cta-btn" disabled>
                <i class="fas fa-lock"></i> Formulário Finalizado
            </button>
        <?php endif; ?>
    </p>

    <?php if (count($categorias) === 0): ?>
        <p>Nenhuma categoria cadastrada.</p>
    <?php endif; ?>
    
    <form class="login-form" id="form-adicionar-pergunta" action="processarAdicionarPergunta.php" method="POST">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($formulario['id_formulario']); ?>">

        <label for="ds_pergunta">Texto da Pergunta</label>
        <input type="text" name="ds_pergunta" placeholder="Digite a pergunta" required>

        <label for="categoria">Categoria</label>
        <select name="id_categoria" required>
            <option value="" disabled selected>Categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id_categoria']; ?>">
                    <?php echo htmlspecialchars($categoria['nm_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tipo_pergunta">Tipo de Pergunta</label>
        <select name="id_tipo_pergunta" id="tipo-pergunta" required>
            <option value="" disabled selected>Tipo de Resposta</option>
            <?php foreach ($tipos_pergunta as $tipo): ?>
                <option value="<?php echo $tipo['id_tipo_pergunta']; ?>">
                    <?php echo htmlspecialchars($tipo['nm_tipo_pergunta']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="opcoes-resposta" style="display: none;">
            <h3>Opções de Resposta</h3>
            <div id="container-opcoes">
                <div class="opcao">
                    <label>Opção de Resposta:</label>
                    <input type="text" name="opcoes[]" placeholder="Ex.: Sim" required>
                    <button type="button" class="adicionar-pergunta-encadeada cta-btn">+ Nova Pergunta Encadeada</button>
                    <button type="button" class="remover-opcao cta-btn">Remover</button>
                </div>
            </div>
            <button type="button" id="adicionar-opcao" class="cta-btn">+ Adicionar Opção</button>
        </div>

        <button type="submit" class="cta-btn">Adicionar Pergunta</button>
    </form>

    <div class="botoes-voltar">
        <a href="criarFormulario.php" class="cta-btn">Criar Novo Formulário</a>
        <a href="../paginaHome/homeAdmin.php" class="cta-btn">🏠 Página Inicial</a>
    </div>
</section>

<script>
  // Mostrar/ocultar opções de resposta ao alterar o tipo
  document.getElementById('tipo-pergunta').addEventListener('change', function () {
        const tipoPergunta = this.value;
        const opcoesResposta = document.getElementById('opcoes-resposta');
        const opcoesInputs = opcoesResposta.querySelectorAll('input[name="opcoes[]"]');

        if (tipoPergunta === '3' || tipoPergunta === '4') {
            opcoesResposta.style.display = 'block';
            opcoesInputs.forEach(input => input.setAttribute('required', 'required'));
        } else {
            opcoesResposta.style.display = 'none';
            opcoesInputs.forEach(input => input.removeAttribute('required'));
        }
    });

 // Botão de adicionar opção principal
 document.getElementById('adicionar-opcao').addEventListener('click', function () {
        const containerOpcoes = document.getElementById('container-opcoes');
        const novaOpcao = document.createElement('div');
        novaOpcao.className = 'opcao';
        novaOpcao.innerHTML = `
            <label>Opção de Resposta:</label>
            <input type="text" name="opcoes[]" placeholder="Ex.: Sim" required>
            <button type="button" class="adicionar-pergunta-encadeada cta-btn">+ Nova Pergunta Encadeada</button>
            <button type="button" class="remover-opcao cta-btn">Remover</button>
        `;
        containerOpcoes.appendChild(novaOpcao);
        novaOpcao.querySelector('.remover-opcao').addEventListener('click', () => novaOpcao.remove());
    });

// Perguntas encadeadas dinâmicas
document.addEventListener('click', function (event) {
        if (event.target.classList.contains('adicionar-pergunta-encadeada')) {
            const opcao = event.target.closest('.opcao');
            const novaPerguntaHTML = `
                <div class="nova-pergunta-encadeada">
                    <hr>
                    <h4>Nova Pergunta Encadeada</h4>
                    <label>Texto da Pergunta:</label>
                    <input type="text" name="nova_pergunta[]" placeholder="Digite a nova pergunta" required>
                    <label>Tipo de Resposta:</label>
                    <select name="tipo_pergunta_encadeada[]" class="tipo-pergunta-encadeada" required>
                        <option value="" disabled selected>Tipo de Resposta</option>
                        <?php foreach ($tipos_pergunta as $tipo): ?>
                            <option value="<?php echo $tipo['id_tipo_pergunta']; ?>">
                                <?php echo htmlspecialchars($tipo['nm_tipo_pergunta']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
    <div class="opcoes-encadeadas" style="display: none;">
                        <h5>Opções de Resposta</h5>
                        <div class="container-opcoes-encadeadas"></div>
                        <button type="button" class="adicionar-opcao-encadeada cta-btn">+ Adicionar Opção</button>
                    </div>
                    <button type="button" class="remover-nova-pergunta cta-btn">Remover</button>
                </div>
            `;
            opcao.insertAdjacentHTML('afterend', novaPerguntaHTML);
            const novaPergunta = opcao.nextElementSibling;

            // Remover pergunta encadeada
            novaPergunta.querySelector('.remover-nova-pergunta').addEventListener('click', () => novaPergunta.remove());

            // Mostrar opções encadeadas dependendo do tipo
            const selectTipo = novaPergunta.querySelector('.tipo-pergunta-encadeada');
            const opcoesEncadeadas = novaPergunta.querySelector('.opcoes-encadeadas');
            selectTipo.addEventListener('change', function () {
                opcoesEncadeadas.style.display = (this.value === '3' || this.value === '4') ? 'block' : 'none';
            });

            // Adicionar opção encadeada
            novaPergunta.querySelector('.adicionar-opcao-encadeada').addEventListener('click', function () {
                const container = novaPergunta.querySelector('.container-opcoes-encadeadas');
                const novaOpcao = document.createElement('div');
                novaOpcao.className = 'opcao';
                novaOpcao.innerHTML = `
                    <label>Opção de Resposta:</label>
                    <input type="text" name="opcoes_encadeadas[]" placeholder="Ex.: Sim" required>
                    <button type="button" class="remover-opcao-encadeada cta-btn">Remover</button>
                `;
                container.appendChild(novaOpcao);
                novaOpcao.querySelector('.remover-opcao-encadeada').addEventListener('click', () => novaOpcao.remove());
            });
        }
    });

 // Mensagem de sessão
 <?php if (isset($_SESSION['mensagem'])): ?>
        alert("<?php echo addslashes($_SESSION['mensagem']); ?>");
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>
</script>

</body>
</html>