<?php
/**
 * API - Estatísticas de Segurança (Dashboard)
 * 
 * GET /apiIgreja/admin/estatisticas-seguranca.php?periodo=24h
 * Header: Authorization: Bearer {access_token} (nível Bispo)
 * 
 * Sprint 4 - Task 4.8
 * 
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../../sistema/helpers/SecurityLogger.php');
require_once(__DIR__ . '/../../sistema/helpers/JWTManager.php');
require_once(__DIR__ . '/../../sistema/helpers/RateLimiter.php');

try {
    // Validar acesso (JWT ou sessão)
    @session_start();
    $autorizado = false;

    // Tentar JWT primeiro
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
        $jwtManager = new JWTManager($pdo);
        $userData = $jwtManager->validarAccessToken($matches[1]);
        if ($userData && (($userData->nivel ?? '') === 'Bispo')) {
            $autorizado = true;
        }
    }

    // Fallback sessão PHP
    if (!$autorizado && isset($_SESSION['nivel']) && $_SESSION['nivel'] === 'Bispo') {
        $autorizado = true;
    }

    if (!$autorizado) {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso restrito a Super Administradores']);
        exit();
    }

    $logger = new SecurityLogger($pdo);
    $periodo = $_GET['periodo'] ?? '24h';

    // Estatísticas gerais
    $stats = $logger->getEstatisticas($periodo);

    // Eventos por hora
    $horas = match($periodo) {
        '1h' => 1, '24h' => 24, '7d' => 168, '30d' => 720,
        default => 24
    };
    $porHora = $logger->getEventosPorHora($horas);

    // Top IPs
    $topIPs = $logger->getTopIPs(10, $periodo);

    // Eventos críticos recentes
    $criticos = $logger->getEventosCriticos(10);

    // Rate Limiter stats
    $rateLimiterStats = RateLimiter::getEstatisticas($pdo);

    // Tokens ativos
    $stmtTokens = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN revoked = 0 AND expires_at > NOW() THEN 1 ELSE 0 END) as ativos,
            SUM(CASE WHEN revoked = 1 THEN 1 ELSE 0 END) as revogados
        FROM refresh_tokens
    ");
    $tokenStats = $stmtTokens->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'periodo' => $periodo,
        'timestamp' => date('c'),
        'resumo' => $stats,
        'tokens' => $tokenStats,
        'rate_limiter' => $rateLimiterStats,
        'eventos_por_hora' => $porHora,
        'top_ips' => $topIPs,
        'eventos_criticos' => $criticos
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno', 'message' => $e->getMessage()]);
    error_log("Estatisticas seguranca error: " . $e->getMessage());
}
?>
