<?php 
include('data_formatada.php');

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}

$nome_sede = $nome_igreja_sistema;

$query = $pdo->query("SELECT * FROM membros where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome = $res[0]['nome'];
$igreja = $res[0]['igreja'];
$cpf = $res[0]['cpf'];
$foto = $res[0]['imagem'];
$data_bat = $res[0]['data_bat'];
$cargo = $res[0]['cargo'];

$query = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_igreja = $res[0]['nome'];
$tel_igreja = $res[0]['telefone'];
$end_igreja = $res[0]['endereco'];
$imagem_igreja = $res[0]['imagem'];
$pastor_igreja = $res[0]['pastor'];
$logo_rel = $res[0]['logo_rel'];
$cart_rel = $res[0]['carteirinha_rel'];


if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}


if($cart_rel != 'sem-foto.jpg'){ 
	$imagem_carteirinha = $cart_rel;

}else{
	$imagem_carteirinha = 'carteirinha-cab.jpg';
}

$dados_igreja = $end_igreja . ' '.$tel_igreja;




$query = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cargo = @$res[0]['nome'];

if($data_bat == "0000-00-00"){
	$data_bat = date('Y-m-d');
}
$data_bat = implode('/', array_reverse(explode('-', $data_bat)));

?>

<!DOCTYPE html>
<html>
<head>
	<title>Certificado de Batismo</title>
	<link rel="shortcut icon" href="../img/favicon.ico" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

	
	<style>

 @page {
            margin: 0px;
            
        }


.imagem {
width: 100%;
}


.imagem-igreja {
position: absolute;
margin-top: 5px;
width:60px;
margin-left: 10px;
}	

.nome-membro {
position: absolute;
margin-top: 337px;
margin-left: 315px;
color:#913610;
font-size:30px;
width:100%;

}


.imagem-membro {
width:100%;
height:100%;
border-radius:7px;
}	

.area-foto{
position: absolute;
margin-top: 75px;
width:65px;
height:78px;
margin-left: 15px;
border-radius:7px;
}

.nome-sede {
position: absolute;
margin-top: 9px;
margin-left: 105px;
color:#545454;
font-size:30px;
width:100%;

}


.dados-igreja {
position: absolute;
margin-top: 24px;
margin-left: 95px;
color:#545454;
font-size:5px;
width:100%;

}


.data-atual {
position: absolute;
margin-top: 380px;
margin-left: 310px;
color:#2b2b2b;
font-size:27px;
width:100%;
font-weight: bold;
}


.cargo {
position: absolute;
margin-top: 84px;
margin-left: 95px;
color:#545454;
font-size:8px;
width:100%;

}


.congregacao {
position: absolute;
margin-top: 495px;
margin-left: 380px;
color:#545454;
font-size:20px;
width:100%;

}


.nascimento {
position: absolute;
margin-top: 143px;
margin-left: 95px;
color:#545454;
font-size:8px;
width:100%;

}


.cpf {
position: absolute;
margin-top: 143px;
margin-left: 240px;
color:#545454;
font-size:8px;
width:100%;
}





</style>


<body>


<div class="congregacao"> <?php echo mb_strtoupper($nome_igreja); ?></div>
<div class="nome-membro"> <?php echo mb_strtoupper($nome); ?></div>
<div class="data-atual"><?php echo $data_bat; ?> </div>

<img class="imagem" src="<?php echo $url_sistema ?>img/batismo.jpg">



</body>


</html>