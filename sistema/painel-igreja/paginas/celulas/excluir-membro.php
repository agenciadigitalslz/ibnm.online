<?php
require_once("../../../conexao.php");

$id = @$_POST['id'];

$query = $pdo->query("DELETE FROM celulas_membros where id = '$id'");
echo 'Excluído com Sucesso';
?>