<?php


require_once("../../../conexao.php");


$id = @$_POST['id-add'];
$igreja = @$_POST['id-igreja'];
$membro = @$_POST['membro'];


$query = $pdo->query("SELECT * FROM celulas_membros where igreja = '$igreja' and membro = '$membro' and celula = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id_reg != $id){
	echo 'O Membro já está Adicionado!';
	exit();
}

$pdo->query("INSERT INTO celulas_membros SET membro = '$membro', celula = '$id', data = curDate(), igreja = '$igreja'");

echo 'Adicionado com Sucesso';


?>