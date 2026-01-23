<?php 
$tabela = 'membros';
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
	
	<th>Nome</th>	
	<th >Telefone</th>
	<th >Email</th>			
	<th >CPF</th>
	<th >Cargo</th>
	<th >Foto</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i<$linhas; $i++){
		$nome = $res[$i]['nome'];
		$cpf = $res[$i]['cpf'];
		$email = $res[$i]['email'];
		$telefone = $res[$i]['telefone'];
		$endereco = $res[$i]['endereco'];
		$foto = $res[$i]['imagem'];
		$igreja = $res[$i]['igreja'];
		$data_nasc = $res[$i]['data_nasc'];
		$data_cad = $res[$i]['data_cad'];
		$data_bat = $res[$i]['data_bat'];
		$obs = $res[$i]['obs'];
		$cargo = $res[$i]['cargo'];
		$estado_civil = $res[$i]['estado_civil'];
		$ativo = $res[$i]['ativo'];
		$id = $res[$i]['id'];

		$rg = $res[$i]['rg'];
		$nacionalidade = $res[$i]['nacionalidade'];
		$naturalidade = $res[$i]['naturalidade'];
		$nome_pai = $res[$i]['nome_pai'];
		$nome_mae = $res[$i]['nome_mae'];
		$membresia = $res[$i]['membresia'];

		$numero = $res[$i]['numero'];
	$bairro = $res[$i]['bairro'];
	$cidade = $res[$i]['cidade'];
	$estado = $res[$i]['estado'];
	$cep = $res[$i]['cep'];
	$complemento = $res[$i]['complemento'];


		$profissao = $res[$i]['profissao'];
		$conjuge = $res[$i]['conjuge'];
		$data_consagracao = $res[$i]['data_consagracao'];
		$igreja_anterior = $res[$i]['igreja_anterior'];
		$cidade_anterior = $res[$i]['cidade_anterior'];
		$titulo_eleitor = $res[$i]['titulo_eleitor'];
		$cidade_votacao = $res[$i]['cidade_votacao'];
		$estado_votacao = $res[$i]['estado_votacao'];
		$zona = $res[$i]['zona'];
		$sessao = $res[$i]['sessao'];

		$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

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



		$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_ig = $res_con[0]['nome'];
		}else{
			$nome_ig = $nome_igreja_sistema;
		}


		$query_con2 = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
		$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con2) > 0){
			$nome_cargo = $res_con2[0]['nome'];
		}else{
			$nome_cargo = '';
		}


					//retirar quebra de texto do obs
		$obs = @str_replace(array("\n", "\r"), ' + ', $obs);

		$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));
		$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
		$data_batF = implode('/', array_reverse(@explode('-', $data_bat)));
		$membresiaF = implode('/', array_reverse(@@explode('-', $membresia)));



		$data_consagracaoF = implode('/', array_reverse(@explode('-', $data_consagracao)));


		echo <<<HTML
		<tr>
		
		<td style="color:{$classe_ativo}"><div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		{$nome}
		</div> </td>
		<td style="color:{$classe_ativo}">{$telefone}</td>
		<td style="color:{$classe_ativo}">{$email}</td>
		<td style="color:{$classe_ativo}">{$cpf}</td>
		<td style="color:{$classe_ativo}">{$nome_cargo}</td>
		<td class="esc"><img src="../img/membros/{$foto}" width="25px"></td>


		<td style="padding: 0;">
		  <!-- Container para os botões -->
		  <div style="display: flex; flex-wrap: nowrap; gap: 5px; padding: 2px; box-sizing: border-box; white-space: nowrap; overflow-x: auto; align-items: center;">
		    <a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$data_nascF}','{$data_batF}','{$cargo}','{$estado_civil}','{$foto}','{$obs}','{$rg}','{$nome_pai}','{$nome_mae}','{$naturalidade}','{$nacionalidade}','{$membresiaF}','{$profissao}','{$conjuge}','{$data_consagracaoF}','{$igreja_anterior}','{$cidade_anterior}','{$titulo_eleitor}','{$cidade_votacao}','{$estado_votacao}','{$zona}','{$sessao}','{$numero}','{$bairro}','{$cidade}','{$estado}','{$cep}','{$complemento}')" title="Editar Dados">
		      <i class="fa fa-edit"></i>
		    </a>

		    <div class="dropdown">
		      <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" style="margin-bottom: 5px;">
		        <i class="fa fa-trash"></i>
		      </a>
		      <div class="dropdown-menu tx-13">
		        <div style="width: 240px; padding: 15px 5px 0 10px;" class="dropdown-item-text">
		          <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		        </div>
		      </div>
		    </div>

		    <a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$data_nascF}','{$data_batF}','{$nome_cargo}','{$estado_civil}','{$foto}','{$obs}','{$rg}','{$nome_pai}','{$nome_mae}','{$naturalidade}','{$nacionalidade}','{$membresiaF}','{$profissao}','{$conjuge}','{$data_consagracaoF}','{$igreja_anterior}','{$cidade_anterior}','{$titulo_eleitor}','{$cidade_votacao}','{$estado_votacao}','{$zona}','{$sessao}','{$numero}','{$bairro}','{$cidade}','{$estado}','{$cep}','{$complemento}')" title="Mostrar Dados" style="margin-bottom: 5px;">
		      <i class="fa fa-info-circle"></i>
		    </a>

		    <a class="btn btn-sm" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos" style="margin-bottom: 5px; background: #1e236e; color: white;">
		      <i class="fa fa-file-o"></i>
		    </a>

		    <a class="btn btn-success btn-sm" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank" style="margin-bottom: 5px;">
		      <i class="fa fa-whatsapp"></i>
		    </a>

		    <a href="#" class="btn btn-success btn-sm" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}" style="margin-bottom: 5px;">
		      <i class="fa {$icone}"></i>
		    </a>

		    <form method="POST" action="rel/rel_carteirinha_class.php?id={$id}" target="_blank" style="display:inline;">
		      <button class="btn btn-sm" title="Gerar Carteirinha" style="background:#365be0; border:none; margin-bottom: 5px; color:#fff;">
		        <i class="bi bi-person-badge-fill"></i>
		      </button>
		    </form>

		    <a class="btn btn-sm" href="#" onclick="modalTransf('{$id}', '{$nome}')" title="Carta de Recomendação" style="margin-bottom: 5px; background: #b8333a; color: white;">
		      <i class="bi bi-clipboard-x"></i>
		    </a>

		    <a class="btn btn-info btn-sm" href="rel/rel_batismo_class.php?id={$id}" title="Certificado de Batismo" target="_blank" style="margin-bottom: 5px;">
		      <i class="bi bi-award"></i>
		    </a>

		    <a class="btn btn-secondary btn-sm" href="#" onclick="modalApresentacao('{$id}', '{$nome}')" title="Certificado de Apresentação" style="margin-bottom: 5px;">
		      <i class="bi bi-bookmark-star"></i>
		    </a>

		    <a href="#" class="btn btn-sm" onclick="transferir('{$id}','{$id}')" title="Transferência de Membro" style="margin-bottom: 5px; background: #c9262e; color: white;">
		      <i class="bi bi-signpost-2"></i>
		    </a>
		  </div>
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
	function editar(id, nome, email, telefone, endereco, cpf, data_nasc, data_bat, cargo, estado_civil, foto, obs, rg, pai, mae, naturalidade, nacionalidade, membresia, profissao, conjuge, data_consagracao, igreja_anterior, cidade_anterior, titulo_eleitor, cidade_votacao, estado_votacao, zona, sessao, numero, bairro, cidade, estado, cep, complemento){
		
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);    	
		$('#cpf').val(cpf);
		$('#data_nasc').val(data_nasc);
		$('#data_bat').val(data_bat);
		$('#cargo').val(cargo).change();
		$('#estado_civil').val(estado_civil).change();
		$('#target').attr('src', '../img/membros/' + foto);
		$('#obs').val(obs);

		$('#rg').val(rg);
		$('#nome_pai').val(pai);
		$('#nome_mae').val(mae);
		$('#naturalidade').val(naturalidade);
		$('#nacionalidade').val(nacionalidade);
		$('#membresia').val(membresia);


		$('#profissao').val(profissao);
		$('#conjuge').val(conjuge);
		$('#data_consagracao').val(data_consagracao);
		$('#igreja_anterior').val(igreja_anterior);
		$('#cidade_anterior').val(cidade_anterior);
		$('#titulo_eleitor').val(titulo_eleitor);
		$('#cidade_votacao').val(cidade_votacao);
		$('#estado_votacao').val(estado_votacao);
		$('#zona').val(zona);
		$('#sessao').val(sessao);

		$('#numero').val(numero);
    	$('#bairro').val(bairro);
    	$('#cidade').val(cidade);
    	$('#estado').val(estado).change();
    	$('#cep').val(cep);
    	$('#complemento').val(cep);

    	$('#foto').val('');

    	
    	$('#complemento').val(complemento);



		$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, cpf, data_nasc, data_bat, nome_cargo, estado_civil, foto, obs, rg, pai, mae, naturalidade, nacionalidade, membresia, profissao, conjuge, data_consagracao, igreja_anterior, cidade_anterior, titulo_eleitor, cidade_votacao, estado_votacao, zona, sessao, numero, bairro, cidade, estado, cep, complemento){

		$('#titulo_dados').text(nome);
		$('#email_dados').text(email);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#cpf_dados').text(cpf);
		$('#nasc_dados').text(data_nasc);
		$('#bat_dados').text(data_bat);
		$('#cargo_dados').text(nome_cargo);
		$('#estado_civil_dados').text(estado_civil);

		$('#foto-dados').attr('src', '../img/membros/' + foto);
		$('#obs_dados').text(obs);

		$('#rg-dados').text(rg);
		$('#nacionalidade-dados').text(nacionalidade);
		$('#naturalidade-dados').text(naturalidade);
		$('#pai-dados').text(pai);
		$('#mae-dados').text(mae);
		$('#membresia-dados').text(membresia);

		$('#numero_dados').text(numero);
    	$('#bairro_dados').text(bairro);
    	$('#cidade_dados').text(cidade);
    	$('#estado_dados').text(estado);
    	$('#cep_dados').text(cep);
    	$('#complemento_dados').text(complemento);


		$('#profissao-dados').text(profissao);
		$('#conjuge-dados').text(conjuge);
		$('#data_consagracao-dados').text(data_consagracao);
		$('#igreja_anterior-dados').text(igreja_anterior);
		$('#cidade_anterior-dados').text(cidade_anterior);
		$('#titulo_eleitor-dados').text(titulo_eleitor);
		$('#cidade_votacao-dados').text(cidade_votacao);
		$('#estado_votacao-dados').text(estado_votacao);
		$('#zona-dados').text(zona);
		$('#sessao-dados').text(sessao);

		$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#email').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#cpf').val('');
		$('#data_nasc').val('');
		$('#data_bat').val('');
		$('#cargo').val('0').change();
		$('#estado_civil').val('0').change();
		$('#foto').val('');
		$('#target').attr('src', '../img/membros/sem-foto.jpg');
		$('#obs').val('');

			$('#numero').val('');
    	$('#bairro').val('');
    	$('#cidade').val('');
    	$('#estado').val('').change();
    	$('#cep').val('');
    	$('#complemento').val('');



		$('#profissao').val('');
		$('#conjuge').val('');
		$('#data_consagracao').val('');
		$('#igreja_anterior').val('');
		$('#cidade_anterior').val('');
		$('#titulo_eleitor').val('');
		$('#cidade_votacao').val('');
		$('#estado_votacao').val('');
		$('#zona').val('');
		$('#sessao').val('');

		$('#membresia').val('');
		$('#nome_pai').val('');
		$('#nome_mae').val('');
		$('#nacionalidade').val('');
		$('#naturalidade').val('');
		$('#rg').val('');
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


<script type="text/javascript">
	
	function modalTransf(id, nome){
		console.log(obs)

		$('#nome-transf').text(nome);
		$('#id-transf').val(id);

		var myModal = new bootstrap.Modal(document.getElementById('modalTransf'), {		});
		myModal.show();
		$('#mensagem-transf').text('');
	}



</script>

<script type="text/javascript">
	function modalApresentacao(id, nome){
						console.log(obs)

						$('#nome-apres').text(nome);
						$('#id-apres').val(id);

						var myModal = new bootstrap.Modal(document.getElementById('modalApresentacao'), {		});
						myModal.show();
						$('#mensagem-apres').text('');
					}


					function certApresentacao(sexo){
						console.log(obs)

						var id = $('#id-apres').val();						
						
						let a= document.createElement('a');
          				a.target= '_blank';
          				a.href= 'rel/rel_apresentacao_class.php?id='+id+'&sexo='+sexo;
          				a.click();
					}




					function transferir(id, nome){
						console.log(obs)

						$('#nome_transferir').text(nome);
						$('#id_transferir').val(id);

						var myModal = new bootstrap.Modal(document.getElementById('modalTransferir'), {		});
						myModal.show();
						$('#mensagem-transferir').text('');
					}

</script>