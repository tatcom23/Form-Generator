<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Form Generator</title>
  <link rel="stylesheet" href="../../css/loginCadastro.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Font Awesome -->
  <style>
    
    .senha-forca {
      height: 10px;
      width: 100%;
      background: #ddd;
      border-radius: 5px;
      margin-top: 5px;
    }

    .senha-forca span {
      display: block;
      height: 100%;
      border-radius: 5px;
    }

    .fraca { width: 25%; background: red; }
    .media { width: 50%; background: orange; }
    .boa   { width: 75%; background: yellowgreen; }
    .forte { width: 100%; background: green; }

    .forca-texto {
      font-size: 14px;
      margin-top: 5px;
      color: #ccc;
      text-align: left;
    }
  </style>
</head>
<body>

<section class="login-container">
  <h1>Bem-vindo ao Form Generator</h1>
  <p>Crie sua conta para acessar o sistema</p>

  <form action="processarCriarUsuario.php" method="post" class="login-form">
    <input type="text" id="nm_usuario" name="nm_usuario" placeholder="Nome Completo" required>

    <input type="text" id="cd_cpf_usuario" name="cd_cpf_usuario" placeholder="CPF (somente números)" required maxlength="14">

    <input type="email" id="nm_email_usuario" name="nm_email_usuario" placeholder="E-mail" required>
    <div class="senha-container">
   
    <input
      type="password"
      id="cd_senha_usuario"
      name="cd_senha_usuario"
      placeholder="Senha"
      required
      minlength="8"
      pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}"
      title="Use no mínimo 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.">

    <button type="button" class="mostrar-senha-btn" id="toggleSenha">
      <i class="fa-solid fa-eye" style="color: lime;"></i>
    </button>
  </div>

    <div class="senha-forca" id="barraForca"><span></span></div>
    <div class="forca-texto" id="textoForca">Digite uma senha</div>

    <div class="role-selection">
      <label class="role-label">
        <input type="radio" name="user_role" value="admin" required>
        Admin
      </label>
      <label class="role-label">
        <input type="radio" name="user_role" value="usuario" required>
        Usuário
      </label>
    </div>

    <input type="submit" class="cta-btn" value="Criar">
  </form>

  <p class="create-account">Já tem uma conta? <a href="../login/login.php">Fazer Login</a></p>
</section>

<script>
  // Força da senha
  const senhaInput = document.getElementById("cd_senha_usuario");
  const barraForca = document.getElementById("barraForca").firstElementChild;
  const textoForca = document.getElementById("textoForca");
  const toggleSenha = document.getElementById("toggleSenha");
  const icon = toggleSenha.querySelector("i");

  senhaInput.addEventListener("input", function () {
    const senha = senhaInput.value;
    let forca = 0;

    if (senha.length >= 8) forca++;
    if (/[A-Z]/.test(senha)) forca++;
    if (/[a-z]/.test(senha)) forca++;
    if (/\d/.test(senha)) forca++;
    if (/[\W]/.test(senha)) forca++;

    if (forca <= 2) {
      barraForca.className = "fraca";
      textoForca.textContent = "Senha fraca";
      textoForca.style.color = "red";
    } else if (forca === 3) {
      barraForca.className = "media";
      textoForca.textContent = "Senha média";
      textoForca.style.color = "orange";
    } else if (forca === 4) {
      barraForca.className = "boa";
      textoForca.textContent = "Senha boa";
      textoForca.style.color = "yellowgreen";
    } else {
      barraForca.className = "forte";
      textoForca.textContent = "Senha forte";
      textoForca.style.color = "lime";
    }
  });

  // Mostrar/ocultar senha
  toggleSenha.addEventListener("click", function () {
    const tipo = senhaInput.getAttribute("type");
    if (tipo === "password") {
      senhaInput.setAttribute("type", "text");
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      senhaInput.setAttribute("type", "password");
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  });

  // Máscara para CPF
  const cpfInput = document.getElementById("cd_cpf_usuario");
  cpfInput.addEventListener("input", function () {
    let valor = cpfInput.value.replace(/\D/g, "");
    if (valor.length > 11) valor = valor.slice(0, 11);
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    cpfInput.value = valor;
  });
</script>

</body>
</html>
