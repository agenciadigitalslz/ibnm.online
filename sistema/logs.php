<?php
require_once("conexao.php");
@session_start();
$id_usuario = @$_SESSION['id_usuario'];

if($id_usuario == ""){
	$id_usuario = 0;
}

if($logs == 'Sim'){
	$query = $pdo->prepare("INSERT INTO logs SET data = curDate(), hora = curTime(), tabela = '$tabela', acao = '$acao', usuario = '$id_usuario', id_reg = '$id_reg', descricao = :descricao, painel = '$painel', igreja = '$igreja'");

	$query->bindValue(":descricao", "$descricao");
	$query->execute();
}

 ?>