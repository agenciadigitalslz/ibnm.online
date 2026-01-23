<?php 
$tabela = 'fechamentos';
require_once("../../../conexao.php");

$id = $_POST['id'];


//EXECUTAR NO LOG

	$acao = 'Exclusão';
	$id_reg = $id;
	$descricao = 'Fechamento';
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");


$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>