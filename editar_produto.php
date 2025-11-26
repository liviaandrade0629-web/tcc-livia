<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    echo "ID do produto não especificado.";
    exit;
}

$id = intval($_GET['id']);
$erro = "";

// Buscar dados atuais para exibir no formulário
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $resultado->fetch_assoc();
$categoria = $produto['categoria']; // <- Variável agora definida corretamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $categoria = $_POST['categoria'] ?? $categoria;
    $imagem_atual = $produto['imagem'];

    // Upload nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo_tmp = $_FILES['imagem']['tmp_name'];
        $nome_original = $_FILES['imagem']['name'];
        $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $ext_permitidas)) {
            $novo_nome_imagem = uniqid() . "." . $ext;
            $destino = "uploads/" . $novo_nome_imagem;
            if (move_uploaded_file($arquivo_tmp, $destino)) {
                if ($imagem_atual && file_exists("uploads/" . $imagem_atual)) {
                    unlink("uploads/" . $imagem_atual);
                }
                $imagem_atual = $novo_nome_imagem;
            } else {
                $erro = "Erro ao salvar a imagem.";
            }
        } else {
            $erro = "Formato da imagem não permitido. Use jpg, jpeg, png ou gif.";
        }
    }

    if (!$erro) {
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, imagem = ? WHERE id = ?");
        $stmt->bind_param("ssdssi", $nome, $descricao, $preco, $categoria, $imagem_atual, $id);

        if ($stmt->execute()) {
            header("Location: produtos.php?mensagem=Produto+alterado+com+sucesso");
            exit;
        } else {
            $erro = "Erro ao alterar produto: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Editar Produto</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f9f6; padding: 30px; }
    h2 { color: #2e7d32; text-align: center; }
    form { background: white; padding: 20px; border-radius: 10px; max-width: 500px; margin: 0 auto; }
    input, textarea, select { width: 100%; padding: 8px; margin-bottom: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 1em; }
    input[type=submit] { background: #2e7d32; color: white; border: none; cursor: pointer; font-weight: bold; }
    input[type=submit]:hover { background: #1b5e20; }
    .erro { padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px; text-align: center; }
    img { display: block; margin: 10px auto; max-width: 150px; }
  </style>
</head>
<body>

<h2>Editar Produto</h2>
<?php if ($erro): ?>
  <div class="erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>

    <label>Categoria:</label>
    <select name="categoria" required>
        <option value="queijo" <?= $categoria === 'queijo' ? 'selected' : '' ?>>Queijo</option>
        <option value="hortalica" <?= $categoria === 'hortalica' ? 'selected' : '' ?>>Hortaliça</option>
        <option value="carne" <?= $categoria === 'carne' ? 'selected' : '' ?>>Carne</option>
        <option value="outros" <?= $categoria === 'outros' ? 'selected' : '' ?>>Outros</option>
    </select>

    <label>Imagem Atual:</label>
    <?php if ($produto['imagem']): ?>
        <img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do produto">
    <?php else: ?>
        <span>Sem imagem</span>
    <?php endif; ?>

    <label>Nova Imagem (opcional):</label>
    <input type="file" name="imagem" accept="image/*">

    <input type="submit" value="Salvar Alterações">
</form>

</body>
</html>
