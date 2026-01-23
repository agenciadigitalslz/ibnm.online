<?php 
$tabela = 'versiculos';
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
	<th>Versículo</th>
	<th>Capítulo</th>			
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;


	for($i=0; $i<$linhas; $i++){
		$versiculo = $res[$i]['versiculo'];	
		$capitulo = $res[$i]['capitulo'];

		$igreja = $res[$i]['igreja'];
		$id = $res[$i]['id'];


		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$versiculo}</td>
		<td>{$capitulo}</td>

		<td>
		<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$versiculo}','{$capitulo}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
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

<script type="text/javascript">
	function editar(id, versiculo, capitulo){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#versiculo').val(versiculo);
		$('#capitulo').val(capitulo);

		$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
		$('#versiculo').val('');
		$('#capitulo').val('');


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