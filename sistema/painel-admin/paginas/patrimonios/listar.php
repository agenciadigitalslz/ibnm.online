<?php 
$tabela = 'patrimonios';
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
	<th>Codigo</th>	
	<th>Nome</th>	
	<th>Cadastrado Por</th>	
	<th>Data Cadastro</th>
	<th>Pertence a</th>		
	<th>Foto</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i<$linhas; $i++){

		$nome = $res[$i]['nome'];
		$codigo = $res[$i]['codigo'];
		$descricao = $res[$i]['descricao'];
		$valor = $res[$i]['valor'];
		$usuario_cad = $res[$i]['usuario_cad'];
		$foto = $res[$i]['foto'];
		$igreja_cad = $res[$i]['igreja_cad'];
		$data_cad = $res[$i]['data_cad'];
		$obs = $res[$i]['obs'];
		$igreja_item = $res[$i]['igreja_item'];
		$usuario_emprestou = $res[$i]['usuario_emprestou'];
		$data_emprestimo = $res[$i]['data_emprestimo'];
		$ativo = $res[$i]['ativo'];
		$entrada = $res[$i]['entrada'];
		$doador = $res[$i]['doador'];
		$id = $res[$i]['id'];


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

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_cad'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res2) > 0){
			$nome_usu_cad = $res2[0]['nome'];
		}else{
			$nome_usu_cad = '';
		}


		$query3 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_emprestou'");
		$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
		if(count($res3) > 0){
			$nome_usu_emp = $res3[0]['nome'];
		}else{
			$nome_usu_emp = 'Sem Empréstimo';
		}


		$query4 = $pdo->query("SELECT * FROM igrejas where id = '$igreja_cad'");
		$res4 = $query4->fetchAll(PDO::FETCH_ASSOC);
		if(count($res4) > 0){
			$nome_ig_cad = $res4[0]['nome'];
		}else{
			$nome_ig_cad = '';
		}


		$query5 = $pdo->query("SELECT * FROM igrejas where id = '$igreja_item'");
		$res5 = $query5->fetchAll(PDO::FETCH_ASSOC);
		if(count($res5) > 0){
			$nome_ig_item = $res5[0]['nome'];
		}else{
			$nome_ig_item = '';
		}





					//retirar quebra de texto do obs
		$obs = str_replace(array("\n", "\r"), ' + ', $obs);

		$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
		$data_emprestimoF = implode('/', array_reverse(@explode('-', $data_emprestimo)));
		$valorF = number_format($valor, 2, ',', '.');



		echo <<<HTML
		<tr style="">
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td style="color:{$classe_ativo}">{$codigo}</td>
		<td style="color:{$classe_ativo}">{$nome}</td>
		<td style="color:{$classe_ativo}">{$nome_usu_cad}</td>
		<td style="color:{$classe_ativo}">{$data_cadF}</td>
		<td style="color:{$classe_ativo}">{$nome_ig_cad}</td>
		<td class="esc"><img src="../img/patrimonios/{$foto}" width="25px"></td>

		<td>
		<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$codigo}','{$nome}','{$descricao}','{$valor}','{$data_cad}','{$doador}','{$entrada}','{$foto}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash "></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>


		<big><a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$codigo}','{$nome}','{$nome_ig_item}','{$valor}','{$data_cadF}','{$entrada}','{$foto}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a></big>


		<big><a class="btn btn-success btn-sm" href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} "></i></a></big>


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
	function editar(id, codigo, nome, descricao, valor, data_cad, doador, entrada, foto){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#codigo').val(codigo);
		$('#nome').val(nome);
		$('#descricao').val(descricao);
		$('#valor').val(valor);
		$('#data_cad').val(data_cad);
		$('#doador').val(doador);
		$('#entrada').val(entrada).change();

		$('#foto').val('');
		$('#target').attr("src", "../img/patrimonios/" + foto);


		$('#modalForm').modal('show');
	}


	function mostrar(codigo, nome, igreja_item, valor, data_cad, entrada, foto){
	

			$('#codigo_dados').text(codigo);
			$('#titulo_dados').text(nome);
			$('#igreja_item_dados').text(igreja_item);
			$('#valor_dados').text(valor);
			$('#data_dados').text(data_cad);
			$('#entrada_dados').text(entrada);

		$('#foto_dados').attr("src", "../img/patrimonios/" + foto);


		$('#modalDados').modal('show');
	}




	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#quantidade').val('');
		$('#valor').val('');    	
		$('#foto').val('');

		$('#target').attr("src", "../img/patrimonios/sem-foto.jpg");

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