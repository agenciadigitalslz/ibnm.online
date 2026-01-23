<?php 
require_once("../conexao.php");
@session_start();
$id_usuario = $_SESSION['id'];

$home = 'ocultar';
$home_midia = 'ocultar';
$configuracoes = 'ocultar';
$caixas = 'ocultar';
$tarefas = 'ocultar';
$lancar_tarefas = 'ocultar';
$anexos = 'ocultar';
$site = 'ocultar';

//grupo pessoas
$usuarios = 'ocultar';
$membros = 'ocultar';
$visitantes = 'ocultar';
$aniversariantes = 'ocultar';
$pastores = 'ocultar';
$tesoureiros = 'ocultar';
$secretarios = 'ocultar';
$fornecedores = 'ocultar';


//grupo cadastros
$dados_igreja = 'ocultar';
$cultos = 'ocultar';
$alertas = 'ocultar';
$eventos = 'ocultar';
$versiculos = 'ocultar';
$grupo_acessos = 'ocultar';
$acessos = 'ocultar';
$informativos = 'ocultar';


//grupo secretaria
$patrimonios = 'ocultar';
$documentos = 'ocultar';
$celulas = 'ocultar';
$grupos = 'ocultar';
$ministerios = 'ocultar';
$turmas = 'ocultar';

//grupo financeiro
$receber = 'ocultar';
$pagar = 'ocultar';
$dizimos = 'ocultar';
$ofertas = 'ocultar';
$ofertas_missoes_enviadas = 'ocultar';
$ofertas_missoes = 'ocultar';
$doacoes = 'ocultar';
$vendas = 'ocultar';
$movimentacoes = 'ocultar';
$fechamentos = 'ocultar';


//grupo relatorios
$rel_membros = 'ocultar';
$rel_patrimonios = 'ocultar';
$rel_financeiro = 'ocultar';
$rel_sintetico_receber = 'ocultar';
$rel_sintetico_despesas = 'ocultar';
$rel_balanco = 'ocultar';
$ficha_visitante = 'ocultar';

$query = $pdo->query("SELECT * FROM usuarios_permissoes_igreja where usuario = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Se o nível for Midia, forçar ocultar financeiro/relatórios por padrão
$nivel_atual = $_SESSION['nivel'] ?? '';
if ($nivel_atual === 'Midia') {
    // Dashboard Midia visível
    $home_midia = '';
    $home = '';

    // Permissões liberadas para Midia (não sensíveis)
    $membros = '';
    $visitantes = '';
    $aniversariantes = '';

    $dados_igreja = '';
    $cultos = '';
    $alertas = '';
    $eventos = '';
    $versiculos = '';
    $informativos = '';

    $patrimonios = '';
    $celulas = '';
    $grupos = '';
    $ministerios = '';
    $turmas = '';

    $tarefas = '';
    $site = '';

    // Bloqueios já aplicados abaixo para financeiro/relatórios

    $receber = 'ocultar';
    $pagar = 'ocultar';
    $dizimos = 'ocultar';
    $ofertas = 'ocultar';
    $ofertas_missoes_enviadas = 'ocultar';
    $ofertas_missoes = 'ocultar';
    $doacoes = 'ocultar';
    $vendas = 'ocultar';
    $movimentacoes = 'ocultar';
    $fechamentos = 'ocultar';

    $rel_membros = 'ocultar';
    $rel_patrimonios = 'ocultar';
    $rel_financeiro = 'ocultar';
    $rel_sintetico_receber = 'ocultar';
    $rel_sintetico_despesas = 'ocultar';
    $rel_balanco = 'ocultar';
    $ficha_visitante = 'ocultar';
}
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){}
			$permissao = $res[$i]['permissao'];

		$query2 = $pdo->query("SELECT * FROM acessos_igreja where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $res2[0]['nome'];
		$chave = $res2[0]['chave'];
		$id = $res2[0]['id'];



		if($chave == 'home'){
			$home = '';
		}

		if($chave == 'tarefas'){
			$tarefas = '';
		}

		if($chave == 'caixas'){
			$caixas = '';
		}

		if($chave == 'configuracoes'){
			$configuracoes = '';
		}

		if($chave == 'lancar_tarefas'){
			$lancar_tarefas = '';
		}

		if($chave == 'anexos'){
			$anexos = '';
		}

		if($chave == 'site'){
			$site = '';
		}


//grupo pessoas

		if($chave == 'usuarios'){
			$usuarios = '';
		}

		if($chave == 'membros'){
			$membros = '';
		}


		if($chave == 'visitantes'){
			$visitantes = '';
		}

		if($chave == 'aniversariantes'){
			$aniversariantes = '';
		}

		if($chave == 'pastores'){
			$pastores = '';
		}

		if($chave == 'tesoureiros'){
			$tesoureiros = '';
		}

		if($chave == 'secretarios'){
			$secretarios = '';
		}
		
		if($chave == 'fornecedores'){
			$fornecedores = '';
		}


//grupo cadastros


		if($chave == 'dados_igreja'){
			$dados_igreja = '';
		}


		if($chave == 'cultos'){
			$cultos = '';
		}

		if($chave == 'alertas'){
			$alertas = '';
		}

		if($chave == 'eventos'){
			$eventos = '';
		}

		if($chave == 'versiculos'){
			$versiculos = '';
		}


		if($chave == 'grupo_acessos'){
			$grupo_acessos = '';
		}

		if($chave == 'acessos'){
			$acessos = '';
		}

		if($chave == 'informativos'){
			$informativos = '';
		}


//grupo secretaria

		if($chave == 'patrimonios'){
			$patrimonios = '';
		}


		if($chave == 'documentos'){
			$documentos = '';
		}

		if($chave == 'celulas'){
			$celulas = '';
		}

		if($chave == 'grupos'){
			$grupos = '';
		}

		if($chave == 'ministerios'){
			$ministerios = '';
		}

		if($chave == 'turmas'){
			$turmas = '';
		}


//grupo financeiro


		if($chave == 'receber'){
			$receber = '';
		}


		if($chave == 'pagar'){
			$pagar = '';
		}


		if($chave == 'dizimos'){
			$dizimos = '';
		}


		if($chave == 'ofertas'){
			$ofertas = '';
		}


		if($chave == 'ofertas_missoes_enviadas'){
			$ofertas_missoes_enviadas = '';
		}


		if($chave == 'ofertas_missoes'){
			$ofertas_missoes = '';
		}


		if($chave == 'doacoes'){
			$doacoes = '';
		}

		if($chave == 'vendas'){
			$vendas = '';
		}

		if($chave == 'movimentacoes'){
			$movimentacoes = '';
		}

		if($chave == 'fechamentos'){
			$fechamentos = '';
		}




		
//grupo relatorios


		if($chave == 'rel_membros'){
			$rel_membros = '';
		}

		if($chave == 'rel_patrimonios'){
			$rel_patrimonios = '';
		}


		if($chave == 'rel_financeiro'){
			$rel_financeiro = '';
		}

		if($chave == 'rel_sintetico_receber'){
			$rel_sintetico_receber = '';
		}

		if($chave == 'rel_sintetico_despesas'){
			$rel_sintetico_despesas = '';
		}

		if($chave == 'rel_balanco'){
			$rel_balanco = '';
		}


		if($chave == 'rel_visitantes'){
			$rel_visitantes = '';
		}





	}

}



