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

$query2 = $pdo->query("SELECT * FROM membros where id = '$membro'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
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
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	$foto = $res[0]['arquivo'];
}else{
	$foto = 'sem-foto.png';
}



//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/contas/' .$nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'doc' or $ext == 'docx' or $ext == 'webp' or $ext == 'PNG' or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'PDF' or $ext == 'RAR' or $ext == 'ZIP' or $ext == 'DOC' or $ext == 'DOCX' or $ext == 'WEBP' or $ext == 'xlsx' or $ext == 'xlsm' or $ext == 'xls' or $ext == 'xml'){ 
	
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
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$id_caixa = @$res1[0]['id'];
}else{
	$id_caixa = 0;
}
//  , caixa = '$id_caixa'


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, membro = :membro, valor = :valor, vencimento = '$vencimento' $pgto, data_lanc = curDate(), forma_pgto = '$forma_pgto', frequencia = '$frequencia', obs = :obs, arquivo = '$foto', subtotal = :valor, usuario_lanc = '$id_usuario' $usu_pgto, pago = '$pago', referencia = 'Conta', caixa = '$id_caixa', hora = curTime(), igreja = '$igreja'");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, membro = :membro, valor = :valor, vencimento = '$vencimento' $pgto, forma_pgto = '$forma_pgto', frequencia = '$frequencia', obs = :obs, arquivo = '$foto', subtotal = :valor, igreja = '$igreja' where id = '$id'");
}


$query->bindValue(":descricao", "$descricao");
$query->bindValue(":membro", "$membro");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
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

	$pdo->query("UPDATE $tabela SET hash = '$hash' where id = '$ultimo_id'");
	
}


echo 'Salvo com Sucesso';
 ?>