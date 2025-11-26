<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel do Administrador</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }

    .topo {
      background-color: #2e7d32;
      padding: 20px;
      color: white;
      font-size: 24px;
      font-weight: bold;
    }

    .botoes {
      display: flex;
      justify-content: center;
      gap: 20px;
      background-color: #2e7d32;
      padding: 15px 0;
    }

    .botoes button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      background-color: white;
      border: 2px solid #2e7d32;
      border-radius: 10px;
      color: #2e7d32;
      font-weight: bold;
      transition: 0.3s;
    }

    .botoes button:hover {
      background-color: #e0e0e0;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-conteudo {
      background-color: #fff;
      margin: 5% auto;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 1000px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .fechar {
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .fechar:hover {
      color: red;
    }

    iframe {
      width: 100%;
      height: 600px;
      border: none;
      border-radius: 8px;
    }

    .mensagem-inicial {
      text-align: center;
      margin-top: 50px;
    }

    .mensagem-inicial p {
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="topo">Painel do Administrador</div>

  <div class="botoes">
  
<button onclick="lerTexto()">🔊 Ler</button>
<button onclick="pausarLeitura()">⏸ Pausar</button>
<button onclick="pararLeitura()">⛔ Parar</button>

    <button onclick="abrirModal('produtos.php')">Gerenciar Produtos</button>
    <button onclick="abrirModal('clientes.php')">Gerenciar Clientes</button>
    <button onclick="abrirModal('vendas.php')">Gerenciar Vendas</button>
  </div>

  <div id="modal" class="modal">
    <div class="modal-conteudo">
      <span class="fechar" onclick="fecharModal()">&times;</span>
      <iframe id="iframeModal" src=""></iframe>
    </div>
  </div>

  <div class="mensagem-inicial">
    <p>Bem-vindo! Clique em uma das opções acima para começar.</p>
  </div>

  <script>
    function abrirModal(pagina) {
      document.getElementById("iframeModal").src = pagina;
      document.getElementById("modal").style.display = "block";
    }

    function fecharModal() {
      document.getElementById("modal").style.display = "none";
      document.getElementById("iframeModal").src = "";
    }

    // Fechar modal clicando fora do conteúdo
    window.onclick = function(event) {
      const modal = document.getElementById("modal");
      if (event.target === modal) {
        fecharModal();
      }
    };
  </script>
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
