<?php 

require_once("../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$postjson['id'];
$titulo = @$postjson['titulo'];
$descricao = @$postjson['descricao'];
$data = @$postjson['dataTar'];
$hora = @$postjson['hora'];
$igreja = @$postjson['igreja'];

$query = $pdo->query("SELECT * FROM tarefas where data = '$data' and hora = '$hora' and igreja = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
$titulo_reg = @$res[0]['titulo'];
if(@count($res) > 0 and $id_reg != $id){
	$result = json_encode(array('mensagem'=>'O horário está indisponível, a tarefa '.$titulo_reg.' já está agendada neste horário', 'sucesso'=>false));
echo $result;
	exit();
}


if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO tarefas SET titulo = :titulo, descricao = :descricao, data = '$data', hora = '$hora', igreja = '$igreja', status = 'Agendada'");

	 $dataF = implode('/', array_reverse(explode('-', $data)));


	$query_not = $pdo->query("SELECT * FROM token where igreja = '$igreja'");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Tarefa '.$titulo;
	$conteudo_not = $dataF . ' - ' .$hora;
	require("../../sistema/notificacao.php");

	}

}else{
	$query = $pdo->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao, data = '$data', hora = '$hora' where id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":titulo", "$titulo");
$query->execute();

$result = json_encode(array('mensagem'=>'Salvo com sucesso!', 'sucesso'=>true));

echo $result;

?>