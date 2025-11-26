<?php
session_start();

function formatar_preco($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

$carrinho = $_SESSION['carrinho'] ?? [];

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "cooperativa");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Carrinho de Compras - Cooperativa</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f9f4;
    color: #2e4d2e;
    margin: 0; padding: 20px;
  }
  h1 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
  }
  .container {
    max-width: 700px;
    background: #e9f2e9;
    padding: 25px 30px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgb(46 77 46 / 0.15);
  }
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
  }
  th, td {
    padding: 12px 15px;
    text-align: left;
  }
  thead th {
    color: #1b331b;
    font-weight: 700;
    border-bottom: 2px solid #a6c7a6;
  }
  tbody tr {
    background-color: #d7e6d7;
    border-radius: 10px;
  }
  tbody tr:hover {
    background-color: #c0d8c0;
  }
  tbody td {
    border-bottom: none;
  }
  .total {
    text-align: right;
    font-size: 1.25em;
    font-weight: 700;
    margin-top: 15px;
    color: #1b331b;
  }
  .btn {
    display: inline-block;
    background-color: #3a7d44;
    color: #fff;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    transition: background-color 0.3s ease;
    margin-top: 20px;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    user-select: none;
  }
  .btn:hover {
    background-color: #2f6534;
  }
  .btn-back {
    background-color: #7bbd7b;
  }
  .btn-back:hover {
    background-color: #5fa45f;
  }
  .empty-msg {
    text-align: center;
    font-size: 1.2em;
    color: #4a704a;
    margin: 40px 0;
  }
</style>
</head>
<body>
<div class="container">
  <h1>Seu Carrinho</h1>

  <?php if (empty($carrinho)): ?>
    <p class="empty-msg">Seu carrinho está vazio.</p>
    <a href="loja.php" class="btn btn-back">← Voltar para a Loja</a>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Preço Unitário</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($carrinho as $id => $quantidade):
          $resultado = $conn->query("SELECT nome, preco FROM produtos WHERE id = $id");
          if ($resultado && $resultado->num_rows > 0):
            $produto = $resultado->fetch_assoc();
            $subtotal = $quantidade * $produto['preco'];
            $total += $subtotal;
        ?>
        <tr>
          <td><?= htmlspecialchars($produto['nome']); ?></td>
          <td><?= intval($quantidade); ?></td>
          <td><?= formatar_preco($produto['preco']); ?></td>
          <td><?= formatar_preco($subtotal); ?></td>
        </tr>
        <?php
          endif;
        endforeach;
        ?>
      </tbody>
    </table>

    <p class="total">Total: <?= formatar_preco($total); ?></p>

    <a href="loja.php" class="btn btn-back">← Continuar Comprando</a>
    <a href="finalizar_pedido.php" class="btn btn-back">← Finalizar compra</a>
  <?php endif; ?>
</div>
</body>
</html>
