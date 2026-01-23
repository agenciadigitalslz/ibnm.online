<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->query("DELETE FROM ofertas where id = '$id'");
$query = $pdo->query("DELETE FROM movimentacoes where id_mov = '$id' and movimento = 'Oferta'");

?>