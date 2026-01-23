<?php 
require_once("data_formatada.php"); 
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));

$separar = explode("-", $dataInicial);
$mes = $separar[1];
$ano = $separar[0];

$mesF = '';
if($mes == '01'){
	$mesF = "Janeiro";
}

if($mes == '02'){
	$mesF = "Fevereiro";
}

if($mes == '03'){
	$mesF = "Março";
}

if($mes == '04'){
	$mesF = "Abril";
}

if($mes == '05'){
	$mesF = "Maio";
}

if($mes == '06'){
	$mesF = "Junho";
}

if($mes == '07'){
	$mesF = "Julho";
}

if($mes == '08'){
	$mesF = "Agosto";
}

if($mes == '09'){
	$mesF = "Setembro";
}

if($mes == '10'){
	$mesF = "Outubro";
}

if($mes == '11'){
	$mesF = "Novembro";
}

if($mes == '12'){
	$mesF = "Dezembro";
}



$nome_igreja = $nome_sistema;



if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}





$nome_pastor = "";







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
						<b><big>FECHAMENTOS DO MÊS <small style="color: #134391;"><?php echo $mesF ?></small> </big></b>
						<br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>		
			</table>
		</div>

		<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:10%">DATA</td>
					<td style="width:12%">IGREJA</td>
					<td style="width:12%">SAÍDAS</td>
					<td style="width:12%">ENTRADAS</td>
					<td style="width:12%">SALDO</td>
					<td style="width:12%">PREBENDA</td>	
					<td style="width:12%">SALDO FINAL</td>							
					
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
					$query = $pdo->query("SELECT * FROM fechamentos where month(data_fec) = '$mes' and year(data_fec) = '$ano' order by id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$linhas = @count($res);
					if($linhas > 0){

						for($i=0; $i<$linhas; $i++){
							$data_fec = $res[$i]['data_fec'];	
							$data = $res[$i]['data'];
							$saidas = $res[$i]['saidas'];
							
							$entradas = $res[$i]['entradas'];
							$saldo = $res[$i]['saldo'];
							$prebenda = $res[$i]['prebenda'];
							$saldo_final = $res[$i]['saldo_final'];
							$igreja = $res[$i]['igreja'];
							
							$id = $res[$i]['id'];

							$dataF = implode('/', array_reverse(explode('-', $data)));
							$data_fecF = implode('/', array_reverse(explode('-', $data_fec)));
							$entradasF = number_format($entradas, 2, ',', '.');
							$saidasF = number_format($saidas, 2, ',', '.');
							$saldoF = number_format($saldo, 2, ',', '.');
							$prebendaF = number_format($prebenda, 2, ',', '.');
							$saldo_finalF = number_format($saldo_final, 2, ',', '.');

							$separar = explode("-", $data_fec);
							$mes = $separar[1];
							$ano = $separar[0];
							
						

							$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
							$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
							if(count($res_con) > 0){
								$nome_igreja = $res_con[0]['nome'];
							}
							

							if($saldo >= 0){
								$cor_saldo = 'green';
							}else{
								$cor_saldo = 'red';
							}

							if($saldo_final >= 0){
								$cor_saldo_final = 'green';
							}else{
								$cor_saldo_final = 'red';
							}


							?>

							
							<tr>
								<td style="width:10%"><?php echo $data_fecF ?></td>
								<td style="width:12%"><?php echo $nome_igreja ?></td>
								<td style="width:12%; color:red">R$ <?php echo $saidasF ?></td>
								<td style="width:12%; color:green">R$ <?php echo $entradasF ?></td>
								<td style="width:12%; color:<?php echo $cor_saldo ?>">R$ <?php echo $saldoF ?></td>
								<td style="width:12%">R$ <?php echo $prebendaF ?></td>
								<td style="width:12%; color:<?php echo $cor_saldo_final ?>">R$ <?php echo $saldo_finalF ?></td>
									

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