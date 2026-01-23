<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$postjson['id'];
$nome = @$postjson['nome'];
$telefone = @$postjson['celular'];
$email = @$postjson['email'];
$endereco = @$postjson['endereco'];
$produto = @$postjson['produto'];
$igreja = @$postjson['igreja'];



$query = $pdo->query("SELECT * FROM fornecedores where email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O Email já está cadastrado nessa ou em outra filial!', 'sucesso'=>false));
echo $result;
	exit();
}

if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO fornecedores SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, produto = :produto, igreja = '$igreja'");
	
}else{
	$query = $pdo->prepare("UPDATE fornecedores SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, produto = :produto where id = '$id'");
	
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":produto", "$produto");
$query->execute();


$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>