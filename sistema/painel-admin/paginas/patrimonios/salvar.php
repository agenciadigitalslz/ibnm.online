<?php 
$tabela = 'patrimonios';
require_once("../../../conexao.php");

$id_usuario = @$_SESSION['id_usuario'];
$pagina = 'patrimonios';
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$entrada = $_POST['entrada'];
$doador = $_POST['doador'];
$data_cad = $_POST['data_cad'];

$id = @$_POST['id'];


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
	$foto = 'sem-foto.png';
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
$query = $pdo->prepare("INSERT INTO $tabela SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor, usuario_cad = '$id_usuario', data_cad = '$data_cad', igreja_cad = '$igreja', igreja_item = '$igreja', ativo = 'Sim', entrada = '$entrada', doador = :doador, foto = '$foto'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor,  entrada = '$entrada', doador = :doador, foto = '$foto' where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":codigo", "$codigo");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->bindValue(":doador", "$doador");

$query->execute();

echo 'Salvo com Sucesso';


 ?>
