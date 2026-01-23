<?php 

@session_start();
$tabela = 'ofertas';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id'];
$valor = $_POST['valor'];
$membro = $_POST['membro'];
$data = $_POST['data1'];
$igreja = $_POST['igreja'];
$id = @$_POST['id'];


$query_con = $pdo->query("SELECT * FROM membros where id = '$membro'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
if(count($res_con) > 0){
	$nome_membro = $res_con[0]['nome'];
}else{
	$nome_membro = 'Membro Não Informado';
}



if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET igreja = '$igreja', membro = '$membro', valor = :valor, data = '$data', usuario_cad = '$id_usuario'");

	$query->bindValue(":valor", "$valor");
	$query->execute();
	$ult_id = $pdo->lastInsertId();


	//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET igreja = '$igreja', tipo = 'Entrada', movimento = 'Oferta', descricao = '$nome_membro', valor = '$valor', data = '$data', usuario_cad = '$id_usuario', id_mov = '$ult_id'");


$pdo->query("INSERT INTO receber SET descricao = 'Oferta', valor = '$valor', subtotal = '$valor', membro = '$membro', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime(), id_rec = '$ult_id', referencia = 'Oferta', id_ref = '$ult_id'");

}else{
	require_once("../verificar-tesoureiro.php");
	$query = $pdo->prepare("UPDATE $tabela SET membro = '$membro', valor = :valor, data = '$data', usuario_cad = '$id_usuario' where id = '$id'");

	//INSIRO NAS MOVIMENTACOES
$pdo->query("UPDATE movimentacoes SET igreja = '$igreja', tipo = 'Entrada', movimento = 'Oferta', descricao = '$nome_membro', valor = '$valor', data = '$data', usuario_cad = '$id_usuario'");


$pdo->query("UPDATE receber SET descricao = 'Oferta', valor = '$valor', subtotal = '$valor', membro = '$membro', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime() where id_ref = '$id' and referencia = 'Oferta'");




$query->bindValue(":valor", "$valor");

$query->execute();
$ult_id = $pdo->lastInsertId();

//EXECUTAR NO LOG

	if($id == "" || $id == 0){
	$acao = 'Inserção';
	$id_reg = $ult_id;
	}else{
	$acao = 'Edição';
	$id_reg = $id;
	}
	$descricao = 'Oferta';
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

}


echo 'Salvo com Sucesso';
 ?>