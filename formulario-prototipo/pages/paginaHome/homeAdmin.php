<?php 
session_start();

// Dados simulados para visualização sem banco
$admin_name = $_SESSION['user_name'] ?? 'Admin Exemplo';

// Simula formulários criados
$formularios = [
    [
        'id_formulario' => 1,
        'nome_formulario' => 'Pesquisa de Satisfação',
        'descricao_formulario' => 'Formulário para avaliar os serviços.'
    ],
    [
        'id_formulario' => 2,
        'nome_formulario' => 'Cadastro de Interesse',
        'descricao_formulario' => 'Formulário para novos interessados.'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Meus Formulários</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclui o CSS -->
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>

<header>
    <div class="container">
        <a href="admin_home.php" class="logo">Painel Admin</a>
        <nav>
            <ul>
                <li><a href="criar_formulario.php">Criar Formulário</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="hero">
    <h1>Bem-vindo, Administrador <?php echo htmlspecialchars($admin_name); ?>!</h1>
    <h2>Formulários Criados</h2>
</div>

<div id="form-list">
    <?php if (!empty($formularios)) : ?>
        <?php foreach ($formularios as $form) : ?>
            <div class="form-item">
                <strong><?php echo htmlspecialchars($form['nome_formulario']); ?></strong><br>
                <p><?php echo htmlspecialchars($form['descricao_formulario']); ?></p>
                <a href="visualizar_respostas.php?id=<?php echo $form['id_formulario']; ?>">Visualizar Respostas</a> |
                <a href="editar_formulario.php?id=<?php echo $form['id_formulario']; ?>">Editar</a> |
                <a href="excluir_formulario.php?id=<?php echo $form['id_formulario']; ?>">Excluir</a>
            </div><br>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Você ainda não criou nenhum formulário.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 Sistema de Formulários. Todos os direitos reservados.</p>
</footer>

</body>
</html>
