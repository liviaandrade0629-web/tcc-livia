<?php
include "conexao.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica se o produto está em algum pedido
    $stmt = $conn->prepare("SELECT COUNT(*) FROM itens_pedido WHERE produto_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    if ($total > 0) {
        // Produto está vinculado a pedidos
        header("Location: produtos.php?erro=Este produto está vinculado a pedidos e não pode ser excluído.");
        exit();
    }

    // Caso contrário, tenta excluir
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: produtos.php?msg=Produto excluído com sucesso");
        exit();
    } else {
        header("Location: produtos.php?erro=Erro ao excluir o produto: " . urlencode($stmt->error));
        exit();
    }

} else {
    header("Location: produtos.php?erro=ID do produto não fornecido.");
    exit();
}

$conn->close();
