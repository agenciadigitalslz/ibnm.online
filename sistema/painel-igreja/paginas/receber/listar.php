<?php
@session_start();
$id_igreja = $_SESSION['id_igreja'];
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
$tabela = 'receber';
require_once("../../../conexao.php");
require_once("../../verificar.php");

// Flag para filtro de usuario
$filtrar_por_usuario = ($mostrar_registros == 'Não');

$data_hoje = date('Y-m-d');
$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";

$data_ontem = date('Y-m-d', @strtotime("-1 days",@strtotime($data_atual)));
$data_amanha = date('Y-m-d', @strtotime("+1 days",@strtotime($data_atual)));


if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}

}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}

$total_pago = 0;
$total_pendentes = 0;

$total_pagoF = 0;
$total_pendentesF = 0;

$dataInicial = @$_POST['p2'];
$dataFinal   = @$_POST['p3'];
$filtro      = @$_POST['p1'];
$tipo_data   = @$_POST['p4'];

if($tipo_data == ""){
	$tipo_data = 'vencimento';
}

// Validar tipo_data para evitar SQL Injection (whitelist)
$tipos_data_permitidos = ['vencimento', 'data_pgto', 'data_lanc'];
if(!in_array($tipo_data, $tipos_data_permitidos)){
	$tipo_data = 'vencimento';
}

if($dataInicial == ""){
	$dataInicial = $data_inicio_mes;
}

if($dataFinal == ""){
	$dataFinal = $data_final_mes;
}


$total_valor = 0;
$total_valorF = 0;
$total_total = 0;
$total_totalF = 0;
$total_vencidas = 0;
$total_vencidasF = 0;
$total_hoje = 0;
$total_hojeF = 0;
$total_amanha = 0;
$total_amanhaF = 0;
$total_recebidas = 0;
$total_recebidasF = 0;
$total_pendent = 0;
$total_pendentF = 0;




//PEGAR O TOTAL DAS CONTAS PENDENTES (usando SUM para performance)
$sql_pendentes = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ? AND pago = 'Não'";
$params_pendentes = [$id_igreja];
if($filtrar_por_usuario){
	$sql_pendentes .= " AND usuario_lanc = ?";
	$params_pendentes[] = $id_usuario;
}
$query = $pdo->prepare($sql_pendentes);
$query->execute($params_pendentes);
$total_valor = $query->fetchColumn();
$total_valorF = @number_format($total_valor, 2, ',', '.');

// Total pendentes (mesmo valor)
$total_pendent = $total_valor;
$total_pendentF = $total_valorF;

//PEGAR O TOTAL DE TODAS AS CONTAS
$sql_total = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ?";
$params_total = [$id_igreja];
if($filtrar_por_usuario){
	$sql_total .= " AND usuario_lanc = ?";
	$params_total[] = $id_usuario;
}
$query = $pdo->prepare($sql_total);
$query->execute($params_total);
$total_total = $query->fetchColumn();
$total_totalF = @number_format($total_total, 2, ',', '.');

//PEGAR O TOTAL DAS CONTAS VENCIDAS
$sql_vencidas = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ? AND vencimento < CURDATE() AND pago = 'Não'";
$params_vencidas = [$id_igreja];
if($filtrar_por_usuario){
	$sql_vencidas .= " AND usuario_lanc = ?";
	$params_vencidas[] = $id_usuario;
}
$query = $pdo->prepare($sql_vencidas);
$query->execute($params_vencidas);
$total_vencidas = $query->fetchColumn();
$total_vencidasF = @number_format($total_vencidas, 2, ',', '.');

//PEGAR O TOTAL DAS CONTAS QUE VENCEM HOJE
$sql_hoje = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ? AND vencimento = CURDATE() AND pago = 'Não'";
$params_hoje = [$id_igreja];
if($filtrar_por_usuario){
	$sql_hoje .= " AND usuario_lanc = ?";
	$params_hoje[] = $id_usuario;
}
$query = $pdo->prepare($sql_hoje);
$query->execute($params_hoje);
$total_hoje = $query->fetchColumn();
$total_hojeF = @number_format($total_hoje, 2, ',', '.');

