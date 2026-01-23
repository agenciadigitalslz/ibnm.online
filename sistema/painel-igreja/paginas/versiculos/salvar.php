<?php 
$tabela = 'versiculos';
require_once("../../../conexao.php");

$versiculo = $_POST['versiculo'];
$capitulo = $_POST['capitulo'];
$igreja = $_POST['igreja'];

$id = @$_POST['id'];


$query = $pdo->query("SELECT * FROM $tabela where capitulo = '$capitulo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'Este versículo já está cadastrado!';
	exit();
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET versiculo = :versiculo, capitulo = :capitulo, igreja = '$igreja'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET versiculo = :versiculo, capitulo = :capitulo, igreja = '$igreja' where id = '$id'");
}
$query->bindValue(":versiculo", "$versiculo");
$query->bindValue(":capitulo", "$capitulo");

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
	$descricao = $versiculo;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';
 ?>