<?php 

@session_start();
$tabela = 'vendas';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id'];
$valor = $_POST['valor'];
$descricao = $_POST['descricao'];
$data = $_POST['data1'];
$igreja = $_POST['igreja'];
$id = @$_POST['id'];




if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET igreja = '$igreja', descricao = '$descricao', valor = :valor, data = '$data', usuario_cad = '$id_usuario'");

	$query->bindValue(":valor", "$valor");
	$query->execute();
	$ult_id = $pdo->lastInsertId();

	//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET igreja = '$igreja', tipo = 'Entrada', movimento = 'Vendas', descricao = '$descricao', valor = '$valor', data = '$data', usuario_cad = '$id_usuario', id_mov = '$ult_id'");

$pdo->query("INSERT INTO receber SET descricao = 'Nova Venda', valor = '$valor', subtotal = '$valor', membro = '0', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime(), id_rec = '$ult_id', referencia = 'Vendas', id_ref = '$ult_id'");

}else{
	require_once("../verificar-tesoureiro.php");
	$query = $pdo->prepare("UPDATE $tabela SET descricao = '$descricao', valor = :valor, data = '$data', usuario_cad = '$id_usuario' where id = '$id'");

	//INSIRO NAS MOVIMENTACOES
$pdo->query("UPDATE movimentacoes SET descricao = '$descricao', valor = '$valor', data = '$data', usuario_cad = '$id_usuario' where id_mov = '$id' and movimento = 'Vendas'");

$pdo->query("UPDATE receber SET valor = '$valor', subtotal = '$valor', membro = '0', igreja = '$igreja', data_lanc = curDate(), usuario_lanc = '$id_usuario', pago = 'Sim', data_pgto = curTime(), arquivo = 'sem-foto.png', vencimento = curTime() where id_ref = '$id' and referencia = 'Vendas'");


$query->bindValue(":valor", "$valor");

$query->execute();
//EXECUTAR NO LOG

	if($id == "" || $id == 0){
	$acao = 'Inserção';
	$id_reg = $ult_id;
	}else{
	$acao = 'Edição';
	$id_reg = $id;
	}
	$descricao = 'Vendas';
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");


}

echo 'Salvo com Sucesso';
 ?>