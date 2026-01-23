<?php 
require_once("data_formatada.php");

$igreja_rec = $igreja;

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}

$query = $pdo->query("SELECT * FROM membros where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$igreja = $res[0]['igreja'];
$nome_membro = $res[0]['nome'];
$cargo = $res[0]['cargo'];
$data_cad = $res[0]['data_cad'];
$data_batismo = $res[0]['data_bat'];
$estado = $res[0]['estado_civil'];
$membresia = $res[0]['membresia'];

$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
$data_batF = implode('/', array_reverse(explode('-', $data_batismo)));
$membresiaF = implode('/', array_reverse(explode('-', $membresia)));

$query = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$cargo_membro = @$res[0]['nome'];

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
$nome_pastor = @$res[0]['nome'];


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
						<b><big>CARTA DE RECOMENDAÇÃO</big></b>
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

<div align="center" style="margin-top:20px">
		<u><?php echo mb_strtoupper($nome_membro) ?></u>
	</div>


	<div style="margin:20px">
		<p><small>Saudações no Senhor, <br>
		Apresentamos a <?php echo $nome_igreja ?> localizada em <?php echo $end_igreja ?>!</small></p>
	</div>

	<div style="margin:20px">
		<p><small>
			<b>Função:</b>	<u><?php echo $cargo_membro ?> </u><br>
			<b>Membro Desde:</b>	<u><?php echo $membresiaF ?> </u><br>
			<?php if($data_batismo != "" and $data_batismo != "0000-00-00"){ ?>
				<b>Batizado em:</b>	<u><?php echo $data_batF ?> </u><br>
			<?php } ?>
			<b>Estado Civil:</b>	<u><?php echo $estado ?> </u><br>
		</small></p>
	</div>


	<div style="margin:20px">
		<p><small>
			Por se achar em comunhão com esta igreja, nós o(a) recomendamos, para que o(a) recebais no Senhor, como usam fazer os Santos.
		</small></p>
	</div>


	<div align="center" style="margin-top:-10px">
		<p><small>
			<b>CONGREGAÇÃO: <?php echo mb_strtoupper($igreja_rec) ?></b>
		</small></p>
	</div>

	<div align="center" style="margin:20px">
		<p><small>
			Pela <?php echo $nome_igreja ?>, na data de <u><?php echo date('d/m/Y'); ?></u>
		</small></p>
	</div>


	<div style="margin:20px">
		<p><small>
			OBSERVAÇÕES<br>
			 <?php echo $obs ?>
		</small></p>
	</div>

	<br>
	<div align="center" style="margin:20px">
		<p><small>
			______________________________________________________________________
		<br>
		PASTOR RESPONSÁVEL
		</small></p>
	</div>


	

	<div class="footer"  align="center">
		<span style="font-size:10px">Essa carta de recomendação é válida até 30 dias a partir da data de geração</span> 
	</div>		

</div>

</body>

</html>




 