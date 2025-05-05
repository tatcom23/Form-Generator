<!DOCTYPE html>
<html lang="pt-BR">
<head>
<head>
    <meta charset="UTF-8">
    <title>Login - Form Generator</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<section class="login-container">
    <h1>Bem-vindo ao Form Generator</h1>
    <p>Faça login na sua conta</p>

    <form class="login-form" action="processarLogin.php" method="POST">
        <!-- Campo CPF -->
        <div class="input-container">
        <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF do Usuário" required>
        </div>

        <!-- Campo Senha -->
        <div class="input-container">
        <input type="password" id="senha" name="cd_senha_usuario" placeholder="Senha" required>
        <button type="button" class="toggle-senha" id="toggleSenha" aria-label="Mostrar ou ocultar senha">
            <i class="fas fa-eye"></i> <!-- Ícone Font Awesome -->
        </button>
        </div>

        <!-- Link para redefinir senha -->
        <p class="forgot-password"><a href="../recuperarSenha/recuperarSenha.php">Esqueceu a senha?</a></p>

        <!-- Botão de Login -->
        <button type="submit" class="cta-btn">Login</button>

        <!-- Link para criar conta -->
        <p class="create-account">Não tem uma conta? <a href="../criarUsuario/criarUsuario.php">Criar Conta</a></p>
    </form>
</section>

<script>
  // Máscara CPF (sem mudanças)
  const cpfInput = document.getElementById("cd_cpf_usuario");
  cpfInput.addEventListener("input", function () {
    let valor = cpfInput.value.replace(/\D/g, "");
    if (valor.length > 11) valor = valor.slice(0, 11);
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    cpfInput.value = valor;
  });

  // Mostrar/ocultar senha
  const senhaInput = document.getElementById("senha");
  const toggleSenhaBtn = document.getElementById("toggleSenha");
  const icon = toggleSenhaBtn.querySelector("i");

  toggleSenhaBtn.addEventListener("click", function () {
    if (senhaInput.type === "password") {
      senhaInput.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      senhaInput.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  });
</script>
</body>
</html>

<?php if (isset($_GET['erro'])): ?>
    <p style="color:red;">CPF ou senha inválidos.</p>
<?php endif; ?>


