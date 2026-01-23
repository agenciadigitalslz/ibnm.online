<?php 
$tabela = 'dizimos';
require_once("../../../conexao.php");

$id = $_POST['id'];


$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$nome = 'Dízimo';

//EXECUTAR NO LOG

	$acao = 'Exclusão';
	$id_reg = $id;
	$descricao = $nome;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

$pdo->query("DELETE FROM movimentacoes WHERE id_mov = '$id' and movimento = 'Dízimo' ");
$pdo->query("DELETE FROM receber WHERE id_ref = '$id' and referencia = 'Dízimo'");
$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
$query = $pdo->query("DELETE FROM receber where id = '$id'");
echo 'Excluído com Sucesso';
?>