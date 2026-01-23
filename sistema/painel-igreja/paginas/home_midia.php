<?php 
require_once("verificar.php");
$pag = 'home_midia';

// Apenas Pastor, Bispo ou Midia podem ver esta página
if(@$_SESSION['nivel'] != 'Pastor' && @$_SESSION['nivel'] != 'Bispo' && @$_SESSION['nivel'] != 'Midia'){
	echo "<script>window.location='index.php'</script>";
	exit();
}

// KPIs focados em mídia/conteúdo
$qtde_eventos = $pdo->query("SELECT COUNT(*) AS total FROM eventos WHERE igreja = '$id_igreja' AND ativo = 'Sim'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$qtde_informativos = $pdo->query("SELECT COUNT(*) AS total FROM informativos WHERE igreja = '$id_igreja'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$qtde_cultos = $pdo->query("SELECT COUNT(*) AS total FROM cultos WHERE igreja = '$id_igreja'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$qtde_alertas = $pdo->query("SELECT COUNT(*) AS total FROM alertas WHERE igreja = '$id_igreja' AND ativo = 'Sim'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Próximos itens
$prox_eventos = $pdo->query("SELECT id,titulo,data_evento,imagem FROM eventos WHERE igreja = '$id_igreja' AND data_evento >= CURDATE() ORDER BY data_evento ASC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
$prox_informativos = $pdo->query("SELECT id,evento,data,tema FROM informativos WHERE igreja = '$id_igreja' AND data >= CURDATE() ORDER BY data ASC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row mb-2 m-2">
  <div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
    <div style="padding:6px; box-shadow: 2px 2px 0px rgba(0,0,0,.1)">
      <p class="mb-1">Eventos Ativos</p>
      <h5 class="mb-1"><?php echo $qtde_eventos ?></h5>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
    <div style="padding:6px; box-shadow: 2px 2px 0px rgba(0,0,0,.1)">
      <p class="mb-1">Informativos</p>
      <h5 class="mb-1"><?php echo $qtde_informativos ?></h5>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
    <div style="padding:6px; box-shadow: 2px 2px 0px rgba(0,0,0,.1)">
      <p class="mb-1">Cultos</p>
      <h5 class="mb-1"><?php echo $qtde_cultos ?></h5>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
    <div style="padding:6px; box-shadow: 2px 2px 0px rgba(0,0,0,.1)">
      <p class="mb-1">Alertas Ativos</p>
      <h5 class="mb-1"><?php echo $qtde_alertas ?></h5>
    </div>
  </div>
</div>

<div class="row m-2">
  <div class="col-xl-6 col-lg-6" style="padding:5px">
    <div class="card custom-card">
      <div class="card-header">
        <h5 class="card-title">Próximos Eventos</h5>
      </div>
      <div class="card-body">
        <?php if(count($prox_eventos) == 0){ echo '<small>Nenhum evento futuro.</small>'; } ?>
        <div class="row">
          <?php foreach($prox_eventos as $ev){
            $dataF = implode('/', array_reverse(explode('-', $ev['data_evento'])));
            $img = $ev['imagem'] ? '../img/eventos/'.$ev['imagem'] : '../img/sem-foto.png';
          ?>
          <div class="col-md-6 mb-3">
            <div style="border:1px solid #eee; padding:8px">
              <img src="<?php echo $img ?>" alt="<?php echo htmlspecialchars($ev['titulo']) ?>" style="width:100%; height:140px; object-fit:cover;">
              <div class="mt-2">
                <div class="tx-13 fw-bold"><?php echo $ev['titulo'] ?></div>
                <small><?php echo $dataF ?></small>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-lg-6" style="padding:5px">
    <div class="card custom-card">
      <div class="card-header">
        <h5 class="card-title">Informativos Agendados</h5>
      </div>
      <div class="card-body">
        <?php if(count($prox_informativos) == 0){ echo '<small>Nenhum informativo futuro.</small>'; } ?>
        <ul class="list-unstyled mb-0">
          <?php foreach($prox_informativos as $inf){
            $dataF = implode('/', array_reverse(explode('-', $inf['data'])));
          ?>
          <li class="mb-2">
            <i class="fa fa-calendar text-primary"></i>
            <span class="ms-2"><?php echo $dataF ?> - <?php echo $inf['evento'] ?> (<?php echo $inf['tema'] ?>)</span>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
