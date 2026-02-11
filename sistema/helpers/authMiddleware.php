<?php
/**
 * Middleware de Autenticação JWT
 * 
 * Uso:
 * require_once('helpers/authMiddleware.php');
 * $user = verificarJWT(); // Retorna dados do usuário ou redireciona
 * 
 * Versão: 1.0 (Sprint 3)
 * @author Nexus (Security Team)
 */

require_once(__DIR__ . '/JWTManager.php');

/**
 * Verifica autenticação via JWT ou sessão PHP (backward compatibility)
 * 
 * @return object|null Dados do usuário autenticado
 */
function verificarJWT(): ?object {
    global $pdo;
    
    // Verificar header Authorization (JWT)
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    if (!empty($authHeader)) {
        // Autenticação via JWT
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            
            try {
                $jwtManager = new JWTManager($pdo);
                $userData = $jwtManager->validarAccessToken($token);
                
                if ($userData) {
                    // Token válido - retornar dados do usuário
                    return (object) [
                        'id' => $userData->id ?? $userData->data->id ?? null,
                        'nome' => $userData->nome ?? $userData->data->nome ?? 'Usuário',
                        'nivel' => $userData->nivel ?? $userData->data->nivel ?? '',
                        'id_igreja' => $userData->id_igreja ?? $userData->data->id_igreja ?? 0
                    ];
                } else {
                    // Token expirado ou inválido
                    respondJSONError(401, 'Token expirado ou inválido', 'TOKEN_EXPIRED');
                    return null;
                }
                
            } catch (Exception $e) {
                error_log("Erro ao validar JWT: " . $e->getMessage());
                respondJSONError(401, 'Erro ao validar token', 'TOKEN_ERROR');
                return null;
            }
        } else {
            // Formato de Authorization header inválido
            respondJSONError(401, 'Formato de token inválido. Use: Bearer {token}', 'INVALID_TOKEN_FORMAT');
            return null;
        }
    }
    
    // Fallback: verificar sessão PHP (compatibilidade com autenticação antiga)
    @session_start();
    
    if (isset($_SESSION['id']) && isset($_SESSION['nivel'])) {
        // Autenticação antiga (sessão PHP) ainda ativa
        return (object) [
            'id' => $_SESSION['id'],
            'nome' => $_SESSION['nome'] ?? 'Usuário',
            'nivel' => $_SESSION['nivel'],
            'id_igreja' => $_SESSION['id_igreja'] ?? 0
        ];
    }
    
    // Sem autenticação válida (nem JWT nem sessão)
    redirectToLogin('Sessão expirada. Faça login novamente.');
    return null;
}

/**
 * Verifica se usuário tem nível de acesso específico
 * 
 * @param object $user
 * @param string|array $niveis Nível(is) permitido(s): 'Bispo', 'Pastor', etc
 * @return bool
 */
function verificarNivel(object $user, $niveis): bool {
    if (is_string($niveis)) {
        $niveis = [$niveis];
    }
    
    return in_array($user->nivel, $niveis);
}

/**
 * Middleware para áreas restritas a Bispo (admin)
 * 
 * @return object Dados do usuário (se autorizado)
 */
function verificarBispo(): object {
    $user = verificarJWT();
    
    if ($user && !verificarNivel($user, 'Bispo')) {
        redirectToLogin('Acesso negado. Apenas administradores.');
        exit();
    }
    
    return $user;
}

/**
 * Middleware para áreas restritas a Pastor/Igreja
 * 
 * @return object Dados do usuário (se autorizado)
 */
function verificarIgreja(): object {
    $user = verificarJWT();
    
    if ($user && $user->nivel === 'Bispo') {
        // Bispo acessando painel-igreja via ?igreja=X → permitir (gerenciamento)
        // Bispo acessando sem igreja definida → voltar pro admin
        @session_start();
        $igreja_param = $_GET['igreja'] ?? ($_SESSION['id_igreja'] ?? 0);
        if (empty($igreja_param) || $igreja_param == 0) {
            echo '<script>window.location="../painel-admin"</script>';
            exit();
        }
        // Bispo com igreja selecionada — permitir acesso
    }
    
    return $user;
}

/**
 * Responde com JSON e código de erro HTTP
 * 
 * @param int $code Código HTTP
 * @param string $message Mensagem de erro
 * @param string $errorCode Código de erro customizado
 * @return void
 */
function respondJSONError(int $code, string $message, string $errorCode = ''): void {
    // Se for requisição AJAX/API, responder com JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $message,
            'code' => $errorCode,
            'redirect' => '../index.php'
        ]);
        exit();
    }
    
    // Requisição normal - redirecionar
    redirectToLogin($message);
}

/**
 * Redireciona para tela de login
 * 
 * @param string $mensagem Mensagem a ser exibida
 * @return void
 */
function redirectToLogin(string $mensagem = 'Sessão expirada'): void {
    @session_start();
    $_SESSION['msg'] = $mensagem;
    
    // Se for requisição AJAX/API
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $mensagem,
            'redirect' => '../index.php'
        ]);
        exit();
    }
    
    // Redirecionamento padrão (JavaScript)
    echo '<script>window.location="../index.php"</script>';
    exit();
}

/**
 * Extrai Access Token do header Authorization
 * 
 * @return string|null
 */
function extrairAccessToken(): ?string {
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    if (empty($authHeader)) {
        return null;
    }
    
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return $matches[1];
    }
    
    return null;
}
?>
