<?php 
$tabela = 'ministerios';
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
	<th>Dias</th>
	<th>Horário</th>	
	<th>Pastor Responsável</th>
	<th>Secretário</th>
	<th>Lider 1</th>
	<th>Lider 2</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i<$linhas; $i++){
		$nome = $res[$i]['nome'];	
		$dias = $res[$i]['dias'];
		$hora = $res[$i]['hora'];
		$pastor = $res[$i]['pastor'];
		$secretario = $res[$i]['secretario'];
		$lider1 = $res[$i]['lider1'];
		$lider2 = $res[$i]['lider2'];
		$obs = $res[$i]['obs'];
		$igreja = $res[$i]['igreja'];
		$id = $res[$i]['id'];



		$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_ig = $res_con[0]['nome'];
		}else{
			$nome_ig = $nome_igreja_sistema;
		}



	//totalizar membros
		$query2 = $pdo->query("SELECT * FROM celulas_membros where igreja = '$id_igreja' and celula = '$id'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$membros_celula = count($res2);


		if($obs != ""){
			$classe_obs = 'text-warning';
		}else{
			$classe_obs = 'text-secondary';
		}




		$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_pastor = $res_con[0]['nome'];
		}else{
			$nome_pastor = 'Nenhum';
		}

		$query_con = $pdo->query("SELECT * FROM membros where id = '$secretario'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_secretario = $res_con[0]['nome'];
		}else{
			$nome_secretario = 'Nenhum';
		}

		$query_con = $pdo->query("SELECT * FROM membros where id = '$lider1'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_lider1 = $res_con[0]['nome'];
		}else{
			$nome_lider1 = 'Nenhum';
		}


		$query_con = $pdo->query("SELECT * FROM membros where id = '$lider2'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_lider2 = $res_con[0]['nome'];
		}else{
			$nome_lider2 = 'Nenhum';
		}


					//retirar quebra de texto do obs
		$obs = @str_replace(array("\n", "\r"), ' + ', $obs);



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$nome}</td>
		<td>{$dias}</td>
		<td>{$hora}</td>
		<td>{$nome_pastor}</td>
		<td>{$nome_secretario}</td>
		<td>{$nome_lider1}</td>
		<td>{$nome_lider2}</td>
		<td>
		<a class="btn btn-primary btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$dias}','{$hora}','{$pastor}','{$secretario}','{$lider1}','{$lider2}','{$obs}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash "></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$dias}','{$hora}','{$nome_pastor}','{$nome_secretario}','{$nome_lider1}','{$nome_lider2}','{$obs}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a>


		<a style="background: #1e236e" class="btn btn-sm" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " style="color: #ffff;" ></i></a>


		<a style="background: #26c96d;" class="btn btn-sm" href="#" onclick="addMembros('{$id}','{$nome}','{$igreja}')" title="Adicionar Membros">	<i class="bi bi-plus-square-fill " style="color: #ffff;" ></i> </a>



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
	function editar(id, nome, dias, hora, pastor, secretario, lider1, lider2, obs){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		$('#dias').val(dias);
		$('#hora').val(hora);   	
		$('#pastor').val(pastor).change();
		$('#secretario').val(secretario).change();
		$('#lider1').val(lider1).change();
		$('#lider2').val(lider2).change();
		$('#obs').val(obs);

		$('#modalForm').modal('show');
	}


	function mostrar(nome, dias, hora, pastor, secretario, lider1, lider2, obs){
		

		$('#titulo_dados').text(nome);
		$('#dias_dados').text(dias);
		$('#hora_dados').text(hora);
		$('#pastor_dados').text(pastor);
		$('#secretario_dados').text(secretario);
		$('#lider1_dados').text(lider1);
		$('#lider2_dados').text(lider2);
		$('#obs_dados').text(obs);

		$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#dias').val('');
		$('#hora').val('');
		$('#pastor').val('');
		$('#secretario').val('');
		$('#lider1').val('');
		$('#lider2').val('');
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

	function arquivo(id, nome){
		$('#id-arquivo').val(id);    
		$('#nome-arquivo').text(nome);
		$('#modalArquivos').modal('show');
		$('#mensagem-arquivo').text(''); 
		$('#arquivo_conta').val('');
		listarArquivos();   
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



	function addMembros(id, nome, igreja){
						console.log(obs)


						$('#nome-add').text(nome);
						$('#id-add').val(id);
						$('#id-ig').val(igreja);

						listarMembrosCB(id, igreja);
						listarMembrosAdd(id, igreja);

						var myModal = new bootstrap.Modal(document.getElementById('modalAddMembros'), {		});
						myModal.show();
						$('#mensagem-add').text('');
					}





					function listarMembrosCB(celula, igreja){
		console.log(obs)
	
		
		$.ajax({
			 url: 'paginas/' + pag + "/listar-membros.php",
        
        method: 'POST',
        data: {celula, igreja},
        dataType: "text",

        success: function (result) {
            $("#listar-membros").html(result);               
        },

    });

	}


	function listarMembrosAdd(celula, igreja){

		
		$.ajax({
			 url: 'paginas/' + pag + "/listar-membros-add.php",
       
        method: 'POST',
        data: {celula, igreja},
        dataType: "text",

        success: function (result) {
            $("#listar-membros-add").html(result);               
        },

    });

	}



	function excluirMembro(id){
		event.preventDefault();
		var igreja = "<?=$id_igreja?>";
		var celula = $('#id-add').val();
				
		$.ajax({
			url: 'paginas/' + pag + "/excluir-membro.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (result) {
            listarMembrosCB(celula, igreja);
			listarMembrosAdd(celula, igreja);            
        },

    });

	}
</script>