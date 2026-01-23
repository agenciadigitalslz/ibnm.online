<?php 
$tabela = 'tesoureiros';
require_once("../../../conexao.php");

$id = $_POST['id'];

//excluir a imagem
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$foto = $res[0]['foto'];
$nome = $res[0]['nome'];
if($foto != "sem-foto.jpg"){
	@unlink('../../../img/perfil'.$foto);	
}

//EXECUTAR NO LOG

	$acao = 'Exclusão';
	$id_reg = $id;
	$descricao = $nome;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");

$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
$query = $pdo->query("DELETE FROM usuarios where id_pessoa = '$id' and nivel = 'Tesoureiro'");
echo 'Excluído com Sucesso';
?>