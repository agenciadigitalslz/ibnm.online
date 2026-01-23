<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$postjson['id'];
$nome = @$postjson['nome'];
$telefone = @$postjson['celular'];
$email = @$postjson['email'];
$endereco = @$postjson['endereco'];
$foto = @$postjson['foto'];
$cpf = @$postjson['cpf'];
$data_nasc = @$postjson['dataNasc'];
$igreja = @$postjson['igreja'];


$query = $pdo->query("SELECT * FROM pastores where cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O CPF já está cadastrado nessa ou em outra filial!!', 'sucesso'=>false));
echo $result;
	exit();
}

$query = $pdo->query("SELECT * FROM pastores where email = '$email'");
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

	$query = $pdo->prepare("INSERT INTO pastores SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, foto = '$foto', data_nasc = '$data_nasc', data_cad = curDate(), igreja = '$igreja'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->execute();

	$ult_id = $pdo->lastInsertId();

	$query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = '123', nivel = 'pastor', id_pessoa = '$ult_id', foto = '$foto', igreja = '$igreja'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->execute();

	
}else{
	
	if($foto == ""){
		$query = $pdo->prepare("UPDATE pastores SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco,  data_nasc = '$data_nasc', igreja = '$igreja' where id = '$id'");
	}else{

		//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
		$query = $pdo->query("SELECT * FROM pastores where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$img = $res[0]['foto'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../sistema/img/membros/'.$img);	
		}

		$query = $pdo->prepare("UPDATE pastores SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', igreja = '$igreja', foto = '$foto' where id = '$id'");
	}

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":endereco", "$endereco");
	$query->execute();


	if($foto == ""){

	$query = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, igreja = '$igreja' where id_pessoa = '$id' and nivel = 'pastor'");
	}else{
	$query = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, foto = '$foto', igreja = '$igreja' where id_pessoa = '$id' and nivel = 'pastor'");
	}

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":email", "$email");
	$query->bindValue(":cpf", "$cpf");
	
	$query->execute();

}




$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>