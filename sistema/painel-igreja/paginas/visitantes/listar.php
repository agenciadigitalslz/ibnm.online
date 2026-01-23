<?php 
$tabela = 'visitantes';
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
	<th>Nome</th>	
	<th >Telefone</th>	
	<th >Email</th>			
	<th >Desejo</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for($i=0; $i<$linhas; $i++){
		$nome = $res[$i]['nome'];	
		$telefone = $res[$i]['telefone'];
		$email = $res[$i]['email'];
		$endereco = $res[$i]['endereco'];
		$desejo = $res[$i]['desejo'];
		$igreja = $res[$i]['igreja'];
		$id = $res[$i]['id'];

		$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);



echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$nome}</td>
		<td>{$telefone}</td>
		<td>{$email}</td>
		<td>{$desejo}</td>
		<td>
		<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$desejo}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span >Sim</span></a></p>
		</div>
		</div>
		</div>

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$desejo}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>


		<a class="btn btn-success btn-sm" class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp "></i></a>


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
	function editar(id, nome, email, telefone, endereco, desejo){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);    	
		$('#desejo').val(desejo);

		$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, desejo){

		$('#titulo_dados').text(nome);
		$('#email_dados').text(email);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#desejo_dados').text(desejo);

		$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#email').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#desejo').val('');

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





	function mostrarContas(nome, id){

		$('#titulo_contas').text(nome); 
		$('#id_contas').val(id); 	

		$('#modalContas').modal('show');
		listarDebitos(id);

	}


	function listarDebitos(id){

		$.ajax({
			url: 'paginas/' + pag + "/listar_debitos.php",
			method: 'POST',
			data: {id},
			dataType: "html",

			success:function(result){
				$("#listar_debitos").html(result);           
			}
		});
	}
</script>