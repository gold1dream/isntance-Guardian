<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$emailUsuario = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)){
    die("E-mail inválido! Tente voltar e preencher corretamente.");
}

$nomeUsuario = htmlspecialchars($_POST['nome'] ?? 'Anônimo');
$mensagemUsuario = htmlspecialchars($_POST['mensagem'] ?? '');

$mail = new PHPMailer(true);

try {
    // CORRIGIDO: Removido o cifrão extra ($) de isSMTP
    $mail->isSMTP();
    
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // Ativa o debug correto para vermos a resposta do Google no terminal
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;

    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom($_ENV['SMTP_USER'], 'Cyberguardian');
    $mail->addAddress($emailUsuario, $nomeUsuario);
    $mail->addReplyTo($emailUsuario, $nomeUsuario);

    $mail->isHTML(true);
    $mail->Subject     = 'Mensagem de test recebida??';
    $mail->Body        = 'Olá! é um e-mail por PHP pois é... quem diria';

    $mail->send();
    echo 'E-mail enviado com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
}
?>