<?php
/**
 * Endpoint de Logout (Revogação de Refresh Token)
 * 
 * POST /apiIgreja/auth/logout.php
 * Body: { "refresh_token": "uuid-v4" }
 * Header: Authorization: Bearer {access_token} (opcional)
 * 
 * Versão: 1.0 (Sprint 3)
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
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
    // Ler JSON do corpo
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'error' => 'JSON inválido',
            'message' => json_last_error_msg()
        ]);
        exit();
    }
    
    $jwtManager = new JWTManager($pdo);
    
    // Opção 1: Revogar refresh token específico
    if (isset($input['refresh_token']) && !empty($input['refresh_token'])) {
        $refreshToken = filter_var($input['refresh_token'], FILTER_SANITIZE_STRING);
        
        $revogado = $jwtManager->revogarRefreshToken($refreshToken);
        
        if ($revogado) {
            $logger = new SecurityLogger($pdo);
            $logger->logLogout(0, 'single');
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Logout realizado com sucesso'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'error' => 'Token não encontrado ou já revogado',
                'code' => 'TOKEN_NOT_FOUND'
            ]);
        }
        exit();
    }
    
    // Opção 2: Revogar todos os tokens do usuário (logout total)
    if (isset($input['user_id']) && !empty($input['user_id'])) {
        // Validar access token no header
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        
        if (empty($authHeader)) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Access token obrigatório para logout total',
                'code' => 'MISSING_ACCESS_TOKEN'
            ]);
            exit();
        }
        
        // Extrair token do header "Bearer {token}"
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $accessToken = $matches[1];
            $userData = $jwtManager->validarAccessToken($accessToken);
            
            if ($userData && $userData->id == $input['user_id']) {
                $revogado = $jwtManager->revogarTodosTokens($input['user_id']);
                
                $logger = new SecurityLogger($pdo);
                $logger->logLogout($input['user_id'], 'all');
                
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Todos os tokens revogados com sucesso',
                    'logout_type' => 'total'
                ]);
                exit();
            }
        }
        
        http_response_code(401);
        echo json_encode([
            'error' => 'Access token inválido ou não autorizado',
            'code' => 'INVALID_ACCESS_TOKEN'
        ]);
        exit();
    }
    
    // Nenhuma opção válida fornecida
    http_response_code(400);
    echo json_encode([
        'error' => 'Forneça refresh_token ou user_id',
        'required_fields' => ['refresh_token', 'user_id (com Authorization header)']
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro no banco de dados',
        'message' => $e->getMessage()
    ]);
    error_log("Erro no endpoint de logout: " . $e->getMessage());
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao fazer logout',
        'message' => $e->getMessage()
    ]);
    error_log("Erro ao fazer logout: " . $e->getMessage());
}
?>
