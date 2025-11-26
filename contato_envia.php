<?php
// contato_envia.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';
    $pagina_anterior = $_POST['pagina_anterior'] ?? 'loja.php';

    // Aqui você pode validar e processar os dados (exemplo básico)
    if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Exemplo de envio de email (opcional)
    /*
    $to = "seuemail@dominio.com";
    $subject = "Contato pelo site: $assunto";
    $body = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem";
    $headers = "From: $email";

    mail($to, $subject, $body, $headers);
    */

    // Redireciona para a página anterior
    header('Location: ' . $pagina_anterior);
    exit;

} else {
    // Se acessar direto, redireciona para o formulário
    header('Location: contato.html');
    exit;
}
