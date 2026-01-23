<?php 
$tabela = 'alertas';
require_once("../../../conexao.php");


@session_start();
$id_usuario = @$_SESSION['id'];


$titulo = $_POST['titulo'];
$link = $_POST['link'];
$descricao = $_POST['descricao'];
$data = $_POST['data1'];
$igreja = $_POST['igreja'];

$id = @$_POST['id'];



//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/alertas/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}



if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET usuario = '$id_usuario', titulo = :titulo, link = :link, descricao = :descricao, data = '$data', ativo = 'Não', imagem = '$imagem', igreja = '$igreja'");


}else{
	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $tabela SET titulo = :titulo, link = :link, descricao = :descricao, data = '$data', ativo = 'Sim', igreja = '$igreja' where id = '$id'");
	}else{

		$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['imagem'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/alertas/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $tabela SET titulo = :titulo, link = :link, descricao = :descricao, data = '$data', ativo = 'Sim', imagem = '$imagem', igreja = '$igreja' where id = '$id'");
	}
	


}
$query->bindValue(":titulo", "$titulo");
$query->bindValue(":link", "$link");
$query->bindValue(":descricao", "$descricao");

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
	$descricao = $titulo;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';
 ?>