//PEGAR O TOTAL DAS CONTAS RECEBIDAS
$sql_recebidas = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ? AND pago = 'Sim'";
$params_recebidas = [$id_igreja];
if($filtrar_por_usuario){
	$sql_recebidas .= " AND usuario_lanc = ?";
	$params_recebidas[] = $id_usuario;
}
$query = $pdo->prepare($sql_recebidas);
$query->execute($params_recebidas);
$total_recebidas = $query->fetchColumn();
$total_recebidasF = @number_format($total_recebidas, 2, ',', '.');

$data_hoje = date('Y-m-d');
$data_amanha = date('Y-m-d', @strtotime("+1 days",@strtotime($data_hoje)));

//PEGAR O TOTAL DAS CONTAS QUE VENCEM AMANHÃ
$sql_amanha = "SELECT COALESCE(SUM(valor), 0) as total FROM $tabela WHERE igreja = ? AND vencimento = ? AND pago = 'Não'";
$params_amanha = [$id_igreja, $data_amanha];
if($filtrar_por_usuario){
	$sql_amanha .= " AND usuario_lanc = ?";
	$params_amanha[] = $id_usuario;
}
$query = $pdo->prepare($sql_amanha);
$query->execute($params_amanha);
$total_amanha = $query->fetchColumn();
$total_amanhaF = @number_format($total_amanha, 2, ',', '.');



// Preparar query com filtros seguros
$sql_base = "SELECT * FROM $tabela WHERE igreja = ?";
$params_lista = [$id_igreja];

if($filtro == 'Vencidas'){
	$sql_base .= " AND vencimento < CURDATE() AND pago = 'Não'";
}else if($filtro == 'Recebidas'){
	$sql_base .= " AND pago = 'Sim'";
}else if($filtro == 'Pendentes'){
	$sql_base .= " AND pago = 'Não'";
}else if($filtro == 'Hoje'){
	$sql_base .= " AND vencimento = CURDATE() AND pago = 'Não'";
}else if($filtro == 'Amanha'){
	$sql_base .= " AND vencimento = ? AND pago = 'Não'";
	$params_lista[] = $data_amanha;
}else if($filtro == 'Todas'){
	// Sem filtro adicional
}else{
	// Filtro por periodo - tipo_data ja foi validado via whitelist
	$sql_base .= " AND $tipo_data >= ? AND $tipo_data <= ?";
	$params_lista[] = $dataInicial;
	$params_lista[] = $dataFinal;
}

// Adicionar filtro de usuario se necessario
if($filtrar_por_usuario){
	$sql_base .= " AND usuario_lanc = ?";
	$params_lista[] = $id_usuario;
}

$sql_base .= " ORDER BY id DESC";

$query = $pdo->prepare($sql_base);
$query->execute($params_lista);



$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-bordered text-nowrap border-bottom dt-responsive " id="tabela">
	<thead> 
	<tr> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Descrição</th>	
	<th class="">Valor</th>	
	<th class="esc">Membro</th>	
	<th class="esc">Vencimento</th>	
	<th class="esc">Pagamento</th>		
	<th class="esc">Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	<small>
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$descricao = $res[$i]['descricao'];
	$membro = $res[$i]['membro'];
	$valor = $res[$i]['valor'];
	$vencimento = $res[$i]['vencimento'];
	$data_pgto = $res[$i]['data_pgto'];
	$data_lanc = $res[$i]['data_lanc'];
	$forma_pgto = $res[$i]['forma_pgto'];
	$frequencia = $res[$i]['frequencia'];
	$obs = $res[$i]['obs'];
	$arquivo = $res[$i]['arquivo'];
	$referencia = $res[$i]['referencia'];
	$id_ref = $res[$i]['id_ref'];
	$multa = $res[$i]['multa'];
	$juros = $res[$i]['juros'];
	$desconto = $res[$i]['desconto'];
	$taxa = $res[$i]['taxa'];
	$subtotal = $res[$i]['subtotal'];
	$usuario_lanc = $res[$i]['usuario_lanc'];
	$usuario_pgto = $res[$i]['usuario_pgto'];
	$pago = $res[$i]['pago'];

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));



	$valorF = @number_format($valor, 2, ',', '.');
	$multaF = @number_format($multa, 2, ',', '.');
	$jurosF = @number_format($juros, 2, ',', '.');
	$descontoF = @number_format($desconto, 2, ',', '.');
	$taxaF = @number_format($taxa, 2, ',', '.');
	$subtotalF = @number_format($subtotal, 2, ',', '.');

	if($pago == "Sim"){
		$valor_finalF = @number_format($subtotal, 2, ',', '.');
	}else{
		$valor_finalF = @number_format($valor, 2, ',', '.');
	}



	//extensão do arquivo
