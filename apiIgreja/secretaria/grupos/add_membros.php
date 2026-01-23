<?php 

require_once("../../conexao.php");

$igreja = @$_GET['igreja']; 
$id = @$_GET['celula'];
$membro = @$_GET['membro'];

$query = $pdo->query("SELECT * FROM grupos_membros where igreja = '$igreja' and membro = '$membro' and grupo = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'Membro já adicionado!', 'sucesso'=>false));

echo $result;
	exit();
}

$pdo->query("INSERT INTO grupos_membros SET membro = '$membro', grupo = '$id', data = curDate(), igreja = '$igreja'");


$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>