<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);
$id_usuario = @$postjson['user'];
$id = @$postjson['id'];
$descricao = @$postjson['descricao'];
$valor = @$postjson['valor'];
$data = @$postjson['vencimento'];
$igreja = @$postjson['igreja'];
$valor = str_replace(',', '.', $valor);

	
if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO vendas SET descricao = '$descricao', valor = :valor, data = '$data', usuario = '$id_usuario', igreja = '$igreja'");

	$query->bindValue(":valor", "$valor");
	$query->execute();
	$ult_id = $pdo->lastInsertId();

	//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', movimento = 'Venda', descricao = '$descricao', valor = '$valor', data = '$data', usuario = '$id_usuario', id_mov = '$ult_id', igreja = '$igreja'");

}else{
	
	$query = $pdo->prepare("UPDATE vendas SET descricao = '$descricao', valor = :valor, data = '$data', usuario = '$id_usuario', igreja = '$igreja' where id = '$id'");

	//INSIRO NAS MOVIMENTACOES
$pdo->query("UPDATE movimentacoes SET descricao = '$descricao', valor = '$valor', data = '$data', usuario = '$id_usuario' where id_mov = '$id' and movimento = 'Venda'");

$query->bindValue(":valor", "$valor");
$query->execute();

}




$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>