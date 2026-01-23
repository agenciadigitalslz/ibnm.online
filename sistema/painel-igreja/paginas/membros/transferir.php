<?php
@session_start();
$id_usuario = @$_SESSION['id_usuario'];
require_once("../../../conexao.php");


$tabela = 'membros';

$id = @$_POST['id'];
$igreja = @$_POST['igreja'];
$obs = @$_POST['obs'];
$igreja_saida = $_POST['igreja_saida'];

//mudar o membro de igreja
$query = $pdo->query("UPDATE $tabela SET igreja = '$igreja' where id = '$id'");

$query = $pdo->prepare("INSERT INTO transferencias SET membro = '$id', igreja_saida = '$igreja_saida',  igreja_entrada = '$igreja', usuario = '$id_usuario', data = curDate(), obs = :obs");
$query->bindValue(":obs", "$obs");
$query->execute();

echo 'Salvo com Sucesso';
?>