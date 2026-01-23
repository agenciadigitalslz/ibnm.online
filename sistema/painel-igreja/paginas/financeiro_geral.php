<?php
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'financeiro_geral';

// Datas (padrão: mês atual)
$hoje = date('Y-m-d');
$mes_atual = date('m');
$ano_atual = date('Y');
$default_ini = $ano_atual.'-'.$mes_atual.'-01';
$default_fim = date('Y-m-t', strtotime($default_ini));

$dataInicial = isset($_GET['dataInicial']) && $_GET['dataInicial'] !== '' ? $_GET['dataInicial'] : $default_ini;
$dataFinal   = isset($_GET['dataFinal'])   && $_GET['dataFinal']   !== '' ? $_GET['dataFinal']   : $default_fim;

$id_igreja = $_SESSION['id_igreja'];
$id_usuario = $_SESSION['id'];
$mostrar_registros = $_SESSION['registros'] ?? 'Sim';
$usuario_lanc_filtro = isset($_GET['usuario_lanc']) && $_GET['usuario_lanc'] !== '' ? preg_replace('/\D/','', $_GET['usuario_lanc']) : '';
$sql_usuario_lanc = ($mostrar_registros == 'Não') ? " AND usuario_lanc = '$id_usuario'" : '';
if($usuario_lanc_filtro !== ''){ $sql_usuario_lanc .= " AND usuario_lanc = '".$usuario_lanc_filtro."'"; }

// Condição parametrizada para os somatórios
$cond_user_param = '';
$params_user = [];
if($mostrar_registros == 'Não') { $cond_user_param .= ' AND usuario_lanc = ?'; $params_user[] = $id_usuario; }
if($usuario_lanc_filtro !== '') { $cond_user_param .= ' AND usuario_lanc = ?'; $params_user[] = $usuario_lanc_filtro; }

function sum_query(PDO $pdo, string $sql, array $params): float {
  $stm = $pdo->prepare($sql);
  foreach ($params as $k => $v) { $stm->bindValue(is_int($k)? $k+1 : $k, $v); }
  $stm->execute();
  $val = (float)$stm->fetchColumn();
  return $val ?: 0.0;
}

// Recebimentos (receber)
$rc_pendentes = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_vencidas  = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento < CURDATE() AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_hoje      = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento = CURDATE() AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_amanha    = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Não' AND vencimento = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_recebidas = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND pago = 'Sim' AND data_pgto BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$rc_total     = sum_query($pdo, "SELECT SUM(valor) FROM receber WHERE igreja = ? AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));

// Pagamentos (pagar)
$pg_pendentes = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_vencidas  = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento < CURDATE() AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_hoje      = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento = CURDATE() AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_amanha    = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Não' AND vencimento = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_pagadas   = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND pago = 'Sim' AND data_pgto BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));
$pg_total     = sum_query($pdo, "SELECT SUM(valor) FROM pagar WHERE igreja = ? AND vencimento BETWEEN ? AND ?$cond_user_param", array_merge([$id_igreja, $dataInicial, $dataFinal], $params_user));

// Balanço do período
$entradas_periodo = $rc_recebidas;
$saidas_periodo   = $pg_pagadas;
$saldo_periodo    = $entradas_periodo - $saidas_periodo;

function moeda($v){ return 'R$ '.number_format($v, 2, ',', '.'); }
?>

<div class="justify-content-between">
  <div class="left-content mt-2 mb-3" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap">
    <a class="btn btn-success text-white" href="receber"><i class="fe fe-plus me-2"></i>Recebimentos</a>
    <a class="btn btn-danger text-white" href="pagar"><i class="fe fe-plus me-2"></i>Despesas</a>
    <form method="get" action="#" onsubmit="return false" style="display:flex; align-items:center; gap:6px; margin:0">
      <input type="date" id="dataInicial" name="dataInicial" value="<?=htmlspecialchars($dataInicial)?>" style="height:35px; font-size:13px"> 
      <input type="date" id="dataFinal"   name="dataFinal"   value="<?=htmlspecialchars($dataFinal)?>"   style="height:35px; font-size:13px"> 
      <select name="usuario_lanc" id="usuario_lanc" style="height:35px; font-size:13px">
        <option value="">Todos os Tesoureiros</option>
        <?php
        $qU = $pdo->prepare("SELECT id, nome FROM usuarios WHERE igreja = ? AND nivel IN ('Tesoureiro','Pastor','Secretário','Administrador') ORDER BY nome");
        $qU->execute([$id_igreja]);
        while($u = $qU->fetch(PDO::FETCH_ASSOC)){
          $sel = (isset($_GET['usuario_lanc']) && $_GET['usuario_lanc'] == $u['id']) ? 'selected' : '';
          echo '<option value="'.$u['id'].'" '.$sel.'>'.htmlspecialchars($u['nome']).'</option>';
        }
        ?>
      </select>
    </form>
  </div>
</div>

<style>
.fin-cards{display:flex;flex-wrap:wrap;gap:10px}
.fin-card{flex:1 1 180px;min-width:160px;max-width:calc(20% - 10px);border-radius:10px}
.fin-card .card-body h5{font-size:1.25rem;word-break:break-word;white-space:normal;overflow-wrap:anywhere}
@media(max-width:1200px){.fin-card{max-width:calc(25% - 10px)}}
@media(max-width:992px){.fin-card{max-width:calc(33.33% - 10px)}}
@media(max-width:768px){.fin-card{max-width:calc(50% - 10px)}}
@media(max-width:480px){.fin-card{max-width:100%}}
</style>
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
      <div class="card-body" id="listar"></div>
    </div>
  </div>
</div>


<script>
  function carregarFinanceiroGeral(){
    var dataInicial = document.getElementById('dataInicial').value;
    var dataFinal   = document.getElementById('dataFinal').value;
    var usuario     = document.getElementById('usuario_lanc').value;

    $.ajax({
      url: 'paginas/financeiro_geral/listar.php',
      method: 'POST',
      data: { dataInicial: dataInicial, dataFinal: dataFinal, usuario_lanc: usuario },
      dataType: 'html',
      success: function(html){
        if ($.fn.DataTable.isDataTable('#tabela')) {
          $('#tabela').DataTable().destroy();
        }
        $('#listar').html(html);
        $('#tabela').DataTable({ ordering:false, stateSave:true });
      }
    });
  }

  $(document).ready(function(){
    // garante container
    if($('#listar').length === 0){
      $('<div id="listar"></div>').insertAfter($('.justify-content-between').first());
    }
    // primeira carga com pequeno atraso para garantir DOM pronto
    setTimeout(carregarFinanceiroGeral, 0);

    // listeners para tempo real (debounce leve)
    let t;
    $('#dataInicial, #dataFinal, #usuario_lanc').on('change input', function(){
      clearTimeout(t);
      t = setTimeout(carregarFinanceiroGeral, 100);
    });
  });
</script>
