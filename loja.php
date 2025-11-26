<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

$mysqli = new mysqli('localhost', 'root', '', 'cooperativa');

if ($mysqli->connect_errno) {
    echo "Falha na conexão com banco de dados: " . $mysqli->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $produto_id = (int) $_POST['produto_id'];
    $quantidade = (int) $_POST['quantidade'];

    if ($quantidade > 0) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produto_id] = $quantidade;
        }

        header("Location: loja.php");
        exit();
    } else {
        $erro = "Quantidade inválida!";
    }
}

$result = $mysqli->query("SELECT * FROM produtos");

$produtos = [];

while ($row = $result->fetch_assoc()) {
    $produtos[$row['categoria']][] = $row;
}

// Array para exibir os nomes corretos das categorias
$categorias_exibicao = [
    'queijo' => 'Queijo',
    'hortalica' => 'Hortaliça',
    'carne' => 'Carne',
    'outros' => 'Outros'
];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Loja</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #e6f2e6;
        margin: 0;
        padding: 20px;
        color: #1a3d1a;
    }

    .topo-etec {
        background-color: #2e8b57;
        color: white;
        font-weight: bold;
        padding: 15px 25px;
        font-size: 1.2rem;
        text-align: left;
        width: 100%;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

	.topo-botoes {
    position: absolute;
    top: 15px;
    right: 25px;
    display: flex;
    gap: 10px;
}

.botao-topo {
    background-color: #3cb371;
    color: white;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: background-color 0.3s;
}

.botao-topo:hover {
    background-color: #2e8b57;
}


    nav {
        margin-bottom: 25px;
    }

    nav a {
        color: #1a3d1a;
        font-weight: bold;
        text-decoration: none;
        border: 2px solid #3cb371;
        padding: 8px 15px;
        border-radius: 6px;
        transition: background-color 0.3s, color 0.3s;
    }

    nav a:hover {
        background-color: #3cb371;
        color: white;
    }

    h1 {
        margin-bottom: 25px;
        text-align: center;
    }

    .erro {
        color: #a94442;
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .categoria {
        margin-bottom: 40px;
    }

    .categoria h2 {
        color: #2e8b57;
        margin-bottom: 15px;
        border-bottom: 2px solid #3cb371;
        padding-bottom: 5px;
    }

    .linha-produtos {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .produto {
        background: white;
        border: 1px solid #3cb371;
        border-radius: 10px;
        padding: 15px;
        width: 250px;
        box-shadow: 1px 2px 8px rgba(60,179,113,0.3);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .produto img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .produto h3 {
        margin-top: 0;
        color: #2e8b57;
        font-size: 1.1rem;
    }

    .produto p {
        font-size: 0.95rem;
        color: #3c763d;
        margin-bottom: 8px;
    }

    form {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    label {
        font-weight: bold;
        color: #2f4f2f;
    }

    input[type=number] {
        width: 100%;
        padding: 6px;
        border: 1px solid #3cb371;
        border-radius: 6px;
        font-size: 1rem;
        color: #1a3d1a;
    }

    button {
        background-color: #3cb371;
        color: white;
        border: none;
        padding: 10px;
        font-size: 1rem;
        border-radius: 7px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2e8b57;
    }

    @media (max-width: 768px) {
        .linha-produtos {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
</head>
<body>
<div class="topo-etec">Etec Orlando Quagliato

		<a href="contato.html" class="botao-topo">📞 Contato</a>
        <a href="editar_usuario2.php" class="botao-topo">⚙️ Alterar Cadastro</a>
		<a href="index.php" class="botao-topo">🏠 Home</a>

</div>

<button onclick="lerTexto()">🔊 Ler</button>
<button onclick="pausarLeitura()">⏸ Pausar</button>
<button onclick="pararLeitura()">⛔ Parar</button>
<br>
<br>
<nav>
    <a href="ver_carrinho.php">Ver Carrinho 🛒</a>
</nav>

<h1>Loja de Produtos</h1>

<?php if (!empty($erro)): ?>
    <div class="erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php foreach ($categorias_exibicao as $categoria_codigo => $categoria_nome): ?>
    <?php if (!empty($produtos[$categoria_codigo])): ?>
    <div class="categoria">
        <h2><?= htmlspecialchars($categoria_nome) ?></h2>
        <div class="linha-produtos">
            <?php foreach ($produtos[$categoria_codigo] as $produto): ?>
            <div class="produto">
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" />
                <?php else: ?>
                    <p style="color: #999;">Imagem não disponível.</p>
                <?php endif; ?>

                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <p><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
                <p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

                <form method="post" action="loja.php">
                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>" />
                    <label for="qtd_<?= $produto['id'] ?>">Quantidade:</label>
                    <input type="number" name="quantidade" id="qtd_<?= $produto['id'] ?>" value="1" min="1" max="99" required />
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>
<script>
let utterance; // Variável global para controlar a fala

function lerTexto() {
    const texto = document.body.innerText;
    utterance = new SpeechSynthesisUtterance(texto);

    utterance.lang = "pt-BR";
    utterance.rate = 1;
    utterance.pitch = 1;

    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
}

function pausarLeitura() {
    if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
        window.speechSynthesis.pause();
    } else if (window.speechSynthesis.paused) {
        window.speechSynthesis.resume();
    }
}

function pararLeitura() {
    window.speechSynthesis.cancel();
}
</script>

</body>
</html>
