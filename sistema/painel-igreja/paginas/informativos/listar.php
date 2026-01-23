<?php 
$tabela = 'informativos';
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
	<th>Data</th>
	<th>Preletor</th>
	<th>Evento</th>
	<th>Horário</th>
	<th>Pastor Responsável</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
					$preletor = $res[$i]['preletor'];
					$data = $res[$i]['data'];
					$texto_base = $res[$i]['texto_base'];
					$tema = $res[$i]['tema'];
					$evento = $res[$i]['evento'];
					$horario = $res[$i]['horario'];
					
					$pastor_responsavel = $res[$i]['pastor_responsavel'];
					$pastores = $res[$i]['pastores'];
					$lider_louvor = $res[$i]['lider_louvor'];
					$obreiros = $res[$i]['obreiros'];
					$apoio = $res[$i]['apoio'];
					$abertura = $res[$i]['abertura'];
					$recado = $res[$i]['recado'];

					$oferta = $res[$i]['oferta'];
					$recepcao = $res[$i]['recepcao'];
					$bercario = $res[$i]['bercario'];
					$escolinha = $res[$i]['escolinha'];
					$membros = $res[$i]['membros'];
					$visitantes = $res[$i]['visitantes'];
					$conversoes = $res[$i]['conversoes'];
					$total_ofertas = $res[$i]['total_ofertas'];
					$total_dizimos = $res[$i]['total_dizimos'];

					$obs = $res[$i]['obs'];

					$dataF = implode('/', array_reverse(explode('-', $data)));

					$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor_responsavel'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_pastor_resp = $res_con[0]['nome'];
					}else{
						$nome_pastor_resp = '';
					}

		
echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td>{$dataF}</td>
<td>{$preletor}</td>
<td>{$evento}</td>
<td>{$horario}</td>
<td>{$nome_pastor_resp}</td>

<td>
	<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$preletor}','{$data}','{$texto_base}','{$tema}','{$evento}','{$horario}','{$obs}','{$pastor_responsavel}','{$pastores}','{$lider_louvor}','{$obreiros}','{$apoio}','{$abertura}','{$recado}','{$oferta}','{$recepcao}','{$bercario}','{$escolinha}','{$membros}','{$visitantes}','{$conversoes}','{$total_ofertas}','{$total_dizimos}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

	<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>

                        <a class="btn btn-primary btn-sm" href="rel/rel_ficha_culto_class.php?id={$id}" target="_blank" title="Gerar PDF">	<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>


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
	function editar(id, preletor, data, texto_base, tema, evento, horario, obs, pastor_responsavel, pastores, lider_louvor, obreiros, apoio, abertura, recado, oferta, recepcao, bercario, escolinha, membros, visitantes, conversoes, total_ofertas, total_dizimos){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
		$('#preletor').val(preletor);
		$('#data').val(data);
		$('#texto_base').val(texto_base);
		$('#tema').val(tema);
		$('#evento').val(evento);

		$('#horario').val(horario);
		$('#obs').val(obs);
		$('#pastor_responsavel').val(pastor_responsavel).change();
		$('#pastores').val(pastores);
		$('#lider_louvor').val(lider_louvor);

			$('#obreiros').val(obreiros);
		$('#apoio').val(apoio);
		$('#abertura').val(abertura);
		$('#recado').val(recado);
		$('#oferta').val(oferta);

		$('#recepcao').val(recepcao);
		$('#bercario').val(bercario);
		$('#escolinha').val(escolinha);
		$('#membros').val(membros);
		$('#visitantes').val(visitantes);

		$('#conversoes').val(conversoes);
		$('#total_ofertas').val(total_ofertas);
		$('#total_dizimos').val(total_dizimos);
    
    	$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
		
		$('#preletor').val('');
		$('#data').val('');
		$('#texto_base').val('');
		$('#tema').val('');
		$('#evento').val('');

		$('#horario').val('');
		$('#obs').val('');
		$('#pastor_responsavel').val('');
		$('#pastores').val('');
		$('#lider_louvor').val('');

		$('#obreiros').val('');
		$('#apoio').val('');
		$('#abertura').val('');
		$('#recado').val('');
		$('#oferta').val('');

		$('#recepcao').val('');
		$('#bercario').val('');
		$('#escolinha').val('');
		$('#membros').val('');
		$('#visitantes').val('');

		$('#conversoes').val('');
		$('#total_ofertas').val('');
		$('#total_dizimos').val('');    

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