<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Formulário - Form Generator</title>
    <link rel="stylesheet" href="../../css/loginCadastro.css">
    <style>
        .login-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .cta-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .cta-btn:hover {
            background-color: #0056b3;
        }
        .opcao {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .remover-opcao {
            background-color: #dc3545;
        }
        .remover-opcao:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>

<section class="login-container">
    <h1><?php echo htmlspecialchars($formulario['nm_formulario']); ?></h1>

    <!-- Botão para Adicionar Categoria -->
    <p><a href="adicionarCategoria.php?id=<?php echo htmlspecialchars($id_formulario); ?>" class="cta-btn">Adicionar Categoria</a></p>

    <?php if (count($categorias) === 0): ?>
        <p>Nenhuma categoria cadastrada.</p>
    <?php endif; ?>

    <h2>Adicionar Pergunta</h2>
    <form class="login-form" id="form-adicionar-pergunta" action="processarAdicionarPergunta.php" method="POST">
        <input type="hidden" name="id_formulario" value="<?php echo htmlspecialchars($formulario['id_formulario']); ?>">
        
        <!-- Texto da Pergunta -->
        <label for="ds_pergunta">Texto da Pergunta:</label>
        <input type="text" name="ds_pergunta" placeholder="Digite a pergunta" required>
        
        <!-- Categoria -->
        <label for="categoria">Categoria:</label>
        <select name="id_categoria" required>
            <option value="" disabled selected>Categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id_categoria']; ?>">
                    <?php echo htmlspecialchars($categoria['nm_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Tipo de Pergunta -->
        <label for="tipo_pergunta">Tipo de Pergunta:</label>
        <select name="id_tipo_pergunta" id="tipo-pergunta" required>
            <option value="" disabled selected>Tipo de Resposta</option>
            <?php foreach ($tipos_pergunta as $tipo): ?>
                <option value="<?php echo $tipo['id_tipo_pergunta']; ?>">
                    <?php echo htmlspecialchars($tipo['nm_tipo_pergunta']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Campo Dinâmico para Opções de Resposta -->
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

    <p><a href="listarFormulario.php" class="cta-btn">Voltar para Meus Formulários</a></p>
</section>

<script>
    // Mostrar/ocultar campos com base no tipo de pergunta
    document.getElementById('tipo-pergunta').addEventListener('change', function () {
        const tipoPergunta = this.value;
        const opcoesResposta = document.getElementById('opcoes-resposta');
        // Mostrar opções de resposta apenas para tipos específicos
        if (tipoPergunta === '2' || tipoPergunta === '3') { // IDs para Múltipla Escolha e Única Escolha
            opcoesResposta.style.display = 'block';
        } else {
            opcoesResposta.style.display = 'none';
        }
    });

    // Adicionar/remover campos dinâmicos para opções de resposta
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
        // Adicionar evento para remover a opção
        novaOpcao.querySelector('.remover-opcao').addEventListener('click', function () {
            containerOpcoes.removeChild(novaOpcao);
        });
    });

    // Adicionar perguntas encadeadas
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('adicionar-pergunta-encadeada')) {
            const opcao = event.target.closest('.opcao');
            const novaPerguntaHTML = `
                <div class="nova-pergunta-encadeada">
                    <hr>
                    <h4>Nova Pergunta Encadeada</h4>
                    <label>Texto da Pergunta:</label>
                    <input type="text" name="nova_pergunta[]" placeholder="Digite a nova pergunta">
                    <label>Tipo de Resposta:</label>
                    <select name="tipo_pergunta_encadeada[]">
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
            // Adicionar evento para remover a nova pergunta
            const novaPergunta = opcao.nextElementSibling;
            novaPergunta.querySelector('.remover-nova-pergunta').addEventListener('click', function () {
                novaPergunta.remove();
            });
            // Mostrar/ocultar opções de resposta para perguntas encadeadas
            const tipoPerguntaEncadeada = novaPergunta.querySelector('select[name="tipo_pergunta_encadeada[]"]');
            const opcoesEncadeadas = novaPergunta.querySelector('.opcoes-encadeadas');
            tipoPerguntaEncadeada.addEventListener('change', function () {
                const tipoSelecionado = this.value;
                if (tipoSelecionado === '2' || tipoSelecionado === '3') { // IDs para Múltipla Escolha e Única Escolha
                    opcoesEncadeadas.style.display = 'block';
                } else {
                    opcoesEncadeadas.style.display = 'none';
                }
            });
            // Adicionar opções dinâmicas para perguntas encadeadas
            novaPergunta.querySelector('.adicionar-opcao-encadeada').addEventListener('click', function () {
                const containerOpcoesEncadeadas = novaPergunta.querySelector('.container-opcoes-encadeadas');
                const novaOpcaoEncadeada = document.createElement('div');
                novaOpcaoEncadeada.className = 'opcao';
                novaOpcaoEncadeada.innerHTML = `
                    <label>Opção de Resposta:</label>
                    <input type="text" name="opcoes_encadeadas[]" placeholder="Ex.: Sim" required>
                    <button type="button" class="remover-opcao-encadeada cta-btn">Remover</button>
                `;
                containerOpcoesEncadeadas.appendChild(novaOpcaoEncadeada);
                // Adicionar evento para remover a opção encadeada
                novaOpcaoEncadeada.querySelector('.remover-opcao-encadeada').addEventListener('click', function () {
                    containerOpcoesEncadeadas.removeChild(novaOpcaoEncadeada);
                });
            });
        }
    });

    // Verifica se há uma mensagem flash e exibe um alert
    <?php if (isset($_SESSION['mensagem'])): ?>
        alert("<?php echo addslashes($_SESSION['mensagem']); ?>");
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>
</script>

</body>
</html>