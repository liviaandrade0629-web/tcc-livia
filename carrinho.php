<?php
session_start();
include "conexao.php";

// Ativa exibição de erros (para depuração)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Garante que o carrinho exista
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Verifica se foi enviado um ID para adicionar
if (isset($_GET['adicionar'])) {
    $id = intval($_GET['adicionar']);

    // Busca o produto no banco
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($produto = $resultado->fetch_assoc()) {
        // Se o produto já está no carrinho, aumenta a quantidade
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['quantidade'] += 1;
        } else {
            // Adiciona o produto ao carrinho
            $_SESSION['carrinho'][$id] = [
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => 1
            ];
        }

        // Redireciona para ver o carrinho
        header("Location: ver_carrinho.php");
        exit;
    } else {
        echo "<script>alert('Produto não encontrado.'); window.location.href='loja.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID de produto não especificado.'); window.location.href='loja.php';</script>";
    exit;
}
?>
