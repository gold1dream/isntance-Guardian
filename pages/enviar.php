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
    'to' => ['neemiaslauriano8@gmail.com'], 
    'subject' => "Novo contato de: $nomeUsuario ($emailUsuario)",
    'html' => "
        <h3>Nova mensagem recebida pelo CyberGuardian</h3>
        <p><strong>Nome:</strong> {$nomeUsuario}</p>
        <p><strong>E-mail:</strong> {$emailUsuario}</p>
        <p><strong>Mensagem:</strong><br>{$mensagemUsuario}</p>
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
    echo "E-mail enviado com sucesso!";
} else {
    echo "Erro ao enviar o e-mail: " . $response;
}
?>