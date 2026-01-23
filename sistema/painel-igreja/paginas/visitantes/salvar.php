<?php 
$tabela = 'visitantes';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$nome = $_POST['nome'];
$desejo = $_POST['desejo'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$igreja = $_POST['igreja'];
$id = @$_POST['id'];


//validacao email
if($email != ""){
	$query = $pdo->query("SELECT * FROM $tabela where email = '$email' and igreja = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'O Email já está Cadastrado!';
	exit();
}
}



//validacao telefone
if($telefone != ""){
	$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if(@count($res) > 0 and $id != $id_reg){
		echo 'Telefone já Cadastrado!';
		exit();
	}
}



if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, endereco = :endereco, email = :email, desejo = :desejo, data = curDate(), igreja = '$igreja'");

}else{
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone, endereco = :endereco, email = :email, desejo = :desejo, igreja = '$igreja' where id = '$id'");
}

$query->bindValue(":desejo", "$desejo");
$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":email", "$email");
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
