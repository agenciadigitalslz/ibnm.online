<?php 
$tabela = 'movimentacoes';
require_once("../conexao.php");
require_once("verificar.php");


@session_start();
$id_igreja = $_SESSION['id_igreja'];


$data_hoje = date('Y-m-d');
$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";


$data_ontem = date('Y-m-d', @strtotime("-1 days",@strtotime($data_atual)));
$data_amanha = date('Y-m-d', @strtotime("+1 days",@strtotime($data_atual)));


if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '07' || $mes_atual == '09'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}

}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}



$dataInicial = @$_POST['p2'];
$dataFinal = @$_POST['p3'];
$filtro = @$_POST['p1'];
$tipo_data = @$_POST['p4'];




if(@$_GET['filtrar'] == 'Entradas'){
	$classe_entradas = 'text-primary';
	$classe_saidas = 'text-dark';
	$classe_todas = 'text-dark';

	$query = $pdo->query("SELECT * FROM $tabela where igreja = '$id_igreja' and tipo = 'Entrada' order by id desc");

}else if(@$_GET['filtrar'] == 'Saídas'){
	$classe_entradas = 'text-dark';
	$classe_saidas = 'text-primary';
	$classe_todas = 'text-dark';

	$query = $pdo->query("SELECT * FROM $tabela where igreja = '$id_igreja' and tipo = 'Saída' order by id desc");

}else{
	$classe_entradas = 'text-dark';
	$classe_saidas = 'text-dark';
	$classe_todas = 'text-primary';

	$query = $pdo->query("SELECT * FROM $tabela where igreja = '$id_igreja'  order by id desc");
}


$query = $pdo->query("SELECT * from $tabela where igreja = '$id_igreja' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	echo <<<HTML
	<br>
	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela" style="margin-top: 15px">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Movimento</th>
	<th>Descrição</th>
	<th>Valor</th>
	<th>Data</th>
	<th>Tesoureiro / Pastor</th>
	</tr> 
	</thead> 
	<tbody>	


	HTML;


	for($i=0; $i<$linhas; $i++){
		$movimento = $res[$i]['movimento'];
					$tipo = $res[$i]['tipo'];
					$valor = $res[$i]['valor'];
					$descricao = $res[$i]['descricao'];
					$data = $res[$i]['data'];
				$usuario_cad = $res[$i]['usuario_cad'];
					$igreja = $res[$i]['igreja'];
					$id = $res[$i]['id'];

					if($tipo == 'Entrada'){
						$classe = 'text-success';
						

					}else{
						$classe = 'text-danger';
						
					}

					

					
					$query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario_cad'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$usuario_cad = $res_con[0]['nome'];
						
					}else{
						$usuario_cad = '';
					}


										

					$valorF = number_format($valor, 2, ',', '.');
					$dataF = implode('/', array_reverse(explode('-', $data)));



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
					

						<td>
							<i class="bi bi-square-fill {$classe}"></i>
							{$movimento}

						</td>
						<td>{$descricao}</td>
						<td>R$ {$valor}</td>
						<td>{$dataF}</td>
						<td>{$usuario_cad}</td>
												
				

	

		

		</td>
		</tr>
		HTML;

	}


	echo <<<HTML
	</tbody>
	<small><div align="center" id="mensagem-excluir"></div></small>
	</table>
	HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}

?>
<script type="text/javascript">var pag = "<?=$pagina?>"</script>
		<script src="../js/ajax.js"></script>

		<script type="text/javascript">
    $(document).ready( function () {      
        if ($.fn.DataTable.isDataTable('#tabela')) { $('#tabela').DataTable().destroy(); }
        $('#tabela').DataTable({
			"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false,
			"stateSave": true
		});
	} );
</script>