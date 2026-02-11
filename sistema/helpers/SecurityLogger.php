<?php
/**
 * SecurityLogger - Auditoria Centralizada de Segurança
 * 
 * Sprint 4 - Task 4.2
 * Versão: 1.0 (v7.30)
 * 
 * Registra todos os eventos de segurança em tabela centralizada.
 * Severidades: INFO, WARNING, ERROR, CRITICAL
 * Categorias: AUTH, RATE_LIMIT, JWT, LGPD, DADOS, SISTEMA
 * 
 * @author Nexus (Security Team)
 */

class SecurityLogger {
    private $pdo;

    // Eventos padronizados
    const LOGIN_SUCCESS       = 'LOGIN_SUCCESS';
    const LOGIN_FAILED        = 'LOGIN_FAILED';
    const LOGIN_BLOCKED       = 'LOGIN_BLOCKED';
    const BOT_DETECTED        = 'BOT_DETECTED';
    const LOGOUT              = 'LOGOUT';
    const LOGOUT_ALL          = 'LOGOUT_ALL';
    const TOKEN_GENERATED     = 'TOKEN_GENERATED';
    const TOKEN_RENEWED       = 'TOKEN_RENEWED';
    const TOKEN_REVOKED       = 'TOKEN_REVOKED';
    const TOKEN_EXPIRED       = 'TOKEN_EXPIRED';
    const TOKEN_INVALID       = 'TOKEN_INVALID';
    const ACCESS_DENIED       = 'ACCESS_DENIED';
    const SESSION_HIJACK      = 'SESSION_HIJACK';
    const DATA_CHANGED        = 'DATA_CHANGED';
    const DATA_DELETED        = 'DATA_DELETED';
    const DATA_EXPORTED       = 'DATA_EXPORTED';
    const LGPD_DELETE_REQUEST = 'LGPD_DELETE_REQUEST';
    const LGPD_DATA_ANONYMIZED = 'LGPD_DATA_ANONYMIZED';
    const CPF_ENCRYPTED       = 'CPF_ENCRYPTED';
    const SYSTEM_ERROR        = 'SYSTEM_ERROR';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Registra evento de segurança
     */
    public function log(
        string $evento,
        string $severidade = 'INFO',
        string $categoria = 'GERAL',
        array $detalhes = [],
        ?int $userId = null,
        ?int $igrejaId = null,
        ?string $recurso = null
    ): bool {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO security_logs 
                (evento, severidade, categoria, user_id, igreja_id, ip_address, user_agent, recurso, detalhes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $evento,
                $severidade,
                $categoria,
                $userId ?? ($_SESSION['id'] ?? null),
                $igrejaId ?? ($_SESSION['id_igreja'] ?? null),
                self::getIP(),
                substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 500),
                $recurso ?? ($_SERVER['REQUEST_URI'] ?? null),
                !empty($detalhes) ? json_encode($detalhes, JSON_UNESCAPED_UNICODE) : null
            ]);
        } catch (Exception $e) {
            error_log("SecurityLogger FAIL: " . $e->getMessage());
            return false;
        }
    }

    // =========================================================================
    // Métodos de conveniência por categoria
    // =========================================================================

    public function logLogin(?int $userId, bool $sucesso, string $email, ?int $igrejaId = null): bool {
        return $this->log(
            $sucesso ? self::LOGIN_SUCCESS : self::LOGIN_FAILED,
            $sucesso ? 'INFO' : 'WARNING',
            'AUTH',
            ['email' => $email],
            $userId,
            $igrejaId
        );
    }

    public function logLoginBloqueado(string $email, int $tentativas, int $bloqueioMin): bool {
        return $this->log(
            self::LOGIN_BLOCKED,
            'WARNING',
            'RATE_LIMIT',
            ['email' => $email, 'tentativas' => $tentativas, 'bloqueio_minutos' => $bloqueioMin]
        );
    }

    public function logBotDetected(string $email): bool {
        return $this->log(
            self::BOT_DETECTED,
            'WARNING',
            'RATE_LIMIT',
            ['email' => $email]
        );
    }

    public function logLogout(int $userId, string $tipo = 'single'): bool {
        return $this->log(
            $tipo === 'all' ? self::LOGOUT_ALL : self::LOGOUT,
            'INFO',
            'AUTH',
            ['tipo' => $tipo],
            $userId
        );
    }

    public function logTokenGerado(int $userId): bool {
        return $this->log(self::TOKEN_GENERATED, 'INFO', 'JWT', [], $userId);
    }

    public function logTokenRenovado(int $userId): bool {
        return $this->log(self::TOKEN_RENEWED, 'INFO', 'JWT', [], $userId);
    }

    public function logTokenRevogado(int $userId, string $motivo = 'logout'): bool {
        return $this->log(self::TOKEN_REVOKED, 'INFO', 'JWT', ['motivo' => $motivo], $userId);
    }

    public function logAcessoNegado(?int $userId, string $recurso, string $motivo = ''): bool {
        return $this->log(
            self::ACCESS_DENIED,
            'WARNING',
            'AUTH',
            ['motivo' => $motivo],
            $userId,
            null,
            $recurso
        );
    }

    public function logSessionHijack(?int $userId): bool {
        return $this->log(self::SESSION_HIJACK, 'CRITICAL', 'AUTH', [], $userId);
    }

    public function logAlteracaoDados(int $userId, string $tabela, $registroId, string $acao = 'UPDATE'): bool {
        return $this->log(
            self::DATA_CHANGED,
            'INFO',
            'DADOS',
            ['tabela' => $tabela, 'registro_id' => $registroId, 'acao' => $acao],
            $userId
        );
    }

    public function logLgpdDeleteRequest(int $userId): bool {
        return $this->log(self::LGPD_DELETE_REQUEST, 'WARNING', 'LGPD', [], $userId);
    }

    public function logLgpdAnonymized(int $userId): bool {
        return $this->log(self::LGPD_DATA_ANONYMIZED, 'WARNING', 'LGPD', [], $userId);
    }

    // =========================================================================
    // Consultas / Estatísticas
    // =========================================================================

    /**
     * Busca eventos com filtros
     */
    public function buscar(array $filtros = [], int $limite = 50, int $offset = 0): array {
        $where = [];
        $params = [];

        if (!empty($filtros['severidade'])) {
            $where[] = "severidade = ?";
            $params[] = $filtros['severidade'];
        }
        if (!empty($filtros['categoria'])) {
            $where[] = "categoria = ?";
            $params[] = $filtros['categoria'];
        }
        if (!empty($filtros['evento'])) {
            $where[] = "evento = ?";
            $params[] = $filtros['evento'];
        }
        if (!empty($filtros['user_id'])) {
            $where[] = "user_id = ?";
            $params[] = $filtros['user_id'];
        }
        if (!empty($filtros['ip'])) {
            $where[] = "ip_address = ?";
            $params[] = $filtros['ip'];
        }
        if (!empty($filtros['data_inicio'])) {
            $where[] = "created_at >= ?";
            $params[] = $filtros['data_inicio'];
        }
        if (!empty($filtros['data_fim'])) {
            $where[] = "created_at <= ?";
            $params[] = $filtros['data_fim'];
        }

        $whereSQL = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->pdo->prepare("
            SELECT sl.*, u.nome as user_nome
            FROM security_logs sl
            LEFT JOIN usuarios u ON sl.user_id = u.id
            {$whereSQL}
            ORDER BY sl.created_at DESC
            LIMIT {$limite} OFFSET {$offset}
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Estatísticas gerais para dashboard
     */
    public function getEstatisticas(string $periodo = '24h'): array {
        $intervalo = match($periodo) {
            '1h'  => 'INTERVAL 1 HOUR',
            '24h' => 'INTERVAL 24 HOUR',
            '7d'  => 'INTERVAL 7 DAY',
            '30d' => 'INTERVAL 30 DAY',
            default => 'INTERVAL 24 HOUR'
        };

        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total_eventos,
                SUM(CASE WHEN evento = 'LOGIN_SUCCESS' THEN 1 ELSE 0 END) as logins_sucesso,
                SUM(CASE WHEN evento = 'LOGIN_FAILED' THEN 1 ELSE 0 END) as logins_falha,
                SUM(CASE WHEN evento = 'LOGIN_BLOCKED' THEN 1 ELSE 0 END) as logins_bloqueados,
                SUM(CASE WHEN evento = 'BOT_DETECTED' THEN 1 ELSE 0 END) as bots_detectados,
                SUM(CASE WHEN evento = 'TOKEN_RENEWED' THEN 1 ELSE 0 END) as tokens_renovados,
                SUM(CASE WHEN evento = 'TOKEN_REVOKED' THEN 1 ELSE 0 END) as tokens_revogados,
                SUM(CASE WHEN evento = 'ACCESS_DENIED' THEN 1 ELSE 0 END) as acessos_negados,
                SUM(CASE WHEN evento = 'SESSION_HIJACK' THEN 1 ELSE 0 END) as session_hijacks,
                SUM(CASE WHEN severidade = 'CRITICAL' THEN 1 ELSE 0 END) as criticos,
                SUM(CASE WHEN severidade = 'WARNING' THEN 1 ELSE 0 END) as alertas,
                COUNT(DISTINCT ip_address) as ips_unicos,
                COUNT(DISTINCT user_id) as usuarios_unicos
            FROM security_logs
            WHERE created_at >= NOW() - {$intervalo}
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Eventos por hora (para gráficos)
     */
    public function getEventosPorHora(int $horas = 24): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m-%d %H:00') as hora,
                COUNT(*) as total,
                SUM(CASE WHEN severidade IN ('WARNING','ERROR','CRITICAL') THEN 1 ELSE 0 END) as alertas
            FROM security_logs
            WHERE created_at >= NOW() - INTERVAL ? HOUR
            GROUP BY hora
            ORDER BY hora ASC
        ");
        $stmt->execute([$horas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Top IPs mais ativos
     */
    public function getTopIPs(int $limite = 10, string $periodo = '24h'): array {
        $intervalo = match($periodo) {
            '24h' => 'INTERVAL 24 HOUR',
            '7d'  => 'INTERVAL 7 DAY',
            default => 'INTERVAL 24 HOUR'
        };

        $stmt = $this->pdo->query("
            SELECT 
                ip_address,
                COUNT(*) as total_eventos,
                SUM(CASE WHEN severidade IN ('WARNING','ERROR','CRITICAL') THEN 1 ELSE 0 END) as alertas,
                MAX(created_at) as ultimo_evento
            FROM security_logs
            WHERE created_at >= NOW() - {$intervalo}
              AND ip_address IS NOT NULL
            GROUP BY ip_address
            ORDER BY total_eventos DESC
            LIMIT {$limite}
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Últimos eventos críticos
     */
    public function getEventosCriticos(int $limite = 20): array {
        $limite = (int)$limite;
        $stmt = $this->pdo->query("
            SELECT sl.*, u.nome as user_nome
            FROM security_logs sl
            LEFT JOIN usuarios u ON sl.user_id = u.id
            WHERE sl.severidade IN ('ERROR', 'CRITICAL')
            ORDER BY sl.created_at DESC
            LIMIT {$limite}
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * IP do cliente
     */
    public static function getIP(): string {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
?>
