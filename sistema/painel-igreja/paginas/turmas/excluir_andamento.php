<?php
require_once("../../../conexao.php");
$pagina = 'progresso_turma';
$id = @$_POST['id'];


$query = $pdo->query("DELETE FROM $pagina where id = '$id'");


echo 'Excluído com Sucesso';
?>