# Sprint 3 - Autenticação Enterprise
## Planejamento Detalhado

**Projeto:** IBNM.online - Sistema de Gestão Eclesiástica  
**Sprint:** 3 de 4 (Defense in Depth)  
**Versão Alvo:** v7.20  
**Previsão:** 10/02/2026 - 12/02/2026  
**Duração Estimada:** 5-7h (baseado em performance Sprints 1-2)  
**Responsável:** Nexus (Security Team)

---

## 🎯 Objetivo

Implementar **autenticação baseada em tokens JWT** com refresh token para:
- ✅ Sessões stateless (escalabilidade horizontal)
- ✅ Expiração automática de tokens (15 minutos)
- ✅ Renovação segura via refresh token (30 dias)
- ✅ Suporte a API mobile/SPA futura
- ✅ Convivência com autenticação atual (migração gradual)

---

## 📐 Arquitetura

### Fluxo de Autenticação JWT

```
┌──────────┐         ┌──────────────┐         ┌──────────┐
│  Cliente │         │    Backend   │         │  Banco   │
└────┬─────┘         └──────┬───────┘         └────┬─────┘
     │                      │                      │
     │ POST /login          │                      │
     │ {email, senha}       │                      │
     ├─────────────────────>│                      │
     │                      │ Valida credenciais   │
     │                      ├─────────────────────>│
     │                      │<─────────────────────┤
     │                      │ Gera Access Token    │
     │                      │ (15 min, JWT)        │
     │                      │                      │
     │                      │ Gera Refresh Token   │
     │                      │ (30 dias, UUID)      │
     │                      ├─────────────────────>│
     │                      │ Salva refresh_tokens │
     │<─────────────────────┤                      │
     │ {access, refresh}    │                      │
     │                      │                      │
     │ GET /painel          │                      │
     │ Header: Bearer {JWT} │                      │
     ├─────────────────────>│                      │
     │                      │ Valida JWT           │
     │                      │ (assinatura + exp)   │
     │<─────────────────────┤                      │
     │ 200 OK               │                      │
     │                      │                      │
     │ (após 15min)         │                      │
     │ POST /token/refresh  │                      │
     │ {refresh_token}      │                      │
     ├─────────────────────>│                      │
     │                      │ Valida refresh       │
     │                      ├─────────────────────>│
     │                      │<─────────────────────┤
     │                      │ Gera novo Access     │
     │<─────────────────────┤                      │
     │ {access, refresh}    │                      │
     └──────────────────────┴──────────────────────┘
```

---

## 📋 Tasks

### Task 3.1: Instalação e Configuração da Biblioteca JWT
**Duração estimada:** 30min

#### Subtasks
1. ✅ Instalar Firebase PHP-JWT via Composer
   ```bash
   composer require firebase/php-jwt
   ```

2. ✅ Criar arquivo de configuração JWT
   ```php
   // config/jwt.php
   return [
       'secret_key' => getenv('JWT_SECRET_KEY') ?: 'ALTERAR_EM_PRODUCAO',
       'algorithm' => 'HS256',
       'access_token_lifetime' => 15 * 60, // 15 minutos
       'refresh_token_lifetime' => 30 * 24 * 60 * 60, // 30 dias
       'issuer' => 'ibnm.online',
   ];
   ```

3. ✅ Atualizar `.env.example`
   ```
   JWT_SECRET_KEY=generate_secure_key_here_min_256_bits
   ```

4. ✅ Gerar chave secreta segura (256+ bits)
   ```php
   // scripts/generate_jwt_secret.php
   echo base64_encode(random_bytes(64));
   ```

**Entregável:**
- `composer.json` atualizado
- `config/jwt.php` criado
- `.env.example` atualizado
- Chave JWT adicionada ao `.env`

---

### Task 3.2: Criação da Tabela de Refresh Tokens
**Duração estimada:** 45min

