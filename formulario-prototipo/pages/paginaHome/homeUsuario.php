<?php 
session_start();

// Simula dados do usuário
$user_id = $_SESSION['user_id'] ?? 1;
$user_name = $_SESSION['user_name'] ?? 'Usuário Exemplo';

// Simula formulários abertos
$formularios_disponiveis = [
    [
        'id_formulario' => 3,
        'nome_formulario' => 'Avaliação de Evento',
        'descricao_formulario' => 'Dê sua opinião sobre o evento que participou.'
    ],
    [
        'id_formulario' => 4,
        'nome_formulario' => 'Cadastro de Habilidades',
        'descricao_formulario' => 'Informe suas principais habilidades técnicas.'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Formulários Disponíveis</title>
    <!-- Link para o arquivo CSS -->
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="home.php" class="logo">Painel Usuário</a>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="hero">
        <h1>Bem-vindo, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <h2>Formulários Disponíveis</h2>
    </div>

    <div id="form-list">
        <?php if (!empty($formularios_disponiveis)) : ?>
            <?php foreach ($formularios_disponiveis as $form) : ?>
                <div class="form-item">
                    <strong><?php echo htmlspecialchars($form['nome_formulario']); ?></strong><br>
                    <p><?php echo htmlspecialchars($form['descricao_formulario']); ?></p>
                    <a href="responder_formulario.php?id=<?php echo $form['id_formulario']; ?>">Responder Formulário</a>
                </div><br>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Não há formulários disponíveis no momento.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Meu Sistema. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
