<?php 
require_once("data_formatada.php");

if($igreja > 0){
	$sql_igreja = " where igreja_item = '$igreja' ";
}else{
	$sql_igreja = " ";
}


if ($token_rel != 'A5030') {
	echo '<script>window.location="../../"</script>';
	exit();
}

$query = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$nome_igreja = $res[0]['nome'];
	$tel_igreja = $res[0]['telefone'];
	$end_igreja = $res[0]['endereco'];
	$imagem_igreja = $res[0]['imagem'];
	$pastor_igreja = $res[0]['pastor'];
	$logo_rel = $res[0]['logo_rel'];
	$cab_rel = $res[0]['cab_rel'];	
}else{
	$logo_rel = 'logo.jpg';
	$pastor_igreja = "";
	$nome_igreja = $nome_sistema;
}




$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if($dataInicial == $dataFinal){
	$texto_apuracao = 'APURADO EM '.$dataInicialF;
}else if($dataInicial == '1980-01-01'){
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
}else{
	$texto_apuracao = 'APURAÇÃO DE '.$dataInicialF. ' ATÉ '.$dataFinalF;
}



if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}




$query = $pdo->query("SELECT * FROM pastores where id = '$pastor_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_pastor = @$res[0]['nome'];



if($status == 'Sim'){
	$status_rel = 'Ativos';
}else if($status == 'Não'){
	$status_rel = 'Inativos';
}else{
	$status_rel = '';
}


if($entrada == 'Compra'){
	$entrada_rel = 'Comprados';
}else if($status == 'Doação'){
	$entrada_rel = 'Doados';
}else{
	$entrada_rel = '';
}


$status = '%'.$status.'%';
$entrada = '%'.$entrada.'%';



if($itens == ""){
	$titulo_rel = 'Relatório de Patrimônios '.$entrada_rel. ' ' .$status_rel;
	$query = $pdo->query("SELECT * FROM patrimonios where (igreja_cad = '$igreja' or igreja_item = '$igreja') and ativo LIKE '$status' and entrada LIKE '$entrada' and data_cad >= '$dataInicial' and data_cad <= '$dataFinal' order by id desc");
}else if($itens == "1"){
	$titulo_rel = 'Patrimônios dessa Igreja '.$entrada_rel. ' ' .$status_rel;
	$query = $pdo->query("SELECT * FROM patrimonios where igreja_cad = '$igreja' and ativo LIKE '$status' and entrada LIKE '$entrada' and data_cad >= '$dataInicial' and data_cad <= '$dataFinal' order by id desc");
}else if($itens == "2"){
	$titulo_rel = 'Patrimônios Emprestados a Outros '.$entrada_rel. ' ' .$status_rel;
		$query = $pdo->query("SELECT * FROM patrimonios where (igreja_cad == '$igreja' and igreja_item != '$igreja') and ativo LIKE '$status' and entrada LIKE '$entrada' and data_cad >= '$dataInicial' and data_cad <= '$dataFinal' order by id desc");
}else{
	$titulo_rel = 'Patrimônios Emprestados a nossa Igreja '.$entrada_rel. ' ' .$status_rel;
	$query = $pdo->query("SELECT * FROM patrimonios where (igreja_cad != '$igreja' and igreja_item = '$igreja') and ativo LIKE '$status' and entrada LIKE '$entrada' and data_cad >= '$dataInicial' and data_cad <= '$dataFinal' order by id desc");
}

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
<img class="marca" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>">	
<?php } ?>


<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>" width="150px">
				</td>
				<td style="width: 30%; text-align: left; font-size: 13px;">
					
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE PATRIMÔNIOS </big></b>
						<br><?php echo mb_strtoupper($nome_igreja) ?>
							<br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
						<td style="width:10%">CÓDIGO<td/>
						<td style="width:25%">NOME</td>
						<td style="width:15%">DATA CADASTRO</td>
						<td style="width:20%">IGREJA DONA</td>
						<td style="width:20%">IGREJA POSSUI</td>
						<td style="width:10%">FOTO</td>					
					
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
$query = $pdo->query("SELECT * from patrimonios $sql_igreja order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$codigo = $res[$i]['codigo'];
	$nome = $res[$i]['nome'];
	$igreja_cad = $res[$i]['igreja_cad'];
	$igreja_item = $res[$i]['igreja_item'];
	$ativo = $res[$i]['ativo'];
	

	$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja_cad'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig_cad = $res_con[0]['nome'];
					}else{
						$nome_ig_cad = '';
					}

					$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja_item'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig_item = $res_con[0]['nome'];
					}else{
						$nome_ig_item = '';
					}


					if($igreja_cad == $igreja){
						if($igreja_item == $igreja){
							$classe_item = '';	
						}else{
							$classe_item = 'text-danger';
						}
					}else{
						$classe_item = 'text-primary';
					}

					if($ativo == 'Sim'){
						$inativa = '';
					}else{
						$inativa = 'text-muted';
					}
	



	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$valorF = @number_format($valor_compra, 2, ',', '.');



  	 ?>

  	 
      <tr>
<td style="width:10%">
	<?php echo $codigo ?></td>
<td style="width:25%"><?php echo $nome ?></td>
<td class="coluna" style="width:15%"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_cad']))) ?></td>
<td style="width:20%">R$ <?php echo $nome_ig_cad ?></td>
<td style="width:20%"><?php echo $nome_ig_item ?></td>
<td class="coluna" style="width:10%"><img src="<?php echo $url_sistema ?>img/patrimonios/<?php echo $res[$i]['foto'] ?>" width="30px"> </td>
5

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