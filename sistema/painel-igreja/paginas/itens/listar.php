<?php 
$tabela = 'itens';
require_once("../../../conexao.php");

$cat = @$_POST['p1'];

if($cat == ""){
	$filtrar = "";
}else{
	$filtrar = " where categoria = '$cat'";
}

$query = $pdo->query("SELECT * from $tabela $filtrar order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>	
	<th>Categoria</th>	
	<th>Tipo</th>	
	<th>Valor</th>	
	<th>Quantidade</th>		
	<th>Foto</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

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
	$alugados = $res[$i]['alugados'];
	

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




echo <<<HTML
<tr style="">
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td style="color:{$classe_ativo}">{$nome}</td>
<td style="color:{$classe_ativo}">{$nome_cat}</td>
<td style="color:{$classe_ativo}">{$nome_tab}</td>
<td style="color:{$classe_ativo}">R$ {$valorF}</td>
<td style="color:{$classe_ativo}">{$quantidade}</td>
<td class="esc"><img src="images/itens/{$foto}" width="25px"></td>

<td>
	<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$categoria}','{$tipo}','{$valor}','{$quantidade}','{$foto}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

	<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash "></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>


<big><a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$nome_cat}','{$nome_tab}','{$valorF}','{$quantidade}','{$foto}', '{$dataF}', '{$ativo}', '{$alugados}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a></big>


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
	function editar(id, nome, categoria, tipo, valor, quantidade, foto){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);  
    	$('#categoria').val(categoria).change();  
    	$('#tipo').val(tipo).change();
    	$('#valor').val(valor);  
    	$('#quantidade').val(quantidade); 
    	
    	$('#foto').val('');
    	$('#target').attr("src", "images/itens/" + foto);
    

    	$('#modalForm').modal('show');
	}


	function mostrar(nome, categoria, tipo, valor, quantidade, foto, data, ativo, alugados){
		    	
    	$('#titulo_dados').text(nome);
    	$('#categoria_dados').text(categoria);
    	$('#tipo_dados').text(tipo);
    	$('#valor_dados').text(valor);
    	$('#quantidade_dados').text(quantidade);
    	$('#data_dados').text(data);
    	$('#alugados_dados').text(alugados);
    	
    	$('#ativo_dados').text(ativo);
    	$('#foto_dados').attr("src", "images/itens/" + foto);
    	

    	$('#modalDados').modal('show');
	}




	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#quantidade').val('');
    	$('#valor').val('');    	
    	$('#foto').val('');

    	$('#target').attr("src", "images/itens/sem-foto.png");
    	
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