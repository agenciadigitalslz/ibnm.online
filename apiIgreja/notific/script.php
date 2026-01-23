<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Script</title>
</head>
<body>

<?php 
require_once("../conexao.php");

// Carrega configurações do sistema (api_whatsapp, token, instancia)
try {
	$cfg = $pdo->query("SELECT api_whatsapp, token_whatsapp, instancia_whatsapp, telefone FROM config LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	$api_whatsapp = $cfg['api_whatsapp'] ?? 'Não';
	$token_whatsapp = $cfg['token_whatsapp'] ?? '';
	$instancia_whatsapp = $cfg['instancia_whatsapp'] ?? '';
	$telefone_igreja_sistema = $cfg['telefone'] ?? '';
} catch (Exception $e) {
	$api_whatsapp = 'Não';
	$token_whatsapp = '';
	$instancia_whatsapp = '';
	$telefone_igreja_sistema = '';
}


// Verifica coluna 'notificacao' para compatibilidade com bases antigas
$hasNotificacao = false;
try {
	$check = $pdo->query("SHOW COLUMNS FROM config LIKE 'notificacao'");
	$hasNotificacao = $check && $check->rowCount() > 0;
} catch (Exception $e) {
	$hasNotificacao = false;
}

if ($hasNotificacao) {
	$query = $pdo->query("SELECT * FROM config where notificacao = curDate()");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_not = @count($res);
} else {
	// Sem coluna 'notificacao': não roda rotina diária legacy de páginas (1..4)
	$total_not = 1;
}

if($hasNotificacao && $total_not == 0){

	$query_ig = $pdo->query("SELECT * FROM igrejas");
	$res_ig = $query_ig->fetchAll(PDO::FETCH_ASSOC);	
	for($i_ig=0; $i_ig < @count($res_ig); $i_ig++){
		foreach ($res_ig[$i_ig] as $key => $value){} 
		$id_igreja = $res_ig[$i_ig]['id'];

	$query = $pdo->query("SELECT * FROM pagar where igreja = '$id_igreja' and vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pagarHoje = @count($res);


$query = $pdo->query("SELECT * FROM receber where igreja = '$id_igreja' and vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$receberHoje = @count($res);


$query = $pdo->query("SELECT * from tarefas where igreja = '$id_igreja' and data = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalTarefas = @count($res);



//total de aniversariantes
$dataMes = Date('m');
$dataDia = Date('d');
$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");
	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);
$res_pastores = $query_pastores->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_pastores = count($res_pastores);
$totalAniversariantes = $total_reg + $total_reg_pastores;

	//NOTIFICAR CONTAS A PAGAR
	if(@$_GET['pagina'] == '1'){
		$query_not = $pdo->query("SELECT * FROM token where igreja = '$id_igreja' and (nivel = 'pastor' or nivel = 'tesoureiro')");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Contas à Pagar';
	$conteudo_not = '('.$pagarHoje. ') Contas Hoje';
	require("../../sistema/notificacao.php");

	}
	
	}
	

	if(@$_GET['pagina'] == '2'){
	//NOTIFICAR CONTAS A RECEBER
	$query_not = $pdo->query("SELECT * FROM token where igreja = '$id_igreja' and (nivel = 'pastor' or nivel = 'tesoureiro')");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Contas à Receber';
	$conteudo_not = '('.$receberHoje. ') Contas Hoje';
	require("../../sistema/notificacao.php");

	}
	
	}


	if(@$_GET['pagina'] == '3'){
	//NOTIFICAR ANIVERSARIANTES
	$query_not = $pdo->query("SELECT * FROM token where igreja = '$id_igreja' and (nivel = 'pastor' or nivel = 'secretario')");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Aniversariantes';
	$conteudo_not = '('.$totalAniversariantes. ') Hoje';
	require("../../sistema/notificacao.php");

	}
	
	}


	if(@$_GET['pagina'] == '4'){
	//NOTIFICAR TAREFAS
	$query_not = $pdo->query("SELECT * FROM token where igreja = '$id_igreja'");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = 'Tarefas';
	$conteudo_not = '('.$totalTarefas. ') Hoje';
	require("../../sistema/notificacao.php");

	}

	if ($hasNotificacao) {
		$query = $pdo->query("UPDATE config SET notificacao = curDate()");
	}
	
	}


}



}else{
	$_GET['pagina'] = 0;
}






//ENVIO BASEADO EM HORÁRIO
$hora_30 = date("H:i",strtotime(date("H:i")." +30 minutes"));

