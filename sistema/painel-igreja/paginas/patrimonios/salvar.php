<?php 

@session_start();
$tabela = 'patrimonios';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id'];
$pagina = 'patrimonios';
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$entrada = $_POST['entrada'];
$doador = $_POST['doador'];
$data_cad = $_POST['data_cad'];
$obs = $_POST['obs'];

$id = @$_POST['id'];
$igreja = $_POST['igreja'];



if($entrada == 'Compra'){
	if($valor == ''){
		echo 'Preencha o Valor';
		exit();
	}
}else{
	if($doador == ''){
		echo 'Preencha o Doador';
		exit();
	}
}

//validacao
$query = $pdo->query("SELECT * FROM $tabela where codigo = '$codigo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
$nome_item = @$res[0]['nome'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'O Código do Item já está cadastrado no item '.$nome_item.' !';
	exit();
}



//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	$foto = $res[0]['foto'];
}else{
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/patrimonios/' .$nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'webp' or $ext == 'WEBP'){ 
	
			//EXCLUO A FOTO ANTERIOR
			if($foto != "sem-foto.png"){
				@unlink('../img/patrimonios/'.$foto);
			}

			$foto = $nome_img;
		
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor, usuario_cad = '$id_usuario', data_cad = '$data_cad', igreja_cad = '$igreja', igreja_item = '$igreja', ativo = 'Sim', entrada = '$entrada', doador = :doador,  obs = :obs, foto = '$foto'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor,  entrada = '$entrada', doador = :doador, obs = :obs, foto = '$foto' where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":codigo", "$codigo");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->bindValue(":doador", "$doador");
$query->bindValue(":obs", "$obs");

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
