<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$postjson['id'];
$nome = @$postjson['nome'];
$codigo = @$postjson['codigo'];
$descricao = @$postjson['descricao'];
$valor = @$postjson['valor'];
$valor = str_replace(',', '.', $valor);
$ativo = @$postjson['ativo'];
$foto = @$postjson['foto'];
$obs = @$postjson['obs'];
$data_cad = @$postjson['dataCad'];
$igreja = @$postjson['igreja'];
$entrada = @$postjson['entrada'];
$doador = @$postjson['doador'];
$id_usuario = @$postjson['user'];

$query = $pdo->query("SELECT * FROM patrimonios where codigo = '$codigo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
$nome_item = @$res[0]['nome'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O Código do Item já está cadastrado no item '.$nome_item.' !', 'sucesso'=>false));
	echo $result;
	
	exit();
}




if($id == "" || $id == 0){

	if($foto == ""){
		$foto = 'sem-foto.jpg';
	}

	$query = $pdo->prepare("INSERT INTO patrimonios SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor, usuario_cad = '$id_usuario', data_cad = '$data_cad', igreja_cad = '$igreja', igreja_item = '$igreja', ativo = '$ativo', obs = :obs, entrada = '$entrada', doador = :doador, foto = '$foto'");
	
}else{
	if($foto == ""){
		$query = $pdo->prepare("UPDATE patrimonios SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor,  entrada = '$entrada', doador = :doador, ativo = '$ativo', obs = :obs where id = '$id'");
	}else{

		//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
		$query = $pdo->query("SELECT * FROM patrimonios where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$img = $res[0]['foto'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../sistema/img/patrimonios/'.$img);	
		}

		$query = $pdo->prepare("UPDATE patrimonios SET codigo = :codigo, nome = :nome, descricao = :descricao, valor = :valor,  entrada = '$entrada', doador = :doador, ativo = '$ativo', obs = :obs, foto = '$foto' where id = '$id'");
	}
	
	
}


$query->bindValue(":nome", "$nome");
$query->bindValue(":codigo", "$codigo");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->bindValue(":doador", "$doador");
$query->bindValue(":obs", "$obs");

$query->execute();


$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>