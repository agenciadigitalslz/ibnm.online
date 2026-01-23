<?php 
$pag = 'home';

$membrosSede = 0;
$membrosCadastrados = 0;
$pastoresCadastrados = 0;
$igrejasCadastradas = 0;

$query = $pdo->query("SELECT * FROM igrejas");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$igrejasCadastradas = @count($res);

$query = $pdo->query("SELECT * FROM pastores");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pastoresCadastrados = @count($res);

$query_m = $pdo->query("SELECT * FROM usuarios where igreja = 0 and ativo = 'Sim'");
$res_m = $query_m->fetchAll(PDO::FETCH_ASSOC);
$usuarios_administrativos = @count($res_m);

$query_m = $pdo->query("SELECT * FROM membros where ativo = 'Sim'");
$res_m = $query_m->fetchAll(PDO::FETCH_ASSOC);
$membrosCadastrados = @count($res_m);


//VERIFICAR DATA PARA EXCLUSÃO DE LOGS
$data_atual = date('Y-m-d');
$data_limpeza = date('Y-m-d', strtotime("-$dias_excluir_logs days",strtotime($data_atual)));
$pdo->query("DELETE FROM logs where data < '$data_limpeza'");
?>


<div class="container-fluid " >
	
	<section id="minimal-statistics" class="container my-4">
 

  <div class="row g-3">
    <!-- Igrejas -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
      <a href="igrejas" class="text-decoration-none">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="fa fa-building fa-2x text-success mb-2"></i>
            <p class="fw-semibold text-muted mb-1">Igrejas Cadastradas</p>
            <h5 class="text-success"><?php echo $igrejasCadastradas ?></h5>
          </div>
        </div>
      </a>
    </div>

    <!-- Pastores -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
      <a href="pastores" class="text-decoration-none">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="fa fa-user fa-2x text-primary mb-2"></i>
            <p class="fw-semibold text-muted mb-1">Pastores Cadastrados</p>
            <h5 class="text-primary"><?php echo $pastoresCadastrados ?></h5>
          </div>
        </div>
      </a>
    </div>

    <!-- Usuários -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
      <a href="usuarios" class="text-decoration-none">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="fa fa-user fa-2x text-info mb-2"></i>
            <p class="fw-semibold text-muted mb-1">Usuários Administrativos</p>
            <h5 class="text-info"><?php echo $usuarios_administrativos ?></h5>
          </div>
        </div>
      </a>
    </div>

    <!-- Membros -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
      <a href="#" class="text-decoration-none">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="fa fa-users fa-2x text-danger mb-2"></i>
            <p class="fw-semibold text-muted mb-1">Total de Membros</p>
            <h5 class="text-danger"><?php echo $membrosCadastrados ?></h5>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>


<div class="text-uppercase fw-bold text-dark mt-4 mb-3 fs-6">Igrejas Cadastradas</div>

<div class="row g-3 me-2">
  <?php 
    $query = $pdo->query("SELECT * FROM igrejas ORDER BY matriz DESC, nome ASC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $igreja) {
      $nome = $igreja['nome'];
      $imagem = $igreja['imagem'];
      $matriz = $igreja['matriz'];
      $pastor = $igreja['pastor'];
      $id_ig = $igreja['id'];

      $query_m = $pdo->query("SELECT * FROM membros WHERE igreja = '$id_ig' AND ativo = 'Sim'");
      $res_m = $query_m->fetchAll(PDO::FETCH_ASSOC);
      $membrosCad = @count($res_m);

      $query_con = $pdo->query("SELECT * FROM pastores WHERE id = '$pastor'");
      $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
      $nome_p = $res_con[0]['nome'] ?? 'Não Definido';
  ?>

  <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
    <a href="../painel-igreja/index.php?igreja=<?php echo $id_ig ?>" class="text-decoration-none">
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="me-2">
              <h6 class="text-primary fw-bold mb-1"><?php echo $nome ?></h6>
              <small class="text-muted"><?php echo mb_strtoupper($nome_p) ?></small>
            </div>
            <div class="text-end">
              <img src="../img/igrejas/<?php echo $imagem ?>" class="rounded-circle img-fluid mb-1" style="width:80px; height:80px; object-fit:cover;">
              <div class="text-muted small">
                <i class="fa fa-user me-1 text-secondary"></i>
                <?php echo @$membrosCad ?> membros
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <?php } ?>
</div>



</div>



<script type="text/javascript">
	// GRAFICO DE BARRAS
function statistics1() {
	var total_pagar = "<?=$total_meses_pagar_grafico?>";
	var total_receber = "<?=$total_meses_receber_grafico?>";

	var split_pagar = total_pagar.split("-");
	var split_receber = total_receber.split("-");


	setTimeout(()=>{
		var options1 = {
			series: [{
				name: 'Contas à Receber',
				data: [split_receber[0], split_receber[1], split_receber[2], split_receber[3], split_receber[4], split_receber[5], split_receber[6], split_receber[7], split_receber[8], split_receber[9], split_receber[10], split_receber[11]],
			},{
				name: 'Contas à Pagar',
				data: [split_pagar[0], split_pagar[1], split_pagar[2], split_pagar[3], split_pagar[4], split_pagar[5], split_pagar[6], split_pagar[7], split_pagar[8], split_pagar[9], split_pagar[10], split_pagar[11]],
			}],
			chart: {
				type: 'bar',
				height: 280
			},
			grid: {
				borderColor: '#f2f6f7',
			},
			colors: ["#098522","#bf281d"],
			plotOptions: {
				bar: {
					colors: {
						ranges: [{
							from: -100,
							to: -46,
							color: '#098522'
						}, {
							from: -45,
							to: 0,
							color: '#bf281d'
			}]
			},
			columnWidth: '40%',
		}
	},
	dataLabels: {
		enabled: false,
	},
	stroke: {
		show: true,
		width: 4,
		colors: ['transparent']
	},
	legend: {
		show: true,
		position:'top',
	},
	yaxis: {
		title: {
			text: 'Valores',
			style: {
				color: '#adb5be',
				fontSize: '14px',
				fontFamily: 'poppins, sans-serif',
				fontWeight: 600,
				cssClass: 'apexcharts-yaxis-label',
			},
		},
		labels: {
			formatter: function (y) {
				return y.toFixed(0) + "";
			}
		}
	},
	xaxis: {
		type: 'month',
		categories: ['Jan','Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
		axisBorder: {
			show: true,
			color: 'rgba(119, 119, 142, 0.05)',
			offsetX: 0,
			offsetY: 0,
		},
		axisTicks: {
			show: true,
			borderType: 'solid',
			color: 'rgba(119, 119, 142, 0.05)',
			width: 6,
			offsetX: 0,
			offsetY: 0
		},
		labels: {
			rotate: -90
		}
	}
		};
		document.getElementById('statistics1').innerHTML = ''; 
		var chart1 = new ApexCharts(document.querySelector("#statistics1"), options1);
		chart1.render();
	}, 300);
}

</script>