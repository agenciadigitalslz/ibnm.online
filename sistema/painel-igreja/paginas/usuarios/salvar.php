<?php 
$tabela = 'usuarios';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$nivel = $_POST['nivel'];
$endereco = $_POST['endereco'];
$senha = '123';
$igreja = $_POST['igreja'];
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
$id = $_POST['id'];

//validacao email
$query = $pdo->query("SELECT * from $tabela where email = '$email' and igreja = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Email já Cadastrado!';
	exit();
}


//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Telefone já Cadastrado!';
	exit();
}

//enviar whatsapp
if($api_whatsapp != 'Não' and $telefone != ''){

	$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
	$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
	$mensagem_whatsapp .= '🤩_Olá '.$nome.' Você foi Cadastrado no Sistema!!_ %0A';
	$mensagem_whatsapp .= '*Email:* '.$email.' %0A';
	$mensagem_whatsapp .= '*Senha:* '.$senha.' %0A';
	$mensagem_whatsapp .= '*Url Acesso:* %0A'.$url_sistema.' %0A%0A';
	$mensagem_whatsapp .= '_Ao entrar no sistema, troque sua senha!_';

	require('../../apis/texto.php');
}

//enviar email
if($email != ''){
	$url_logo = $url_sistema.'img/logo.png';
	$destinatario = $email;
	$assunto = '🤩 Cadastrado no sistema '. $nome_sistema;
	$mensagem_email = 'Olá '.$nome.' você foi cadastrado no sistema <br>';
	$mensagem_email .= '<b>Usuário</b>: '.$email.'<br>';
	$mensagem_email .= '<b>Senha: </b>'.$senha.'<br><br>';
	$mensagem_email .= 'Url Acesso: <br><a href="'.$url_sistema.'">'.$url_sistema. '</a><br><br>';
	$mensagem_email .= '<i>Ao entrar no sistema, troque sua senha!</i>'. '<br><br>';
	$mensagem_email .= "<img src='".$url_logo."' width='200px'> ";
}

//tratamento nivel usuarios
if($nivel == ''){
	echo 'Escolha um nivel para este usuário!';
	exit();
}else{

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, senha = '', senha_crip = '$senha_crip', nivel = '$nivel', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = :telefone, endereco = :endereco, igreja = '$igreja'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, nivel = '$nivel', telefone = :telefone, endereco = :endereco, igreja = '$igreja' where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
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
 }?>