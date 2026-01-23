<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];
$id_usuario = @$_GET['user'];

$query = $pdo->query("UPDATE pagar SET pago = 'Sim', usuario_baixa = '$id_usuario', data_baixa = curDate() where id = '$id'");


//RECUPERAR INFORMAÇÕES DA CONTA
$query = $pdo->query("SELECT * FROM pagar where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $res[0]['valor'];
$descricao = $res[0]['descricao'];
$frequencia = $res[0]['frequencia'];
$vencimento = $res[0]['vencimento'];
$fornecedor = $res[0]['fornecedor'];
$arquivo = $res[0]['arquivo'];
$igreja = $res[0]['igreja'];
$frequencia = $res[0]['frequencia'];

//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET tipo = 'Saída', movimento = 'Conta à Pagar', descricao = '$descricao', valor = '$valor', data = curDate(), usuario = '$id_usuario', id_mov = '$id', igreja = '$igreja'");


//CRIAR A PRÓXIMA CONTA A PAGAR
	if($frequencia > 0){	
	$dias_frequencia = $frequencia;
	if($dias_frequencia == 30 || $dias_frequencia == 31){
		
		$nova_data_vencimento = date('Y/m/d', strtotime("+1 month",strtotime($vencimento)));

	}else if($dias_frequencia == 90){ 
		
		$nova_data_vencimento = date('Y/m/d', strtotime("+3 month",strtotime($vencimento)));

	}else if($dias_frequencia == 180){ 

		$nova_data_vencimento = date('Y/m/d', strtotime("+6 month",strtotime($vencimento)));

	}else if($dias_frequencia == 360){ 

		$nova_data_vencimento = date('Y/m/d', strtotime("+1 year",strtotime($vencimento)));

	}else{
		$nova_data_vencimento = date('Y/m/d', strtotime("+$dias_frequencia days",strtotime($vencimento))); 
	}


	//criar a nova conta
	$query = $pdo->query("INSERT INTO pagar SET descricao = '$descricao', fornecedor = '$fornecedor', valor = '$valor', data = curDate(), vencimento = '$nova_data_vencimento', usuario_cad = '$id_usuario', pago = 'Não', igreja = '$igreja', frequencia = '$frequencia', arquivo = '$arquivo'");

	}


?>