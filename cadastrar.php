<?php 
require_once("sistema/conexao.php");

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$data_nasc = $_POST['data_nasc'];
$igreja = $_POST['igreja'];
$data_bat = $_POST['data_batismo'];

$query = $pdo->query("SELECT * FROM membros where cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	echo "<script language='javascript'> window.alert('O Cpf já está cadastrado!') </script>";
	exit();
}

$query = $pdo->query("SELECT * FROM membros where email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0 ){
	echo "<script language='javascript'> window.alert('O email já está cadastrado!') </script>";
	exit();
}



//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = 'sistema/img/membros/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'jpg' or $ext == 'jpeg'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo "<script language='javascript'> window.alert('A extensão da imagem só pode ser jpg ou jpeg') </script>";
	exit();
}


	$query = $pdo->prepare("INSERT INTO membros SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, imagem = '$imagem', data_nasc = '$data_nasc', data_cad = curDate(), igreja = '$igreja', data_bat = '$data_bat', cargo = '1', ativo = 'Sim' ");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->execute();
	

echo "<script>window.location='index.php'</script>";

 ?>