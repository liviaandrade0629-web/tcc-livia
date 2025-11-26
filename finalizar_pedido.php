<?php
session_start();
include 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? 0;
$carrinho = $_SESSION['carrinho'] ?? [];

if ($usuario_id == 0 || empty($carrinho)) {
  echo "<script>alert('Carrinho vazio ou sessão expirada.'); window.location='loja.php';</script>";
  exit;
}

// Cria o pedido
$conn->query("INSERT INTO pedidos (usuario_id) VALUES ($usuario_id)");
$pedido_id = $conn->insert_id;

// Insere os itens do pedido
foreach ($carrinho as $id => $qtd) {
  $res = $conn->query("SELECT preco FROM produtos WHERE id = $id");

  if ($res && $res->num_rows > 0) {
    $dados = $res->fetch_assoc();
    $preco = (float) $dados['preco'];
    $id = (int) $id;
    $qtd = (int) $qtd;
    $pedido_id = (int) $pedido_id;

    // INSERE O ITEM
    $sql_insert = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unit)
                   VALUES ($pedido_id, $id, $qtd, $preco)";
    if (!$conn->query($sql_insert)) {
      echo "<p style='color:red;'>Erro ao inserir item: " . $conn->error . "</p>";
    }

    // ATUALIZA O ESTOQUE
    $sql_update = "UPDATE produtos SET estoque = estoque - $qtd WHERE id = $id";
    if (!$conn->query($sql_update)) {
      echo "<p style='color:red;'>Erro ao atualizar estoque: " . $conn->error . "</p>";
    }
  }
}

unset($_SESSION['carrinho']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pedido Finalizado</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f8e9;
      text-align: center;
      padding: 50px;
    }
    .caixa {
      background: #fff;
      display: inline-block;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    h2 {
      color: #2e7d32;
      margin-bottom: 20px;
    }
    .btn {
      background: #388e3c;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 6px;
      display: inline-block;
      margin-top: 20px;
    }
    .btn:hover {
      background: #2e7d32;
    }
  </style>
</head>
<body>
  <div class="caixa">
    <h2>✅ Pedido realizado com sucesso!</h2>
    <p>Número do pedido: <strong>#<?= $pedido_id ?></strong></p>
    <a href="loja.php" class="btn">Voltar à loja</a>
  </div>
</body>
</html>
