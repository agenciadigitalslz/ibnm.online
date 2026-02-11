<?php 
$pag = 'seguranca';

// Carregar dados de segurança
require_once(__DIR__ . "/../../helpers/SecurityLogger.php");
require_once(__DIR__ . "/../../helpers/RateLimiter.php");

$secLogger = new SecurityLogger($pdo);

// Estatísticas por período
$stats24h = $secLogger->getEstatisticas('24h');
$stats7d  = $secLogger->getEstatisticas('7d');
$stats30d = $secLogger->getEstatisticas('30d');

// Eventos por hora (gráfico)
$eventosPorHora = $secLogger->getEventosPorHora(24);

// Top IPs
$topIPs = $secLogger->getTopIPs(10, '24h');

// Eventos críticos
$criticos = $secLogger->getEventosCriticos(10);

// Últimos 50 eventos
$ultimosEventos = $secLogger->buscar([], 50);

// Rate Limiter
$rateLimiterStats = RateLimiter::getEstatisticas($pdo);

// Tokens
$stmtTokens = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN revoked = 0 AND expires_at > NOW() THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN revoked = 1 THEN 1 ELSE 0 END) as revogados
    FROM refresh_tokens
");
$tokenStats = $stmtTokens->fetch(PDO::FETCH_ASSOC);

// Preparar dados para Chart.js
$horasLabels = [];
$horasData = [];
$horasAlertas = [];
foreach ($eventosPorHora as $row) {
    $horasLabels[] = date('H:i', strtotime($row['hora']));
    $horasData[] = (int)$row['total'];
    $horasAlertas[] = (int)$row['alertas'];
}
?>

