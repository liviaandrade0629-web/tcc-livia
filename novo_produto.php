<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header("Location: produtos.php");
  exit;
}

$msg = "";
$erro = "";
$nome = $descricao = $preco = $estoque = $categoria = "";
$nome_imagem = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = trim($_POST['nome']);
  $descricao = trim($_POST['descricao']);
  $preco = floatval($_POST['preco']);
  $estoque = intval($_POST['estoque']);
  $categoria = $_POST['categoria'];

  // Tratar upload da imagem
  if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $arquivo_tmp = $_FILES['imagem']['tmp_name'];
    $nome_original = $_FILES['imagem']['name'];
    $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $ext_permitidas)) {
      $nome_imagem = uniqid() . "." . $ext;
      $destino = "uploads/" . $nome_imagem;
      if (!move_uploaded_file($arquivo_tmp, $destino)) {
        $erro = "Erro ao mover arquivo da imagem.";
        $nome_imagem = null;
      }
    } else {
      $erro = "Formato de imagem não permitido. Use jpg, jpeg, png ou gif.";
    }
  }

  if (!$erro) {
    if ($nome === "" || $categoria === "") {
      $erro = "Nome e categoria são obrigatórios.";
    } else {
      $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria, imagem) VALUES (?, ?, ?, ?, ?, ?)");
      if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
      }
      $stmt->bind_param("ssdiss", $nome, $descricao, $preco, $estoque, $categoria, $nome_imagem);
      if ($stmt->execute()) {
        $msg = "Produto cadastrado com sucesso!";
        $nome = $descricao = $preco = $estoque = $categoria = "";
        $nome_imagem = null;
      } else {
        $erro = "Erro ao cadastrar produto: " . $conn->error;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<center>
  <meta charset="UTF-8" />
  <title>Novo Produto</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f9f6; padding: 30px; }
    h1 { color: #2e7d32; }
    form { background: white; padding: 20px; border-radius: 10px; max-width: 450px; }
    input, textarea, select { width: 100%; padding: 8px; margin-bottom: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 1em; }
    input[type=submit] { background: #2e7d32; color: white; border: none; cursor: pointer; font-weight: bold; }
    input[type=submit]:hover { background: #1b5e20; }
    .msg { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px; }
    .erro { padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px; }
  </style>
</head>
<body>
  <h1>Cadastrar Produto</h1>

  <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <?php if ($erro): ?>
    <div class="erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" autocomplete="off">
    <input name="nome" placeholder="Nome" required autofocus value="<?= htmlspecialchars($nome) ?>">
    <textarea name="descricao" placeholder="Descrição"><?= htmlspecialchars($descricao) ?></textarea>
    <input name="preco" type="number" step="0.01" placeholder="Preço" required value="<?= htmlspecialchars($preco) ?>">
    <input name="estoque" type="number" placeholder="Estoque" required value="<?= htmlspecialchars($estoque) ?>">
    <select name="categoria" required>
      <option value="">Selecione uma categoria</option>
      <option value="queijo" <?= $categoria === 'queijo' ? 'selected' : '' ?>>Queijo</option>
      <option value="hortalica" <?= $categoria === 'hortaliça' ? 'selected' : '' ?>>Hortaliça</option>
      <option value="carne" <?= $categoria === 'carne' ? 'selected' : '' ?>>Carne</option>
    </select>
    <label>Imagem do Produto (jpg, jpeg, png, gif):</label>
    <input type="file" name="imagem" accept="image/*"><br>
    <input type="submit" value="Salvar">
  </form>
  </center>
</body>
</html>
