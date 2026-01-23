<?php 
$tabela = 'pastores';
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
	<th>Nome</th>	
	<th >Telefone</th>	
	<th >Email</th>			
	<th >CPF</th>
	<th >Igreja</th>
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
	$foto = $res[$i]['foto'];
	$data_nasc = $res[$i]['data_nasc'];
	$data_cad = $res[$i]['data_cad'];
	$obs = $res[$i]['obs'];
	$igreja = $res[$i]['igreja'];
	$pix = $res[$i]['pix'];
	$prebenda = $res[$i]['prebenda'];
	$id = $res[$i]['id'];

	$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);


$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig = $res_con[0]['nome'];
					}else{
						$nome_ig = $nome_igreja_sistema;
					}


					//retirar quebra de texto do obs
					$obs = str_replace(array("\n", "\r"), ' + ', $obs);

					$data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
					$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
					

echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td>{$nome} <span class="text-danger">({$prebenda}%)</span></td>
<td>{$telefone}</td>
<td>{$email}</td>
<td>{$cpf}</td>
<td>{$nome_ig}</td>
<td class="esc"><img src="../img/perfil/{$foto}" width="25px"></td>
<td>
	<a class="btn btn-primary btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$data_nascF}','{$igreja}','{$pix}','{$prebenda}','{$foto}','{$obs}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

	<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>

<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$data_nascF}','{$nome_ig}','{$prebenda}','{$foto}','{$obs}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>


<a  class="btn btn-info btn-sm" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " ></i></a>


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
	function editar(id, nome, email, telefone, endereco, cpf, data_nasc, igreja, pix, prebenda, foto, obs){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);
    	$('#email').val(email);
    	$('#telefone').val(telefone);
    	$('#endereco').val(endereco);    	
    	$('#cpf').val(cpf);
    	$('#data_nasc').val(data_nasc);
    	$('#igreja').val(igreja).change();
    	$('#pix').val(pix);
		$('#prebenda').val(prebenda);
		$('#target').attr('src', '../img/perfil/' + foto);
		$('#obs').val(obs);

    	$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, cpf, data_nasc, nome_ig, prebenda, foto, obs){

    	$('#titulo_dados').text(nome);
    	$('#email_dados').text(email);
    	$('#telefone_dados').text(telefone);
    	$('#endereco_dados').text(endereco);
    	$('#cpf_dados').text(cpf);
		$('#nasc_dados').text(data_nasc);
		$('#igreja_dados').text(nome_ig);
		$('#prebenda_dados').text(prebenda);
		$('#foto-dados').attr('src', '../img/perfil/' + foto);
		$('#obs_dados').text(obs);


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
    	$('#igreja').val('');
		$('#pix').val('');
		$('#prebenda').val('');
		$('#target').attr('src', '../img/perfil/sem-foto.jpg');
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
</script>