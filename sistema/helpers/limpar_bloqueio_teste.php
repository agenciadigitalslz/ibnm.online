<?php
require_once("../conexao.php");
require_once("RateLimiter.php");

$rateLimiter = new RateLimiter($pdo);
$rateLimiter->limparBloqueio();

echo "✅ Bloqueio do seu IP foi removido com sucesso!";
?>
