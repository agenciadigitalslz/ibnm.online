<?php
require_once("../../../conexao.php");

$id = @$_POST['id'];

$query = $pdo->query("DELETE FROM turmas_membros where id = '$id'");
echo 'Excluído com Sucesso';
?>