$pag_inicial = '';
if($home_midia != 'ocultar'){
	$pag_inicial = 'home_midia';
}else if($home != 'ocultar'){
	$pag_inicial = 'home';
}else{
	$query = $pdo->query("SELECT * FROM usuarios_permissoes_igreja where usuario = '$id_usuario'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);	
	if($total_reg > 0){
		for($i=0; $i<$total_reg; $i++){
			$permissao = $res[$i]['permissao'];		
			$query2 = $pdo->query("SELECT * FROM acessos_igreja where id = '$permissao'");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			if($res2[0]['pagina'] == 'Não'){
				continue;
			}else{
				$pag_inicial = $res2[0]['chave'];
				break;
			}	

		}


	}else{
		echo 'Você não tem permissão para acessar nenhuma página, acione o administrador!';
		exit();
	}
}



if($usuarios == 'ocultar' and $bispos == 'ocultar' and $fornecedores == 'ocultar' and $tesoureiros == 'ocultar' and $secretarios == 'ocultar' and $membros == 'ocultar' and $visitantes == 'ocultar' and $aniversariantes == 'ocultar' and $pastores == 'ocultar'){
	$menu_pessoas = 'ocultar';
}else{
	$menu_pessoas = '';
}


if($grupo_acessos == 'ocultar' and $acessos == 'ocultar' and $cultos == 'ocultar' and $alertas == 'ocultar' and $eventos == 'ocultar' and $versiculos == 'ocultar' and $informativos == 'ocultar' and $dados_igreja == 'ocultar'){
	$menu_cadastros = 'ocultar';
}else{
	$menu_cadastros = '';
}





if($patrimonios == 'ocultar' and $documentos == 'ocultar' and $celulas == 'ocultar' and $grupos == 'ocultar' and $ministerios == 'ocultar' and $turmas == 'ocultar'){
	$menu_secretaria = 'ocultar';
}else{
	$menu_secretaria = '';
}


if($receber == 'ocultar' and $pagar == 'ocultar' and $dizimos == 'ocultar' and $ofertas == 'ocultar' and $ofertas_missoes_enviadas == 'ocultar' and $ofertas_missoes == 'ocultar' and $doacoes == 'ocultar' and $vendas == 'ocultar' and $movimentacoes == 'ocultar' and $fechamentos == 'ocultar'){
	$menu_financeiro = 'ocultar';
}else{
	$menu_financeiro = '';
}


if($rel_membros == 'ocultar' and $rel_patrimonios == 'ocultar' and $rel_financeiro == 'ocultar' and $rel_sintetico_receber == 'ocultar' and $rel_sintetico_despesas == 'ocultar' and $rel_balanco == 'ocultar' and $ficha_visitante == 'ocultar' ){
	$menu_relatorios = 'ocultar';
}else{
	$menu_relatorios = '';
}

?>

