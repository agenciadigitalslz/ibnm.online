<?php 
require_once("verificar.php");
$pag = 'home';

if($mostrar_registros == 'Não'){
	$sql_usuario = " and usuario = '$id_usuario '";
	$sql_usuario_lanc = " and usuario_lanc = '$id_usuario '";
}else{
	$sql_usuario = " ";
	$sql_usuario_lanc = " ";
}


$gruposCadastrados = 0;
$celulasCadastradas = 0;


$total_ofertas = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM ofertas WHERE igreja = '$id_igreja' AND data >= '$data_inicio_mes' AND data <= '$data_final_mes'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_ofertasF = @number_format($total_ofertas, 2, ',', '.');


$total_ofertas_hoje = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM ofertas WHERE igreja = '$id_igreja' AND data = curDate()")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_ofertas_hojeF = @number_format($total_ofertas_hoje, 2, ',', '.');

$totalGastos = 0;


$total_vendas = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM vendas WHERE igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_vendasF = @number_format($total_vendas, 2, ',', '.');




//vendas mes
$total_vendas_mes = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM vendas WHERE igreja = '$id_igreja' AND data >= '$data_inicio_mes' AND data <= '$data_final_mes'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_vendas_mesF = @number_format($total_vendas_mes, 2, ',', '.');



//vendas dia
$total_vendas_dia = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM vendas WHERE igreja = '$id_igreja' AND data = CurDate()")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_vendas_diaF = @number_format($total_vendas_dia, 2, ',', '.');



$totalDoacoes = 0;


$total_dizimos = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM dizimos WHERE igreja = '$id_igreja' AND data >= '$data_inicio_mes' AND data <= '$data_final_mes'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);


$total_dizimos_hoje = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM dizimos WHERE igreja = '$id_igreja' AND data = curDate()")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);


$total_dizimosF = @number_format($total_dizimos, 2, ',', '.');
$total_dizimos_hojeF = @number_format($total_dizimos_hoje, 2, ',', '.');

$totalSaldoInicio = 0;

$dataInicioMes = $ano_atual."-".$mes_atual."-01";

