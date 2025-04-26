<?php
session_start();

// Simulação de sessão para testes (remover isso em produção)
$_SESSION['user_id'] = $_SESSION['user_id'] ?? 1;
$_SESSION['user_name'] = $_SESSION['user_name'] ?? 'Usuário Exemplo';
$_SESSION['user_role'] = $_SESSION['user_role'] ?? 'user'; // ou 'admin'

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS compartilhado -->
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>

<?php if ($user_role === 'admin') : ?>
    <!-- Página do Administrador -->
    <header>
        <div class="container">
            <a href="home.php" class="logo">Painel Admin</a>
            <nav>
                <ul>
                    <li><a href="criar_formulario.php">Criar Formulário</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="hero">
        <h1>Bem-vindo, Administrador <?php echo htmlspecialchars($user_name); ?>!</h1>
        <h2>Formulários Criados</h2>
    </div>

    <div id="form-list">
        <?php
        // Simulação de formulários do admin
        $formularios_admin = [
            [
                'id_formulario' => 1,
                'nome_formulario' => 'Formulário de Feedback',
                'descricao_formulario' => 'Coletar opiniões sobre o serviço.'
            ],
            [
                'id_formulario' => 2,
                'nome_formulario' => 'Pesquisa de Satisfação',
                'descricao_formulario' => 'Avalie o atendimento recebido.'
            ]
        ];

        if (!empty($formularios_admin)) {
            foreach ($formularios_admin as $form) {
                echo "<div class='form-item'>";
                echo "<strong>{$form['nome_formulario']}</strong><br>";
                echo "<p>{$form['descricao_formulario']}</p>";
                echo "<a href='visualizar_respostas.php?id={$form['id_formulario']}'>Visualizar Respostas</a> | ";
                echo "<a href='editar_formulario.php?id={$form['id_formulario']}'>Editar</a> | ";
                echo "<a href='excluir_formulario.php?id={$form['id_formulario']}'>Excluir</a>";
                echo "</div><br>";
            }
        } else {
            echo "<p>Você ainda não criou nenhum formulário.</p>";
        }
        ?>
    </div>

<?php else : ?>
    <!-- Página do Usuário Comum -->
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
        <?php
        // Simulação de formulários disponíveis para o usuário
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

        if (!empty($formularios_disponiveis)) {
            foreach ($formularios_disponiveis as $form) {
                echo "<div class='form-item'>";
                echo "<strong>{$form['nome_formulario']}</strong><br>";
                echo "<p>{$form['descricao_formulario']}</p>";
                echo "<a href='responder_formulario.php?id={$form['id_formulario']}'>Responder Formulário</a>";
                echo "</div><br>";
            }
        } else {
            echo "<p>Não há formulários disponíveis no momento.</p>";
        }
        ?>
    </div>
<?php endif; ?>

<footer>
    <p>&copy; 2025 Sistema de Formulários. Todos os direitos reservados.</p>
</footer>

</body>
</html>


