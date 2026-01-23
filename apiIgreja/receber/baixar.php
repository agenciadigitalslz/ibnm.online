<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];
$id_usuario = @$_GET['user'];

$query = $pdo->query("UPDATE receber SET pago = 'Sim', usuario_baixa = '$id_usuario', data_baixa = curDate() where id = '$id'");


//RECUPERAR INFORMAÇÕES DA CONTA
$query = $pdo->query("SELECT * FROM receber where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $res[0]['valor'];
$descricao = $res[0]['descricao'];
$vencimento = $res[0]['vencimento'];
$membro = $res[0]['membro'];
$igreja = $res[0]['igreja'];

//INSIRO NAS MOVIMENTACOES
$pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', movimento = 'Venda', descricao = '$descricao', valor = '$valor', data = curDate(), usuario = '$id_usuario', id_mov = '0', igreja = '$igreja'");


?>