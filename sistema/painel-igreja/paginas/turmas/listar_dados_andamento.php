<?php 
require_once("../../../conexao.php");

$id = @$_POST['id'];

$query = $pdo->query("SELECT * FROM progresso_turma where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if($total_reg > 0){

$conteudo = $res[0]['conteudo'];
$obs = $res[0]['obs'];

echo '<div>';
echo $conteudo;
echo '</div>';

if($obs != ""){
echo '<div style="margin-top:25px"><b>Observações</b><br>';
echo $obs;
echo '</div>';
}
}
?>

