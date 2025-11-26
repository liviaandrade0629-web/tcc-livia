<?php
session_start();
include "conexao.php";

// Conecta e busca usuários
$sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY nome";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Gerenciar Usuários</title>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
    max-width: 800px;
    margin: 20px auto;
  }
  th, td {
    border: 1px solid #999;
    padding: 8px 12px;
    text-align: left;
  }
  th {
    background: #3a7d44;
    color: white;
  }
  a.btn {
    padding: 5px 10px;
    background: #3a7d44;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  a.btn:hover {
    background: #2f6334;
  }
</style>
</head>
<body>

<h2 style="text-align:center;">Gerenciar Usuários</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>E-mail</th>
      <th>Tipo</th>
      <th>Ações</th>
    </tr>
  </thead>
  
  <div class="box">
  <h3>Clientes</h3>
  <a href="cadastro_usuario2.php" class="btn">Novo Cliente</a>
</div>

  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['nome']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= $row['tipo'] ?></td>
          <td>
            <a class="btn" href="editar_usuario.php?id=<?= $row['id'] ?>">Editar</a>
            <a class="btn" href="excluir_usuario.php?id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5" style="text-align:center;">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>
