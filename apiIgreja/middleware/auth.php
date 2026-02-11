<?php
/**
 * Middleware de Autenticação JWT
 * 
 * Versão: 1.0 (Sprint 3 - Task 3.3)
 * 
 * Valida tokens JWT em endpoints protegidos da API.
 * 
 * @author Nexus (Security Team)
 */

require_once(__DIR__ . '/../helpers/JWT.php');

/**
 * Validar JWT e retornar dados do usuário
 * 
 * @return object Dados do usuário decodificados do token
 * @throws Exception Se token inválido/ausente
 */
function validarJWT() {
    // Obter header Authorization
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    // Verificar formato Bearer
    if (empty($authHeader) || stripos($authHeader, 'Bearer ') !== 0) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Unauthorized',
            'message' => 'Token de autenticação não fornecido'
        ]);
        exit();
    }
    
    // Extrair token
    $jwt = trim(substr($authHeader, 7)); // Remove "Bearer "
    
    // Validar token
    $secret_key = getenv('JWT_SECRET') ?: 'chave-padrao-desenvolvimento-trocar-em-producao';
    
    try {
        $decoded = JWT::decode($jwt, $secret_key);
        
        // Validar claims obrigatórios
        if (!isset($decoded->data)) {
            throw new Exception('Token não contém dados de usuário');
        }
        
        // Retornar dados do usuário
        return $decoded->data;
        
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Unauthorized',
            'message' => $e->getMessage()
        ]);
        exit();
    }
}

/**
 * Validar JWT e retornar apenas se usuário pertencer à igreja específica
 * 
 * @param int $igreja_id ID da igreja requerida
 * @return object Dados do usuário
 */
function validarJWTComIgreja($igreja_id) {
    $usuario = validarJWT();
    
    if ($usuario->igreja != $igreja_id) {
        http_response_code(403);
        echo json_encode([
            'error' => 'Forbidden',
            'message' => 'Acesso negado a recursos desta igreja'
        ]);
        exit();
    }
    
    return $usuario;
}

/**
 * Validar JWT e retornar apenas se usuário tiver nível específico
 * 
 * @param array $niveis_permitidos Níveis permitidos (ex: ['Bispo', 'Pastor'])
 * @return object Dados do usuário
 */
function validarJWTComNivel($niveis_permitidos) {
    $usuario = validarJWT();
    
    if (!in_array($usuario->nivel, $niveis_permitidos)) {
        http_response_code(403);
        echo json_encode([
            'error' => 'Forbidden',
            'message' => 'Nível de acesso insuficiente'
        ]);
        exit();
    }
    
    return $usuario;
}
?>
