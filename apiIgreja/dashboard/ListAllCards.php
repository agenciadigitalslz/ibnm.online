<?php 

include_once('../conexao.php');

$id_igreja = @$_GET['id'];


$totalDizimos = 0;
$membrosCadastrados = 0;
$gruposCadastrados = 0;
$celulasCadastradas = 0;
$totalOfertas = 0;
$totalGastos = 0;
$totalVendas = 0;
$totalDoacoes = 0;
$saldoMes = 0;
$patCadastrados = 0;

$pagarVencidas = 0;
$pagarHoje = 0;
$receberHoje = 0;

$query = $pdo->query("SELECT * FROM igrejas where id = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_igreja = $res[0]['nome'];
$foto_igreja = $res[0]['imagem'];

$query = $pdo->query("SELECT * from membros where igreja = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_pessoas = @count($res);


$query = $pdo->query("SELECT * from tarefas where igreja = '$id_igreja' and data = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalTarefas = @count($res);

$query = $pdo->query("SELECT * from tarefas where igreja = '$id_igreja' and data = curDate() and status = 'Concluída'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tarefasConcluidas = @count($res);




$query = $pdo->query("SELECT * FROM pagar where igreja = '$id_igreja' and vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pagarHoje = @count($res);

$query = $pdo->query("SELECT * FROM receber where igreja = '$id_igreja' and vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$receberHoje = @count($res);

$query = $pdo->query("SELECT * FROM pagar where igreja = '$id_igreja' and vencimento < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pagarVencidas = @count($res);


$query = $pdo->query("SELECT * FROM grupos where igreja = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$gruposCadastrados = @count($res);

$query = $pdo->query("SELECT * FROM celulas where igreja = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$celulasCadastradas = @count($res);

$query = $pdo->query("SELECT * FROM patrimonios where igreja_cad = '$id_igreja' and ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$patCadastrados = @count($res);


$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual."-".$mes_atual."-01";


$query = $pdo->query("SELECT * FROM movimentacoes where igreja = '$id_igreja' and data >= '$dataInicioMes' and data <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){} 
			$tipo = $res[$i]['tipo'];
		$movimento = $res[$i]['movimento'];

		if($tipo == 'Saída'){
			$totalGastos += $res[$i]['valor'];
		}

		if($movimento == 'Dízimo'){
			$totalDizimos += $res[$i]['valor'];
		}

		if($movimento == 'Oferta'){
			$totalOfertas += $res[$i]['valor'];
		}

		if($movimento == 'Venda'){
			$totalVendas += $res[$i]['valor'];
		}

		if($movimento == 'Doação'){
			$totalDoacoes += $res[$i]['valor'];
		}

	}
}

$saldoMes = $totalDizimos + $totalOfertas + $totalVendas + $totalDoacoes - $totalGastos;
if($saldoMes < 0){
	$classeSaldo = '#bd0404';
}else{
	$classeSaldo = '#006317';
}

$totalGastos = number_format($totalGastos, 2, ',', '.');
$totalDizimos = number_format($totalDizimos, 2, ',', '.');
$totalOfertas = number_format($totalOfertas, 2, ',', '.');
$totalVendas = number_format($totalVendas, 2, ',', '.');
$totalDoacoes = number_format($totalDoacoes, 2, ',', '.');
$saldoMes = number_format($saldoMes, 2, ',', '.');


//total de aniversariantes
$dataMes = Date('m');
$dataDia = Date('d');
$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");
	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);
$res_pastores = $query_pastores->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_pastores = count($res_pastores);
$totalAniversariantes = $total_reg + $total_reg_pastores;



$result = json_encode(array('success'=>true, 
    'quantidade_clientes'=>$total_pessoas,
    'nome_igreja'=>$nome_igreja,
    'foto_igreja'=>$foto_igreja,
    'totalTarefas'=>$totalTarefas,
    'tarefasConcluidas'=>$tarefasConcluidas,
    'pagarHoje'=>$pagarHoje,
    'receberHoje'=>$receberHoje,
    'pagarVencidas'=>$pagarVencidas,
    'gruposCadastrados'=>$gruposCadastrados,
    'celulasCadastradas'=>$celulasCadastradas,
    'patCadastrados'=>$patCadastrados,
    'totalGastos'=>$totalGastos,
    'totalDizimos'=>$totalDizimos,
    'totalOfertas'=>$totalOfertas,
    'totalDoacoes'=>$totalDoacoes,
    'totalVendas'=>$totalVendas,
    'saldoMes'=>$saldoMes,
    'totalAniversariantes'=>$totalAniversariantes,
    'classeSaldo'=>$classeSaldo,
    
));

echo $result;
