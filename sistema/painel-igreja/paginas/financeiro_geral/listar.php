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
?>

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
