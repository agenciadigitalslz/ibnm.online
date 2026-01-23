<?php
@session_start();
require_once("../../../conexao.php");
require_once("../../verificar.php");

$id_igreja = $_SESSION['id_igreja'];
$id_usuario = $_SESSION['id'];
$mostrar_registros = $_SESSION['registros'] ?? 'Sim';

function moeda($v){ return 'R$ '.number_format((float)$v, 2, ',', '.'); }

$dataInicial = isset($_POST['dataInicial']) && $_POST['dataInicial'] != '' ? $_POST['dataInicial'] : date('Y-m-01');
$dataFinal   = isset($_POST['dataFinal'])   && $_POST['dataFinal']   != '' ? $_POST['dataFinal']   : date('Y-m-t');
$usuario_lanc = isset($_POST['usuario_lanc']) && $_POST['usuario_lanc'] !== '' ? preg_replace('/\D/','', $_POST['usuario_lanc']) : '';

$cond_user = '';
$params_user = [];
if($mostrar_registros == 'Não'){ $cond_user .= ' AND usuario_lanc = ?'; $params_user[] = $id_usuario; }
if($usuario_lanc !== ''){ $cond_user .= ' AND usuario_lanc = ?'; $params_user[] = $usuario_lanc; }

function sum_query(PDO $pdo, string $sql, array $params): float {
  $stm = $pdo->prepare($sql);
  $stm->execute($params);
  $val = (float)$stm->fetchColumn();
  return $val ?: 0.0;
}

$rc_pendentes = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_vencidas  = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento < CURDATE() AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_hoje      = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento = CURDATE() AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_amanha    = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_recebidas = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Sim' AND data_pgto BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_total     = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));

$pg_pendentes = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_vencidas  = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento < CURDATE() AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_hoje      = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento = CURDATE() AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_amanha    = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_pagadas   = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Sim' AND data_pgto BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_total     = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND vencimento BETWEEN ? AND ?$cond_user", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));

$entradas_periodo = $rc_recebidas;
$saidas_periodo   = $pg_pagadas;
$saldo_periodo    = $entradas_periodo - $saidas_periodo;
?>

<h5 class="mt-2 mb-2">Recebimentos</h5>
<div class="fin-cards" style="margin-bottom:10px">
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#b31d33">Pendentes</div>
    <div class="card-body"><h5><?=moeda($rc_pendentes)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#de5b1a">Vence Hoje</div>
    <div class="card-body"><h5><?=moeda($rc_hoje)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:gray">Vence Amanhã</div>
    <div class="card-body"><h5><?=moeda($rc_amanha)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#2b7a00">Recebidas</div>
    <div class="card-body"><h5><?=moeda($rc_recebidas)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#08688c">Total</div>
    <div class="card-body"><h5><?=moeda($rc_total)?></h5></div>
  </div>
</div>

<h5 class="mt-3 mb-2">Pagamentos</h5>
<div class="fin-cards" style="margin-bottom:10px">
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#b31d33">Pendentes</div>
    <div class="card-body"><h5><?=moeda($pg_pendentes)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#de5b1a">Vence Hoje</div>
    <div class="card-body"><h5><?=moeda($pg_hoje)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:gray">Vence Amanhã</div>
    <div class="card-body"><h5><?=moeda($pg_amanha)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#2b7a00">Pagas</div>
    <div class="card-body"><h5><?=moeda($pg_pagadas)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#1f1f1f">Total</div>
    <div class="card-body"><h5><?=moeda($pg_total)?></h5></div>
  </div>
</div>

<h5 class="mt-3 mb-2">Balanço atual</h5>
<div class="fin-cards" style="margin-bottom:10px">
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#098522">Entradas no Período</div>
    <div class="card-body"><h5><?=moeda($entradas_periodo)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#bf281d">Saídas no Período</div>
    <div class="card-body"><h5><?=moeda($saidas_periodo)?></h5></div>
  </div>
  <div class="card text-center mb-3 fin-card">
    <div class="card-header border-light text-white" style="background:#283593">Saldo no Período</div>
    <div class="card-body"><h5><?=moeda($saldo_periodo)?></h5></div>
  </div>
</div>

<div class="row row-sm">
  <div class="col-lg-12">
    <div class="card custom-card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Data Pgto</th>
              </tr>
            </thead>
            <tbody>
<?php
  $extra = '';
  $params = [$id_igreja, $dataInicial, $dataFinal];
  if($mostrar_registros == 'Não'){ $extra .= ' AND usuario_lanc = ?'; $params[] = $id_usuario; }
  if($usuario_lanc !== ''){ $extra .= ' AND usuario_lanc = ?'; $params[] = $usuario_lanc; }

  $stm = $pdo->prepare(
    "SELECT 'Recebimento' AS tipo, descricao, valor, vencimento, pago, data_pgto
       FROM receber
      WHERE igreja = ? AND vencimento BETWEEN ? AND ? $extra
     UNION ALL
     SELECT 'Despesa' AS tipo, descricao, valor, vencimento, pago, data_pgto
       FROM pagar
      WHERE igreja = ? AND vencimento BETWEEN ? AND ? $extra
     ORDER BY vencimento DESC"
  );
  $params = array_merge($params, [$id_igreja, $dataInicial, $dataFinal]);
  if($mostrar_registros == 'Não'){ $params[] = $id_usuario; }
  if($usuario_lanc !== ''){ $params[] = $usuario_lanc; }
  $stm->execute($params);
  while($row = $stm->fetch(PDO::FETCH_ASSOC)){
    $status = $row['pago'];
    $vencF = implode('/', array_reverse(explode('-', $row['vencimento'])));
    $pgtoF = $row['data_pgto'] ? implode('/', array_reverse(explode('-', $row['data_pgto']))) : '';
    echo '<tr>';
    echo '<td>'.htmlspecialchars($row['tipo']).'</td>';
    echo '<td>'.htmlspecialchars($row['descricao']).'</td>';
    echo '<td>'.moeda((float)$row['valor']).'</td>';
    echo '<td>'.$vencF.'</td>';
    echo '<td>'.$status.'</td>';
    echo '<td>'.$pgtoF.'</td>';
    echo '</tr>';
  }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
