<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$postjson['id'];
$nome = @$postjson['nome'];
$telefone = @$postjson['celular'];
$email = @$postjson['email'];
$endereco = @$postjson['endereco'];
$ativo = @$postjson['ativo'];
$foto = @$postjson['foto'];
$cpf = @$postjson['cpf'];
$data_nasc = @$postjson['dataNasc'];
$igreja = @$postjson['igreja'];
$data_bat = @$postjson['dataBat'];
$cargo = @$postjson['cargo'];
$estado = @$postjson['estado'];
$batizado = @$postjson['batizado'];

if($batizado == 'Não'){
	$data_bat = "";
}

$query = $pdo->query("SELECT * FROM membros where cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O CPF já está cadastrado nessa ou em outra filial!!', 'sucesso'=>false));
echo $result;
	exit();
}

$query = $pdo->query("SELECT * FROM membros where email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O Email já está cadastrado nessa ou em outra filial!', 'sucesso'=>false));
echo $result;
	exit();
}


$data = date('Y-m-d');

if($id == "" || $id == "0"){

	if($foto == ""){
		$foto = 'sem-foto.jpg';
	}

	$query = $pdo->prepare("INSERT INTO membros SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, foto = '$foto', data_nasc = '$data_nasc', data_cad = curDate(), igreja = '$igreja', data_batismo = '$data_bat', cargo = '$cargo', ativo = '$ativo', estado_civil = '$estado'");


	$query_c = $pdo->query("SELECT * FROM cargos where id = '$cargo'");	
	$res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
	$nome_cargo = $res_c[0]['nome'];

	$query_not = $pdo->query("SELECT * FROM token where igreja = '$igreja'");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}
	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Novo '.$nome_cargo;
	$conteudo_not = $nome;
	require("../../sistema/notificacao.php");

	}

	
}else{
	
	if($foto == ""){
		$query = $pdo->prepare("UPDATE membros SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco,  data_nasc = '$data_nasc', igreja = '$igreja', data_batismo = '$data_bat', cargo = '$cargo', ativo = '$ativo', estado_civil = '$estado' where id = '$id'");
	}else{

		//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
		$query = $pdo->query("SELECT * FROM membros where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$img = $res[0]['foto'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../sistema/img/membros/'.$img);	
		}

		$query = $pdo->prepare("UPDATE membros SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', igreja = '$igreja', data_batismo = '$data_bat', cargo = '$cargo', ativo = '$ativo', estado_civil = '$estado', foto = '$foto' where id = '$id'");
	}

}


$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->execute();

$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>