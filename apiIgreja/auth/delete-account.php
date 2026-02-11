<?php
/**
 * Endpoint LGPD - Exclusão/Anonimização de Conta
 * 
 * POST /apiIgreja/auth/delete-account.php
 * Header: Authorization: Bearer {access_token}
 * Body: { "senha": "...", "modo": "anonimizar" | "excluir" }
 * 
 * Sprint 4 - Task 4.5 (LGPD Art. 18 - Direito ao Esquecimento)
 * 
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../../sistema/helpers/JWTManager.php');
require_once(__DIR__ . '/../../sistema/helpers/SecurityLogger.php');

try {
    $jwtManager = new JWTManager($pdo);
    $logger = new SecurityLogger($pdo);

    // Validar Access Token
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Access token obrigatório', 'code' => 'MISSING_TOKEN']);
        exit();
    }

    $userData = $jwtManager->validarAccessToken($matches[1]);
    if (!$userData) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido ou expirado', 'code' => 'INVALID_TOKEN']);
        exit();
    }

    $userId = $userData->id ?? null;
    if (!$userId) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados do usuário inválidos']);
        exit();
    }

    // Ler body
    $input = json_decode(file_get_contents('php://input'), true);
    $senha = $input['senha'] ?? '';
    $modo = $input['modo'] ?? 'anonimizar'; // anonimizar (padrão) ou excluir

    if (empty($senha)) {
        http_response_code(400);
        echo json_encode(['error' => 'Confirmação de senha obrigatória']);
        exit();
    }

    // Buscar usuário
    $stmt = $pdo->prepare("SELECT id, nome, email, nivel, senha_crip, igreja FROM usuarios WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuário não encontrado']);
        exit();
    }

    // Não permitir exclusão de Bispo (Super Admin)
    if ($user['nivel'] === 'Bispo') {
        http_response_code(403);
        echo json_encode(['error' => 'Conta de Super Administrador não pode ser excluída por esta via', 'code' => 'ADMIN_PROTECTED']);
        $logger->logAcessoNegado($userId, '/auth/delete-account', 'Tentativa de excluir conta Bispo');
        exit();
    }

    // Verificar senha
    if (!password_verify($senha, $user['senha_crip'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Senha incorreta', 'code' => 'WRONG_PASSWORD']);
        $logger->logAcessoNegado($userId, '/auth/delete-account', 'Senha incorreta na exclusão');
        exit();
    }

    // Registrar solicitação LGPD
    $logger->logLgpdDeleteRequest($userId);

    $pdo->beginTransaction();

    try {
        // 1. Revogar todos os refresh tokens
        $jwtManager->revogarTodosTokens($userId);

        if ($modo === 'excluir') {
            // Exclusão total (CASCADE nas FKs ou manual)
            // Deletar de tabelas dependentes primeiro
            $tabelasDependentes = [
                'refresh_tokens' => 'user_id',
                'failed_logins'  => 'ip', // não tem FK, mas limpar
            ];
            
            foreach ($tabelasDependentes as $tabela => $coluna) {
                if ($coluna === 'user_id') {
                    $pdo->prepare("DELETE FROM {$tabela} WHERE user_id = ?")->execute([$userId]);
                }
            }

            $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$userId]);

            $logger->logLgpdAnonymized($userId);

            $pdo->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Conta excluída permanentemente conforme LGPD Art. 18',
                'modo' => 'excluir'
            ]);

        } else {
            // Anonimização (padrão - mantém integridade referencial)
            $stmt = $pdo->prepare("
                UPDATE usuarios SET
                    nome = 'Usuário Excluído',
                    email = NULL,
                    cpf = NULL,
                    cpf_hash = NULL,
                    cpf_encrypted = NULL,
                    senha_crip = '',
                    senha = '',
                    telefone = NULL,
                    foto = NULL,
                    ativo = 'Não'
                WHERE id = ?
            ");
            $stmt->execute([$userId]);

            $logger->logLgpdAnonymized($userId);

            $pdo->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Dados anonimizados conforme LGPD Art. 18 (Direito ao Esquecimento)',
                'modo' => 'anonimizar'
            ]);
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno', 'message' => $e->getMessage()]);
    error_log("LGPD delete-account error: " . $e->getMessage());
}
?>
