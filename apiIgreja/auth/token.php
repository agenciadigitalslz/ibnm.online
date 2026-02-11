<?php
/**
 * Endpoint de Renovação de Tokens
 * 
 * POST /apiIgreja/auth/token.php
 * Body: { "refresh_token": "uuid-v4" }
 * 
 * Versão: 1.0 (Sprint 3)
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Ajustar em produção para domínios específicos
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Aceitar apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Método não permitido',
        'allowed_methods' => ['POST', 'OPTIONS']
    ]);
    exit();
}

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../../sistema/helpers/JWTManager.php');
require_once(__DIR__ . '/../../sistema/helpers/SecurityLogger.php');

try {
    // Ler JSON do corpo da requisição
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'error' => 'JSON inválido',
            'message' => json_last_error_msg()
        ]);
        exit();
    }
    
    if (!isset($input['refresh_token']) || empty($input['refresh_token'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Refresh token obrigatório',
            'field' => 'refresh_token'
        ]);
        exit();
    }
    
    $refreshToken = filter_var($input['refresh_token'], FILTER_SANITIZE_STRING);
    
    // Validar refresh token
    $jwtManager = new JWTManager($pdo);
    $userData = $jwtManager->validarRefreshToken($refreshToken);
    
    if (!$userData) {
        $logger = new SecurityLogger($pdo);
        $logger->log('TOKEN_INVALID', 'WARNING', 'JWT', ['motivo' => 'refresh_token_invalido']);
        
        http_response_code(401);
        echo json_encode([
            'error' => 'Refresh token inválido, expirado ou revogado',
            'code' => 'INVALID_REFRESH_TOKEN'
        ]);
        exit();
    }
    
    // Gerar novo access token
    $newAccessToken = $jwtManager->gerarAccessToken([
        'id' => $userData['user_id'],
        'nome' => $userData['nome'],
        'nivel' => $userData['nivel'],
        'id_igreja' => $userData['id_igreja']
    ]);
    
    // Rotacionar refresh token (gerar novo e revogar antigo) se configurado
    $config = require __DIR__ . '/../../config/jwt.php';
    $newRefreshToken = $refreshToken; // Manter o mesmo por padrão
    
    if ($config['rotate_refresh_tokens']) {
        $newRefreshToken = $jwtManager->gerarRefreshToken(
            $userData['user_id'],
            JWTManager::getClientIP(),
            $_SERVER['HTTP_USER_AGENT'] ?? null
        );
        
        $jwtManager->revogarRefreshToken($refreshToken); // Revoga o antigo
    }
    
    // Log de renovação (Sprint 4)
    $logger = new SecurityLogger($pdo);
    $logger->logTokenRenovado($userData['user_id']);
    
    // Resposta de sucesso
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'access_token' => $newAccessToken,
        'refresh_token' => $newRefreshToken,
        'token_type' => 'Bearer',
        'expires_in' => $config['access_token_lifetime'], // 900 segundos (15 minutos)
        'user' => [
            'id' => $userData['user_id'],
            'nome' => $userData['nome'],
            'nivel' => $userData['nivel'],
            'id_igreja' => $userData['id_igreja']
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro no banco de dados',
        'message' => $e->getMessage()
    ]);
    error_log("Erro no endpoint de renovação de token: " . $e->getMessage());
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao renovar token',
        'message' => $e->getMessage()
    ]);
    error_log("Erro ao renovar token: " . $e->getMessage());
}
?>
