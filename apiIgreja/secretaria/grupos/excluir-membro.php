<?php 

require_once("../../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->query("DELETE FROM grupos_membros where id = '$id'");

?>