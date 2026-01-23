<?php 
$tabela = 'tesoureiros';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];


$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$igreja = $_POST['igreja'];
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
$id = @$_POST['id'];


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

//validacao cpf
if($cpf != ""){
	$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if(@count($res) > 0 and $id != $id_reg){
		echo 'Email já Cadastrado!';
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


//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/perfil/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
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


if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, foto = '$imagem', igreja = '$igreja'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->execute();
	$ult_id = $pdo->lastInsertId();

	$query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, telefone = :telefone, email = :email, cpf = :cpf, senha = '', senha_crip = '$senha_crip', nivel = 'Tesoureiro', id_pessoa = '$ult_id', foto = '$imagem', ativo = 'Sim', igreja = '$igreja'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->execute();

}else{
	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, igreja = '$igreja' where id = '$id'");
	}else{

		$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['foto'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/perfil/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, foto = '$imagem', igreja = '$igreja' where id = '$id'");
	}
	


	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->execute();

	if($imagem == "sem-foto.jpg"){
	$query = $pdo->prepare("UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email, cpf = :cpf where id_pessoa = '$id', igreja = '$igreja' and nivel = 'Tesoureiro'");
}else{
	$query = $pdo->prepare("UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email, cpf = :cpf, foto = '$imagem' where id_pessoa = '$id', igreja = '$igreja' and nivel = 'Tesoureiro'");
}

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	
	$query->execute();
}
echo 'Salvo com Sucesso';


 ?>
