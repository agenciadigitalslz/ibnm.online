<?php 
$tabela = 'ministerios';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$nome = @$_POST['nome'];
$dias = @$_POST['dias'];
$hora = @$_POST['hora'];
$pastor = @$_POST['pastor'];
$secretario = @$_POST['secretario'];
$lider1 = @$_POST['lider1'];
$lider2 = @$_POST['lider2'];
$igreja = @$_POST['igreja'];
$id = @$_POST['id'];
$obs = @$_POST['obs'];


$query = $pdo->query("SELECT * FROM $tabela where nome = '$nome' and igreja = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'O nome já está indisponível!';
	exit();
}



if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, dias = :dias, hora = :hora, igreja = '$igreja', pastor = '$pastor', secretario = '$secretario', lider1 = '$lider1', lider2 = '$lider2', obs = :obs");

}else{
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, dias = :dias, hora = :hora, pastor = '$pastor', secretario = '$secretario', lider1 = '$lider1', lider2 = '$lider2', obs = :obs where id = '$id'");
}	
$query->bindValue(":nome", "$nome");
$query->bindValue(":dias", "$dias");
$query->bindValue(":hora", "$hora");
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
