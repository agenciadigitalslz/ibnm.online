<?php
/**
 * API de Autenticação JWT - Renovação de Tokens
 * 
 * Versão: 1.0 (Sprint 3 - Task 3.4)
 * Endpoint: POST /apiIgreja/auth/refresh.php
 * 
 * Renova access_token usando refresh_token válido.
 * 
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');

require_once('../conexao.php');
require_once('../helpers/JWT.php');

// Ler e decodificar JSON
$postjson = json_decode(file_get_contents("php://input"), true);
$refresh_token = $postjson['refresh_token'] ?? '';

// Validação básica
if (empty($refresh_token)) {
    http_response_code(400);
    echo json_encode(['error' => 'Refresh token é obrigatório']);
    exit();
}

try {
    // Validar refresh token no banco
    $query = $pdo->prepare("
        SELECT * FROM tokens 
        WHERE refresh_token = ? 
        AND expiracao > NOW() 
        AND revogado = FALSE
    ");
    $query->execute([$refresh_token]);
    $token_db = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$token_db) {
        http_response_code(401);
        echo json_encode(['error' => 'Refresh token inválido ou expirado']);
        exit();
    }
    
    // Atualizar último uso
    $update = $pdo->prepare("UPDATE tokens SET ultimo_uso = NOW() WHERE id = ?");
    $update->execute([$token_db['id']]);
    
    // Buscar dados do usuário
    $query_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = ? AND ativo = 'Sim'");
    $query_user->execute([$token_db['usuario']]);
    $usuario = $query_user->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuário inválido ou inativo']);
        exit();
    }
    
    // Buscar dados da igreja
    $query_igreja = $pdo->prepare("SELECT nome, imagem FROM igrejas WHERE id = ?");
    $query_igreja->execute([$usuario['igreja']]);
    $res_igreja = $query_igreja->fetch(PDO::FETCH_ASSOC);
    
    // Gerar novo Access Token
    $secret_key = getenv('JWT_SECRET') ?: 'chave-padrao-desenvolvimento-trocar-em-producao';
    $url_sistema = getenv('URL_SISTEMA_SITE') ?: 'http://localhost/ibnm.online/';
    
    $payload = [
        'iss' => $url_sistema,
        'aud' => $url_sistema,
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24), // 24h
        'data' => [
            'id' => intVal($usuario['id']),
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => $usuario['nivel'],
            'igreja' => intVal($usuario['igreja']),
            'nome_igreja' => $res_igreja['nome'] ?? '',
            'foto_igreja' => $res_igreja['imagem'] ?? ''
        ]
    ];
    
    $access_token = JWT::encode($payload, $secret_key, 'HS256');
    
    // Resposta
    http_response_code(200);
    echo json_encode([
        'access_token' => $access_token,
        'token_type' => 'Bearer',
        'expires_in' => 86400
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor']);
    error_log('API Refresh Error: ' . $e->getMessage());
}
?>
