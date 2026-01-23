<?php 

if($igreja > 0){
	$sql_igreja = " and igreja = '$igreja' ";
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




if($logo_rel != 'sem-foto.jpg'){ 
	$imagem_igreja = $logo_rel;

}else{
	$imagem_igreja = 'logo-rel.jpg';
}



$query = $pdo->query("SELECT * FROM pastores where id = '$pastor_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_pastor = @$res[0]['nome'];


if($cargo != ""){
	$query = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_cargo = $res[0]['nome'];
}else{
	$nome_cargo = "";
}

if($status == 'Sim'){
	$status_rel = 'Ativos';
}else if($status == 'Não'){
	$status_rel = 'Inativos';
}else{
	$status_rel = '';
}


if($nome_cargo == "" || $status == "Membro"){
	$titulo_rel = 'Relatório de Membros '.$status_rel;
}else{
	$titulo_rel = 'Relatório de '.$nome_cargo.' '.$status_rel;
}

require_once("data_formatada.php"); 


$status = '%'.$status.'%';
$cargo = '%'.$cargo.'%';

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
						<b><big>RELATÓRIO DE MEMBROS </big></b>
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
					<td style="width:30%">NOME</td>
					<td style="width:15%">CARGO</td>
					<td style="width:25%">EMAIL</td>
					<td style="width:10%">TELEFONE</td>
					<td style="width:10%">CADASTRO</td>
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



		<table style="width: 100%; table-layout: fixed; font-size:8px;">
			<thead>
				<tbody>
					<?php
$query = $pdo->query("SELECT * FROM membros where ativo LIKE '$status' and cargo LIKE '$cargo' $sql_igreja order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$cargo = $res[$i]['cargo'];
	$email = $res[$i]['email'];
	$telefone = $res[$i]['telefone'];
	$id_igreja = $res[$i]['igreja'];

	$query_con2 = $pdo->query("SELECT * FROM igrejas where id = '$id_igreja'");
		$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con2) > 0){
			$nome_igreja = $res_con2[0]['nome'];
		}else{
			$nome_igreja = '';
		}


	$query_con2 = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
		$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con2) > 0){
			$nome_cargo = $res_con2[0]['nome'];
		}else{
			$nome_cargo = '';
		}


  	 ?>

  	 
      <tr>
<td style="width:30%">
	<?php echo $nome ?> <?php if($igreja == 0){ echo '<span style="color:blue"><small>('.$nome_igreja.')</small></span>'; } ?></td>
<td style="width:15%"><?php echo $nome_cargo ?></td>
<td style="width:25%"><?php echo $email ?></td>
<td style="width:10%"><?php echo $telefone ?></td>
<td style="width:10%"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_cad']))) ?></td>				
						<td style="width:10%"><img src="<?php echo $url_sistema ?>img/membros/<?php echo $res[$i]['imagem'] ?>" width="20px"> </td>

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