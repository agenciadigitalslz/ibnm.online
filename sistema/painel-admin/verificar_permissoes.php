<?php 
require_once("../conexao.php");
@session_start();
$id_usuario = $_SESSION['id'];

$home = 'ocultar';
$configuracoes = 'ocultar';
$caixas = 'ocultar';
$tarefas = 'ocultar';
$lancar_tarefas = 'ocultar';
$site = 'ocultar';

//grupo pessoas
$usuarios = 'ocultar';
$tesoureiros = 'ocultar';
$fornecedores = 'ocultar';
$pastores = 'ocultar';
$bispos = 'ocultar';
$secretarios = 'ocultar';


//grupo cadastros
$grupo_acessos = 'ocultar';
$acessos = 'ocultar';
$frequencias = 'ocultar';
$cargos = 'ocultar';
$formas_pgto = 'ocultar';
$igrejas = 'ocultar';


//grupo consultas
$anexos = 'ocultar';
$patrimonios = 'ocultar';


//grupo relatorios
$rel_membros = 'ocultar';
$rel_patrimonios = 'ocultar';
$rel_logs = 'ocultar';
$rel_transfMembros = 'ocultar';
$rel_financeiro = 'ocultar';
$rel_fechamentos = 'ocultar';
$rel_sintetico_despesas = 'ocultar';
$rel_sintetico_receber = 'ocultar';
$rel_balanco = 'ocultar';


$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){}
		$permissao = $res[$i]['permissao'];

		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $res2[0]['nome'];
		$chave = $res2[0]['chave'];
		$id = $res2[0]['id'];

		if($chave == 'home'){
			$home = '';
		}

		if($chave == 'site'){
			$site = '';
		}

		if($chave == 'configuracoes'){
			$configuracoes = '';
		}


		if($chave == 'usuarios'){
			$usuarios = '';
		}

		if($chave == 'tesoureiros'){
			$tesoureiros = '';
		}

		if($chave == 'fornecedores'){
			$fornecedores = '';
		}

		if($chave == 'caixas'){
			$caixas = '';
		}

		if($chave == 'tarefas'){
			$tarefas = '';
		}

		if($chave == 'lancar_tarefas'){
			$lancar_tarefas = '';
		}
		
		if($chave == 'secretarios'){
			$secretarios = '';
		}

		if($chave == 'pastores'){
			$pastores = '';
		}

		if($chave == 'bispos'){
			$bispos = '';
		}


		if($chave == 'grupo_acessos'){
			$grupo_acessos = '';
		}

		if($chave == 'acessos'){
			$acessos = '';
		}

		if($chave == 'frequencias'){
			$frequencias = '';
		}

		if($chave == 'cargos'){
			$cargos = '';
		}

		if($chave == 'formas_pgto'){
			$formas_pgto = '';
		}

		if($chave == 'igrejas'){
			$igrejas = '';
		}

		




		if($chave == 'anexos'){
			$anexos = '';
		}

		if($chave == 'patrimonios'){
			$patrimonios = '';
		}
		

		if($chave == 'rel_membros'){
			$rel_membros = '';
		}


		if($chave == 'rel_patrimonios'){
			$rel_patrimonios = '';
		}


		if($chave == 'rel_logs'){
			$rel_logs = '';
		}


		if($chave == 'rel_transfMembros'){
			$rel_transfMembros = '';
		}

		if($chave == 'rel_fechamentos'){
			$rel_fechamentos = '';
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

	



	}

}



$pag_inicial = '';
if($home != 'ocultar'){
	$pag_inicial = 'home';
}else{
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);	
	if($total_reg > 0){
		for($i=0; $i<$total_reg; $i++){
			$permissao = $res[$i]['permissao'];		
			$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
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



if($usuarios == 'ocultar' and $bispos == 'ocultar' and $pastores == 'ocultar' and $tesoureiros == 'ocultar' and $secretarios == 'ocultar' and $fornecedores == 'ocultar'){
	$menu_pessoas = 'ocultar';
}else{
	$menu_pessoas = '';
}


if($grupo_acessos == 'ocultar' and $acessos == 'ocultar' and $igrejas == 'ocultar' and $frequencias == 'ocultar' and $cargos == 'ocultar' and $formas_pgto == 'ocultar'){
	$menu_cadastros = 'ocultar';
}else{
	$menu_cadastros = '';
}





if($patrimonios == 'ocultar' and $anexos == 'ocultar'){
	$menu_consultas = 'ocultar';
}else{
	$menu_consultas = '';
}


if($rel_membros == 'ocultar' and $rel_patrimonios == 'ocultar' and $rel_logs == 'ocultar' and $rel_transfMembros == 'ocultar' and $rel_fechamentos == 'ocultar' and $rel_financeiro == 'ocultar' and $rel_sintetico_despesas == 'ocultar' and $rel_sintetico_receber == 'ocultar' and $rel_balanco == 'ocultar'){
	$menu_relatorios = 'ocultar';
}else{
	$menu_relatorios = '';
}

?>