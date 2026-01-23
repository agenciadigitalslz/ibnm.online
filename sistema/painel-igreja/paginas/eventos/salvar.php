<?php 
$tabela = 'eventos';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];


$pagina = 'eventos';
$titulo = $_POST['titulo'];
$subtitulo = $_POST['areasub'];
$descricao1 = $_POST['area1'];
$descricao2 = $_POST['area2'];
$descricao3 = $_POST['area3'];
$data_evento = $_POST['data_evento'];
$video = $_POST['video'];
$tipo = $_POST['tipo'];
$pregador = $_POST['pregador'];
$obs = $_POST['obs'];

$id = @$_POST['id'];
$igreja = $_POST['igreja'];




if($tipo == 'Pregação'){
	if($pregador == ''){
		echo 'Insira o nome do Pregador!';
		exit();
	}
}

$nome_novo = strtolower( preg_replace("[^a-zA-Z0-9-]", "-", 
        strtr(utf8_decode(trim($titulo)), utf8_decode("/áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),
        "-aaaaeeiooouuncAAAAEEIOOOUUNC-")) );
$url = preg_replace('/[ -]+/' , '-' , $nome_novo);


//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/eventos/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp' or $ext == 'WEBP'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}




//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['banner']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../../img/eventos/' .$nome_img;
if (@$_FILES['banner']['name'] == ""){
	$banner = "sem-foto.jpg";
}else{
	$banner = $nome_img;
}

$imagem_temp = @$_FILES['banner']['tmp_name']; 
$ext = pathinfo($banner, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp' or $ext == 'WEBP'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem para o banner não permitida!';
	exit();
}



if($id == "" || $id == 0){
	$query = $pdo->prepare("INSERT INTO $pagina SET titulo = :titulo, subtitulo = :subtitulo, descricao1 = :descricao1, descricao2 = :descricao2, descricao3 = :descricao3, data_cad = curDate(), data_evento = '$data_evento', usuario = '$id_usuario', video = :video, ativo = 'Sim', igreja = '$igreja', imagem = '$imagem', tipo = '$tipo', banner = '$banner', url = '$url', pregador = :pregador, obs = :obs");

	$query->bindValue(":titulo", "$titulo");
$query->bindValue(":subtitulo", "$subtitulo");
$query->bindValue(":descricao1", "$descricao1");
$query->bindValue(":descricao2", "$descricao2");
$query->bindValue(":descricao3", "$descricao3");
$query->bindValue(":video", "$video");
$query->bindValue(":pregador", "$pregador");
$query->bindValue(":obs", "$obs");

$query->execute();
	
}else{
	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $pagina SET titulo = :titulo, subtitulo = :subtitulo, descricao1 = :descricao1, descricao2 = :descricao2, descricao3 = :descricao3, data_cad = curDate(), data_evento = '$data_evento', usuario = '$id_usuario', video = :video, ativo = 'Sim', igreja = '$igreja', tipo = '$tipo', url = '$url', pregador = :pregador, obs = :obs where id = '$id'");

		$query->bindValue(":titulo", "$titulo");
$query->bindValue(":subtitulo", "$subtitulo");
$query->bindValue(":descricao1", "$descricao1");
$query->bindValue(":descricao2", "$descricao2");
$query->bindValue(":descricao3", "$descricao3");
$query->bindValue(":video", "$video");
$query->bindValue(":pregador", "$pregador");
$query->bindValue(":obs", "$obs");

$query->execute();


	}else{

		$query = $pdo->query("SELECT * FROM $pagina where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['imagem'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/eventos/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $pagina SET titulo = :titulo, subtitulo = :subtitulo, descricao1 = :descricao1, descricao2 = :descricao2, descricao3 = :descricao3, data_cad = curDate(), data_evento = '$data_evento', usuario = '$id_usuario', video = :video, ativo = 'Sim', igreja = '$igreja', imagem = '$imagem', tipo = '$tipo', url = '$url', pregador = :pregador where id = '$id'");

		$query->bindValue(":titulo", "$titulo");
$query->bindValue(":subtitulo", "$subtitulo");
$query->bindValue(":descricao1", "$descricao1");
$query->bindValue(":descricao2", "$descricao2");
$query->bindValue(":descricao3", "$descricao3");
$query->bindValue(":video", "$video");
$query->bindValue(":pregador", "$pregador");

$query->execute();
	}


	if($banner == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $pagina SET titulo = :titulo, subtitulo = :subtitulo, descricao1 = :descricao1, descricao2 = :descricao2, descricao3 = :descricao3, data_cad = curDate(), data_evento = '$data_evento', usuario = '$id_usuario', video = :video, ativo = 'Sim', igreja = '$igreja', tipo = '$tipo', url = '$url', pregador = :pregador where id = '$id'");

		$query->bindValue(":titulo", "$titulo");
$query->bindValue(":subtitulo", "$subtitulo");
$query->bindValue(":descricao1", "$descricao1");
$query->bindValue(":descricao2", "$descricao2");
$query->bindValue(":descricao3", "$descricao3");
$query->bindValue(":video", "$video");
$query->bindValue(":pregador", "$pregador");

$query->execute();
	}else{

		$query = $pdo->query("SELECT * FROM $pagina where id = '$id'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$foto = $res[0]['banner'];
		if($foto != "sem-foto.jpg"){
			@unlink('../../img/eventos/'.$foto);	
		}

		$query = $pdo->prepare("UPDATE $pagina SET titulo = :titulo, subtitulo = :subtitulo, descricao1 = :descricao1, descricao2 = :descricao2, descricao3 = :descricao3, data_cad = curDate(), data_evento = '$data_evento', usuario = '$id_usuario', video = :video, ativo = 'Sim', igreja = '$igreja', banner = '$banner', tipo = '$tipo', url = '$url', pregador = :pregador where id = '$id'");

		$query->bindValue(":titulo", "$titulo");
$query->bindValue(":subtitulo", "$subtitulo");
$query->bindValue(":descricao1", "$descricao1");
$query->bindValue(":descricao2", "$descricao2");
$query->bindValue(":descricao3", "$descricao3");
$query->bindValue(":video", "$video");
$query->bindValue(":pregador", "$pregador");

$query->execute();
$ult_id = $pdo->lastInsertId();

//EXECUTAR NO LOG

	if($id == "" || $id == 0){
	$acao = 'Inserção';
	$id_reg = $ult_id;
	}else{
	$acao = 'Edição';
	$id_reg = $id;
	}
	$descricao = $titulo;
	$painel = 'Painel Igreja';
	$igreja = 0;	
	require_once("../../../logs.php");
	}
	
	
}

echo 'Salvo com Sucesso';
 ?>