<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);
$id_usuario = @$postjson['user'];
$id = @$postjson['id'];
$descricao = @$postjson['descricao'];
$valor = @$postjson['valor'];
$fornecedor = @$postjson['fornecedor'];
$vencimento = @$postjson['vencimento'];

$igreja = @$postjson['igreja'];
$valor = str_replace(',', '.', $valor);


$data = date('Y-m-d');

if($id == "" || $id == "0"){

	
	$query = $pdo->prepare("INSERT INTO receber SET descricao = :descricao, membro = :membro, valor = :valor, data = curDate(), vencimento = :vencimento, usuario_cad = '$id_usuario', pago = 'Não', igreja = '$igreja'");

	
}else{	

		$query = $pdo->prepare("UPDATE receber SET descricao = :descricao, membro = :membro, valor = :valor, vencimento = :vencimento, usuario_cad = '$id_usuario' where id = '$id' ");
	

}


$query->bindValue(":descricao", "$descricao");
$query->bindValue(":membro", "$fornecedor");
$query->bindValue(":valor", "$valor");
$query->bindValue(":vencimento", "$vencimento");
$query->execute();

$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>