$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
if($ext == 'pdf' || $ext == 'PDF'){
	$tumb_arquivo = 'pdf.png';
}else if($ext == 'rar' || $ext == 'zip' || $ext == 'RAR' || $ext == 'ZIP'){
	$tumb_arquivo = 'rar.png';
}else if($ext == 'doc' || $ext == 'docx' || $ext == 'DOC' || $ext == 'DOCX'){
	$tumb_arquivo = 'word.png';
}else if($ext == 'xlsx' || $ext == 'xlsm' || $ext == 'xls'){
	$tumb_arquivo = 'excel.png';
}else if($ext == 'xml'){
	$tumb_arquivo = 'xml.png';
}else{
	$tumb_arquivo = $arquivo;
}
	
	

$query2 = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
$query2->execute([$usuario_lanc]);
$res2 = $query2->fetch(PDO::FETCH_ASSOC);
$nome_usu_lanc = $res2 ? $res2['nome'] : 'Sem Usuário';

$query2 = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
$query2->execute([$usuario_pgto]);
$res2 = $query2->fetch(PDO::FETCH_ASSOC);
$nome_usu_pgto = $res2 ? $res2['nome'] : 'Sem Usuário';

$query2 = $pdo->prepare("SELECT frequencia FROM frequencias WHERE dias = ?");
$query2->execute([$frequencia]);
$res2 = $query2->fetch(PDO::FETCH_ASSOC);
$nome_frequencia = $res2 ? $res2['frequencia'] : 'Sem Registro';

$query2 = $pdo->prepare("SELECT nome, taxa FROM formas_pgto WHERE id = ?");
$query2->execute([$forma_pgto]);
$res2 = $query2->fetch(PDO::FETCH_ASSOC);
if($res2){
	$nome_pgto = $res2['nome'];
	$taxa_pgto = $res2['taxa'];
}else{
	$nome_pgto = 'Sem Registro';
	$taxa_pgto = 0;
}

$query2 = $pdo->prepare("SELECT nome FROM membros WHERE id = ? AND igreja = ?");
$query2->execute([$membro, $id_igreja]);
$res2 = $query2->fetch(PDO::FETCH_ASSOC);
$nome_membro = $res2 ? $res2['nome'] : 'Sem Registro';


if($pago == 'Sim'){
	$classe_pago = 'verde';
	$ocultar = 'ocultar';
	$ocultar_pendentes = '';
	$total_pago += $subtotal;
}else{
	$classe_pago = 'text-danger';
	$ocultar_pendentes = 'ocultar';
	$ocultar = '';
	$total_pendentes += $valor;
}	

$valor_multa = 0;
$valor_juros = 0;
$classe_venc = '';
if(@strtotime($vencimento) < @strtotime($data_hoje)){
	$classe_venc = 'text-danger';
	$valor_multa = $multa_atraso;

	//pegar a quantidade de dias que o pagamento está atrasado
	$dif = @strtotime($data_hoje) - @strtotime($vencimento);
	$dias_vencidos = floor($dif / (60*60*24));

	$valor_juros = ($valor * $juros_atraso / 100) * $dias_vencidos;
}

