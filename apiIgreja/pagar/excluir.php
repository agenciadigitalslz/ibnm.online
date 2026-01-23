<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

//excluir a imagem
$query = $pdo->query("SELECT * FROM pagar where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$foto = $res[0]['arquivo'];
if($foto != "sem-foto.jpg"){
	@unlink('../../sistema/img/contas/'.$foto);	
}

$query = $pdo->query("DELETE FROM pagar where id = '$id'");

?>