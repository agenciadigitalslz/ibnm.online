<?php 
$tabela = 'igrejas';
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$foto = @$res[0]['foto'];
$nome = $res[0]['nome'];
if($foto != "sem-foto.png"){
	@unlink('../../../img/igrejas/'.$foto);
}

//EXECUTAR NO LOG

	$acao = 'Exclusão';
	$id_reg = $id;
	$descricao = $nome;
	$painel = 'Painel Administrativo';
	$igreja = 0;	
	require_once("../../../logs.php");

	$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
	$query = $pdo->query("DELETE FROM usuarios where id_pessoa = '$id' and nivel = 'Pastor'");
	echo 'Excluído com Sucesso';

?>