$total_pendentesF = @number_format($total_pendentes, 2, ',', '.');
$total_pagoF = @number_format($total_pago, 2, ',', '.');

$taxa_conta = $taxa_pgto * $valor / 100;




//PEGAR RESIDUOS DA CONTA
	$total_resid = 0;
	$valor_com_residuos = 0;
	$query2 = $pdo->prepare("SELECT id, valor, desconto FROM receber WHERE igreja = ? AND id_ref = ? AND residuo = 'Sim'");
	$query2->execute([$id_igreja, $id]);
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(count($res2) > 0){

		$descricao = '(Resíduo) - ' .$descricao;

		foreach($res2 as $residuo){
			$total_resid += $residuo['valor'] - $residuo['desconto'];
		}

		$valor_com_residuos = $valor + $total_resid;
	}
	if($valor_com_residuos > 0){
		$vlr_antigo_conta = '('.$valor_com_residuos.')';
		$descricao_link = '';
		$descricao_texto = 'd-none';
	}else{
		$vlr_antigo_conta = '';
		$descricao_link = 'd-none';
		$descricao_texto = '';
	}


	if($api_whatsapp != 'Não' and $membro != "" and $membro != "0"){
		$ocultar_cobranca = '';
	}else{
		$ocultar_cobranca = 'ocultar';
	}

echo <<<HTML

<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td><i class="fa fa-square {$classe_pago} mr-1"></i> {$descricao}</td>
<td class="">R$ {$valor_finalF} <small><a href="#" onclick="mostrarResiduos('{$id}')" class="text-danger" title="Ver Resíduos">{$vlr_antigo_conta}</a></small></td>	
<td class="esc">{$nome_membro}</td>
<td class="esc {$classe_venc}">{$vencimentoF}</td>
<td class="esc">{$data_pgtoF}</td>

<td class="esc"><a href="../img/contas/{$arquivo}" target="_blank"><img src="../img/contas/{$tumb_arquivo}" width="25px"></a></td>
<td>
	<big><a class="btn btn-primary btn-sm" href="#" onclick="editar('{$id}','{$descricao}','{$valor}','{$membro}','{$vencimento}','{$data_pgto}','{$forma_pgto}','{$frequencia}','{$obs}','{$tumb_arquivo}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

	<div class="btn btn-danger btn-sm" class="dropdown" style="display: inline-block;">                      
                        <a href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash " style="color: #ffff;" ></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div class="dropdown-item-text botao_excluir">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>

<big><a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$descricao}','{$valorF}','{$nome_membro}','{$vencimentoF}','{$data_pgtoF}','{$nome_pgto}','{$nome_frequencia}','{$obs}','{$tumb_arquivo}','{$multaF}','{$jurosF}','{$descontoF}','{$taxaF}','{$subtotalF}','{$nome_usu_lanc}','{$nome_usu_pgto}', '{$pago}', '{$arquivo}')" title="Mostrar Dados"><i class="fa fa-info-circle "></i></a></big>

<big><a class="{$ocultar} btn btn-success btn-sm" href="#" onclick="baixar('{$id}', '{$valor}', '{$descricao}', '{$forma_pgto}', '{$taxa_conta}', '{$valor_multa}', '{$valor_juros}')" title="Baixar Conta"><i class="fa fa-check-square "></i></a></big>

	<big><a  class="{$ocultar} btn btn-secondary btn-sm" href="#" onclick="parcelar('{$id}', '{$valor}', '{$descricao}')" title="Parcelar Conta"><i class="fa fa-calendar-o "></i></a></big>

		<big><a class="btn btn-primary btn-sm" href="#" onclick="arquivo('{$id}', '{$descricao}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o" ></i></a></big>

		<big><a class="{$ocultar} {$ocultar_cobranca} btn btn-success btn-sm" href="#" onclick="cobrar('{$id}')" title="Gerar Cobrança"><i class="fa fa-whatsapp " ></i></a></big>

			<form   method="POST" action="rel/recibo_conta_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id}">
					<big><button class="{$ocultar_pendentes} btn btn-success btn-sm" title="PDF do Recibo Conta; border:none; margin:0; padding:0"><i class="fa fa-file-pdf-o " style="color:white;"></i></button></big>
					</form>


					<form   method="POST" action="rel/imp_recibo.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id}">
					<big><button class="{$ocultar_pendentes} btn btn-sm" title="Imprimir Recibo 80mmm" style="background:#666464; border:none; margin:0; padding:0"><i class="fa fa-print " style="color:white"></i></button></big>
					</form>



	


