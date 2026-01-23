<?php 
$tabela = 'anexos';
require_once("../../../conexao.php");


$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	echo <<<HTML

	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Igreja</th>	
	<th>Nome</th>	
	<th>Descrição</th>	
	<th>Arquivo</th>
	<th>Data</th>		
	<th>Cadastrado Por</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i<$linhas; $i++){

		$descricao = $res[$i]['descricao'];
		$nome = $res[$i]['nome'];
		$usuario_cad = $res[$i]['usuario_cad'];
		$data = $res[$i]['data'];
		$arquivo = $res[$i]['arquivo'];
		$igreja = $res[$i]['igreja'];

		$id = $res[$i]['id'];

//EXTRAIR EXTENSÃO DO ARQUIVO
		$ext = pathinfo($arquivo, PATHINFO_EXTENSION);   
		if($ext == 'pdf'){ 
			$tumb_arquivo = 'pdf.png';
		}else if($ext == 'rar' || $ext == 'zip'){
			$tumb_arquivo = 'rar.png';
		}else if($ext == 'doc' || $ext == 'docx'){
			$tumb_arquivo = 'word.png';
		}else{
			$tumb_arquivo = $arquivo;
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_cad'");
					$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if(count($res2) > 0){
						$nome_usu_cad = $res2[0]['nome'];
					}else{
						$nome_usu_cad = '';
					}


		$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_igreja = $res_con[0]['nome'];
			$foto_igreja = $res_con[0]['imagem'];

		}else{
			$nome_igreja = '';
		}

		$dataF = implode('/', array_reverse(explode('-', $data)));


		echo <<<HTML
		<tr style="">
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td >{$nome_igreja}</td>
		<td >{$nome}</td>
		<td >{$descricao}</td>
		<td class="esc">
							<a href="../img/documentos/{$arquivo}" target="_blank">
								<img src="../img/documentos/{$tumb_arquivo}" width="30px">
							</a>
						</td>
		<td >{$dataF}</td>
		<td class="esc">{$nome_usu_cad}</td>

		<td>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash "></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>


		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$descricao}','{$dataF}','{$tumb_arquivo}')" title="Mostrar Dados"><i class="fa fa-info-circle text-white"></i></a>




		</td>
		</tr>
		HTML;

	}

}else{
	echo 'Não possui nenhum cadastro!';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>

<br>		
<p align="right" style="margin-top: -10px">
<span style="margin-right: 10px">Total Itens  <span > {$linhas} </span></span>

</p>

HTML;
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
	function mostrar(nome, descricao, dataF, arquivo){
		    	
    	$('#titulo_dados').text(nome);
    	$('#descricao_dados').text(descricao);
    	$('#data_dados').text(dataF);
		$('#arquivo_dados').attr('src', '../img/documentos/' + arquivo);

    	$('#modalDados').modal('show');
	}




	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#descricao').val('');
		$('#dataF').val('');    	
		$('#usuario_cad').val('');

		$('#target').attr("src", "img/patrimonios/sem-foto.jpg");

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