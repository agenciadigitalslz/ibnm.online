<?php
require_once("../../../conexao.php");
$pagina = 'progresso_turma';
$data = $_POST['data_and'];
$membros = $_POST['membros'];
$ministrador = $_POST['ministrador'];
$obs = $_POST['obs_andamento'];
$conteudo = $_POST['conteudo'];
$turma = $_POST['id'];
$igreja = $_POST['id-igreja'];


//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/turmas/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'docx' or $ext == 'doc'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}


$query = $pdo->prepare("INSERT INTO $pagina SET turma = '$turma', igreja = '$igreja', data = '$data', membros = '$membros', ministrador = '$ministrador', obs = :obs, conteudo = :conteudo, arquivo = '$imagem'");

$query->bindValue(":obs", "$obs");
$query->bindValue(":conteudo", "$conteudo");
$query->execute();
echo 'Salvo com Sucesso';


?>