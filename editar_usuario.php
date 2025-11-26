<?php
session_start();
include "conexao.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}

$id = intval($_GET['id']);

// Buscando dados do usuário incluindo telefone e endereco
$sql = "SELECT id, nome, email, tipo, telefone, endereco FROM usuarios WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    header("Location: usuarios.php");
    exit;
}
$usuario = $result->fetch_assoc();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $tipo = $_POST['tipo'] === 'admin' ? 'admin' : 'cliente';
    $telefone = $conn->real_escape_string(trim($_POST['telefone']));
    $endereco = $conn->real_escape_string(trim($_POST['endereco']));

    if (empty($nome) || empty($email)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        // Verificar se email já existe para outro usuário
        $sqlCheck = "SELECT id FROM usuarios WHERE email = '$email' AND id != $id LIMIT 1";
        $resCheck = $conn->query($sqlCheck);
        if ($resCheck->num_rows > 0) {
            $erro = 'E-mail já está em uso por outro usuário.';
        } else {
            // Atualiza usuário com telefone e endereco
            $sqlUpdate = "UPDATE usuarios SET 
                            nome='$nome', 
                            email='$email', 
                            tipo='$tipo',
                            telefone='$telefone',
                            endereco='$endereco'
                          WHERE id = $id";
            if ($conn->query($sqlUpdate)) {
                header("Location: painel_admin.php");
                exit;
            } else {
                $erro = 'Erro ao atualizar usuário: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Editar Usuário</title>
<style>
  form {
    max-width: 400px;
    margin: 30px auto;
    background: #e0f2e9;
    padding: 20px;
    border-radius: 8px;
  }
  label {
    display: block;
    margin-top: 15px;
  }
  input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
  }
  button {
    margin-top: 20px;
    background: #3a7d44;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
  }
  .erro {
    color: red;
    margin-top: 15px;
  }
</style>
</head>
<body>

<h2 style="text-align:center;">Editar Usuário</h2>

<form method="post" action="">
  <label>Nome:
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
  </label>
  <label>E-mail:
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
  </label>
  <label>Telefone:
    <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
  </label>
  <label>Endereço:
    <input type="text" name="endereco" value="<?= htmlspecialchars($usuario['endereco'] ?? '') ?>">
  </label>
  <label>Tipo:
    <select name="tipo">
      <option value="cliente" <?= $usuario['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
      <option value="admin" <?= $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
    </select>
  </label>

  <button type="submit">Salvar</button>

  <?php if ($erro): ?>
    <p class="erro"><?= $erro ?></p>
  <?php endif; ?>
</form>
</body>
</html>