$gruposCadastrados = (int) ($pdo->query("SELECT COUNT(*) AS cnt FROM grupos WHERE igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$celulasCadastradas = (int) ($pdo->query("SELECT COUNT(*) AS cnt FROM celulas WHERE igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$patCadastrados = (int) ($pdo->query("SELECT COUNT(*) AS cnt FROM patrimonios WHERE igreja_cad = '$id_igreja' AND ativo = 'Sim'")
    ->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);


//total membros

$total_membros = (int) ($pdo->query("SELECT COUNT(*) AS cnt FROM membros WHERE igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

//total membros mes
$total_membros_mes = (int) ($pdo->query("SELECT COUNT(*) AS cnt FROM membros WHERE data_cad >= '$data_inicio_mes' AND data_cad <= '$data_final_mes' $sql_usuario AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$rowPV = $pdo->query("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM pagar WHERE vencimento < curDate() AND pago != 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = (int) ($rowPV['cnt'] ?? 0);
$total_pagar_vencidas = (float) ($rowPV['total'] ?? 0);
$total_pagar_vencidasF = @number_format($total_pagar_vencidas, 2, ',', '.');



//total pagar hoje (itens em aberto)
$rowPH = $pdo->query("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM pagar WHERE pago != 'Sim' AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC);
$contas_pagar_hoje = (int) ($rowPH['cnt'] ?? 0);
$total_pagar_hoje = (float) ($rowPH['total'] ?? 0);
$total_pagar_hojeF = @number_format($total_pagar_hoje, 2, ',', '.');	



$rowRV = $pdo->query("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM receber WHERE vencimento < curDate() AND pago != 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC);
$contas_receber_vencidas = (int) ($rowRV['cnt'] ?? 0);
$total_receber_vencidas = (float) ($rowRV['total'] ?? 0);
$total_receber_vencidasF = @number_format($total_receber_vencidas, 2, ',', '.');


//total recebidas mes
$total_recebidas_mes = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM receber WHERE data_pgto >= '$data_inicio_mes' AND data_pgto <= '$data_final_mes' AND pago = 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_recebidas_mesF = @number_format($total_recebidas_mes, 2, ',', '.');

//total contas pagas mes
$total_pagas_mes = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM pagar WHERE data_pgto >= '$data_inicio_mes' AND data_pgto <= '$data_final_mes' AND pago = 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_pagas_mesF = @number_format($total_pagas_mes, 2, ',', '.');


$total_saldo_mes = $total_recebidas_mes - $total_pagas_mes;
$total_saldo_mesF = @number_format($total_saldo_mes, 2, ',', '.');
if($total_saldo_mes >= 0){
	$classe_saldo = 'text-success';
}else{
	$classe_saldo = 'text-danger';
}





//total recebidas dia
$total_recebidas_dia = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM receber WHERE data_pgto = curDate() AND pago = 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_recebidas_diaF = @number_format($total_recebidas_dia, 2, ',', '.');

//total contas pagas dia
$total_pagas_dia = (float) ($pdo->query("SELECT COALESCE(SUM(valor),0) AS total FROM pagar WHERE data_pgto = curDate() AND pago = 'Sim' $sql_usuario_lanc AND igreja = '$id_igreja'")
    ->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
$total_pagas_diaF = @number_format($total_pagas_dia, 2, ',', '.');


$total_saldo_dia = $total_recebidas_dia - $total_pagas_dia;
$total_saldo_diaF = @number_format($total_saldo_dia, 2, ',', '.');
if($total_saldo_dia >= 0){
	$classe_saldo_dia = 'bg-success';
}else{
	$classe_saldo_dia = 'bg-danger';
}




//valores para o grafico de linhas (ano atual, despesas e recebimentos)
$total_meses_pagar_grafico = '';
$total_meses_receber_grafico = '';

for ($i=1; $i <= 12; $i++) { 
	if($i < 10){
		$mes_verificado = '0'.$i;
	}else{
		$mes_verificado = $i;
	}


	$data_inicio_mes_grafico = $ano_atual."-".$mes_verificado."-01";

	if($mes_verificado == '04' || $mes_verificado == '06' || $mes_verificado == '09' || $mes_verificado == '11'){
		$data_final_mes_grafico = $ano_atual.'-'.$mes_verificado.'-30';
	}else if($mes_verificado == '02'){
		$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $mes_verificado));
		if($bissexto == 1){
			$data_final_mes_grafico = $ano_atual.'-'.$mes_verificado.'-29';
		}else{
			$data_final_mes_grafico = $ano_atual.'-'.$mes_verificado.'-28';
		}

	}else{
		$data_final_mes_grafico = $ano_atual.'-'.$mes_verificado.'-31';
	}




	//total recebidas mes
	$total_recebidas_mes_grafico = 0;
	$query2 = $pdo->query("SELECT * from receber where data_pgto >= '$data_inicio_mes_grafico' and data_pgto <= '$data_final_mes_grafico' and pago = 'Sim' $sql_usuario_lanc and igreja = '$id_igreja'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$contas_recebidas_mes_grafico = @count($res2);
	for($i2=0; $i2<$contas_recebidas_mes_grafico; $i2++){
		$valor_3 = $res2[$i2]['valor'];	
		$total_recebidas_mes_grafico += $valor_3;
	}


//total contas pagas mes
	$total_pagas_mes_grafico = 0;
	$query2 = $pdo->query("SELECT * from pagar where data_pgto >= '$data_inicio_mes_grafico' and data_pgto <= '$data_final_mes_grafico' and pago = 'Sim' $sql_usuario_lanc and igreja = '$id_igreja'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$contas_pagas_mes_grafico = @count($res2);
	for($i2=0; $i2<$contas_pagas_mes_grafico; $i2++){
		$valor_4 = $res2[$i2]['valor'];	
		$total_pagas_mes_grafico += $valor_4;
	}


	$total_meses_pagar_grafico .= $total_pagas_mes_grafico.'-';
	$total_meses_receber_grafico .= $total_recebidas_mes_grafico.'-';
	
}



//tarefas
$query = $pdo->query("SELECT * from tarefas where usuario = '$id_usuario' and status = 'Agendada' and data <= curDate() and hora < curTime() and igreja = '$id_igreja' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tarefas_atrasadas = @count($res);

$query = $pdo->query("SELECT * from tarefas where usuario = '$id_usuario' and status = 'Agendada' and igreja = '$id_igreja' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_tarefas = @count($res);


$query = $pdo->query("SELECT * from tarefas where usuario = '$id_usuario' and status = 'Agendada' and data = curDate() and igreja = '$id_igreja' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tarefas_agendadas_hoje = @count($res);

//verificar se ele tem a permissão de estar nessa página
if(@$home == 'ocultar'){
	echo "<script>window.location='index'</script>";
	exit();
}
?>



<div class="mt-4 justify-content-between">

<?php
// Notificacoes de contas em atraso e pendentes
$tem_notificacao = false;

// Contas a PAGAR vencidas
$rowPagarVenc = $pdo->prepare("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM pagar WHERE vencimento < CURDATE() AND pago != 'Sim' AND igreja = ?");
$rowPagarVenc->execute([$id_igreja]);
$pagarVenc = $rowPagarVenc->fetch(PDO::FETCH_ASSOC);
$qtd_pagar_venc = (int)($pagarVenc['cnt'] ?? 0);
$total_pagar_venc = number_format((float)($pagarVenc['total'] ?? 0), 2, ',', '.');

// Contas a RECEBER vencidas
$rowReceberVenc = $pdo->prepare("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM receber WHERE vencimento < CURDATE() AND pago != 'Sim' AND igreja = ?");
$rowReceberVenc->execute([$id_igreja]);
$receberVenc = $rowReceberVenc->fetch(PDO::FETCH_ASSOC);
$qtd_receber_venc = (int)($receberVenc['cnt'] ?? 0);
$total_receber_venc = number_format((float)($receberVenc['total'] ?? 0), 2, ',', '.');

// Contas que vencem HOJE
$rowPagarHoje = $pdo->prepare("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM pagar WHERE vencimento = CURDATE() AND pago != 'Sim' AND igreja = ?");
$rowPagarHoje->execute([$id_igreja]);
$pagarHoje = $rowPagarHoje->fetch(PDO::FETCH_ASSOC);
$qtd_pagar_hoje = (int)($pagarHoje['cnt'] ?? 0);
$total_pagar_hoje_notif = number_format((float)($pagarHoje['total'] ?? 0), 2, ',', '.');

$rowReceberHoje = $pdo->prepare("SELECT COUNT(*) AS cnt, COALESCE(SUM(valor),0) AS total FROM receber WHERE vencimento = CURDATE() AND pago != 'Sim' AND igreja = ?");
$rowReceberHoje->execute([$id_igreja]);
$receberHoje = $rowReceberHoje->fetch(PDO::FETCH_ASSOC);
$qtd_receber_hoje = (int)($receberHoje['cnt'] ?? 0);
$total_receber_hoje_notif = number_format((float)($receberHoje['total'] ?? 0), 2, ',', '.');

if($qtd_pagar_venc > 0 || $qtd_receber_venc > 0 || $qtd_pagar_hoje > 0 || $qtd_receber_hoje > 0){
	$tem_notificacao = true;
}
?>

<?php if($tem_notificacao): ?>
<div class="row m-2 mb-3">
	<?php if($qtd_pagar_venc > 0): ?>
	<div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2">
		<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="margin-bottom:0">
			<i class="fe fe-alert-triangle me-2" style="font-size:24px"></i>
			<div style="flex:1">
				<strong>Contas a Pagar Vencidas!</strong><br>
				<small><?php echo $qtd_pagar_venc ?> conta(s) vencida(s) totalizando <b>R$ <?php echo $total_pagar_venc ?></b></small>
			</div>
			<a href="pagar" class="btn btn-sm btn-outline-danger ms-2">Ver Contas</a>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<?php endif; ?>

	<?php if($qtd_receber_venc > 0): ?>
	<div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2">
		<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert" style="margin-bottom:0">
			<i class="fe fe-alert-circle me-2" style="font-size:24px"></i>
			<div style="flex:1">
				<strong>Contas a Receber Vencidas!</strong><br>
				<small><?php echo $qtd_receber_venc ?> conta(s) vencida(s) totalizando <b>R$ <?php echo $total_receber_venc ?></b></small>
			</div>
			<a href="receber" class="btn btn-sm btn-outline-warning ms-2">Ver Contas</a>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<?php endif; ?>

	<?php if($qtd_pagar_hoje > 0): ?>
	<div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2">
		<div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert" style="margin-bottom:0">
			<i class="fe fe-clock me-2" style="font-size:24px"></i>
			<div style="flex:1">
				<strong>Contas a Pagar Vencem Hoje!</strong><br>
				<small><?php echo $qtd_pagar_hoje ?> conta(s) vence(m) hoje totalizando <b>R$ <?php echo $total_pagar_hoje_notif ?></b></small>
			</div>
			<a href="pagar" class="btn btn-sm btn-outline-info ms-2">Ver Contas</a>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<?php endif; ?>

	<?php if($qtd_receber_hoje > 0): ?>
	<div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2">
		<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="margin-bottom:0">
			<i class="fe fe-dollar-sign me-2" style="font-size:24px"></i>
			<div style="flex:1">
				<strong>Contas a Receber Vencem Hoje!</strong><br>
				<small><?php echo $qtd_receber_hoje ?> conta(s) vence(m) hoje totalizando <b>R$ <?php echo $total_receber_hoje_notif ?></b></small>
			</div>
			<a href="receber" class="btn btn-sm btn-outline-success ms-2">Ver Contas</a>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>

<div style="margin-top: 30px;">
		
		<div class="row mb-2 m-2">


			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
				<div style="padding:6px;  box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
					<p class=" mb-1">Saldo no Mês</p>
					<h5 class="mb-1 <?php echo $classe_saldo ?>">R$ <?php echo $total_saldo_mesF ?></h5>
					<p class="tx-11 text-muted">Saldo Hoje<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge <?php echo $classe_saldo_dia ?> text-white tx-11">R$ <?php echo $total_saldo_diaF ?></span></span></p>
				</div>
			</div>
			
			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px;  ">
				<a href="pagar">
					<div style="padding:6px;  box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class=" mb-1">Despesas Hoje</p>
						<h5 class="mb-1">R$ <?php echo $total_pagar_hojeF ?></h5>
						<p class="tx-11 text-muted">Pagar<span class="text-warning ms-2"><i class="fa fa-caret-down me-2"></i><span class="badge bg-warning text-white tx-11"><?php echo $contas_pagar_hoje ?></span></span></p>
					</div>
				</a>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px;  ">
				<a href="pagar">
					<div style="padding:6px;  box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class=" mb-1">Despesas Vencidas</p>
						<h5 class="mb-1">R$ <?php echo $total_pagar_vencidasF ?></h5>
						<p class="tx-11 text-muted">Pagar Vencidas<span class="text-danger ms-2"><i class="fa fa-caret-down me-2"></i><span class="badge bg-danger text-white tx-11"><?php echo $contas_pagar_vencidas ?></span></span></p>
					</div>
				</a>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px">
				<a href="receber">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class=" mb-1">Receber Vencidas</p>
						<h5 class="mb-1">R$ <?php echo $total_receber_vencidasF ?></h5>
						<p class="tx-11 text-muted">Receber Vencidas<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11"><?php echo $contas_receber_vencidas ?></span></span></p>
					</div>
				</a>
			</div>
			
			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="membros">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Membros Cadastrados</p>
						<h5 class="mb-1"><?php echo $total_membros ?></h5>
						<p class="tx-11 text-muted">Este Mês<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11"><?php echo $total_membros_mes ?></span></span></p>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="grupos">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Grupos Cadastrados</p>
						<h5 class="mb-1"><?php echo $gruposCadastrados ?></h5>
						<p class="tx-11 text-muted">Este Mês<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11"><?php echo $gruposCadastrados ?></span></span></p>
					</div>
				</a>
			</div>


			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="celulas">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Células Cadastradas</p>
						<h5 class="mb-1"><?php echo $celulasCadastradas ?></h5>
						<p class="tx-11 text-muted">Este Mês<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11"><?php echo $celulasCadastradas ?></span></span></p>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="patrimonios">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Patrimônios / Itens</p>
						<h5 class="mb-1"><?php echo $patCadastrados ?></h5>
						<p class="tx-11 text-muted">Este Mês<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11"><?php echo $patCadastrados ?></span></span></p>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="dizimos">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Dízimos do Mês</p>
						<h5 class="mb-1">R$ <?php echo $total_dizimosF ?></h5>
						<p class="tx-11 text-muted">Hoje<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11">R$ <?php echo $total_dizimos_hojeF ?></span></span></p>
					</div>
				</a>
			</div>


			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="ofertas">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Ofertas do Mês</p>
						<h5 class="mb-1">R$ <?php echo $total_ofertasF ?></h5>
						<p class="tx-11 text-muted">Hoje<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11">R$ <?php echo $total_ofertas_hojeF ?></span></span></p>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="pagar">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Gastos do Dia</p>
						<h5 class="mb-1">R$ <?php echo $total_pagas_diaF ?></h5>
						<p class="tx-11 text-muted">Gastos no Mês<span class="text-danger ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-danger text-white tx-11">R$ <?php echo $total_pagas_mesF ?></span></span></p>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-lg-3 col-md-6 col-6" style="padding:5px; ">
				<a href="vendas">
					<div style="padding:6px; box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);">
						<p class="mb-1">Vendas do Mês</p>
						<h5 class="mb-1">R$ <?php echo $total_vendas_mesF ?></h5>
						<p class="tx-11 text-muted">Hoje<span class="text-success ms-2"><i class="fa fa-caret-up me-2"></i><span class="badge bg-success text-white tx-11">R$ <?php echo $total_vendas_diaF ?></span></span></p>
					</div>
				</a>
			</div>




		</div>
        <!-- <div id="statistics2"></div> -->



		<div class="card custom-card overflow-hidden ocultar_mobile">
			<div class="card-header border-bottom-0">
				<div>
					<h3 class="card-title mb-2 ">Recebimentos / Despesas <?php echo $ano_atual ?></h3> <span class="d-block tx-12 mb-0 text-muted"></span>
				</div>
			</div>
			<div class="card-body">
				<div id="statistics1"></div>
			</div>
		</div>


	</div>	





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
							name: 'Total Recebido',
							data: [split_receber[0], split_receber[1], split_receber[2], split_receber[3], split_receber[4], split_receber[5], split_receber[6], split_receber[7], split_receber[8], split_receber[9], split_receber[10], split_receber[11]],
						},{
							name: 'Total Pago',
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