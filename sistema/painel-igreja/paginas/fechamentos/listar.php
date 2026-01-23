<?php 
$tabela = 'fechamentos';
require_once("../../../conexao.php");

@session_start();
$id_igreja = $_SESSION['id_igreja'];


$query = $pdo->query("SELECT * from $tabela where igreja = '$id_igreja' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Mês</th>
	<th>Data</th>
	<th>Usuário</th>	
	<th>Saídas</th>
	<th>Entradas</th>
	<th>Saldo</th>	
	<th>Prebenda</th>	
	<th>Saldo Final</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$data_fec = $res[$i]['data_fec'];	
					$data = $res[$i]['data'];
					$saidas = $res[$i]['saidas'];
					$usuario = $res[$i]['usuario_cad'];
					$entradas = $res[$i]['entradas'];
					$saldo = $res[$i]['saldo'];
					$prebenda = $res[$i]['prebenda'];
					$saldo_final = $res[$i]['saldo_final'];
									
					$id = $res[$i]['id'];

					$dataF = implode('/', array_reverse(explode('-', $data)));
					$entradasF = number_format($entradas, 2, ',', '.');
					$saidasF = number_format($saidas, 2, ',', '.');
					$saldoF = number_format($saldo, 2, ',', '.');
					$prebendaF = number_format($prebenda, 2, ',', '.');
					$saldo_finalF = number_format($saldo_final, 2, ',', '.');

					$separar = explode("-", $data_fec);
					$mes = $separar[1];
					$ano = $separar[0];
				
					$query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$usuario_cad = $res_con[0]['nome'];
						
					}else{
						$usuario_cad = '';
						
					}

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

		
echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td>{$mesF} / {$ano}</td>
<td>{$dataF}</td>
<td>{$usuario_cad}</td>
<td>{$saidasF}</td>
<td>{$entradasF}</td>
<td>{$saldoF}</td>
<td>{$prebendaF}</td>
<td>{$saldo_finalF}</td>

<td>
	<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}','{$mesF}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>
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



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>

<script type="text/javascript">



	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#dia').val('');
    	$('#hora').val('');
    	$('#descricao').val('');
    	$('#obs').val('');
    

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
	}

	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluirMultiplos(id[i]);			
		}

		setTimeout(() => {
		  	listar();	
		}, 1000);

		limparCampos();
	}
</script>