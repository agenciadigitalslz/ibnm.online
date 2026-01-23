<?php 
$tabela = 'documentos';
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
	<th>Título</th>
	<th>Descrição</th>
	<th>Arquivo</th>
	<th>Data</th>			
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;


	for($i=0; $i<$linhas; $i++){
		$nome = $res[$i]['nome'];
		$descricao = $res[$i]['descricao'];
		$arquivo = $res[$i]['arquivo'];
		$data = $res[$i]['data'];
		$usuario = $res[$i]['usuario'];
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



		
		$query_usu = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
		$res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_usu) > 0){
			$nome_usu_cad = $res_usu[0]['nome'];
		}else{
			$nome_usu_cad = '';
		}



		$dataF = implode('/', array_reverse(@explode('-', $data)));



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td class="esc">{$nome}</td>
		<td class="esc">{$descricao}</td>

		<td class="esc">
							<a href="../img/documentos/{$arquivo}" target="_blank">
								<img src="../img/documentos/{$tumb_arquivo}" width="30px">
							</a>
						</td>
		<td class="esc">{$dataF}</td>

		<td>
		<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$descricao}','{$data}','{$tumb_arquivo}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
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
	function editar(id, nome, descricao, data, arquivo){


		
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		$('#descricao').val(descricao);
		$('#data').val(data);
		$('#target').attr('src', '../img/documentos/' + arquivo);
		
		$('#modalForm').modal('show');
	}



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
		$('#data').val('');
		$('#target').attr('src', '../img/documentos/sem-foto.jpg');

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