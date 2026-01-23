<?php 

require_once("../conexao.php");

$id_igreja = @$_GET['igreja']; 
$id = @$_GET['id']; 
$id_usuario = @$_GET['user']; 

$query = $pdo->query("UPDATE patrimonios SET igreja_item = '$id_igreja', usuario_emprestou = '$id_usuario', data_emprestimo = curDate()  where id = '$id'");

$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>