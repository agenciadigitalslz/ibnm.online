<?php
/**
 * Rate Limiter - Controle de Tentativas Falhas de Login
 * 
 * Versão: 1.0 (Sprint 2 - Task 2.2)
 * Data: 2026-02-09
 * 
 * Funcionalidades:
 * - Bloqueio progressivo de IPs
 * - Detecção de bots (honeypot)
 * - Limpeza de bloqueios expirados
 * 
 * @author Nexus (Security Hardening Team)
 */

class RateLimiter {
    private $pdo;
    private $ip;
    
    // Escalação progressiva (conforme aprovado por André)
    private $escalacao = [
        3  => 1,      // 3 tentativas  → 1 minuto
        6  => 30,     // 6 tentativas  → 30 minutos
        9  => 60,     // 9 tentativas  → 1 hora
        12 => 1440,   // 12 tentativas → 24 horas
    ];
    
    public function __construct($pdo, $ip = null) {
        $this->pdo = $pdo;
        $this->ip = $ip ?: $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Verificar se IP está bloqueado
     * 
     * @return array ['bloqueado' => bool, 'minutos_restantes' => int, 'tentativas' => int]
     */
    public function verificarBloqueio() {
        // Limpar bloqueios expirados primeiro
        $this->limparBloqueiosExpirados();
        
        $query = $this->pdo->prepare("
            SELECT * FROM failed_logins 
            WHERE ip = ? AND bloqueado_ate > NOW()
        ");
        $query->execute([$this->ip]);
        $bloqueio = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($bloqueio) {
            $minutos_restantes = ceil((strtotime($bloqueio['bloqueado_ate']) - time()) / 60);
            
            return [
                'bloqueado' => true,
                'minutos_restantes' => $minutos_restantes,
                'tentativas' => $bloqueio['tentativas'],
                'bloqueado_ate' => $bloqueio['bloqueado_ate']
            ];
        }
        
        return ['bloqueado' => false];
    }
    
    /**
     * Registrar tentativa falha de login
     * 
     * @param string $email Email usado na tentativa
     * @param string $tipo Tipo de erro: 'LOGIN_FAILED' ou 'BOT_DETECTED'
     * @return array Status do bloqueio após registro
     */
    public function registrarFalha($email, $tipo = 'LOGIN_FAILED') {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        // Verificar se já existe registro para este IP
        $query = $this->pdo->prepare("SELECT * FROM failed_logins WHERE ip = ?");
        $query->execute([$this->ip]);
        $registro = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($registro) {
            // Incrementar tentativas
            $tentativas = $registro['tentativas'] + 1;
            $bloqueio_minutos = $this->calcularBloqueio($tentativas);
            $bloqueado_ate = date('Y-m-d H:i:s', strtotime("+{$bloqueio_minutos} minutes"));
            
            $update = $this->pdo->prepare("
                UPDATE failed_logins 
                SET tentativas = ?, 
                    bloqueado_ate = ?, 
                    email_tentativa = ?, 
                    ultimo_erro = ?,
                    user_agent = ?
                WHERE ip = ?
            ");
            $update->execute([
                $tentativas, 
                $bloqueado_ate, 
                $email, 
                $tipo,
                $user_agent,
                $this->ip
            ]);
            
        } else {
            // Primeiro erro: 1 tentativa (sem bloqueio ainda)
            $insert = $this->pdo->prepare("
                INSERT INTO failed_logins (ip, email_tentativa, ultimo_erro, user_agent) 
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([$this->ip, $email, $tipo, $user_agent]);
            
            $tentativas = 1;
            $bloqueio_minutos = 0;
        }
        
        return [
            'tentativas' => $tentativas,
            'bloqueio_minutos' => $bloqueio_minutos,
            'proximo_limite' => $this->proximoLimite($tentativas)
        ];
    }
    
    /**
     * Limpar registro de IP (após login bem-sucedido)
     */
    public function limparBloqueio() {
        $delete = $this->pdo->prepare("DELETE FROM failed_logins WHERE ip = ?");
        $delete->execute([$this->ip]);
    }
    
    /**
     * Calcular tempo de bloqueio baseado em tentativas
     * 
     * @param int $tentativas Número de tentativas falhas
     * @return int Minutos de bloqueio
     */
    private function calcularBloqueio($tentativas) {
        // Encontrar escalação apropriada
        foreach ($this->escalacao as $limite => $minutos) {
            if ($tentativas >= $limite) {
                $bloqueio = $minutos;
            }
        }
        
        return $bloqueio ?? 0;
    }
    
    /**
     * Calcular próximo limite de escal

ação
     * 
     * @param int $tentativas Tentativas atuais
     * @return int|null Próximo limite ou null se já passou de todos
     */
    private function proximoLimite($tentativas) {
        foreach ($this->escalacao as $limite => $minutos) {
            if ($tentativas < $limite) {
                return $limite;
            }
        }
        
        return null; // Já passou de todos os limites
    }
    
    /**
     * Limpar bloqueios expirados (performance)
     */
    private function limparBloqueiosExpirados() {
        $this->pdo->query("
            DELETE FROM failed_logins 
            WHERE bloqueado_ate IS NOT NULL 
            AND bloqueado_ate < NOW()
        ");
    }
    
    /**
     * Obter estatísticas de bloqueios
     * 
     * @return array Estatísticas gerais
     */
    public static function getEstatisticas($pdo) {
        $query = $pdo->query("
            SELECT 
                COUNT(*) as total_ips,
                SUM(CASE WHEN bloqueado_ate > NOW() THEN 1 ELSE 0 END) as ips_bloqueados,
                SUM(CASE WHEN ultimo_erro = 'BOT_DETECTED' THEN 1 ELSE 0 END) as bots_detectados,
                MAX(tentativas) as max_tentativas
            FROM failed_logins
        ");
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>
