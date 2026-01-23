<?php 
$tabela = 'membros';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$data_nasc = @$_POST['data_nasc'];
$igreja = @$_POST['igreja'];
$data_bat = @$_POST['data_bat'];
$cargo = @$_POST['cargo'];
$estado_civil = @$_POST['estado_civil'];
$obs = @$_POST['obs'];
$id = @$_POST['id'];

$data_nasc = date("Y-m-d", strtotime(str_replace("/", "-", $_POST["data_nasc"])));
$data_bat = date("Y-m-d", strtotime(str_replace("/", "-", $_POST["data_bat"])));

$numero = filter_var($_POST['numero'], @FILTER_SANITIZE_STRING);
$bairro = filter_var($_POST['bairro'], @FILTER_SANITIZE_STRING);
$cidade = filter_var($_POST['cidade'], @FILTER_SANITIZE_STRING);
$estado = filter_var(@$_POST['estado'], @FILTER_SANITIZE_STRING);
$cep = filter_var($_POST['cep'], @FILTER_SANITIZE_STRING);
$complemento = filter_var($_POST['complemento'], @FILTER_SANITIZE_STRING);

$rg = @$_POST['rg'];
$nome_pai = @$_POST['nome_pai'];
$nome_mae = @$_POST['nome_mae'];
$membresia = @$_POST['membresia'];
$naturalidade = @$_POST['naturalidade'];
$nacionalidade = @$_POST['nacionalidade'];


$profissao = @$_POST['profissao'];
$conjuge = @$_POST['conjuge'];
$data_consagracao = @$_POST['data_consagracao'];
$igreja_anterior = @$_POST['igreja_anterior'];
$cidade_anterior = @$_POST['cidade_anterior'];
$titulo_eleitor = @$_POST['titulo_eleitor'];
$cidade_votacao = @$_POST['cidade_votacao'];
$estado_votacao = @$_POST['estado_votacao'];
$zona = @$_POST['zona'];
$sessao = @$_POST['sessao'];

$membresia = date("Y-m-d", strtotime(str_replace("/", "-", $_POST["membresia"])));
$data_consagracao = date("Y-m-d", strtotime(str_replace("/", "-", $_POST["data_consagracao"])));

if($cpf != ""){
	$query = $pdo->query("SELECT * FROM $tabela where cpf = '$cpf'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if(@count($res) > 0 and $id_reg != $id){
		echo 'O CPF já está cadastrado nessa ou em outra filial!';
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

$caminho = '../../../img/membros/' .$nome_img;
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
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', igreja = '$igreja', ativo = 'Sim', data_bat = '$data_bat', data_cad = curDate(), cargo = '$cargo', estado_civil = '$estado_civil', obs = :obs, imagem = '$imagem', rg = :rg, membresia = :membresia, nome_pai = :nome_pai, nome_mae = :nome_mae, naturalidade = :naturalidade, nacionalidade = :nacionalidade, profissao = :profissao, conjuge = :conjuge, data_consagracao = :data_consagracao, igreja_anterior = :igreja_anterior, cidade_anterior = :cidade_anterior, titulo_eleitor = :titulo_eleitor, cidade_votacao = :cidade_votacao, estado_votacao = :estado_votacao, zona = :zona, sessao = :sessao, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento");


}else{
	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', igreja = '$igreja', data_bat = '$data_bat', data_cad = curDate(), cargo = '$cargo', obs = :obs, estado_civil = '$estado_civil', rg = :rg, membresia = :membresia, nome_pai = :nome_pai, nome_mae = :nome_mae, naturalidade = :naturalidade, nacionalidade = :nacionalidade, profissao = :profissao, conjuge = :conjuge, data_consagracao = :data_consagracao, igreja_anterior = :igreja_anterior, cidade_anterior = :cidade_anterior, titulo_eleitor = :titulo_eleitor, cidade_votacao = :cidade_votacao, estado_votacao = :estado_votacao, zona = :zona, sessao = :sessao, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento where id = '$id'");
	}else{

		$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['imagem'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/membros/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', igreja = '$igreja', data_bat = '$data_bat', data_cad = curDate(), cargo = '$cargo', estado_civil = '$estado_civil', obs = :obs, imagem = '$imagem', rg = :rg, membresia = :membresia, nome_pai = :nome_pai, nome_mae = :nome_mae, naturalidade = :naturalidade, nacionalidade = :nacionalidade, profissao = :profissao, conjuge = :conjuge, data_consagracao = :data_consagracao, igreja_anterior = :igreja_anterior, cidade_anterior = :cidade_anterior, titulo_eleitor = :titulo_eleitor, cidade_votacao = :cidade_votacao, estado_votacao = :estado_votacao, zona = :zona, sessao = :sessao, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento where id = '$id'");
	}
	

	
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":obs", "$obs");

$query->bindValue(":rg", "$rg");
$query->bindValue(":membresia", "$membresia");
$query->bindValue(":nome_pai", "$nome_pai");
$query->bindValue(":nome_mae", "$nome_mae");
$query->bindValue(":naturalidade", "$naturalidade");
$query->bindValue(":nacionalidade", "$nacionalidade");

$query->bindValue(":profissao", "$profissao");
$query->bindValue(":conjuge", "$conjuge");
$query->bindValue(":data_consagracao", "$data_consagracao");
$query->bindValue(":igreja_anterior", "$igreja_anterior");
$query->bindValue(":cidade_anterior", "$cidade_anterior");
$query->bindValue(":titulo_eleitor", "$titulo_eleitor");
$query->bindValue(":cidade_votacao", "$cidade_votacao");
$query->bindValue(":estado_votacao", "$estado_votacao");
$query->bindValue(":zona", "$zona");
$query->bindValue(":sessao", "$sessao");
$query->bindValue(":numero", "$numero");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":cep", "$cep");
$query->bindValue(":complemento", "$complemento");

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
