<?php 
$tabela = 'igrejas';
require_once("../../../conexao.php");

@session_start();
$id_igreja = $_SESSION['id_igreja'];


$query = $pdo->query("SELECT * from $tabela where id = '$id_igreja' order by id desc");
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
	<th >Data Cadastro</th>			
	<th >Membros</th>
	<th >Responsável</th>
	<th >Imagem</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i<$linhas; $i++){
		$nome = $res[$i]['nome'];

		$telefone = $res[$i]['telefone'];
		$endereco = $res[$i]['endereco'];
		$foto = $res[$i]['imagem'];
		$matriz = $res[$i]['matriz'];					
		$data_cad = $res[$i]['data_cad'];
		$pastor = $res[$i]['pastor'];
		$video = $res[$i]['video'];
		$email = $res[$i]['email'];
		$id = $res[$i]['id'];
		$url = $res[$i]['url'];
		$youtube = $res[$i]['youtube'];
		$instagram = $res[$i]['instagram'];
		$facebook = $res[$i]['facebook'];
		$descricao = $res[$i]['descricao'];
		$obs = $res[$i]['obs'];
		$prebenda = $res[$i]['prebenda'];

		$logo_rel = $res[$i]['logo_rel'];
		$cab_rel = $res[$i]['cab_rel'];
		$painel_jpg = $res[$i]['painel_jpg'];
		$carteirinha_rel = $res[$i]['carteirinha_rel'];


		$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));

		$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor'");
		$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con) > 0){
			$nome_p = $res_con[0]['nome'];
		}else{
			$nome_p = 'Não Definido';
		}


		$query_m = $pdo->query("SELECT * FROM membros where igreja = '$id'");
		$res_m = $query_m->fetchAll(PDO::FETCH_ASSOC);
		$membrosCad = @count($res_m);



					//retirar quebra de texto do obs
		$obs = str_replace(array("\n", "\r"), ' + ', $obs);

					//retirar aspas do texto do desc
		$descricao = str_replace('"', "**", $descricao);


		$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);


		echo <<<HTML
		<input type="text" id="descricao_{$id}" value="{$descricao}" style="display:none">
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$nome} <span class="text-danger">({$prebenda}%)</span></td>
		<td>{$telefone}</td>
		<td>{$data_cadF}</td>
		<td>{$membrosCad}</td>
		<td>{$nome_p}</td>
		<td class="esc"><img src="../img/igrejas/{$foto}" width="30px">
		<td>
		<a class="btn btn-primary btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$telefone}','{$endereco}','{$foto}','{$pastor}','{$video}','{$email}','{$url}','{$youtube}','{$instagram}','{$facebook}','{$obs}','{$prebenda}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$telefone}','{$email}','{$endereco}','{$data_cadF}','{$matriz}','{$nome_p}','{$obs}','{$prebenda}','{$id}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>


		<a style="background: #1e236e" class="btn btn-sm" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " style="color: #ffff;"></i></a>

		<a style="background:#ad2626;" class="btn btn-sm" href="#" onclick="imagens('{$id}', '{$nome}','{$logo_rel}','{$cab_rel}','{$painel_jpg}','{$carteirinha_rel}')" title="Cadastrar Imagens Relatório"><i class="fa fa-file-image-o" style="color: #ffff;" ></i></a>


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
	function editar(id, nome, telefone, endereco, foto, pastor, video, email, url, youtube, instagram, facebook, obs, prebenda){


		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);
		$('#target').attr('src', '../img/igrejas/' + foto);
		$('#pastor').val(pastor).change();
		$('#video').val(video);
		$('#email').val(email);
		$('#url').val(url);
		$('#youtube').val(youtube);
		$('#instagram').val(instagram);
		$('#facebook').val(facebook);

		var descricao = $('#descricao_'+id).val();
		nicEditors.findEditor("descricao").setContent(descricao);	
		$('#obs').val(obs);
		$('#prebenda').val(prebenda);
	

		$('#modalForm').modal('show');
	}


	function mostrar(nome, telefone, email, endereco, data_cad, matriz, pastor, obs, prebenda, id){

		$('#titulo-dados').text(nome);
		
		$('#telefone-dados').text(telefone);
		$('#email-dados').text(email);
		$('#endereco-dados').text(endereco);
		$('#cadastro-dados').text(data_cad);
		$('#matriz-dados').text(matriz);
		$('#pastor-dados').text(pastor);

		var descricao = $('#descricao_'+id).val();
		$('#descricao-dados').html(descricao);
		$('#obs-dados').html(obs);
		$('#prebenda_dados').text(prebenda);
		$('#foto-dados').attr('src', '../img/igrejas/' + foto);

		$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');		
		$('#telefone').val('');
		$('#endereco').val('');
		$('#target').attr('src', '../img/igrejas/sem-foto.jpg');
		$('#pastor').val('');
		$('#video').val('');
		$('#email').val('');
		$('#url').val('');
		$('#youtube').val('');
		$('#facebook').val('');	
		$('#instagram').val('');
		nicEditors.findEditor("descricao").setContent('');	
		$('#obs').val('');
		$('#prebenda').val('');
			
		
		


		$('#ids').val('');
		$('#btn-deletar').hide();	
	}




	function imagens(id, nome, logo, cab, painel_jpg, cart){

		$('#nome-imagem').text(nome);
		$('#id-img').val(id);

		$('#targetlogojpg').attr('src', '../img/igrejas/' + logo);
		$('#targetcabjpg').attr('src', '../img/igrejas/' + cab);
		$('#targetpaineljpg').attr('src', '../img/igrejas/' + painel_jpg);
		$('#targetcartjpg').attr('src', '../img/igrejas/' + cart);

				
		var myModal = new bootstrap.Modal(document.getElementById('modalImagens'), {		});
		myModal.show();
		$('#mensagem-imagens').text('');
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