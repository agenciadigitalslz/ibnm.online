<?php 
$tabela = 'eventos';
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
	<th>Data Evento</th>
	<th>Cadastrado Por</th>
	<th>Foto</th>				
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;


	for($i=0; $i<$linhas; $i++){
		$titulo = $res[$i]['titulo'];
					$subtitulo = $res[$i]['subtitulo'];
					$descricao1 = $res[$i]['descricao1'];
					$descricao2 = $res[$i]['descricao2'];
					$descricao3 = $res[$i]['descricao3'];
					$data_cad = $res[$i]['data_cad'];
					$data_evento = $res[$i]['data_evento'];
					$usuario = $res[$i]['usuario'];
					$imagem = $res[$i]['imagem'];
					$video = $res[$i]['video'];
					$ativo = $res[$i]['ativo'];
					$igreja = $res[$i]['igreja'];
					$obs = $res[$i]['obs'];
					$id = $res[$i]['id'];
					$banner = $res[$i]['banner'];
					$tipo = $res[$i]['tipo'];
					$pregador = $res[$i]['pregador'];

		$id = $res[$i]['id'];


		

			//retirar aspas do texto do desc
					$descricao1 = str_replace('"', "**", $descricao1);
					//retirar aspas do texto do desc
					$descricao2 = str_replace('"', "**", $descricao2);
					//retirar aspas do texto do desc
					$descricao3 = str_replace('"', "**", $descricao3);
					//retirar aspas do texto do desc
					$subtitulo = str_replace('"', "**", $subtitulo);


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

		$query_usu = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
					$res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_usu) > 0){
						$nome_usu_cad = $res_usu[0]['nome'];
					}else{
						$nome_usu_cad = '';
					}



		$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
  		$data_eventoF = implode('/', array_reverse(explode('-', $data_evento)));



		echo <<<HTML
		<tr>
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td style="color:{$classe_ativo}">{$titulo}</td>
		<td style="color:{$classe_ativo}">{$data_eventoF}</td>
		<td style="color:{$classe_ativo}">{$nome_usu_cad}</td>
		<td style="color:{$classe_ativo}" class="esc"><img src="../img/eventos/{$imagem}" width="25px"></td>

		<td>
		<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$titulo}','{$subtitulo}','{$descricao1}','{$descricao2}','{$descricao3}','{$data_evento}','{$imagem}','{$video}','{$banner}','{$tipo}','{$pregador}','{$obs}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</div>
		</div>

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$titulo}','{$subtitulo}','{$data_cadF}','{$data_eventoF}','{$tipo}','{$nome_usu_cad}','{$obs}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a>

		
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
	function editar(id, titulo, subtitulo, descricao1, descricao2, descricao3, data_evento, imagem, video, banner, tipo, pregador, obs){

		
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#titulo').val(titulo);
		$('#areasub').val(subtitulo);
		$('#area1').val(descricao1);
		$('#area2').val(descricao2);
		$('#area3').val(descricao3);
		$('#data_evento').val(data_evento);

		$('#target_imagem').attr('src', '../img/eventos/' + imagem);

		$('#video').val(video);

		$('#target_banner').attr('src', '../img/eventos/' + banner);

		$('#tipo').val(tipo).change();
		$('#pregador').val(pregador);
		$('#obs').val(obs);
		
		
		$('#modalForm').modal('show');
	}




	function mostrar(titulo, subtitulo, data_cadF, data_eventoF, tipo, nome_usu_cad, obs){
		

		$('#titulo_dados').text(titulo);
		$('#sub_dados').text(subtitulo);
		$('#data_cad_dados').text(data_cadF);
		$('#data_evento_dados').text(data_eventoF);
		$('#tipo_dados').text(tipo);
		$('#usuario_dados').text(nome_usu_cad);
		$('#obs_dados').text(obs);

		$('#modalDados').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
		$('#titulo').val('');
		$('#areasub').val('');
		$('#area1').val('');
		$('#area2').val('');
		$('#area3').val('');
		$('#data_evento').val( "<?php echo date('Y-m-d') ?>");
		$('#video').val('');
		$('#tipo').val(0).change();
		$('#pregador').val('');
		$('#obs').val('');
		$('#target_imagem').attr('src', '../img/eventos/sem-foto.png');
		$('#target_banner').attr('src', '../img/eventos/sem-foto.png');


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