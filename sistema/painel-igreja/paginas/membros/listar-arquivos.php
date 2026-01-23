<?php 
require_once("../conexao.php"); 

$igreja = $_GET['igreja'];
$status = $_GET['status'];
$cargo = $_GET['cargo'];


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
	<title>Relatório de Membros</title>
	<link rel="shortcut icon" href="../img/favicon.ico" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

	<style>

		@page {
			margin: 0px;

		}

		body{
			margin-top:0px;
			font-family:Times, "Times New Roman", Georgia, serif;
		}


		<?php if($relatorio_pdf == 'Sim'){ ?>

			.footer {
				margin-top:20px;
				width:100%;
				background-color: #ebebeb;
				padding:5px;
				position:absolute;
				bottom:0;
			}

		<?php }else{ ?>
			.footer {
				margin-top:20px;
				width:100%;
				background-color: #ebebeb;
				padding:5px;
				
			}

		<?php } ?>

		.cabecalho {    
			padding:10px;
			margin-bottom:30px;
			width:100%;
			font-family:Times, "Times New Roman", Georgia, serif;
		}

		.titulo_cab{
			color:#0340a3;
			font-size:17px;
		}

		
		
		.titulo{
			margin:0;
			font-size:28px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;

		}

		.subtitulo{
			margin:0;
			font-size:12px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;
		}



		hr{
			margin:8px;
			padding:0px;
		}


		
		.area-cab{
			
			display:block;
			width:100%;
			height:10px;

		}

		
		.coluna{
			margin: 0px;
			float:left;
			height:30px;
		}

		.area-tab{
			
			display:block;
			width:100%;
			height:30px;

		}


		.imagem {
			width: 100%;
		}

		.titulo_img {
		position: absolute;
		margin-top: 10px;
		margin-left: 10px;
		
		}

		.data_img {
		position: absolute;
		margin-top: 40px;
		margin-left: 10px;
		
		}

		

	</style>


</head>
<body>

	<?php if($cabecalho_rel_img != 'Sim'){ ?>
	<section class="area-cab">
		<div class="cabecalho">

			
			<div class="coluna titulo_cab" style="width:50%"> <u><?php echo $titulo_rel ?></u></div>		
						
			<div align="right" class="coluna" style="width:50%"> <img style="margin-top:5px" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $imagem_igreja ?>" width="30px"> <?php echo mb_strtoupper($nome_igreja) ?></div>

			
		</div>
	</section>

	<br>

	<section class="area-cab">
		<div class="cabecalho">
			<div class="coluna" style="width:60%"><small> <small><small><?php echo mb_strtoupper($data_hoje) ?></small></small></small></div>
			<div align="right" class="coluna" style="width:40%"><small> <small><small> <?php echo $end_igreja ?></small></small></small></div>
		</div>
	</section>

	<?php }else{ ?>
		<div class="titulo_cab titulo_img"><u><?php echo $titulo_rel ?></u></div>	
		<div class="data_img"><small> <small><small><?php echo mb_strtoupper($data_hoje) ?></small></small></small></div>

		<img class="imagem" src="<?php echo $url_sistema ?>img/igrejas/<?php echo $cabecalho_rel ?>">
	<?php } ?>

	<br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>


	
	<?php 
	$query = $pdo->query("SELECT * FROM membros where igreja = '$igreja' and ativo LIKE '$status' and cargo LIKE '$cargo' order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);
	if($total_reg > 0){
		?>

		
		<div class="mx-2" style="padding-top:15px ">

			<small><small><small>
				<section class="area-tab" style="background-color: #f5f5f5;">
					
					<div class="linha-cab" style="padding-top: 5px;">
						<div class="coluna" style="width:25%">NOME</div>
						<div class="coluna" style="width:15%">CARGO</div>
						<div class="coluna" style="width:20%">EMAIL</div>
						<div class="coluna" style="width:15%">TELEFONE</div>
						<div class="coluna" style="width:15%">DATA CADASTRO</div>
						<div class="coluna" style="width:10%">FOTO</div>

					</div>
					
				</section><small></small>

				<div class="cabecalho mb-1" style="border-bottom: solid 1px #e3e3e3;">
				</div>

				<?php
				 for($i=0; $i < $total_reg; $i++){
					foreach ($res[$i] as $key => $value){}
					$cargo = $res[$i]['cargo'];

					$query_con = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_cargo = $res_con[0]['nome'];
					}else{
						$nome_cargo = '';
					} 
				?>

				<section class="area-tab" style="padding-top:5px">					
					<div class="linha-cab">				
						<div class="coluna" style="width:25%"><?php echo $res[$i]['nome'] ?></div>
						<div class="coluna" style="width:15%"><?php echo $nome_cargo ?></div>
						<div class="coluna" style="width:20%"><?php echo $res[$i]['email'] ?></div>				
						<div class="coluna" style="width:15%"><?php echo $res[$i]['telefone'] ?></div>
						<div class="coluna" style="width:15%"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_cad']))) ?></div>				
						<div class="coluna" style="width:10%"><img src="<?php echo $url_sistema ?>img/membros/<?php echo $res[$i]['foto'] ?>" width="30px"> </div>				

					</div>
				</section>
				<div class="cabecalho" style="border-bottom: solid 1px #e3e3e3;">
				</div>

			<?php } ?>

			</small></small>



		</div>


		<div class="cabecalho mt-3" style="border-bottom: solid 1px #0340a3">
		</div>


	<?php }else{
		echo 'Sem Registros no banco de dados!';
	} ?>



	<div class="col-md-12 p-2">
			<div class="" align="right">
				<span class=""> <small><small><small><small>TOTAL DE REGISTROS</small> :  <?php echo $total_reg ?></small></small></small>  </span>
			</div>
		</div>
		<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
		</div>




	<div class="footer"  align="center">
		<span style="font-size:10px"><?php echo $end_igreja ?> - Telefone: <?php echo $tel_igreja ?> - Pastor Responsável: <?php echo $nome_pastor ?></span> 
	</div>



</body>
</html>