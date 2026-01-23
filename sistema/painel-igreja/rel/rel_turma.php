<?php 

require_once("data_formatada.php"); 

if ($token_rel != 'M543661') {
	echo '<script>window.location="../../"</script>';
	exit();
}

$query = $pdo->query("SELECT * from turmas where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

						$nome = $res[0]['nome'];	
					$dias = $res[0]['dias'];
					$hora = $res[0]['hora'];
					$local = $res[0]['local'];
					$pastor = $res[0]['pastor'];
					$coordenador = $res[0]['coordenador'];
					$lider1 = $res[0]['lider1'];
					$lider2 = $res[0]['lider2'];
					$obs = $res[0]['obs'];
					$igreja = $res[0]['igreja'];
					$id = $res[0]['id'];

					$data_inicio = $res[0]['data_inicio'];
					$data_termino = $res[0]['data_termino'];
					$status = $res[0]['status'];


					//totalizar membros
					$query2 = $pdo->query("SELECT * FROM celulas_membros where igreja = '$igreja' and celula = '$id'");
					$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					$membros_celula = count($res2);


					if($obs != ""){
						$classe_obs = 'text-warning';
					}else{
						$classe_obs = 'text-secondary';
					}


					$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_pastor = $res_con[0]['nome'];
					}else{
						$nome_pastor = 'Nenhum';
					}

					$query_con = $pdo->query("SELECT * FROM membros where id = '$coordenador'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_coordenador = $res_con[0]['nome'];
					}else{
						$nome_coordenador = 'Nenhum';
					}

					$query_con = $pdo->query("SELECT * FROM membros where id = '$lider1'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_lider1 = $res_con[0]['nome'];
					}else{
						$nome_lider1 = 'Nenhum';
					}


					$query_con = $pdo->query("SELECT * FROM membros where id = '$lider2'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_lider2 = $res_con[0]['nome'];
					}else{
						$nome_lider2 = 'Nenhum';
					}

					$data_inicioF = implode('/', array_reverse(explode('-', $data_inicio)));
					$data_terminoF = implode('/', array_reverse(explode('-', $data_termino)));

					$classe_status = '';
					if($status == 'Não Iniciada'){
						$classe_status = '#a60d08';
					}

					if($status == 'Andamento'){
						$classe_status = '#065b80';
					}

					if($status == 'Finalizada'){
						$classe_status = '#02360e';
					}


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


if($data_inicioF == '00/00/0000'){
	$data_inicioF = 'Não Lançada';
}

