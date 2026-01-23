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
$pastor_igreja = "";



if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}



$query = $pdo->query("SELECT * FROM pastores where id = '$pastor_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_pastor = @$res[0]['nome'];



if($acao == ''){
	$acao_rel = '';
}else{
	$acao_rel = ' - '.$acao;
}


if($tabelas == ''){
	$tabelas_rel = '';
}else{
	$tabelas_rel = ' - Tabela: '.$tabelas;
}


$acao = '%'.$acao.'%';
$tabelas = '%'.$tabelas.'%';


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
						<b><big>RELATÓRIO DE LOGS </big></b>
						<br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>		
			</table>
		</div>

		<br>



		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:30%">DATA</td>
					<td style="width:25%">HORA</td>
					<td style="width:15%">TABELA</td>
					<td style="width:15%">AÇÃO</td>
					<td style="width:15%">USUÁRIO</td>
					<td style="width:15%">REGISTRO</td>
					<td style="width:10%">DESCRIÇÃO REGISTRO</td>							
					
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
					$query = $pdo->query("SELECT * FROM logs where acao LIKE '$acao' and tabela LIKE '$tabelas' and data >= '$dataInicial' and data <= '$dataFinal' order by id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$linhas = @count($res);
					if($linhas > 0){

						for($i=0; $i<$linhas; $i++){
							$data = $res[$i]['data'];
							$hora = $res[$i]['hora'];
							$tabela = $res[$i]['tabela'];
							$acao = $res[$i]['acao'];
							$usuario = $res[$i]['usuario'];
							$registro = $res[$i]['id_reg'];
							$descricao = $res[$i]['descricao'];



							$data = implode('/', array_reverse(explode('-', $data)));

					$query_con2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
					$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con2) > 0){
						$nome_usu = $res_con2[0]['nome'];
					}else{
						$nome_usu= '';
					}

					


							?>


							<tr>
								<td style="width:30%">
									<?php echo $data ?></td>
									<td style="width:25%"><?php echo $hora ?></td>
									<td style="width:15%"><?php echo $tabela ?></td>
									<td style="width:15%"><?php echo $acao ?></td>
									<td style="width:15%"><?php echo $nome_usu ?></td>
									<td style="width:15%"><?php echo $registro ?></td>
									<td style="width:10%"><?php echo $descricao ?></td>

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