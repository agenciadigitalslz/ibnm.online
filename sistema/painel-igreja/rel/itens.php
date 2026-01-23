<?php 
require_once("../../conexao.php");
require_once("data_formatada.php");

$cat = $_GET['cat'];

if($cat == ""){
	$filtrar = "";
}else{
	$filtrar = " where categoria = '$cat'";
}


$query2 = $pdo->query("SELECT * FROM categorias where id = '$cat'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cat = ' CATEGORIA: '.$res2[0]['nome'];
	}else{
		$nome_cat = '';
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
						<b><big>RELATÓRIO DE ITENS / PRODUTOS </big></b>
							<br><?php echo mb_strtoupper($nome_cat) ?> 
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
					<td style="width:25%">CATEGORIA</td>
					<td style="width:15%">TIPO</td>
					<td style="width:15%">VALOR</td>
					<td style="width:15%">QUANTIDADE</td>						
					
				</tr>
			</thead>
		</table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">



		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php
$query = $pdo->query("SELECT * from itens $filtrar order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$categoria = $res[$i]['categoria'];
	$tipo = $res[$i]['tipo'];
	$valor = $res[$i]['valor'];
	$quantidade = $res[$i]['quantidade'];
	$data = $res[$i]['data'];

	$foto = $res[$i]['foto'];	
	$ativo = $res[$i]['ativo'];
	

	if($ativo == 'Sim'){
	$icone = 'fa-check-square';
	$titulo_link = 'Desativar Usuário';
	$acao = 'Não';
	$classe_ativo = '';
	}else{
		$icone = 'fa-square-o';
		$titulo_link = 'Ativar Usuário';
		$acao = 'Sim';
		$classe_ativo = '#c4c4c4';
	}

	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$valorF = @number_format($valor, 2, ',', '.');


	$query2 = $pdo->query("SELECT * FROM categorias where id = '$categoria'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cat = $res2[0]['nome'];
	}else{
		$nome_cat = 'Sem Categoria';
	}


	$query2 = $pdo->query("SELECT * FROM tabela_locacao where id = '$tipo'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_tab = $res2[0]['nome'];
		$tipo_tab = $res2[0]['tipo'];
	}else{
		$nome_tab = 'Sem Referência';
		$tipo_tab = "";
	}

  	 ?>

  	 
      <tr>
<td style="width:30%">
	<?php echo $nome ?></td>
<td style="width:25%"><?php echo $nome_cat ?></td>
<td style="width:15%"><?php echo $nome_tab ?></td>
<td style="width:15%">R$ <?php echo $valorF ?></td>
<td style="width:15%"><?php echo $quantidade ?></td>
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




 