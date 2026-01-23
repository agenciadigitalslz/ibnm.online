<?php 
$tabela = 'turmas';
require_once("../../../conexao.php");

$id = $_POST['id'];


//excluir a imagem
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome = $res[0]['nome'];


//EXECUTAR NO LOG

	$acao = 'Exclusão';
	$id_reg = $id;
	$descricao = $nome;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>