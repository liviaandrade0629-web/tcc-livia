<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

// Buscar os dados atuais
$sql = "SELECT nome, email, telefone, endereco FROM usuarios WHERE id = $usuario_id";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $endereco = $conn->real_escape_string($_POST['endereco']);
    
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone', endereco='$endereco', senha='$senha' WHERE id=$usuario_id";
    } else {
        $sql_update = "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone', endereco='$endereco' WHERE id=$usuario_id";
    }

    if ($conn->query($sql_update)) {
    header("Location: index.html");
    exit;
}

    } else {
        $erro = "Erro ao atualizar: " . $conn->error;
    }

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Cadastro</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #eef7ee;
        padding: 20px;
    }
    form {
        max-width: 500px;
        margin: 40px auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #2e7d32;
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #2e7d32;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    input[type="submit"] {
        margin-top: 20px;
        background: #2e7d32;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background: #1b5e20;
    }
    .mensagem {
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
        color: green;
    }
    .erro {
        text-align: center;
        margin-bottom: 15px;
        color: red;
    }
</style>
</head>
<body>

<form method="post">
    <h2>Editar Meu Cadastro</h2>

    <?php if ($sucesso): ?>
        <div class="mensagem"><?= $sucesso ?></div>
    <?php elseif ($erro): ?>
        <div class="erro"><?= $erro ?></div>
    <?php endif; ?>

    <label for="nome">Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

    <label for="telefone">Telefone:</label>
    <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">

    <label for="endereco">Endereço:</label>
    <input type="text" name="endereco" value="<?= htmlspecialchars($usuario['endereco']) ?>">

    <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
    <input type="password" name="senha">

    <input type="submit" value="Salvar Alterações">
	
</form>

</body>
</html>
