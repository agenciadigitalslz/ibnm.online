<?php 
require_once("../../../conexao.php");

@session_start();
$id_igreja = $_SESSION['id_igreja'];

$pagina = 'aniversariantes';
$dataMes = Date('m');
$dataDia = Date('d');

$filtrar = @$_POST['p1'];

	
if(@$filtrar == "dia"){
	$classe_dia = 'text-primary';
	$classe_mes = 'text-dark';

	$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");

	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");

	
}else{
	$classe_mes = 'text-primary';
	$classe_dia = 'text-dark';

	$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' order by data_nasc asc, id desc");

	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' order by data_nasc asc, id desc");

	
}



	echo <<<HTML

	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>
	<th>Data Nascimento</th>
	<th>Email</th>
	<th>Telefone</th>
	<th>Cargo</th>
	<th>Foto</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;


	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);	

	$res_pastores = $query_pastores->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_pastores = count($res_pastores);
	if($total_reg > 0 || $total_reg_pastores > 0){


		
	for($i=0; $i<$total_reg; $i++){
					$nome = $res[$i]['nome'];
					$cpf = $res[$i]['cpf'];
					$email = $res[$i]['email'];
					$telefone = $res[$i]['telefone'];
					$endereco = $res[$i]['endereco'];
					$foto = $res[$i]['imagem'];
					$data_nasc = $res[$i]['data_nasc'];
					$data_cad = $res[$i]['data_cad'];
					$obs = $res[$i]['obs'];
					$igreja = $res[$i]['igreja'];
					$cargo = $res[$i]['cargo'];
					$data_bat = $res[$i]['data_bat'];
					$igreja = $res[$i]['igreja'];
					$ativo = $res[$i]['ativo'];
					$id = $res[$i]['id'];
					$estado = $res[$i]['estado_civil'];

		$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

		$query_con2 = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
		$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con2) > 0){
			$nome_cargo = $res_con2[0]['nome'];
		}else{
			$nome_cargo = '';
		}


		$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig = $res_con[0]['nome'];
					}else{
						$nome_ig = $nome_igreja_sistema;
					}

					

		
		$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
		$data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
		$data_batF = implode('/', array_reverse(explode('-', $data_bat)));

		



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$nome}</td>
		<td>{$data_nascF}</td>
		<td>{$email}</td>
		<td>{$telefone}</td>
		<td>{$nome_cargo}</td>
		<td class="esc"><img src="../img/membros/{$foto}" width="25px"></td>
		<td>


		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$cpf}','{$email}','{$telefone}','{$endereco}','{$data_cadF}','{$data_nascF}','{$nome_ig}','{$data_batF}','{$nome_cargo}','{$estado}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>

		<a class="btn btn-success btn-sm" class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}&text=Ola {$nome}, nos da {$nome_ig} desejamos a você um feliz aniversário, que Deus te abençoe e te ilumine.." title="Enviar Felicitações" target="_blank"><i class="fa fa-whatsapp " ></i></a>


		</td>
		</tr>
		HTML;

	}
}else{
	//echo 'Não possui nenhum cadastro!';
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


	function mostrar(nome, cpf, email, telefone, endereco, data_cadF, data_nascF, nome_ig, data_batF, nome_cargo, estado){

		$('#titulo_dados').text(nome);
		$('#cpf_dados').text(cpf);
		$('#email_dados').text(email);
		$('#telefone_dados').text(telefone)
		$('#endereco_dados').text(endereco);
		$('#data_cad_dados').text(data_cadF);
		$('#data_nasc_dados').text(data_nascF);
		$('#igreja_dados').text(nome_ig);
		$('#data_bat_dados').text(data_batF);
		$('#cargo_dados').text(nome_cargo);
		$('#estado_dados').text(estado);

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
</script>