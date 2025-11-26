<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sobre a Cooperativa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4fdf4;
            color: #1a3d1a;
            line-height: 1.6;
        }

        header {
            background: #2e7d32;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        nav {
            background: #e9f5e9;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #2e7d32;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #1b5e20;
        }

        main {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #388e3c;
            margin-top: 25px;
        }

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        footer {
            text-align: center;
            padding: 15px;
            background: #2e7d32;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    Cooperativa Etec Orlando Quagliato
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="loja.php">Loja</a>
    <a href="contato.php">Contato</a>
    <a href="login.html">Login</a>
</nav>

<button onclick="lerTexto()">🔊 Ler</button>
<button onclick="pausarLeitura()">⏸ Pausar</button>
<button onclick="pararLeitura()">⛔ Parar</button>


<main>

    <h1>Bem-vindo à nossa Cooperativa!</h1>
    <p>
        Este site foi desenvolvido para apoiar a <strong>Cooperativa da Etec Orlando Quagliato</strong>,
        que é vinculada à Escola Agrícola. O principal objetivo da cooperativa é integrar os conhecimentos 
        adquiridos pelos alunos em sala de aula e nas práticas agrícolas com a comunidade local, oferecendo 
        produtos de qualidade, cultivados e produzidos de forma sustentável.
    </p>

    <h2>Por que a cooperativa existe?</h2>
    <p>
        A cooperativa nasceu da necessidade de criar um elo entre a produção agrícola dos estudantes e a sociedade.
        Dessa forma, conseguimos incentivar o empreendedorismo, valorizar o trabalho agrícola e proporcionar 
        alimentos frescos e de confiança diretamente ao consumidor.
    </p>

    <h2>Como funciona o site?</h2>
    <p>
        O site funciona como uma plataforma de compras online. Nele, você pode:
    </p>
    <ul>
        <li>Navegar pela <strong>Loja</strong> e conhecer os produtos disponíveis, como queijos, hortaliças, carnes e outros.</li>
        <li>Adicionar os itens desejados ao carrinho de compras.</li>
        <li>Finalizar o pedido de forma prática, com registro do cliente e acompanhamento dos pedidos.</li>
        <li>Administradores podem gerenciar os produtos, clientes e vendas por meio do painel de administração.</li>
    </ul>

    <h2>Nosso compromisso</h2>
    <p>
        Prezamos pela <strong>qualidade</strong> dos alimentos, <strong>responsabilidade social</strong> e pela 
        <strong>formação prática dos alunos</strong>. Cada compra realizada aqui é também um incentivo 
        ao aprendizado e ao desenvolvimento sustentável da nossa região.
    </p>
</p>
</main>
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

<footer>
    &copy; <?= date('Y') ?> Cooperativa Etec Orlando Quagliato - Todos os direitos reservados
</footer>

</body>
</html>