</td>
</tr>
HTML;

}


echo <<<HTML
</small>
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>

</table>
</small>
<br>

			<span class="ocultar_mobile" style="font-size: 13px; border:1px solid #6092a8; padding:5px; ">
				Filtrar Por:  
				<a href="#" onclick="tipoData('vencimento')">Vencimento</a> / 
				<a href="#" onclick="tipoData('data_pgto')">Pagamento</a> /
				<a href="#" onclick="tipoData('data_lanc')">Lançamento</a> 
			</span>

			<p align="right" style="margin-top: -10px">
				<span style="margin-right: 10px">Total Pendentes  <span style="color:red">R$ {$total_pendentesF} </span></span>
				<span>Total Pago  <span style="color:green">R$ {$total_pagoF} </span></span>
			</p>

HTML;

}else{
	echo 'Nenhum Registro Encontrado!';
}
?>



<script type="text/javascript">
	$(document).ready( function () { 
    // Evita recriar em cima de dados vazios; só destrói se houver instância
    if ($.fn.DataTable.isDataTable('#tabela')) {
        $('#tabela').DataTable().destroy();
    }
    // Inicializa sobre a tabela já populada pelo PHP
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });


    $('#total_itens').text('R$ <?=$total_valorF?>');
        // Auto ajuste do tamanho dos números
        function shrinkIfLarge(id){
            var el = document.getElementById(id);
            if(!el) return;
            var text = (el.textContent||'').replace(/[^0-9]/g,'');
            if(text.length >= 7){ el.closest('h4').classList.add('auto-shrink'); }
        }
        shrinkIfLarge('total_total');
        shrinkIfLarge('total_vencidas');
        shrinkIfLarge('total_hoje');
        shrinkIfLarge('total_amanha');
        shrinkIfLarge('total_recebidas');
        shrinkIfLarge('total_pendent');

	    $('#total_total').text('R$ <?=$total_totalF?>');
	    $('#total_vencidas').text('R$ <?=$total_vencidasF?>');
	    $('#total_hoje').text('R$ <?=$total_hojeF?>');
	    $('#total_amanha').text('R$ <?=$total_amanhaF?>');
	    $('#total_recebidas').text('R$ <?=$total_recebidasF?>');
	    $('#total_pendent').text('R$ <?=$total_pendentF?>');
} );
</script>