#### Subtasks
1. ✅ Criar script SQL
   ```sql
   -- SQL/sprint3_refresh_tokens.sql
   CREATE TABLE IF NOT EXISTS `refresh_tokens` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `user_id` int(11) NOT NULL,
     `token` varchar(255) NOT NULL UNIQUE,
     `expires_at` datetime NOT NULL,
     `revoked` tinyint(1) DEFAULT 0,
     `ip_address` varchar(45) DEFAULT NULL,
     `user_agent` text DEFAULT NULL,
     `created_at` datetime DEFAULT current_timestamp(),
     PRIMARY KEY (`id`),
     KEY `idx_user_id` (`user_id`),
     KEY `idx_token` (`token`),
     KEY `idx_expires_at` (`expires_at`),
     FOREIGN KEY (`user_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   
   -- Índice composto para queries de validação
   CREATE INDEX idx_token_user ON refresh_tokens(token, user_id, expires_at, revoked);
   
   -- Evento de limpeza (LGPD - 90 dias após expiração)
   CREATE EVENT IF NOT EXISTS limpar_refresh_tokens_expirados
   ON SCHEDULE EVERY 1 DAY
   DO DELETE FROM refresh_tokens 
   WHERE expires_at < NOW() - INTERVAL 90 DAY;
   ```

2. ✅ Executar migration
3. ✅ Testar constraints e índices

**Entregável:**
- `SQL/sprint3_refresh_tokens.sql`
- Tabela `refresh_tokens` criada no banco

---

### Task 3.3: Classe JWTManager
**Duração estimada:** 1h 30min

#### Subtasks
1. ✅ Criar `sistema/helpers/JWTManager.php`

```php
<?php
/**
 * Gerenciador de JWT (Access + Refresh Tokens)
 * 
 * Versão: 1.0 (Sprint 3)
 * 
 * @author Nexus (Security Team)
 */

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTManager {
    private $pdo;
    private $config;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->config = require __DIR__ . '/../../config/jwt.php';
    }
    
    /**
     * Gera Access Token (JWT)
     * 
     * @param array $userData ['id', 'nome', 'nivel', 'id_igreja']
     * @return string JWT token
     */
    public function gerarAccessToken(array $userData): string {
        $issuedAt = time();
        $expire = $issuedAt + $this->config['access_token_lifetime'];
        
        $payload = [
            'iss' => $this->config['issuer'],
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => [
                'id' => $userData['id'],
                'nome' => $userData['nome'],
                'nivel' => $userData['nivel'],
                'id_igreja' => $userData['id_igreja']
            ]
        ];
        
        return JWT::encode($payload, $this->config['secret_key'], $this->config['algorithm']);
    }
    
    /**
     * Valida Access Token
     * 
     * @param string $token
     * @return object|false Dados decodificados ou false
     */
    public function validarAccessToken(string $token) {
        try {
            $decoded = JWT::decode(
                $token,
                new Key($this->config['secret_key'], $this->config['algorithm'])
            );
            return $decoded->data;
        } catch (Exception $e) {
            error_log("JWT validation failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gera Refresh Token (UUID v4)
     * 
     * @param int $userId
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @return string Refresh token (UUID)
     */
    public function gerarRefreshToken(int $userId, ?string $ipAddress = null, ?string $userAgent = null): string {
        // Gerar UUID v4
        $token = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        
        $expiresAt = date('Y-m-d H:i:s', time() + $this->config['refresh_token_lifetime']);
        
        $query = $this->pdo->prepare("
            INSERT INTO refresh_tokens (user_id, token, expires_at, ip_address, user_agent)
            VALUES (:user_id, :token, :expires_at, :ip_address, :user_agent)
        ");
        
        $query->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt,
            ':ip_address' => $ipAddress,
            ':user_agent' => $userAgent
        ]);
        
        return $token;
    }
    
    /**
     * Valida Refresh Token
     * 
     * @param string $token
     * @return array|false ['user_id', 'nivel', ...] ou false
     */
    public function validarRefreshToken(string $token) {
        $query = $this->pdo->prepare("
            SELECT rt.user_id, rt.expires_at, rt.revoked,
                   u.nome, u.nivel, u.igreja as id_igreja, u.ativo
            FROM refresh_tokens rt
            INNER JOIN usuarios u ON rt.user_id = u.id
            WHERE rt.token = :token
        ");
        
        $query->bindValue(':token', $token);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return false; // Token não encontrado
        }
        
        // Verificar expiração
        if (strtotime($result['expires_at']) < time()) {
            return false; // Token expirado
        }
        
        // Verificar revogação
        if ($result['revoked'] == 1) {
            return false; // Token revogado
        }
        
        // Verificar usuário ativo
        if ($result['ativo'] != 'Sim') {
            return false; // Usuário desativado
        }
        
        return $result;
    }
    
    /**
     * Revoga Refresh Token (logout)
     * 
     * @param string $token
     * @return bool
     */
    public function revogarRefreshToken(string $token): bool {
        $query = $this->pdo->prepare("
            UPDATE refresh_tokens
            SET revoked = 1
            WHERE token = :token
        ");
        
        return $query->execute([':token' => $token]);
    }
    
    /**
     * Revoga todos os tokens de um usuário
     * 
     * @param int $userId
     * @return bool
     */
    public function revogarTodosTokens(int $userId): bool {
        $query = $this->pdo->prepare("
            UPDATE refresh_tokens
            SET revoked = 1
            WHERE user_id = :user_id AND revoked = 0
        ");
        
        return $query->execute([':user_id' => $userId]);
    }
    
    /**
     * Limpa tokens expirados de um usuário (manual cleanup)
     * 
     * @param int $userId
     * @return int Quantidade removida
     */
    public function limparTokensExpirados(int $userId): int {
        $query = $this->pdo->prepare("
            DELETE FROM refresh_tokens
            WHERE user_id = :user_id AND expires_at < NOW()
        ");
        
        $query->execute([':user_id' => $userId]);
        return $query->rowCount();
    }
    
    /**
     * Obtém IP do cliente (considera proxies)
     * 
     * @return string
     */
    public static function getClientIP(): string {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($keys as $key) {
            if (isset($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
                return $_SERVER[$key];
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
?>
```

**Entregável:**
- `sistema/helpers/JWTManager.php` (completo e testado)

---

### Task 3.4: Endpoint de Renovação de Token
**Duração estimada:** 1h

#### Subtasks
1. ✅ Criar `apiIgreja/auth/token.php`

```php
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
header('Access-Control-Allow-Origin: *'); // Ajustar em produção
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
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../../sistema/helpers/JWTManager.php');

try {
    // Ler JSON do corpo
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['refresh_token']) || empty($input['refresh_token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Refresh token obrigatório']);
        exit();
    }
    
    $refreshToken = filter_var($input['refresh_token'], FILTER_SANITIZE_STRING);
    
    // Validar refresh token
    $jwtManager = new JWTManager($pdo);
    $userData = $jwtManager->validarRefreshToken($refreshToken);
    
    if (!$userData) {
        http_response_code(401);
        echo json_encode(['error' => 'Refresh token inválido ou expirado']);
        exit();
    }
    
    // Gerar novo access token
    $newAccessToken = $jwtManager->gerarAccessToken([
        'id' => $userData['user_id'],
        'nome' => $userData['nome'],
        'nivel' => $userData['nivel'],
        'id_igreja' => $userData['id_igreja']
    ]);
    
    // Opcional: Rotacionar refresh token (gerar novo e revogar antigo)
    $newRefreshToken = $jwtManager->gerarRefreshToken(
        $userData['user_id'],
        JWTManager::getClientIP(),
        $_SERVER['HTTP_USER_AGENT'] ?? null
    );
    
    $jwtManager->revogarRefreshToken($refreshToken); // Revoga o antigo
    
    // Resposta
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'access_token' => $newAccessToken,
        'refresh_token' => $newRefreshToken,
        'token_type' => 'Bearer',
        'expires_in' => 900 // 15 minutos em segundos
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao renovar token',
        'message' => $e->getMessage()
    ]);
}
?>
```

2. ✅ Testar endpoint com Postman/curl

**Entregável:**
- `apiIgreja/auth/token.php` (funcional)

---

### Task 3.5: Middleware de Autenticação JWT
**Duração estimada:** 1h 30min

#### Subtasks
1. ✅ Criar `sistema/helpers/authMiddleware.php`

```php
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

function verificarJWT(): ?object {
    global $pdo;
    
    // Verificar header Authorization
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    if (empty($authHeader)) {
        // Fallback: verificar sessão PHP (compatibilidade com autenticação antiga)
        session_start();
        if (isset($_SESSION['id']) && isset($_SESSION['nivel'])) {
            // Autenticação antiga (sessão PHP) ainda ativa
            return (object) [
                'id' => $_SESSION['id'],
                'nome' => $_SESSION['nome'],
                'nivel' => $_SESSION['nivel'],
                'id_igreja' => $_SESSION['id_igreja']
            ];
        }
        
        // Sem autenticação válida
        redirectToLogin();
        return null;
    }
    
    // Extrair token do header "Bearer {token}"
    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        redirectToLogin('Formato de token inválido');
        return null;
    }
    
    $token = $matches[1];
    
    // Validar JWT
    $jwtManager = new JWTManager($pdo);
    $userData = $jwtManager->validarAccessToken($token);
    
    if (!$userData) {
        // Token expirado ou inválido
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Token expirado ou inválido',
            'code' => 'TOKEN_EXPIRED'
        ]);
        exit();
    }
    
    return $userData;
}

function redirectToLogin(string $mensagem = 'Sessão expirada') {
    $_SESSION['msg'] = $mensagem;
    
    // Se for requisição AJAX/API
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => $mensagem, 'redirect' => '../index.php']);
        exit();
    }
    
    // Redirecionamento padrão
    echo '<script>window.location="../index.php"</script>';
    exit();
}
?>
```

2. ✅ Integrar em `painel-admin/verificar.php` e `painel-igreja/verificar.php`

**Exemplo de integração:**
```php
<?php
// painel-admin/verificar.php (atualizado)
require_once("../helpers/authMiddleware.php");
require_once("../helpers/SessionFingerprint.php");

