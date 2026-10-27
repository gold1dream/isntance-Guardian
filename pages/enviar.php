<?php

$nomeUsuario = htmlspecialchars($_POST['nome'] ?? 'Anônimo');
$emailUsuario = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$mensagemUsuario = htmlspecialchars($_POST['mensagem'] ?? '');

if (!$emailUsuario) {
    die("E-mail inválido! Tente voltar e preencher corretamente.");
}


$apiKey = getenv('RESEND_API_KEY');


$dados = [
    'from' => 'onboarding@resend.dev',
 
    'to' => [$emailUsuario], 
    'subject' => "Recebemos a sua mensagem, $nomeUsuario!",
    'html' => "
        <h3>Olá, {$nomeUsuario}!</h3>
        <p>Recebemos a sua mensagem através do CyberGuardian e em breve entraremos em contato.</p>
        <hr>
        <p><strong>Sua mensagem enviada:</strong></p>
        <p>{$mensagemUsuario}</p>
    "
];


$ch = curl_init('https://api.resend.com/emails');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


if ($httpCode === 200 || $httpCode === 201) {
    echo "E-mail de confirmação enviado com sucesso!";
} else {
    echo "Erro ao enviar o e-mail: " . $response;
}
?>