<script type="text/javascript">
	function editar(id, descricao, valor, membro, vencimento, data_pgto, forma_pgto, frequencia, obs, arquivo){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#descricao').val(descricao);
    	$('#valor').val(valor);
    	$('#membro').val(membro).change();
    	$('#vencimento').val(vencimento);
    	$('#data_pgto').val(data_pgto);
    	$('#forma_pgto').val(forma_pgto).change();
    	$('#frequencia').val(frequencia).change();
    	$('#obs').val(obs);

    	$('#arquivo').val('');
    	$('#target').attr('src','../img/contas/' + arquivo);		

    	$('#modalForm').modal('show');
	}


	function mostrar(descricao, valor, membro, vencimento, data_pgto, nome_pgto, frequencia, obs, arquivo, multa, juros, desconto, taxa, total, usu_lanc, usu_pgto, pago, arq){

		if(data_pgto == ""){
			data_pgto = 'Pendente';
		}
		    	
    	$('#titulo_dados').text(descricao);
    	$('#valor_dados').text(valor);
    	$('#membro_dados').text(membro);
    	$('#vencimento_dados').text(vencimento);
    	$('#data_pgto_dados').text(data_pgto);
    	$('#nome_pgto_dados').text(nome_pgto);
    	$('#frequencia_dados').text(frequencia);
    	$('#obs_dados').text(obs);
    	
    	$('#multa_dados').text(multa);
    	$('#juros_dados').text(juros);
    	$('#desconto_dados').text(desconto);    	
    	$('#taxa_dados').text(taxa);
    	$('#total_dados').text(total);
    	$('#usu_lanc_dados').text(usu_lanc);
    	$('#usu_pgto_dados').text(usu_pgto);
    	
    	$('#pago_dados').text(pago);
    	$('#target_dados').attr("src", "../img/contas/" + arquivo);
    	$('#target_link_dados').attr("href", "../img/contas/" + arq);

    	$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
    	$('#descricao').val('');
    	$('#valor').val('');    	
    	$('#vencimento').val("<?=$data_atual?>");
    	$('#data_pgto').val('');    	
    	$('#obs').val('');
    	$('#arquivo').val('');

    	$('#target').attr("src", "../img/contas/sem-foto.png");

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
    	$('#btn-baixar').hide();	
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
			$('#btn-baixar').hide();
		}else{
			$('#btn-deletar').show();
			$('#btn-baixar').show();
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


	function deletarSelBaixar(){
		var ids = $('#ids').val();
		var id = ids.split("-");

		for(i=0; i<id.length-1; i++){
			var novo_id = id[i];
				$.ajax({
					url: 'paginas/' + pag + "/baixar_multiplas.php",
					method: 'POST',
					data: {novo_id},
					dataType: "html",

					success:function(result){
						//alert(result)
						
					}
				});		
		}

		setTimeout(() => {
		  	buscar();
			limparCampos();
		}, 1000);

		
	}


	function permissoes(id, nome){
		    	
    	$('#id_permissoes').val(id);
    	$('#nome_permissoes').text(nome);    	

    	$('#modalPermissoes').modal('show');
    	listarPermissoes(id);
	}

	
		function parcelar(id, valor, nome){
    $('#id-parcelar').val(id);
    $('#valor-parcelar').val(valor);
    $('#qtd-parcelar').val('');
    $('#nome-parcelar').text(nome);
    $('#nome-input-parcelar').val(nome);
    $('#modalParcelar').modal('show');
    $('#mensagem-parcelar').text('');
}


function baixar(id, valor, descricao, pgto, taxa, multa, juros){
	$('#id-baixar').val(id);
	$('#descricao-baixar').text(descricao);
	$('#valor-baixar').val(valor);
	$('#saida-baixar').val(pgto).change();
	$('#subtotal').val(valor);

	
	$('#valor-juros').val(juros);
	$('#valor-desconto').val('');
	$('#valor-multa').val(multa);
	$('#valor-taxa').val(taxa);

	totalizar()

	$('#modalBaixar').modal('show');
	$('#mensagem-baixar').text('');
}


function mostrarResiduos(id){

	$.ajax({
		url: 'paginas/' + pag + "/listar-residuos.php",
		method: 'POST',
		data: {id},
		dataType: "html",

		success:function(result){
			$("#listar-residuos").html(result);
		}
	});
	$('#modalResiduos').modal('show');
	
	
}

function arquivo(id, nome){
    $('#id-arquivo').val(id);    
    $('#nome-arquivo').text(nome);
    $('#modalArquivos').modal('show');
    $('#mensagem-arquivo').text(''); 
    $('#arquivo_conta').val('');
    listarArquivos();   
}


function cobrar(id){
	$.ajax({
		url: 'paginas/' + pag + "/cobrar.php",
		method: 'POST',
		data: {id},
		dataType: "html",

		success:function(result){
			alert(result);
		}
	});
}
	
</script>