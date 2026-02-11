<?php
/**
 * Gerenciador de JWT (Access + Refresh Tokens)
 * 
 * Versão: 1.0 (Sprint 3)
 * 
 * @author Nexus (Security Team)
 */

require_once(__DIR__ . '/JWT.php');

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
            'aud' => $this->config['audience'],
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
            $decoded = JWT::decode($token, $this->config['secret_key']);
            
            if (!$decoded) {
                return false;
            }
            
            // Verificar issuer
            if (isset($decoded->iss) && $decoded->iss !== $this->config['issuer']) {
                error_log("JWT: Issuer inválido");
                return false;
            }
            
            return $decoded->data ?? $decoded;
            
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
        
        // Limitar tokens por usuário (se configurado)
        if ($this->config['max_refresh_tokens_per_user'] > 0) {
            $this->limitarTokensPorUsuario($userId, $this->config['max_refresh_tokens_per_user']);
        }
        
        $query = $this->pdo->prepare("
            INSERT INTO refresh_tokens (user_id, token, expires_at, ip_address, user_agent)
            VALUES (:user_id, :token, :expires_at, :ip_address, :user_agent)
        ");
        
        $query->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt,
            ':ip_address' => $ipAddress,
            ':user_agent' => substr($userAgent ?? '', 0, 255) // Limitar tamanho
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
                   u.nome, u.nivel, u.igreja as id_igreja, u.ativo, u.email
            FROM refresh_tokens rt
            INNER JOIN usuarios u ON rt.user_id = u.id
            WHERE rt.token = :token
        ");
        
        $query->bindValue(':token', $token);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("Refresh token não encontrado: " . substr($token, 0, 8) . "...");
            return false; // Token não encontrado
        }
        
        // Verificar expiração
        if (strtotime($result['expires_at']) < time()) {
            error_log("Refresh token expirado");
            return false; // Token expirado
        }
        
        // Verificar revogação
        if ($result['revoked'] == 1) {
            error_log("Refresh token revogado");
            return false; // Token revogado
        }
        
        // Verificar usuário ativo
        if ($result['ativo'] != 'Sim') {
            error_log("Usuário desativado: " . $result['email']);
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
     * Limita quantidade de tokens ativos por usuário
     * 
     * @param int $userId
     * @param int $maxTokens
     * @return void
     */
    private function limitarTokensPorUsuario(int $userId, int $maxTokens): void {
        // Contar tokens ativos
        $query = $this->pdo->prepare("
            SELECT COUNT(*) as total
            FROM refresh_tokens
            WHERE user_id = :user_id
              AND revoked = 0
              AND expires_at > NOW()
        ");
        
        $query->execute([':user_id' => $userId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total'] >= $maxTokens) {
            // Revogar os mais antigos
            $query = $this->pdo->prepare("
                UPDATE refresh_tokens
                SET revoked = 1
                WHERE user_id = :user_id
                  AND revoked = 0
                  AND id NOT IN (
                    SELECT id FROM (
                      SELECT id
                      FROM refresh_tokens
                      WHERE user_id = :user_id
                        AND revoked = 0
                        AND expires_at > NOW()
                      ORDER BY created_at DESC
                      LIMIT :max_tokens
                    ) AS latest
                  )
            ");
            
            $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $query->bindValue(':max_tokens', $maxTokens - 1, PDO::PARAM_INT); // -1 para abrir espaço para o novo
            $query->execute();
        }
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
            if (isset($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Estatísticas de tokens de um usuário
     * 
     * @param int $userId
     * @return array
     */
    public function getEstatisticasTokens(int $userId): array {
        $query = $this->pdo->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN revoked = 0 AND expires_at > NOW() THEN 1 ELSE 0 END) as ativos,
                SUM(CASE WHEN revoked = 1 THEN 1 ELSE 0 END) as revogados,
                SUM(CASE WHEN expires_at < NOW() THEN 1 ELSE 0 END) as expirados
            FROM refresh_tokens
            WHERE user_id = :user_id
        ");
        
        $query->execute([':user_id' => $userId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>
