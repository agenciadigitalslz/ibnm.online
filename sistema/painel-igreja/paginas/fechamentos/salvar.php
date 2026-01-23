<?php

@session_start();
$tabela = 'fechamentos';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id'];

$data_fec = $_POST['data_fec'];
$id_igreja = $_POST['igreja'];




$separar = explode("-", $data_fec);
$mes = $separar[1];
$ano = $separar[0];
$dataInicioMes = $ano."-".$mes."-01";
$dataInicioMesSeguinte = date('Y-m-d', strtotime("+1 month",strtotime($dataInicioMes)));

if($mes == "04" || $mes == "06" || $mes == "09" || $mes == "11"){
	$diaFinalMes = '30';
}else if($mes == "02"){
	$diaFinalMes = '28';
}else{
	$diaFinalMes = '31';
}

$dataFinalMes = $ano."-".$mes."-".$diaFinalMes;


	$query = $pdo->query("SELECT * FROM $tabela where igreja = '$id_igreja' and month(data_fec) = '$mes' and year(data_fec) = '$ano' order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);
	if($total_reg > 0){
		echo 'Este mês já foi fechado! Exclua o fechamento ou feche outro mês!';
		exit();
	}

$totalSaidas = 0;
$totalEntradas = 0;

$query = $pdo->query("SELECT * FROM receber where igreja = '$id_igreja' and data_pgto >= '$dataInicioMes' and data_pgto <= '$dataFinalMes' and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){} 
			
			$totalEntradas += $res[$i]['valor'];	

	}
}

$query = $pdo->query("SELECT * FROM pagar where igreja = '$id_igreja' and data_pgto >= '$dataInicioMes' and data_pgto <= '$dataFinalMes' and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){} 
			
			$totalSaidas += $res[$i]['valor'];	

	}
}

$saldoMes = $totalEntradas - $totalSaidas;

$query = $pdo->query("SELECT * FROM igrejas where id = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$prebenda = $res[0]['prebenda'];

$total_prebenda = $saldoMes * $prebenda / 100;

$saldo_final = $saldoMes - $total_prebenda;

$query = $pdo->query("INSERT INTO $tabela SET saidas = '$totalSaidas', entradas = '$totalEntradas', saldo = '$saldoMes', saldo_final = '$saldo_final', prebenda = '$total_prebenda', data = curDate(), data_fec = '$data_fec', usuario_cad = '$id_usuario', igreja = '$id_igreja'");
$ult_id = $pdo->lastInsertId();

	//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', movimento = 'Saldo', descricao = 'Saldo Mês', valor = '$saldo_final', data = '$dataInicioMesSeguinte', usuario_cad = '$id_usuario', id_mov = '$ult_id', igreja = '$id_igreja'");



echo 'Salvo com Sucesso';

 ?>