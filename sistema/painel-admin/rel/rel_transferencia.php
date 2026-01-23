<?php 
require_once("../../conexao.php"); 

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if($dataInicial == $dataFinal){
	$texto_apuracao = 'APURADO EM '.$dataInicialF;
}else if($dataInicial == '1980-01-01'){
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
}else{
	$texto_apuracao = 'APURAÇÃO DE '.$dataInicialF. ' ATÉ '.$dataFinalF;
}


$nome_igreja = $nome_sistema;



if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}


$nome_pastor = "";



require_once("data_formatada.php"); 



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
		<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
	<?php } ?>


	<div id="header" >

		<div style="border-style: solid; font-size: 10px; height: 50px;">
			<table style="width: 100%; border: 0px solid #ccc;">
				<tr>
					<td style="border: 1px; solid #000; width: 7%; text-align: left;">
						<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="150px">
					</td>
					<td style="width: 30%; text-align: left; font-size: 13px;">

					</td>
					<td style="width: 1%; text-align: center; font-size: 13px;">

					</td>
					<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>TRANSFERÊNCIA DE MEMBROS </big></b>
						<br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>		
			</table>
		</div>

		<br>



		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:30%">MEMBRO</td>
					<td style="width:25%">IGREJA SAÍDA</td>
					<td style="width:15%">IGREJA ENTRADA</td>
					<td style="width:15%">DATA</td>
					<td style="width:15%">OBS</td>						
					
				</tr>
			</thead>
		</table>
	</div>

	<div id="footer" class="row">
		<hr style="margin-bottom: 0;">
		<table style="width:100%;">
			<tr style="width:100%;">
				<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_igreja ?> Telefone: <?php echo $telefone_igreja_sistema ?></td>
				<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
			</tr>
		</table>
	</div>

	<div id="content" style="margin-top: 0;">



		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php
					$query = $pdo->query("SELECT * FROM transferencias where data >= '$dataInicial' and data <= '$dataFinal' order by id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$linhas = @count($res);
					if($linhas > 0){

						for($i=0; $i<$linhas; $i++){
							$data = $res[$i]['data'];
					$membro = $res[$i]['membro'];
					$igreja_saida = $res[$i]['igreja_saida'];
					$igreja_entrada = $res[$i]['igreja_entrada'];
					$usuario = $res[$i]['usuario'];
					$obs = $res[$i]['obs'];					
					
					$data = implode('/', array_reverse(explode('-', $data)));
					$obs = mb_strimwidth($obs, 0, 65, "...");

					$query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_usu = $res_con[0]['nome'];
					}else{
						$nome_usu= '';
					}

					$query_con2 = $pdo->query("SELECT * FROM membros where id = '$membro'");
					$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con2) > 0){
						$nome_membro = $res_con2[0]['nome'];
					}else{
						$nome_membro= '';
					}

					$query_con3 = $pdo->query("SELECT * FROM igrejas where id = '$igreja_entrada'");
					$res_con3 = $query_con3->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con3) > 0){
						$nome_entrada = $res_con3[0]['nome'];
					}else{
						$nome_entrada = '';
					}

					$query_con4 = $pdo->query("SELECT * FROM igrejas where id = '$igreja_saida'");
					$res_con4 = $query_con4->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con4) > 0){
						$nome_saida = $res_con4[0]['nome'];
					}else{
						$nome_saida = '';
					}

					


							?>


							<tr>
								<td style="width:30%">
									<?php echo $nome_membro ?></td>
									<td style="width:25%"><?php echo $nome_saida ?></td>
									<td style="width:15%"><?php echo $nome_entrada ?></td>
									<td style="width:15%"><?php echo $data ?></td>
									<td style="width:15%"><?php echo $obs ?></td>

								</tr>

							<?php } } ?>
						</tbody>

					</thead>
				</table>



			</div>
			<hr>
			<table>
				<thead>
					<tbody>
						<tr>

							<td style="font-size: 10px; width:300px; text-align: right;"></td>



							<td style="font-size: 10px; width:70px; text-align: right;"></td>

							<td style="font-size: 10px; width:70px; text-align: right;"></td>


							<td style="font-size: 10px; width:140px; text-align: right;"></td>

							<td style="font-size: 10px; width:120px; text-align: right;"><b>Total Itens: <span style="color:green"><?php echo $linhas ?></span></td>

							</tr>
						</tbody>
					</thead>
				</table>

			</body>

			</html>