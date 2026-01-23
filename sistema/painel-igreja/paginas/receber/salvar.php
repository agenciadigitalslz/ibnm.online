<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$descricao = $_POST['descricao'] ?? '';
$valor = $_POST['valor'] ?? '0';
$membro = $_POST['membro'] ?? '0';
$vencimento = $_POST['vencimento'] ?? '';
$data_pgto = $_POST['data_pgto'] ?? '';
$forma_pgto = $_POST['forma_pgto'] ?? '0';
$frequencia = $_POST['frequencia'] ?? '0';
$obs = $_POST['obs'] ?? '';
$id = $_POST['id'] ?? '';
$igreja = $_POST['igreja'] ?? (@$_SESSION['id_igreja'] ?? '');

$valor = str_replace(',', '.', $valor);

$valorF = @number_format($valor, 2, ',', '.');

if($membro == "" || $membro === null){
	$membro = 0;
}

if($forma_pgto == "" || $forma_pgto === null){
	$forma_pgto = 0;
}

if($frequencia == "" || $frequencia === null){
	$frequencia = 0;
}

if($data_pgto == ""){
	$pgto = '';
	$usu_pgto = '';
	$pago = 'Não';
}else{
	$pgto = " ,data_pgto = '$data_pgto'";
	$usu_pgto = " ,usuario_pgto = '$id_usuario'";
	$pago = 'Sim';
}

//validacao
if($descricao == "" and $membro == "0"){
	echo 'Selecione um Membro ou uma Descrição!';
	exit();
}

$query2 = $pdo->prepare("SELECT * FROM membros WHERE id = ? AND igreja = ?");
$query2->execute([$membro, $igreja]);
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(count($res2) > 0){
	$nome_membro = $res2[0]['nome'];
	$telefone_membro = $res2[0]['telefone'];
}else{
	$nome_membro = 'Sem Registro';
	$telefone_membro = "";
}

if($descricao == ""){
	$descricao = $nome_membro;
}

//validar troca da foto
$foto = 'sem-foto.png';
if($id != ''){
	$query = $pdo->prepare("SELECT arquivo FROM $tabela WHERE id = ? AND igreja = ?");
	$query->execute([$id, $igreja]);
	$res = $query->fetch(PDO::FETCH_ASSOC);
	if($res){
		$foto = $res['arquivo'];
	}
}



//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/contas/' .$nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
	$extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

	if(in_array($ext, $extensoes_permitidas)){

		// Validacao MIME type para seguranca adicional
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $imagem_temp);
		finfo_close($finfo);

		$mimes_permitidos = [
			'image/jpeg', 'image/png', 'image/gif', 'image/webp',
			'application/pdf',
			'application/zip', 'application/x-rar-compressed', 'application/x-rar', 'application/vnd.rar',
			'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'text/xml', 'application/xml'
		];

		if(!in_array($mime, $mimes_permitidos)){
			echo 'Tipo de arquivo não permitido!';
			exit();
		}

		//EXCLUO A FOTO ANTERIOR
		if($foto != "sem-foto.png"){
			@unlink('../../img/contas/'.$foto);
		}

		$foto = $nome_img;
		
		//pegar o tamanho da imagem
			list($largura, $altura) = getimagesize($imagem_temp);
		 	if($largura > 1400){
		 		if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'PDF' or $ext == 'RAR' or $ext == 'WEBP'){
		 			$image = imagecreatefromjpeg($imagem_temp);
			        // Reduza a qualidade para 20% ajuste conforme necessário
			        imagejpeg($image, $caminho, 20);
			        imagedestroy($image);
		 		}else{
		 			move_uploaded_file($imagem_temp, $caminho);
		 		}
			 		
		 	}else{
		 		move_uploaded_file($imagem_temp, $caminho);
		 	}
		 		


	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//verificar caixa aberto
