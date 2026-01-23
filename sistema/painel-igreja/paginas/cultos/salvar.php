<?php 
$tabela = 'cultos';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$dia = $_POST['dia'];
$hora = $_POST['hora'];
$descricao = $_POST['descricao'];
$obs = $_POST['obs'];
$igreja = @$_POST['igreja'];
$id = $_POST['id'];

//validacao nome
$query = $pdo->query("SELECT * from $tabela where nome = '$nome' and igreja = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Nome já Cadastrado!';
	exit();
}


if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, descricao = :descricao, dia = '$dia', hora = '$hora', igreja = '$igreja', obs = :obs");


	}else{
		$query = $pdo->prepare("UPDATE $tabela SET  nome = :nome, descricao = :descricao, dia = '$dia', hora = '$hora', igreja = '$igreja', obs = :obs where id = '$id'");
	}
	

	

$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":obs", "$obs");
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
	$descricao = $nome;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';
 ?>