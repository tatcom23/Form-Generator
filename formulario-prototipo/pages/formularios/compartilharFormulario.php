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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartilhar Formulário</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../css/compartilharFormulario.css">
</head>
<body class="bg-gray-100 font-sans">
    <section class="container max-w-3xl mx-auto my-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-emerald-600 mb-8">
            <i class="fas fa-share-alt mr-2"></i> Compartilhar Formulário
        </h1>

        <div class="formato mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">
                <i class="fas fa-link mr-2 text-emerald-500"></i> URL
            </h2>
            <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <input type="text" id="url" value="<?php echo htmlspecialchars($url_formulario); ?>" readonly
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <button onclick="copiarTexto('url')"
                        class="p-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center w-full sm:w-auto justify-center">
                    <i class="fas fa-copy mr-2"></i> Copiar
                </button>
            </div>
        </div>

        <div class="formato mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">
                <i class="fas fa-code mr-2 text-emerald-500"></i> Iframe
            </h2>
            <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3">
                <textarea id="iframe" readonly
                          class="iframe-textarea w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"><?php echo htmlspecialchars($iframe_code); ?></textarea>
                <button onclick="copiarTexto('iframe')"
                        class="p-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center w-full sm:w-auto justify-center">
                    <i class="fas fa-copy mr-2"></i> Copiar
                </button>
            </div>
        </div>

        <div class="formato mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">
                <i class="fas fa-qrcode mr-2 text-emerald-500"></i> QR Code
            </h2>
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <img src="<?php echo htmlspecialchars($qr_code_url); ?>" alt="QR Code" class="w-40 h-40 rounded-lg shadow-md">
                <button onclick="copiarTexto('url')"
                        class="p-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center w-full sm:w-auto justify-center">
                    <i class="fas fa-copy mr-2"></i> Copiar Link do QR Code
                </button>
            </div>
        </div>

        <div class="text-center">
            <a href="../paginaHome/homeAdmin.php" 
               class="inline-block p-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center justify-center mx-auto">
                <i class="fas fa-home mr-2"></i> Voltar para Home
            </a>
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