<?php
session_start();
include "conexao.php";

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
  if (password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['tipo'] = $usuario['tipo'];
    $_SESSION['nome'] = $usuario['nome'];

    if (strtolower(trim($usuario['tipo'])) === 'admin') {
      header("Location: painel_admin.php");
      exit;
    } else {
      header("Location: home.php");
      exit;
    }
  } else {
    echo "<script>alert('Senha incorreta!'); window.location.href='index.html';</script>";
    exit;
  }
} else {
  echo "<script>alert('Usuário não encontrado!'); window.location.href='index.html';</script>";
  exit;
}
?>
