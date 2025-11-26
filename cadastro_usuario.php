<?php
session_start();
include 'conexao.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $endereco = $conn->real_escape_string($_POST['endereco']);
    $tipo = isset($_POST['admin']) ? 'admin' : 'cliente';

    // Verifica se email já existe
    $sql_check = "SELECT id FROM usuarios WHERE email = '$email' LIMIT 1";
    $res_check = $conn->query($sql_check);

    if ($res_check && $res_check->num_rows > 0) {
        $erro = "Email já cadastrado!";
    } else {
        $sql_insert = "INSERT INTO usuarios (nome, email, senha, telefone, endereco, tipo) VALUES 
                       ('$nome', '$email', '$senha', '$telefone', '$endereco', '$tipo')";
        if ($conn->query($sql_insert)) {
            echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='index.html';</script>";
            exit;
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Cadastro de Usuário</title>
<style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
    form { background: #c8e6c9; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; }
    input, label { display: block; width: 100%; margin-bottom: 12px; }
    label { font-weight: bold; color: #2e7d32; }
    input[type="submit"] { background-color: #2e7d32; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 6px; }
    input[type="submit"]:hover { background-color: #1b5e20; }
    .erro { color: red; margin-bottom: 12px; }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: normal;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
<button onclick="lerTexto()">🔊 Ler</button>
<button onclick="pausarLeitura()">⏸ Pausar</button>
<button onclick="pararLeitura()">⛔ Parar</button>

<h2>Cadastro de Usuário</h2>

<?php if ($erro): ?>
    <div class="erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" action="">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required />

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required />

    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" />

    <label for="endereco">Endereço:</label>
    <input type="text" id="endereco" name="endereco" />

    <label class="checkbox-label">
        <input type="checkbox" name="admin" value="1" />
        Cadastrar como administrador
    </label>

    <input type="submit" value="Cadastrar" />
</form>
<script>
let utterance; // Variável global para controlar a fala

function lerTexto() {
    const texto = document.body.innerText;
    utterance = new SpeechSynthesisUtterance(texto);

    utterance.lang = "pt-BR";
    utterance.rate = 1;
    utterance.pitch = 1;

    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
}

function pausarLeitura() {
    if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
        window.speechSynthesis.pause();
    } else if (window.speechSynthesis.paused) {
        window.speechSynthesis.resume();
    }
}

function pararLeitura() {
    window.speechSynthesis.cancel();
}
</script>

</body>
</html>
