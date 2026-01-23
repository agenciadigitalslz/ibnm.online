<?php
require_once("../../../conexao.php");
$pagina = 'igrejas';

$id = @$_POST['id-img'];


//SCRIPT PARA SUBIR FOTO NO BANCO LOGO JPG
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['logojpg']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/igrejas/' .$nome_img;
if (@$_FILES['logojpg']['name'] == ""){
	$logojpg = "sem-foto.jpg";
}else{
	$logojpg = $nome_img;

	$imagem_temp = @$_FILES['logojpg']['tmp_name']; 
	$ext = pathinfo($logojpg, PATHINFO_EXTENSION);   
	if($ext == 'jpg' or $ext == 'jpeg'){ 
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida para a imagem da Logo!. Somente JPG para os Relatórios';
		exit();
	}

}





//SCRIPT PARA SUBIR FOTO NO BANCO CAB RELATORIO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['cabjpg']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/igrejas/' .$nome_img;
if (@$_FILES['cabjpg']['name'] == ""){
	$cabjpg = "sem-foto.jpg";
}else{
	$cabjpg = $nome_img;

	$imagem_temp = @$_FILES['cabjpg']['tmp_name']; 
	$ext = pathinfo($cabjpg, PATHINFO_EXTENSION);   
	if($ext == 'jpg' or $ext == 'jpeg'){ 
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida para a imagem do Cabeçalho. Somente JPG para os Relatórios';
		exit();
	}
}





//SCRIPT PARA SUBIR FOTO NO BANCO LOGO PAINEL
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['paineljpg']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/igrejas/' .$nome_img;
if (@$_FILES['paineljpg']['name'] == ""){
	$paineljpg = "sem-foto.jpg";
}else{
	$paineljpg = $nome_img;
	$imagem_temp = @$_FILES['paineljpg']['tmp_name']; 
	$ext = pathinfo($paineljpg, PATHINFO_EXTENSION);   
	if($ext == 'png' or $ext == 'PNG'){ 
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida para a imagem do Logo Painel. Somente PNG';
		exit();
	}
}







//SCRIPT PARA SUBIR FOTO NO BANCO CAB RELATORIO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['cartjpg']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/igrejas/' .$nome_img;
if (@$_FILES['cartjpg']['name'] == ""){
	$cartjpg = "sem-foto.jpg";
}else{
	$cartjpg = $nome_img;
}

$imagem_temp = @$_FILES['cartjpg']['tmp_name']; 
$ext = pathinfo($cartjpg, PATHINFO_EXTENSION);   
if($ext == 'jpg' or $ext == 'jpeg'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida para a imagem da Carteirinha. Somente JPG para os Relatórios';
	exit();
}




if($logojpg != "sem-foto.jpg"){
	$pdo->query("UPDATE $pagina SET logo_rel = '$logojpg' where id = '$id'");
}

if($paineljpg != "sem-foto.jpg"){
	$pdo->query("UPDATE $pagina SET painel_jpg = '$paineljpg' where id = '$id'");
}

if($cabjpg != "sem-foto.jpg"){
	$pdo->query("UPDATE $pagina SET cab_rel = '$cabjpg' where id = '$id'");
}

if($cartjpg != "sem-foto.jpg"){
	$pdo->query("UPDATE $pagina SET carteirinha_rel = '$cartjpg' where id = '$id'");
}
	

//EXECUTAR NO LOG
	$tabela = $pagina;
	$acao = 'Edição';
	$id_reg = $id;
	$descricao = 'Adicionou Imagens';
	$painel = 'Painel Administrativo';
	$igreja = 0;	
	require_once("../../../logs.php");

echo 'Salvo com Sucesso';


?>