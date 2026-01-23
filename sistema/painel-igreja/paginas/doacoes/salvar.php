<?php 

session_start();
$tabela = 'doacoes';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id'];
$valor = $_POST['valor'];
$membro = $_POST['membro'];
$data = $_POST['data1'];
$igreja = $_POST['igreja'];
$id = @$_POST['id'];


$query_con = $pdo->query("SELECT * FROM membros where id = '$membro' and igreja = '$igreja'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
if(count($res_con) > 0){
	$nome_membro = $res_con[0]['nome'];
}else{
	$nome_membro = 'Membro Não Informado';
}



if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET membro = '$membro', valor = :valor, data = '$data', usuario_cad = '$id_usuario', igreja = '$igreja'");

	$query->bindValue(":valor", "$valor");
	$query->execute();
	$ult_id = $pdo->lastInsertId();

	//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', movimento = 'Doação', descricao = '$nome_membro', valor = '$valor', data = '$data', usuario_cad = '$id_usuario', id_mov = '$ult_id', igreja = '$igreja'");

$pdo->query("INSERT INTO receber SET descricao = 'Doação Recebida', valor = '$valor', subtotal = '$valor', membro = '$membro', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime(), id_rec = '$ult_id', referencia = 'Doações', id_ref = '$ult_id'");

}else{
	require_once("../verificar-tesoureiro.php");
	$query = $pdo->prepare("UPDATE $tabela SET membro = '$membro', valor = :valor, data = '$data', usuario_cad = '$id_usuario' where id = '$id'");

	//INSIRO NAS MOVIMENTACOES
$pdo->query("UPDATE movimentacoes SET descricao = '$nome_membro', valor = '$valor', data = '$data', usuario_cad = '$id_usuario' where id_mov = '$id' and movimento = 'Dízimo'");

$pdo->query("UPDATE receber SET valor = '$valor', subtotal = '$valor', membro = '$membro', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime() where id_ref = '$id' and referencia = 'Doações'");

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
	$descricao = 'Doações';
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

}

echo 'Salvo com Sucesso';
 ?>