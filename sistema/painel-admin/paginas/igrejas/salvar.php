<?php 
$tabela = 'igrejas';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$pastor = @$_POST['pastor'];
$video = @$_POST['video'];
$email = @$_POST['email'];
$url = @$_POST['url'];
$youtube = @$_POST['youtube'];
$instagram = @$_POST['instagram'];
$facebook = @$_POST['facebook'];
$descricao = @$_POST['descricao'];
$obs = @$_POST['obs'];
$prebenda = $_POST['prebenda'];
$id = @$_POST['id'];


$nome_novo = strtolower( preg_replace("[^a-zA-Z0-9-]", "-", 
        strtr(@utf8_decode(trim($url)), @utf8_decode("/áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),
        "-aaaaeeiooouuncAAAAEEIOOOUUNC-")) );
$nome_url = preg_replace('/[ -]+/' , '-' , $nome_novo);


//validacao nome
$query = $pdo->query("SELECT * FROM $tabela where nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'O Nome da Igreja já está cadastrado!';
	exit();
}

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

$caminho = '../../../img/igrejas/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}


if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, endereco = :endereco, imagem = '$imagem', data_cad = curDate(), matriz = 'Não', pastor = '$pastor', logo_rel = 'sem-foto.jpg', cab_rel = 'sem-foto.jpg', carteirinha_rel = 'sem-foto.jpg', video = :video, email = :email, url = :url, youtube = :youtube, instagram = :instagram, facebook = :facebook, descricao = :descricao, obs = :obs, prebenda = :prebenda");


}else{
	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone, endereco = :endereco, pastor = '$pastor', video = :video, email = :email, url = :url, youtube = :youtube, instagram = :instagram, facebook = :facebook, descricao = :descricao, obs = :obs, prebenda = :prebenda where id = '$id'");
	}else{

		$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['imagem'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/igrejas/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone, endereco = :endereco, imagem = '$imagem', pastor = '$pastor', video = :video, email = :email, url = :url, youtube = :youtube, instagram = :instagram, facebook = :facebook, descricao = :descricao, obs = :obs, prebenda = :prebenda where id = '$id'");
	}
	

	
}


	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->bindValue(":video", "$video");
	$query->bindValue(":email", "$email");
	$query->bindValue(":url", "$nome_url");
	$query->bindValue(":youtube", "$youtube");
	$query->bindValue(":instagram", "$instagram");
	$query->bindValue(":facebook", "$facebook");
	$query->bindValue(":descricao", "$descricao");
	$query->bindValue(":obs", "$obs");
	$query->bindValue(":prebenda", "$prebenda");
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
	$painel = 'Painel Administrativo';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';


 ?>
