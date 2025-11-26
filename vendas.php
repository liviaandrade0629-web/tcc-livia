<?php
session_start();
include 'conexao.php';

function formatar_preco($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

$sql = "SELECT p.id AS pedido_id, p.data, p.status,
               u.nome, u.email, u.telefone, u.endereco
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Pedidos Realizados</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #2e7d32;
      margin-bottom: 30px;
    }

    .pedido-box {
      background: #fff;
      border-left: 6px solid #2e7d32;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .pedido-cabecalho {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .status {
      color: #1b5e20;
      font-weight: bold;
    }

    .cliente-info p {
      margin: 3px 0;
      font-size: 14px;
    }

    .tabela-itens {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 14px;
    }

    .tabela-itens th, .tabela-itens td {
      padding: 8px;
      border: 1px solid #ccc;
      text-align: left;
    }

    .tabela-itens th {
      background-color: #c8e6c9;
    }

    .pedido-total {
      margin-top: 15px;
      font-weight: bold;
      text-align: right;
      color: #2e7d32;
      font-size: 15px;
    }
  </style>
</head>
<body>

<h1>Pedidos Realizados</h1>

<?php
if (!$result || $result->num_rows === 0) {
    echo "<p style='text-align:center;'>Nenhum pedido encontrado.</p>";
} else {
    while ($pedido = $result->fetch_assoc()) {
        // Buscar os itens do pedido
        $sql_itens = "SELECT pr.nome AS produto_nome, ip.quantidade, ip.preco_unit 
                      FROM itens_pedido ip 
                      INNER JOIN produtos pr ON ip.produto_id = pr.id
                      WHERE ip.pedido_id = ?";
        $stmt = $conn->prepare($sql_itens);
        $stmt->bind_param("i", $pedido['pedido_id']);
        $stmt->execute();
        $result_itens = $stmt->get_result();

        $total_pedido = 0;
?>

<div class="pedido-box">
  <div class="pedido-cabecalho">
    <strong>Pedido #<?= $pedido['pedido_id'] ?></strong> - <?= date('d/m/Y H:i', strtotime($pedido['data'])) ?> <br>
    <span class="status">Status: <?= htmlspecialchars($pedido['status']) ?></span>
  </div>

  <div class="cliente-info">
    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
    <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido['telefone'] ?? 'Não informado') ?></p>
    <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['endereco'] ?? 'Não informado') ?></p>
  </div>

  <table class="tabela-itens">
    <thead>
      <tr>
        <th>Produto</th>
        <th>Qtd</th>
        <th>Preço Unitário</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($item = $result_itens->fetch_assoc()):
        $subtotal = $item['quantidade'] * $item['preco_unit'];
        $total_pedido += $subtotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($item['produto_nome']) ?></td>
          <td><?= $item['quantidade'] ?></td>
          <td><?= formatar_preco($item['preco_unit']) ?></td>
          <td><?= formatar_preco($subtotal) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="pedido-total">
    <strong>Total do Pedido:</strong> <?= formatar_preco($total_pedido) ?>
  </div>
</div>

<?php
        $stmt->close();
    }
}
?>

</body>
</html>