$query_ig = $pdo->query("SELECT * FROM igrejas");
	$res_ig = $query_ig->fetchAll(PDO::FETCH_ASSOC);	
	for($i_ig=0; $i_ig < @count($res_ig); $i_ig++){
		foreach ($res_ig[$i_ig] as $key => $value){} 
		$id_igreja = $res_ig[$i_ig]['id'];

	// Aniversariantes automático às 06:00 (WhatsApp)
	$hora_agora = date("H:i");
	$modo_teste = (isset($_GET['teste']) && $_GET['teste'] == '1');
	if (($hora_agora == '06:00' || $modo_teste) && $api_whatsapp != 'Não') {
		$mes = date('m');
		$dia = date('d');

		// Membros aniversariantes
		$stmt = $pdo->prepare("SELECT nome, telefone FROM membros WHERE igreja = :igreja AND ativo = 'Sim' AND MONTH(data_nasc) = :mes AND DAY(data_nasc) = :dia");
		$stmt->bindValue(':igreja', $id_igreja);
		$stmt->bindValue(':mes', $mes);
		$stmt->bindValue(':dia', $dia);
		$stmt->execute();
		$dest_membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Pastores aniversariantes
		$stmt2 = $pdo->prepare("SELECT nome, telefone FROM pastores WHERE igreja = :igreja AND MONTH(data_nasc) = :mes AND DAY(data_nasc) = :dia");
		$stmt2->bindValue(':igreja', $id_igreja);
		$stmt2->bindValue(':mes', $mes);
		$stmt2->bindValue(':dia', $dia);
		$stmt2->execute();
		$dest_pastores = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		$destinatarios = array_merge($dest_membros ?: [], $dest_pastores ?: []);
		$enviados = [];

		foreach ($destinatarios as $dest) {
			$nome_dest = trim($dest['nome'] ?? '');
			$tel_dest = trim($dest['telefone'] ?? '');
			if ($tel_dest == '') { continue; }

			$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $tel_dest);

			// Modo de teste: envia para número específico
			if ($modo_teste) {
				$telefone_envio = '5598987417250';
			}

			$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
			$mensagem_whatsapp .= '🎉 Feliz Aniversário, '.$nome_dest.'!%0A';
			$mensagem_whatsapp .= 'Que o Senhor abençoe sua vida com graça e paz. Estamos com você em oração.%0A';
			$mensagem_whatsapp .= '— Igreja Batista Nacional Maracanã';

			require("../../sistema/painel-igreja/apis/texto.php");

			$enviados[] = $nome_dest;
		}

		// Fallback: confirmar envio ao Super Admin (telefone do sistema)
		if (!empty($enviados) && !empty($telefone_igreja_sistema)) {
			$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_igreja_sistema);
			if ($modo_teste) {
				$telefone_envio = '5598987417250';
			}
			$lista = implode(', ', array_slice($enviados, 0, 5));
			$mais = count($enviados) > 5 ? ' +'.(count($enviados)-5).' mais' : '';
			$mensagem_whatsapp = '*'.$nome_sistema.'*%0A';
			$mensagem_whatsapp .= '✅ Parabéns enviados hoje às 06:00 para '.count($enviados).' aniversariante(s).%0A';
			if ($lista != '') { $mensagem_whatsapp .= 'Ex.: '.$lista.$mais.'%0A'; }
			$mensagem_whatsapp .= 'Deus abençoe!';

			require("../../sistema/painel-igreja/apis/texto.php");
		}
	}

$query_tar = $pdo->query("SELECT * FROM tarefas where igreja = '$id_igreja' and hora = '$hora_30' and data = curDate() and status = 'Agendada'");
$res_tar = $query_tar->fetchAll(PDO::FETCH_ASSOC);
$tar = @count($res_tar);

if($tar > 0){
	$horaF = (new DateTime($res_tar[0]['hora']))->format('H:i');

	$titulo_tar = 'Tarefa '.$res_tar[0]['titulo'];
	$desc_tar = 'Hoje as '. $horaF;


	//NOTIFICAR TAREFAS
	$query_not = $pdo->query("SELECT * FROM token where igreja = '$id_igreja'");	
	$res_not = $query_not->fetchAll(PDO::FETCH_ASSOC);
	for ($i_not=  0; $i_not < count($res_not); $i_not++) { 
		foreach ($res_not[$i_not] as $key => $value) {
	}

	$token = $res_not[$i_not]['token'];
	$titulo_not = $titulo_tar;
	$conteudo_not = $desc_tar;
	require("../../sistema/notificacao.php");

	}

}


}



if(!isset($_GET['pagina']) || $_GET['pagina'] >= 4){
	echo "<meta HTTP-EQUIV='refresh' CONTENT='60;URL=script.php?pagina=1'>"; 
}else{
	$valor = @$_GET['pagina'] + 1;
	echo "<meta HTTP-EQUIV='refresh' CONTENT='60;URL=script.php?pagina=$valor'>"; 
}


echo 'Mantenha este script aberto para ele buscar notificações a cada minuto!';
if (isset($_GET['teste']) && $_GET['teste'] == '1') {
	echo "<br>Modo de teste ativo: envios direcionados para 98 98741-7250.";
}

?>

</body>
</html>