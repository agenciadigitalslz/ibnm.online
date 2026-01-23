<?php 
$tabela = 'alertas';
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
	<th>Data Expiração</th>
	<th>Usuário</th>
	<th>Foto</th>				
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;


	for($i=0; $i<$linhas; $i++){
		$titulo = $res[$i]['titulo'];
		$descricao = $res[$i]['descricao'];
		$link = $res[$i]['link'];
		$foto = $res[$i]['imagem'];
		$data = $res[$i]['data'];
		$usuario = $res[$i]['usuario'];
		$igreja = $res[$i]['igreja'];
		$ativo = $res[$i]['ativo'];

		$id = $res[$i]['id'];


		
		$query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_usu_cad = $res_con[0]['nome'];
		}else{
			$nome_usu_cad = '';
		}



		$dataF = implode('/', array_reverse(@explode('-', $data)));


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



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td style="color:{$classe_ativo}">{$titulo}</td>
		<td style="color:{$classe_ativo}">{$dataF}</td>
		<td style="color:{$classe_ativo}">{$nome_usu_cad}</td>
		<td style="color:{$classe_ativo}" class="esc"><img src="../img/alertas/{$foto}" width="25px"></td>

		<td>
		<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$titulo}','{$descricao}','{$link}','{$foto}','{$data}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$descricao}','{$link}','{$data}','{$nome_usu_cad}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a>

		<big><a class="btn btn-success btn-sm" href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} "></i></a></big>
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
	function editar(id, titulo, descricao, link, foto, data){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#titulo').val(titulo);
		$('#descricao').val(descricao);
		$('#link').val(link);
		$('#target').attr('src', '../img/alertas/' + foto);
		$('#data').val(data);
		
		$('#modalForm').modal('show');
	}


function mostrar(descricao, link, data, usuario){

		$('#link_ref').attr('href', link);
		    	
    	$('#descricao_dados').text(descricao);
    	$('#link_dados').text(link);
    	$('#data_dados').text(data);
    	$('#usuario_dados').text(usuario);
    	
    	$('#modalDados').modal('show');
	}


	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#dia').val('');
		$('#hora').val('');
		$('#descricao').val('');
		$('#target').attr('src', '../img/alertas/sem-foto.jpg');

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