<!-- CSS Inline para Cards -->
<style>
.security-card { border-radius: 12px; padding: 20px; color: #fff; margin-bottom: 15px; }
.security-card h6 { font-size: 13px; opacity: 0.85; margin-bottom: 5px; }
.security-card h3 { font-size: 28px; font-weight: 700; margin: 0; }
.bg-success-gradient { background: linear-gradient(135deg, #1fa855 0%, #43d477 100%); }
.bg-danger-gradient { background: linear-gradient(135deg, #dc3545 0%, #e8646e 100%); }
.bg-warning-gradient { background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%); color: #333 !important; }
.bg-info-gradient { background: linear-gradient(135deg, #3498db 0%, #5dade2 100%); }
.bg-purple-gradient { background: linear-gradient(135deg, #6f42c1 0%, #9b6dd7 100%); }
.bg-dark-gradient { background: linear-gradient(135deg, #2c3e50 0%, #4a6785 100%); }
.severity-badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
.sev-info { background: #d1ecf1; color: #0c5460; }
.sev-warning { background: #fff3cd; color: #856404; }
.sev-error { background: #f8d7da; color: #721c24; }
.sev-critical { background: #721c24; color: #fff; }
</style>

<div class="justify-content-between">
    <div class="left-content mt-2 mb-3">
        <h4 class="mb-1">🔐 Monitor de Segurança</h4>
        <p class="text-muted">Dashboard em tempo real — IBNM.online v7.30</p>
    </div>
</div>

<!-- Cards de Métricas (24h) -->
<div class="row row-sm mb-4">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-success-gradient">
            <h6>Logins OK (24h)</h6>
            <h3><?= (int)($stats24h['logins_sucesso'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-danger-gradient">
            <h6>Logins Falhos</h6>
            <h3><?= (int)($stats24h['logins_falha'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-warning-gradient">
            <h6>IPs Bloqueados</h6>
            <h3><?= (int)($stats24h['logins_bloqueados'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-purple-gradient">
            <h6>Bots Detectados</h6>
            <h3><?= (int)($stats24h['bots_detectados'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-info-gradient">
            <h6>Tokens Ativos</h6>
            <h3><?= (int)($tokenStats['ativos'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="security-card bg-dark-gradient">
            <h6>Eventos Críticos</h6>
            <h3><?= (int)($stats24h['criticos'] ?? 0) ?></h3>
        </div>
    </div>
</div>

<!-- Gráfico de Atividade + Eventos Críticos -->
<div class="row row-sm mb-4">
    <div class="col-lg-8">
        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">📊 Atividade nas Últimas 24 Horas</h6>
            </div>
            <div class="card-body">
                <canvas id="chartAtividade" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">🚨 Eventos Críticos Recentes</h6>
            </div>
            <div class="card-body" style="max-height: 310px; overflow-y: auto;">
                <?php if (empty($criticos)): ?>
                    <p class="text-muted text-center mt-4">✅ Nenhum evento crítico recente</p>
                <?php else: ?>
                    <?php foreach ($criticos as $ev): ?>
                    <div class="d-flex align-items-start mb-3 pb-2 border-bottom">
                        <div>
                            <span class="severity-badge sev-<?= strtolower($ev['severidade']) ?>"><?= $ev['severidade'] ?></span>
                            <strong class="ms-2"><?= htmlspecialchars($ev['evento']) ?></strong>
                            <br>
                            <small class="text-muted">
                                <?= date('d/m H:i', strtotime($ev['created_at'])) ?> 
                                | IP: <?= htmlspecialchars($ev['ip_address'] ?? 'N/A') ?>
                                <?= $ev['user_nome'] ? ' | ' . htmlspecialchars($ev['user_nome']) : '' ?>
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top IPs + Resumo 7d/30d -->
<div class="row row-sm mb-4">
    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">🌐 Top 10 IPs Mais Ativos (24h)</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead><tr><th>IP</th><th>Eventos</th><th>Alertas</th><th>Último</th></tr></thead>
                    <tbody>
                    <?php foreach ($topIPs as $ip): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($ip['ip_address']) ?></code></td>
                            <td><?= (int)$ip['total_eventos'] ?></td>
                            <td>
                                <?php if ((int)$ip['alertas'] > 0): ?>
                                    <span class="badge bg-danger"><?= (int)$ip['alertas'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success">0</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= date('d/m H:i', strtotime($ip['ultimo_evento'])) ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($topIPs)): ?>
                        <tr><td colspan="4" class="text-center text-muted">Sem dados</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">📈 Resumo por Período</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Métrica</th><th>24h</th><th>7 dias</th><th>30 dias</th></tr></thead>
                    <tbody>
                        <tr><td>Total de Eventos</td><td><?= (int)($stats24h['total_eventos'] ?? 0) ?></td><td><?= (int)($stats7d['total_eventos'] ?? 0) ?></td><td><?= (int)($stats30d['total_eventos'] ?? 0) ?></td></tr>
                        <tr><td>Logins OK</td><td><?= (int)($stats24h['logins_sucesso'] ?? 0) ?></td><td><?= (int)($stats7d['logins_sucesso'] ?? 0) ?></td><td><?= (int)($stats30d['logins_sucesso'] ?? 0) ?></td></tr>
                        <tr><td>Logins Falhos</td><td><?= (int)($stats24h['logins_falha'] ?? 0) ?></td><td><?= (int)($stats7d['logins_falha'] ?? 0) ?></td><td><?= (int)($stats30d['logins_falha'] ?? 0) ?></td></tr>
                        <tr><td>IPs Bloqueados</td><td><?= (int)($stats24h['logins_bloqueados'] ?? 0) ?></td><td><?= (int)($stats7d['logins_bloqueados'] ?? 0) ?></td><td><?= (int)($stats30d['logins_bloqueados'] ?? 0) ?></td></tr>
                        <tr><td>Bots</td><td><?= (int)($stats24h['bots_detectados'] ?? 0) ?></td><td><?= (int)($stats7d['bots_detectados'] ?? 0) ?></td><td><?= (int)($stats30d['bots_detectados'] ?? 0) ?></td></tr>
                        <tr><td>IPs Únicos</td><td><?= (int)($stats24h['ips_unicos'] ?? 0) ?></td><td><?= (int)($stats7d['ips_unicos'] ?? 0) ?></td><td><?= (int)($stats30d['ips_unicos'] ?? 0) ?></td></tr>
                        <tr><td>Usuários Únicos</td><td><?= (int)($stats24h['usuarios_unicos'] ?? 0) ?></td><td><?= (int)($stats7d['usuarios_unicos'] ?? 0) ?></td><td><?= (int)($stats30d['usuarios_unicos'] ?? 0) ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Últimos Eventos (Log Completo) -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title mb-0">📋 Últimos 50 Eventos</h6>
                <div>
                    <select id="filtroSeveridade" class="form-select form-select-sm d-inline-block" style="width: auto;">
                        <option value="">Todas</option>
                        <option value="INFO">INFO</option>
                        <option value="WARNING">WARNING</option>
                        <option value="ERROR">ERROR</option>
                        <option value="CRITICAL">CRITICAL</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-sm table-hover" id="tabelaEventos">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Severidade</th>
                            <th>Evento</th>
                            <th>Categoria</th>
                            <th>Usuário</th>
                            <th>IP</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ultimosEventos as $ev): ?>
                        <tr data-sev="<?= $ev['severidade'] ?>">
                            <td><small><?= date('d/m/Y H:i:s', strtotime($ev['created_at'])) ?></small></td>
                            <td><span class="severity-badge sev-<?= strtolower($ev['severidade']) ?>"><?= $ev['severidade'] ?></span></td>
                            <td><strong><?= htmlspecialchars($ev['evento']) ?></strong></td>
                            <td><small><?= htmlspecialchars($ev['categoria']) ?></small></td>
                            <td><small><?= htmlspecialchars($ev['user_nome'] ?? 'N/A') ?></small></td>
                            <td><code style="font-size:11px"><?= htmlspecialchars($ev['ip_address'] ?? 'N/A') ?></code></td>
                            <td>
                                <?php if ($ev['detalhes']): ?>
                                    <small class="text-muted" title="<?= htmlspecialchars($ev['detalhes']) ?>">
                                        <?= htmlspecialchars(mb_substr($ev['detalhes'], 0, 60)) ?><?= mb_strlen($ev['detalhes']) > 60 ? '...' : '' ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
// Gráfico de Atividade (24h)
const ctx = document.getElementById('chartAtividade');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($horasLabels) ?>,
            datasets: [
                {
                    label: 'Total de Eventos',
                    data: <?= json_encode($horasData) ?>,
                    backgroundColor: 'rgba(52, 152, 219, 0.6)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Alertas',
                    data: <?= json_encode($horasAlertas) ?>,
                    backgroundColor: 'rgba(231, 76, 60, 0.6)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}

// Filtro de severidade
document.getElementById('filtroSeveridade')?.addEventListener('change', function() {
    const val = this.value;
    document.querySelectorAll('#tabelaEventos tbody tr').forEach(tr => {
        tr.style.display = (!val || tr.dataset.sev === val) ? '' : 'none';
    });
});
</script>
