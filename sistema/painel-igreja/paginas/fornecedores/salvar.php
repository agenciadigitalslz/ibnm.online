<?php 
$tabela = 'fornecedores';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$pix = $_POST['pix'];
$produto = $_POST['produto'];
$igreja = @$_POST['igreja'];
$id = $_POST['id'];


//validacao email
if($email != ""){
	$query = $pdo->query("SELECT * from $tabela where email = '$email'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if(@count($res) > 0 and $id != $id_reg){
		echo 'Email já Cadastrado!';
		exit();
	}
}

//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Telefone já Cadastrado!';
	exit();
}

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, telefone = :telefone, data = curDate(), endereco = :endereco, pix = :pix, produto = :produto, igreja = '$igreja', usuario = '$id_usuario' ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, pix = :pix, produto = :produto, igreja = '$igreja' where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":pix", "$pix");
$query->bindValue(":produto", "$produto");
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
