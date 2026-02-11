<?php
require_once("../conexao.php");
require_once("RateLimiter.php");

header('Content-Type: application/json');

$stats = RateLimiter::getEstatisticas($pdo);

echo json_encode($stats, JSON_PRETTY_PRINT);
?>