$query1 = $pdo->prepare("SELECT id FROM caixas WHERE operador = ? AND data_fechamento IS NULL ORDER BY id DESC LIMIT 1");
$query1->execute([$id_usuario]);
$res1 = $query1->fetch(PDO::FETCH_ASSOC);
$id_caixa = $res1 ? $res1['id'] : 0;


if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET
		descricao = :descricao,
		membro = :membro,
		valor = :valor,
		vencimento = :vencimento,
		data_lanc = CURDATE(),
		forma_pgto = :forma_pgto,
		frequencia = :frequencia,
		obs = :obs,
		arquivo = :arquivo,
		subtotal = :valor,
		usuario_lanc = :usuario_lanc,
		pago = :pago,
		referencia = 'Conta',
		caixa = :caixa,
		hora = CURTIME(),
		igreja = :igreja"
		. ($data_pgto != '' ? ", data_pgto = :data_pgto, usuario_pgto = :usuario_pgto" : ""));

	$query->bindValue(":descricao", $descricao);
	$query->bindValue(":membro", $membro);
	$query->bindValue(":valor", $valor);
	$query->bindValue(":vencimento", $vencimento);
	$query->bindValue(":forma_pgto", $forma_pgto);
	$query->bindValue(":frequencia", $frequencia);
	$query->bindValue(":obs", $obs);
	$query->bindValue(":arquivo", $foto);
	$query->bindValue(":usuario_lanc", $id_usuario);
	$query->bindValue(":pago", $pago);
	$query->bindValue(":caixa", $id_caixa);
	$query->bindValue(":igreja", $igreja);
	if($data_pgto != ''){
		$query->bindValue(":data_pgto", $data_pgto);
		$query->bindValue(":usuario_pgto", $id_usuario);
	}
}else{
	$query = $pdo->prepare("UPDATE $tabela SET
		descricao = :descricao,
		membro = :membro,
		valor = :valor,
		vencimento = :vencimento,
		forma_pgto = :forma_pgto,
		frequencia = :frequencia,
		obs = :obs,
		arquivo = :arquivo,
		subtotal = :valor,
		igreja = :igreja"
		. ($data_pgto != '' ? ", data_pgto = :data_pgto" : "")
		. " WHERE id = :id AND igreja = :igreja2");

	$query->bindValue(":descricao", $descricao);
	$query->bindValue(":membro", $membro);
	$query->bindValue(":valor", $valor);
	$query->bindValue(":vencimento", $vencimento);
	$query->bindValue(":forma_pgto", $forma_pgto);
	$query->bindValue(":frequencia", $frequencia);
	$query->bindValue(":obs", $obs);
	$query->bindValue(":arquivo", $foto);
	$query->bindValue(":igreja", $igreja);
	$query->bindValue(":id", $id);
	$query->bindValue(":igreja2", $igreja);
	if($data_pgto != ''){
		$query->bindValue(":data_pgto", $data_pgto);
	}
}

$query->execute();
$ultimo_id = $pdo->lastInsertId();

//enviar whatsapp
if($api_whatsapp != 'Não' and $telefone_membro != ''){

	$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_membro);
	$mensagem_whatsapp = '💰 *'.$nome_sistema.'*%0A';
	$mensagem_whatsapp .= '_Conta Vencendo Hoje_ %0A';
	$mensagem_whatsapp .= '*Descrição:* '.$descricao.' %0A';
	$mensagem_whatsapp .= '*Valor:* '.$valorF.' %0A';	


	if($dados_pagamento != ""){
				$mensagem_whatsapp .= '*Dados para o Pagamento:* %0A';
				$mensagem_whatsapp .= $dados_pagamento;
			}
	
	$data_agd = $vencimento.' 08:00:00';
	require('../../apis/agendar.php');

	$stmtHash = $pdo->prepare("UPDATE $tabela SET hash = ? WHERE id = ?");
	$stmtHash->execute([$hash, $ultimo_id]);
	
}


echo 'Salvo com Sucesso';
 ?>