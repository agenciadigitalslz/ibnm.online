<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->query("UPDATE tarefas SET status = 'Agendada' where id = '$id'");

?>