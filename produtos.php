<?php
session_start();
include "conexao.php";

// Excluir produto via GET
if (isset($_GET['excluir_id'])) {
    $id = intval($_GET['excluir_id']);
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Buscar produtos
$result = $conn->query("SELECT * FROM produtos ORDER BY categoria");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Lista de Produtos</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f8f8f8;
        margin: 0;
        padding: 0;
    }

    .modal {
        background: white;
        width: 90%;
        max-width: 900px;
        margin: 40px auto;
        border-radius: 16px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
        overflow: hidden;
        padding-bottom: 30px;
    }

    .modal-header {
        background-color: #2d8235;
        color: white;
        padding: 20px;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        position: relative;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 26px;
    }

    h3 {
        text-align: center;
        margin: 25px 0 10px;
        font-size: 24px;
    }

    .novo-produto {
        text-align: center;
        margin: 10px 0 20px;
    }

    .novo-produto a {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .novo-produto a:hover {
        background-color: #388E3C;
    }

    table {
        width: 95%;
        margin: 0 auto;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        background-color: #4CAF50;
        color: white;
        padding: 12px;
        text-align: center;
        border-radius: 8px 8px 0 0;
    }

    td {
        padding: 10px;
        text-align: center;
    }

    .actions a {
        margin: 0 5px;
        padding: 6px 12px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 6px;
    }

    .actions a:hover {
        background-color: #388E3C;
    }

    .no-produtos {
        text-align: center;
        padding: 20px;
        font-size: 18px;
    }
</style>
</head>
<body>

<div class="modal">
    <div class="modal-header">
        <h2>Etec Orlando Quagliato</h2>
    </div>

    <h3>Lista de Produtos</h3>

    <div class="novo-produto">
        <a href="novo_produto2.php">+ Novo Produto</a>
    </div>

    <table>
        <tr>
            <th>Categoria</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['categoria']) ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                <td><?= $row['estoque'] ?></td>
                <td class="actions">
                    <a href="editar_produto.php?id=<?= $row['id'] ?>">Editar</a>
                    <a href="excluir_produto.php?id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="no-produtos">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
