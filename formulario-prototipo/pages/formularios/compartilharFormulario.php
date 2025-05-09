<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para acessar esta página.");
}

// Coleta o ID do formulário da URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do formulário não especificado.");
}

$id_formulario = $_GET['id'];
$url_formulario = "http://localhost/responderFormulario.php?id=" . $id_formulario;
$iframe_code = '<iframe src="' . $url_formulario . '" width="600" height="400"></iframe>';
$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url_formulario);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Compartilhar Formulário</title>
    <link rel="stylesheet" href="../../css/responderFormulario.css">
</head>
<body>
<section class="container">
    <h1>Compartilhar Formulário</h1>

    <div class="formato">
        <h2>URL</h2>
        <input type="text" id="url" value="<?php echo htmlspecialchars($url_formulario); ?>" readonly>
        <button onclick="copiarTexto('url')">Copiar</button>
    </div>

    <div class="formato">
        <h2>Iframe</h2>
        <textarea id="iframe" readonly><?php echo htmlspecialchars($iframe_code); ?></textarea>
        <button onclick="copiarTexto('iframe')">Copiar</button>
    </div>

    <div class="formato">
        <h2>QR Code</h2>
        <img src="<?php echo htmlspecialchars($qr_code_url); ?>" alt="QR Code">
        <button onclick="copiarTexto('qrcode')">Copiar Link do QR Code</button>
    </div>
</section>

<script>
function copiarTexto(id) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.select();
        document.execCommand('copy');
        alert("Texto copiado: " + elemento.value);
    }
}
</script>
</body>
</html>