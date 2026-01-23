<?php 
$tabela = 'config';
require_once("../conexao.php");

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$endereco = $_POST['endereco_sistema'];
$instagram = $_POST['instagram_sistema'];
$marca_dagua = $_POST['marca_dagua'];
$qtd_tar = $_POST['qtd_tar_igr'];
$limitar_tesoureiro = $_POST['limitar_tesoureiro'];
$relatorio_pdf = $_POST['relatorio_pdf'];
$cabecalho_rel_img = $_POST['cabecalho_rel_img'];
$itens_por_pagina = $_POST['itens_por_pagina'];
$logs = $_POST['logs'];
$multa_atraso = $_POST['multa_atraso'];
$juros_atraso = $_POST['juros_atraso'];
$dias_excluir_logs = $_POST['dias_excluir_logs'];
$assinatura_recibo = $_POST['assinatura_recibo'];
$impressao_automatica = $_POST['impressao_automatica'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$entrar_automatico = $_POST['entrar_automatico'];
$mostrar_preloader = $_POST['mostrar_preloader'];
$ocultar_mobile = $_POST['ocultar_mobile'];
$api_whatsapp = $_POST['api_whatsapp'];
$token_whatsapp = $_POST['token_whatsapp'];
$instancia_whatsapp = $_POST['instancia_whatsapp'];
$alterar_acessos = $_POST['alterar_acessos'];
$dados_pagamento = $_POST['dados_pagamento'];
$mostrar_acessos = $_POST['mostrar_acessos'];

//$foto_lr = @$_POST['foto-logo-rel'];
//$foto_ll = @$_POST['foto-logo'];
//$foto_ic = @$_POST['foto-icone'];





//recuperar fotos
//

$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$foto_ll = @$res[0]['logo'];
$foto_lr = @$res[0]['logo_rel'];
$foto_ic = @$res[0]['icone'];








//foto logo
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto-logo']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../teste/' .$nome_img;


$imagem_temp = @$_FILES['foto-logo']['tmp_name']; 

if(@$_FILES['foto-logo']['name'] != ""){
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){  
	
			if($logo_sistema != "sem-foto.jpg"){
				@unlink('../teste/'.$logo_sistema);
			}

			$foto_ll = $nome_img;
		
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}





//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$caminho = '../img/logo.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name']; 

if(@$_FILES['foto-logo-rel']['name'] != ""){
	$ext = $ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){  
			
			$foto_lr = 'logo.jpg';
		
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto icone
$caminho = '../img/icone.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name']; 

if(@$_FILES['foto-icone']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);    
	if($ext == 'png' || $ext == 'PNG'){  
	
			if($icone_sistema != "sem-foto.jpg"){
				@unlink('../teste/'.$icone_sistema);
			}

			$foto_ic = 'icone.png';
		
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida para o Ícone!';
		exit();
	}
}


//foto ass
$caminho = '../img/assinatura.jpg';
$imagem_temp = @$_FILES['assinatura_rel']['tmp_name']; 

if(@$_FILES['assinatura_rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['assinatura_rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto painel
$caminho = '../img/foto-painel.png';
$imagem_temp = @$_FILES['foto-painel']['tmp_name']; 

if(@$_FILES['foto-painel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-painel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

$multa_atraso = str_replace(',', '.', $multa_atraso);
$multa_atraso = str_replace('%', '', $multa_atraso);

$juros_atraso = str_replace(',', '.', $juros_atraso);
$juros_atraso = str_replace('%', '', $juros_atraso);


$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, instagram = :instagram, marca_dagua = :marca_dagua, qtd_tarefas = '$qtd_tar', limitar_tesoureiro = :limitar_tesoureiro, relatorio_pdf = :relatorio_pdf, cabecalho_rel_img = :cabecalho_rel_img, itens_por_pagina = :itens_por_pagina, logs = :logs, multa_atraso = :multa_atraso, juros_atraso = :juros_atraso, dias_excluir_logs = :dias_excluir_logs, assinatura_recibo = :assinatura_recibo, impressao_automatica = :impressao_automatica, cnpj = :cnpj_sistema, entrar_automatico = :entrar_automatico, mostrar_preloader = :mostrar_preloader, ocultar_mobile = :ocultar_mobile, api_whatsapp = '$api_whatsapp', token_whatsapp = :token_whatsapp, instancia_whatsapp = :instancia_whatsapp, alterar_acessos = :alterar_acessos, dados_pagamento = :dados_pagamento, mostrar_acessos = :mostrar_acessos, logo_rel =  '$foto_lr', logo =  '$foto_ll', icone =  '$foto_ic' where id = 1");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":marca_dagua", "$marca_dagua");
$query->bindValue(":limitar_tesoureiro", "$limitar_tesoureiro");
$query->bindValue(":relatorio_pdf", "$relatorio_pdf");
$query->bindValue(":cabecalho_rel_img", "$cabecalho_rel_img");
$query->bindValue(":itens_por_pagina", "$itens_por_pagina");
$query->bindValue(":logs", "$logs");
$query->bindValue(":multa_atraso", "$multa_atraso");
$query->bindValue(":juros_atraso", "$juros_atraso");
$query->bindValue(":dias_excluir_logs", "$dias_excluir_logs");
$query->bindValue(":assinatura_recibo", "$assinatura_recibo");
$query->bindValue(":impressao_automatica", "$impressao_automatica");
$query->bindValue(":cnpj_sistema", "$cnpj_sistema");
$query->bindValue(":entrar_automatico", "$entrar_automatico");
$query->bindValue(":mostrar_preloader", "$mostrar_preloader");
$query->bindValue(":ocultar_mobile", "$ocultar_mobile");
$query->bindValue(":token_whatsapp", "$token_whatsapp");
$query->bindValue(":instancia_whatsapp", "$instancia_whatsapp");
$query->bindValue(":alterar_acessos", "$alterar_acessos");
$query->bindValue(":dados_pagamento", "$dados_pagamento");
$query->bindValue(":mostrar_acessos", "$mostrar_acessos");
$query->execute();

echo 'Editado com Sucesso';
 ?>