if($data_terminoF == '00/00/0000'){
	$data_terminoF = 'Não Lançada';
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
	opacity:.1%;
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
					<img style="margin-top: -2px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>" width="160px">

				</td>
				
				<td style="width: 5%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 40%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>DETALHAMENTO <?php echo mb_strtoupper($nome) ?></big></b><br><br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#f2f0f0">
					<td style="width:33%"><b>STATUS : <?php echo $status ?></b> </td>
					<td style="width:33%"><b>DIAS : <?php echo $dias ?> </b></td>
					<td style="width:34%"><b>HORA : <?php echo $hora ?></b></td>			
					
					
				</tr>
			</thead>
		</table>




		<table id="cabecalhotabela" style="border-style: solid; font-size: 9px; margin-bottom:10px; width: 100%; table-layout: fixed; margin-top:10px;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#f2f0f0">
					<td colspan="4" style="width:100%; font-size: 10px"><b>DADOS DA TURMA</b> </td>					
				</tr>

				<tr >
					<td style="width:20%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">DATA INÍCIO: </td>
					<td style="width:30%; border-right: : 1px solid #000; border-bottom: : 1px solid #000;"><b>
						<?php echo mb_strtoupper($data_inicioF) ?></b>
					</td>
					
					<td style="width:20%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">DATA TÉRMINO: </td>
					<td style="width:30%; border-bottom: : 1px solid #000;"><b>
						<?php echo mb_strtoupper($data_terminoF) ?></b>
					</td>
    			</tr>

				<tr >
					<td style="width:20%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">PASTOR RESPONSÁVEL: </td>
					<td style="width:30%; border-right: : 1px solid #000; border-bottom: : 1px solid #000;"><b>
						<?php echo mb_strtoupper($nome_pastor) ?></b>
					</td>
					
					<td style="width:20%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">MINISTRADOR: </td>
					<td style="width:30%; border-bottom: : 1px solid #000;"><b>
						<?php echo mb_strtoupper($nome_coordenador) ?></b>
					</td>
    			</tr>
    			<tr >
					<td style="width:10%; border-right: 1px solid #000; border-bottom: : 1px solid #000;">DEMAIS RESPONSÁVEIS: </td>
					<td style="width:40%; border-right: : 1px solid #000; border-bottom: : 1px solid #000;"><b>
						<?php echo mb_strtoupper($nome_lider1) ?></b>
					</td>
					
					<td style="width:10%; border-right: 1px solid #000; border-bottom: : 1px solid #000;">DEMAIS RESPONSÁVEIS: </td>
					<td style="width:40%; border-bottom: : 1px solid #000;"><b>	
						<?php echo mb_strtoupper($nome_lider2) ?></b>
					</td>
    			</tr>

    			<tr >
					<td style="width:20%; border-right: 1px solid #000; ">LOCAL: </td>
					<td style="width:80%; border-right: : 1px solid #000; " colspan="3"><b>
						<?php echo mb_strtoupper($local) ?></b>
					</td>
					
					
    			</tr>
			</thead>
		</table>


<?php 
$query = $pdo->query("SELECT * FROM progresso_turma where turma = '$id' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
 ?>
		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:10%">DATA</td>
					<td style="width:22%">MINISTRADOR</td>
					<td style="width:8%">MEMBROS</td>
					<td style="width:60%">CONTEÚDO MINISTRADO</td>	
					
				</tr>
			</thead>
		</table>


		<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase; margin-top: -5px">
			<thead>
				<tbody>
					<?php 
for($i=0; $i<$linhas; $i++){
	
	$data = $res[$i]['data'];	
	$membros = $res[$i]['membros'];
	$ministrador = $res[$i]['ministrador'];
	$conteudo = $res[$i]['conteudo'];
	$obs = $res[$i]['obs'];
	$id_and = $res[$i]['id'];
	$arquivo = $res[$i]['arquivo'];
	
	$dataF = implode('/', array_reverse(explode('-', $data)));

	$query_con = $pdo->query("SELECT * FROM membros where id = '$ministrador'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ministrador = $res_con[0]['nome'];
					}else{
						$nome_ministrador = 'Nenhum';
					}
	
  	 ?>

  	 
<tr>
<td style="width:10%"><?php echo $dataF ?></td>
<td style="width:22%"><?php echo $nome_ministrador ?></td>
<td style="width:8%"><?php echo $membros ?></td>
<td style="width:60%; text-transform: all;"><?php echo $conteudo ?></td>


    </tr>

<?php } ?>
				</tbody>
	
			</thead>
		</table>

		<?php } ?>






<?php 
$query = $pdo->query("SELECT * FROM turmas_membros where celula = '$id' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

 ?>

		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed; margin-top: 20px">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:25%">MEMBROS DA TURMA</td>
					<td style="width:15%">TELEFONE</td>
					<td style="width:60%">ENDEREÇO</td>										
				</tr>
			</thead>
		</table>


		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase; margin-top: -5px">
			<thead>
				<tbody>
					<?php 
for($i=0; $i<$linhas; $i++){	
	$membro = $res[$i]['membro'];

	$query_con = $pdo->query("SELECT * FROM membros where id = '$membro'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_membro = $res_con[0]['nome'];
						$tel_membro = $res_con[0]['telefone'];
						$end_membro = $res_con[0]['endereco'];
					}
	
  	 ?>

  	 
<tr>
<td style="width:25%"><?php echo @$nome_membro ?></td>
<td style="width:15%"><?php echo @$tel_membro ?></td>
<td style="width:60%"><?php echo @$end_membro ?></td>


    </tr>

<?php } ?>
				</tbody>
	
			</thead>
		</table>

		<?php } ?>




<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_igreja ?> Telefone: <?php echo $tel_igreja ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>


	
</body>

</html>


