<?php
/**
 * Session Fingerprint - Proteção Contra Session Hijacking
 * 
 * Versão: 1.0 (Sprint 2 - Task 2.3)
 * Data: 2026-02-09
 * 
 * Funcionalidades:
 * - Gerar fingerprint único por sessão
 * - Validar fingerprint a cada requisição
 * - Destruir sessão se fingerprint mudar
 * 
 * @author Nexus (Security Hardening Team)
 */

class SessionFingerprint {
    
    /**
     * Gerar fingerprint da sessão atual
     * 
     * Baseado em:
     * - User-Agent do navegador
     * - IP do usuário
     * 
     * @return string Hash MD5 do fingerprint
     */
    public static function gerar() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Gerar hash combinando User-Agent + IP
        return md5($user_agent . $ip);
    }
    
    /**
     * Validar fingerprint da sessão
     * 
     * @return bool True se válido, False se alterado
     */
    public static function validar() {
        $fingerprint_atual = self::gerar();
        
        // Se não existe fingerprint salvo, criar
        if (!isset($_SESSION['fingerprint'])) {
            $_SESSION['fingerprint'] = $fingerprint_atual;
            return true;
        }
        
        // Comparar fingerprint salvo com atual
        return $_SESSION['fingerprint'] === $fingerprint_atual;
    }
    
    /**
     * Destruir sessão por fingerprint inválido
     * 
     * @param string $redirect_url URL para redirecionar após destruir sessão
     */
    public static function destruirSessao($redirect_url = 'index.php') {
        // Log da tentativa de hijacking (opcional)
        if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
            error_log(sprintf(
                'Session Hijacking detected - User: %s (ID: %d), IP: %s',
                $_SESSION['nome'],
                $_SESSION['id'],
                $_SERVER['REMOTE_ADDR']
            ));
        }
        
        // Destruir sessão completamente
        session_unset();
        session_destroy();
        session_write_close();
        
        // Redirecionar para login
        header("Location: $redirect_url?erro=sessao_invalida");
        exit();
    }
    
    /**
     * Verificar e validar fingerprint (uso em verificar.php)
     * 
     * Destrói sessão automaticamente se fingerprint for inválido
     * 
     * @param string $redirect_url URL de redirecionamento
     * @return bool True se válido
     */
    public static function verificarEValidar($redirect_url = 'index.php') {
        if (!self::validar()) {
            self::destruirSessao($redirect_url);
            // Não retorna (exit no destruirSessao)
        }
        
        return true;
    }
    
    /**
     * Renovar fingerprint (após login bem-sucedido ou mudança legítima)
     */
    public static function renovar() {
        $_SESSION['fingerprint'] = self::gerar();
    }
    
    /**
     * Obter informações do fingerprint atual
     * 
     * @return array Informações de debug
     */
    public static function getInfo() {
        return [
            'fingerprint' => self::gerar(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'fingerprint_salvo' => $_SESSION['fingerprint'] ?? null,
            'valido' => self::validar()
        ];
    }
}
?>
