<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);
$id_usuario = @$postjson['user'];
$id = @$postjson['id'];
$descricao = @$postjson['descricao'];
$valor = @$postjson['valor'];
$fornecedor = @$postjson['fornecedor'];
$vencimento = @$postjson['vencimento'];
$frequencia = @$postjson['frequencia'];
$foto = @$postjson['foto'];
$igreja = @$postjson['igreja'];
$valor = str_replace(',', '.', $valor);


$data = date('Y-m-d');

if($id == "" || $id == "0"){

	if($foto == ""){
		$foto = 'sem-foto.jpg';
	}

	$query = $pdo->prepare("INSERT INTO pagar SET descricao = :descricao, fornecedor = :fornecedor, valor = :valor, data = curDate(), vencimento = :vencimento, usuario_cad = '$id_usuario', pago = 'Não', igreja = '$igreja', frequencia = :frequencia, arquivo = '$foto'");

	
}else{
	
	if($foto == ""){
		$query = $pdo->prepare("UPDATE pagar SET descricao = :descricao, fornecedor = :fornecedor, valor = :valor, vencimento = :vencimento, frequencia = :frequencia where id = '$id' ");
	}else{

		//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
		$query = $pdo->query("SELECT * FROM pagar where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$img = $res[0]['arquivo'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../sistema/img/contas/'.$img);	
		}

		$query = $pdo->prepare("UPDATE pagar SET descricao = :descricao, fornecedor = :fornecedor, valor = :valor, vencimento = :vencimento, frequencia = :frequencia, arquivo = '$foto' where id = '$id' ");
	}

}


$query->bindValue(":descricao", "$descricao");
$query->bindValue(":fornecedor", "$fornecedor");
$query->bindValue(":valor", "$valor");
$query->bindValue(":frequencia", "$frequencia");
$query->bindValue(":vencimento", "$vencimento");
$query->execute();

$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>