// Verificar JWT OU sessão PHP
$user = verificarJWT();

// Verificar fingerprint (mantido do Sprint 2)
if (!SessionFingerprint::validar()) {
    exit(); // Sessão inválida
}

// Verificar nível de acesso
if ($user->nivel != 'Bispo') {
    echo '<script>window.location="../index.php"</script>';
    exit();
}
?>
```

**Entregável:**
- `sistema/helpers/authMiddleware.php`
- `painel-admin/verificar.php` atualizado
- `painel-igreja/verificar.php` atualizado

---

### Task 3.6: Atualização de autenticar.php
**Duração estimada:** 45min

#### Subtasks
1. ✅ Adicionar geração de JWT após login bem-sucedido

```php
// Após validação bem-sucedida (linha ~170 em autenticar.php)

// Gerar tokens JWT (Sprint 3)
require_once("helpers/JWTManager.php");
$jwtManager = new JWTManager($pdo);

$accessToken = $jwtManager->gerarAccessToken([
    'id' => $res[0]['id'],
    'nome' => $res[0]['nome'],
    'nivel' => $res[0]['nivel'],
    'id_igreja' => $res[0]['igreja']
]);

$refreshToken = $jwtManager->gerarRefreshToken(
    $res[0]['id'],
    JWTManager::getClientIP(),
    $_SERVER['HTTP_USER_AGENT'] ?? null
);

