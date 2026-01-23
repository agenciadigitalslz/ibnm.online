<?php 
require_once("data_formatada.php");

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}

$query = $pdo->query("SELECT * FROM informativos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$igreja = $res[0]['igreja'];

$query = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_igreja = $res[0]['nome'];
$tel_igreja = $res[0]['telefone'];
$end_igreja = $res[0]['endereco'];
$imagem_igreja = $res[0]['imagem'];
$pastor_igreja = $res[0]['pastor'];
$logo_rel = $res[0]['logo_rel'];
$cab_rel = $res[0]['cab_rel'];


if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}


if($cab_rel != 'sem-foto.jpg'){ 
	$cabecalho_rel = $cab_rel;

}else{
	$cabecalho_rel = 'cabecalho-rel.jpg';
}


$query = $pdo->query("SELECT * FROM pastores where id = '$pastor_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_pastor = $res[0]['nome'];


$query = $pdo->query("SELECT * FROM informativos where id = '$id'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);
	if($total_reg > 0){		
						$id = $res[0]['id'];
						$preletor = $res[0]['preletor'];
					$data = $res[0]['data'];
					$texto_base = $res[0]['texto_base'];
					$tema = $res[0]['tema'];
					$evento = $res[0]['evento'];
					$horario = $res[0]['horario'];
					
					$pastor_responsavel = $res[0]['pastor_responsavel'];
					$pastores = $res[0]['pastores'];
					$lider_louvor = $res[0]['lider_louvor'];
					$obreiros = $res[0]['obreiros'];
					$apoio = $res[0]['apoio'];
					$abertura = $res[0]['abertura'];
					$recado = $res[0]['recado'];

					$oferta = $res[0]['oferta'];
					$recepcao = $res[0]['recepcao'];
					$bercario = $res[0]['bercario'];
					$escolinha = $res[0]['escolinha'];
					$membros = $res[0]['membros'];
					$visitantes = $res[0]['visitantes'];
					$conversoes = $res[0]['conversoes'];
					$total_ofertas = $res[0]['total_ofertas'];
					$total_dizimos = $res[0]['total_dizimos'];

					if($membros == 0){
						$membros = '';
					}

					if($visitantes == 0){
						$visitantes = '';
					}

					if($conversoes == 0){
						$conversoes = '';
					}

					if($total_ofertas == 0){
						$total_ofertas = '';
					}

					if($total_dizimos == 0){
						$total_dizimos = '';
					}

					$obs = $res[0]['obs'];

					$dataF = implode('/', array_reverse(explode('-', $data)));

					$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor_responsavel'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_pastor_resp = $res_con[0]['nome'];
					}else{
						$nome_pastor_resp = '';
					}
	}else{
		echo 'Id não reconhecido!';
		exit();
	}


?>

<!DOCTYPE html>
<html>
<head>

<style>

@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}

.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}

</style>

</head>
<body>
<?php 
if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>">	
<?php } ?>


<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>" width="160px">
				</td>
				<td style="width: 30%; text-align: left; font-size: 13px;">
					
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>FICHA DE CULTO</big></b>
							<br>
							<br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_igreja_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">


<small>
	<table style="width:100%" border="1" cellpadding="10" cellspacing="5" style="font-size: 12px; width:100%">
	<tr>
		<td class="celula"><b>Igreja: </b> <?php echo $nome_igreja ?></td>
		<td class="celula"><b>Pregrador:</b> <?php echo $preletor ?></td>
		<td class="celula"><b>Data:</b> <?php echo $dataF ?> </td>
	</tr>

	<tr>
		<td class="celula"><b>Texto Base: </b> <?php echo $texto_base ?></td>		
		<td class="celula"><b>Evento:</b> <?php echo $evento ?> </td>
		<td class="celula"><b>Horário:</b> <?php echo $horario ?></td>
	</tr>

	<tr>
		<td colspan="3" class="celula"><b>Tema: </b> <?php echo $tema ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Pastor Responsável:</b> <?php echo $nome_pastor_resp ?></td>
		<td colspan="2" class="celula"><b>Demais Pastores: </b> <?php echo $pastores ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Líder Louvor:</b> <?php echo $lider_louvor ?></td>
		<td colspan="2" class="celula"><b>Obreiros: </b> <?php echo $obreiros ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Abertura:</b> <?php echo $abertura ?></td>
		<td colspan="2" class="celula"><b>Apoio: </b> <?php echo $apoio ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Recados:</b> <?php echo $recado ?></td>
		<td colspan="2" class="celula"><b>Recepção: </b> <?php echo $recepcao ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Ofertas: </b> <?php echo $oferta ?></td>		
		<td class="celula"><b>Berçário:</b> <?php echo $bercario ?> </td>
		<td class="celula"><b>Escolinha:</b> <?php echo $escolinha ?></td>
	</tr>

	<tr>
		<td colspan="3" class="celula"><b>Observações: </b> <?php echo $obs ?></td>		
		
	</tr>

	<tr>
		<td class="celula"><b>Total Membros: </b> <?php echo $membros ?></td>		
		<td class="celula"><b>Total Visitantes:</b> <?php echo $visitantes ?> </td>
		<td class="celula"><b>Total Conversões:</b> <?php echo $conversoes ?></td>
	</tr>

	<tr>
		<td class="celula"><b>Total Ofertas: </b> <?php echo $total_ofertas ?></td>		
		<td colspan="2" class="celula"><b>Total Dízimos:</b> <?php echo $total_dizimos ?> </td>
		
	</tr>

</table>
</small>

	


</div>

</body>

</html>




 