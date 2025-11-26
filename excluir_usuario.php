<?php
session_start();
include "conexao.php";

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}

$id = intval($_GET['id']);

// Para evitar que um admin exclua a si próprio (opcional)
if ($id === $_SESSION['id']) {
    echo "<script>alert('Você não pode excluir seu próprio usuário.');window.location='usuarios.php';</script>";
    exit;
}

$sql = "DELETE FROM usuarios WHERE id = $id";
$conn->query($sql);

header("Location: painel_admin.php");
exit;