// Armazenar tokens no localStorage (JavaScript)
echo "<script>
    localStorage.setItem('access_token', '$accessToken');
    localStorage.setItem('refresh_token', '$refreshToken');
</script>";

// Manter sessão PHP por compatibilidade (removido no Sprint 4)
$_SESSION['nome'] = $res[0]['nome'];
$_SESSION['id'] = $res[0]['id'];
// ...
```

2. ✅ Testar login e verificar tokens no localStorage

**Entregável:**
- `sistema/autenticar.php` com JWT integrado

---

### Task 3.7: Endpoint de Logout com Revogação de Token
**Duração estimada:** 30min

#### Subtasks
1. ✅ Criar `apiIgreja/auth/logout.php`

```php
<?php
/**
 * Endpoint de Logout (Revogação de Refresh Token)
 * 
 * POST /apiIgreja/auth/logout.php
 * Body: { "refresh_token": "uuid-v4" }
 * 
 * Versão: 1.0 (Sprint 3)
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit();
}

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../../sistema/helpers/JWTManager.php');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $refreshToken = $input['refresh_token'] ?? '';
    
    if (empty($refreshToken)) {
        http_response_code(400);
        echo json_encode(['error' => 'Refresh token obrigatório']);
        exit();
    }
    
    $jwtManager = new JWTManager($pdo);
    $revogado = $jwtManager->revogarRefreshToken($refreshToken);
    
    if ($revogado) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Logout realizado com sucesso']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Token não encontrado']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao fazer logout']);
}
?>
```

**Entregável:**
- `apiIgreja/auth/logout.php`

---

## 🧪 Plano de Testes

### Testes Unitários (Manual)
1. ✅ Gerar access token e decodificar (validar claims)
2. ✅ Gerar refresh token e consultar no banco
3. ✅ Renovar access token com refresh válido
4. ✅ Tentar renovar com refresh expirado/revogado
5. ✅ Revogar token e verificar invalidação

### Testes de Integração
1. ✅ Login → Receber tokens → Acessar painel com Bearer
2. ✅ Aguardar 15min → Access token expirar → Renovar automaticamente
3. ✅ Logout → Revogar refresh → Tentar renovar (deve falhar)
4. ✅ Validar convivência: sessão PHP + JWT funcionando simultaneamente

### Testes de Segurança
1. ✅ Tentar forjar JWT com chave errada (deve falhar)
2. ✅ Alterar claims manualmente (deve invalidar assinatura)
3. ✅ Reutilizar refresh token após revogação (deve falhar)
4. ✅ Validar expiração de tokens (deve bloquear após TTL)

---

## 📊 Métricas de Sucesso

| Métrica | Meta |
|---------|------|
| Tempo de implementação | < 7h |
| Testes aprovados | 100% (12/12) |
| Overhead de performance | < 20ms |
| Compatibilidade backward | 100% (sessões antigas funcionando) |

---

## 🔧 Ferramentas

- **Biblioteca:** Firebase PHP-JWT 6.x
- **Gerador de secrets:** `openssl rand -base64 64`
- **Testes:** Postman / cURL
- **Validação JWT:** jwt.io

---

## 📁 Entregáveis Finais

1. ✅ Classe `JWTManager` completa
2. ✅ Endpoint `/apiIgreja/auth/token.php` (renovação)
3. ✅ Endpoint `/apiIgreja/auth/logout.php` (revogação)
4. ✅ Middleware `authMiddleware.php`
5. ✅ Tabela `refresh_tokens` + evento de limpeza
6. ✅ Integração em `autenticar.php`
7. ✅ Atualização de `verificar.php` (admin + igreja)
8. ✅ Documentação completa (`SPRINT_3_RELATORIO_FINAL.md`)

---

## 🚀 Próximos Passos (Sprint 4)

- SecurityLogger (auditoria centralizada)
- Dashboard de segurança (admin)
- Criptografia AES-256 para CPF
- Endpoint LGPD de exclusão de dados
- Alertas WhatsApp para eventos críticos

---

**Pronto para iniciar Sprint 3?** 🎯
