<?php
/**
 * AlertManager - Alertas de Segurança
 * 
 * Sprint 4 - Task 4.7
 * 
 * Envia alertas via email quando thresholds de segurança são excedidos.
 * Pode ser estendido para WhatsApp/Telegram no futuro.
 * 
 * @author Nexus (Security Team)
 */

class AlertManager {
    private $pdo;
    private string $emailAdmin;

    // Thresholds configuráveis
    const THRESHOLD_BLOQUEIOS_5MIN = 10;   // 10 bloqueios em 5 min
    const THRESHOLD_BOTS_1H = 20;          // 20 bots em 1 hora
    const THRESHOLD_CRITICOS_1H = 5;       // 5 eventos críticos em 1 hora

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->emailAdmin = getenv('EMAIL_SUPER_ADM') ?: $_ENV['EMAIL_SUPER_ADM'] ?? 'contato@agenciadigitalslz.com.br';
    }

    /**
     * Verifica thresholds e envia alertas se necessário
     * Chamar periodicamente (ex: a cada login bloqueado)
     */
    public function verificarThresholds(): array {
        $alertas = [];

        // Bloqueios nos últimos 5 minutos
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as total FROM security_logs 
            WHERE evento = 'LOGIN_BLOCKED' AND created_at >= NOW() - INTERVAL 5 MINUTE
        ");
        $bloqueios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($bloqueios >= self::THRESHOLD_BLOQUEIOS_5MIN) {
            $alertas[] = $this->enviarAlerta(
                '🚨 ALERTA: Bloqueios em massa',
                "{$bloqueios} IPs bloqueados nos últimos 5 minutos. Possível ataque de força bruta.",
                'CRITICAL'
            );
        }

        // Bots na última hora
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as total FROM security_logs 
            WHERE evento = 'BOT_DETECTED' AND created_at >= NOW() - INTERVAL 1 HOUR
        ");
        $bots = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($bots >= self::THRESHOLD_BOTS_1H) {
            $alertas[] = $this->enviarAlerta(
                '🤖 ALERTA: Atividade de Bots',
                "{$bots} bots detectados na última hora.",
                'WARNING'
            );
        }

        // Eventos críticos na última hora
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as total FROM security_logs 
            WHERE severidade = 'CRITICAL' AND created_at >= NOW() - INTERVAL 1 HOUR
        ");
        $criticos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($criticos >= self::THRESHOLD_CRITICOS_1H) {
            $alertas[] = $this->enviarAlerta(
                '🔴 ALERTA CRÍTICO: Eventos de Segurança',
                "{$criticos} eventos críticos na última hora. Investigar imediatamente.",
                'CRITICAL'
            );
        }

        return $alertas;
    }

    /**
     * Envia alerta via email
     */
    public function enviarAlerta(string $titulo, string $mensagem, string $nivel = 'WARNING'): array {
        $resultado = ['titulo' => $titulo, 'nivel' => $nivel, 'enviado' => false];

        // Registrar alerta no log
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO security_logs (evento, severidade, categoria, detalhes, ip_address)
                VALUES ('ALERT_SENT', ?, 'SISTEMA', ?, ?)
            ");
            $stmt->execute([
                $nivel,
                json_encode(['titulo' => $titulo, 'mensagem' => $mensagem], JSON_UNESCAPED_UNICODE),
                $_SERVER['REMOTE_ADDR'] ?? 'SYSTEM'
            ]);
        } catch (Exception $e) {
            error_log("AlertManager log error: " . $e->getMessage());
        }

        // Enviar email (apenas em produção)
        if (getenv('APP_ENV') === 'production') {
            $headers = "From: noreply@ibnm.online\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            $corpo = "=== IBNM.online - Alerta de Segurança ===\n\n";
            $corpo .= "Nível: {$nivel}\n";
            $corpo .= "Data: " . date('d/m/Y H:i:s') . "\n\n";
            $corpo .= $mensagem . "\n\n";
            $corpo .= "---\nSistema de Monitoramento IBNM.online v7.30";

            $resultado['enviado'] = @mail($this->emailAdmin, $titulo, $corpo, $headers);
        } else {
            // Em dev, apenas logar
            error_log("[ALERT][{$nivel}] {$titulo}: {$mensagem}");
            $resultado['enviado'] = true;
            $resultado['modo'] = 'dev_log';
        }

        return $resultado;
    }
}
?>
