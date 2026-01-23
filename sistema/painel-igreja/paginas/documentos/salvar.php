<?php 
$tabela = 'documentos';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$data = $_POST['data1'];
$igreja = $_POST['igreja'];

$id = @$_POST['id'];



//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['arquivo']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/documentos/' .$nome_img;
if (@$_FILES['arquivo']['name'] == ""){
	$arquivo = "sem-foto.jpg";
}else{
	$arquivo = $nome_img;
}

$arquivo_temp = @$_FILES['arquivo']['tmp_name']; 
$ext = pathinfo($arquivo, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'docx' or $ext == 'doc'){ 
	move_uploaded_file($arquivo_temp, $caminho);
}else{
	echo 'Extensão de arquivo não permitida!';
	exit();
}



if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, descricao = :descricao, data = '$data', arquivo = '$arquivo', igreja = '$igreja'");


}else{
	if($arquivo == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, descricao = :descricao, data = '$data', igreja = '$igreja' where id = '$id'");
	}else{

		$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['arquivo'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/documentos/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, descricao = :descricao, data = '$data', arquivo = '$arquivo', igreja = '$igreja' where id = '$id'");
	}
	

	
}
$query->bindValue(":nome", "$nome");
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
	$descricao = $nome;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';
 ?>