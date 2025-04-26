<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Formulários Criados</title>
    <link rel="stylesheet" href="../../css/vizualizadorFormulario.css">
</head>
<body>
<section class="login-container">
    <h1>Formulários Criados</h1>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Simulação de formulários -->
            <tr>
                <td>Pesquisa de Satisfação</td>
                <td>Atendimento</td>
                <td>Ativo</td>
                <td>
                    <a href="editarFormulario.php?id=123">Editar</a> |
                    <a href="visualizarRespostas.php?id=123">Ver Respostas</a> |
                    <a href="excluirFormulario.php?id=123" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        </tbody>
    </table>
</section>
</body>
</html>
