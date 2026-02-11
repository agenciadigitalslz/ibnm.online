<?php 
date_default_timezone_set('America/Sao_Paulo');

// Configuraçao para produção cPanel
 // $banco = "agen9552_sistema-ibnm";
 // $servidor = "localhost"; //"186.209.113.108"	
 // $usuario = "agen9552_asl";
 // $senha = "ASL@2023web";

// Configuraçao para produção local wampp
$banco = "ibnm.online";
$servidor = "localhost";
$usuario = "root";
$senha = "";

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8mb4", "$usuario", "$senha", [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
	]);
} catch (Exception $e) {
	echo 'Erro ao conectar com o Banco de Dados! <br><br>' .$e;
	exit();
}



$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$scheme = $isHttps ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$scriptDir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$projectBase = preg_replace('#/sistema(?:/.*)?$#', '/', $scriptDir);
if (!$projectBase) { $projectBase = '/'; }
if (substr($projectBase, -1) !== '/') { $projectBase .= '/'; }

$url_sistema_site = $scheme . '://' . $host . $projectBase; // ex.: http://localhost/igreja/
$url_sistema = $url_sistema_site . 'sistema/'; // ex.: http://localhost/igreja/sistema/


$email_super_adm = "contato@agenciadigitalslz.com.br"; //email principal da igreja
$nome_sistema = "Nome da Igreja";
$telefone_igreja_sistema = '(00) 00000-0000';
$endereco_igreja_sistema = 'Rua A, Número 150, Bairro XX - Belo Horizonte - MG';



//VARIAVEIS GLOBAIS
$verso_carteirinha = 'Sim';
$quantidade_tarefas = 20; //exibir as proximas 20 tarefas no painel da igreja
$limitar_tesoureiro = 'Sim'; //Se tiver sim, o tesoureiro nao poderá excluir e nem editar as ofertas e dizimos.
$relatorio_pdf = 'Sim'; //SE ESSA OPÇÃO ESTIVER NÃÕ, O RELATÓRIO SERÁ GERADO EM HTML
$cabecalho_rel_img = 'Sim'; //SE ESSA OPÇÃO ESTIVER SIM, O RELATÓRIO TERÁ UMA IMAGEM NO CABEÇALHO, CADA IGREJA DEVERÁ SUBIR A SUA IMAGEM JPG NO CADASTRO DE IGREJAS
$itens_por_pagina = 9;
$logs = 'Sim';
$dias_excluir_logs = 40;


//INSERIR REGISTROS INICIAIS

//Criar o cargo de Membro
$query = $pdo->query("SELECT * FROM cargos");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);

if($total_reg == 0)
$pdo->query("INSERT INTO cargos SET nome = 'Membro'");


//Criar a frequencia de uma vez (unica)
$query = $pdo->query("SELECT * FROM frequencias");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);

if($total_reg == 0)
$pdo->query("INSERT INTO frequencias SET frequencia = 'Uma Vez', dias = 0");


//Criar variaveis padrões do sistema
$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);

if($total_reg == 0){
$pdo->query("INSERT INTO config SET nome = '$nome_igreja_sistema', email = '$email_super_adm', endereco = '$endereco_igreja_sistema', telefone = '$telefone_igreja_sistema', qtd_tarefas = '$quantidade_tarefas', limitar_tesoureiro = '$limitar_tesoureiro', relatorio_pdf = '$relatorio_pdf', cabecalho_rel_img = '$cabecalho_rel_img', itens_por_pagina = '$itens_por_pagina', logs = '$logs', multa_atraso = '0', juros_atraso = '0', notificacao = curDate(), dias_excluir_logs = '$dias_excluir_logs', marca_dagua = 'Sim', assinatura_recibo = 'Não', impressao_automatica = 'Não', api_whatsapp = 'Não', alterar_acessos = 'Não', logo = 'logo.png', logo_rel = 'logo.jpg', icone = 'icone.png'");

}


//BUSCAR INFORMAÇÕES DE CONFIGURAÇÕES NO BANCO
$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_sistema = $res[0]['nome'];
$email_super_adm = $res[0]['email'];
$nome_igreja_sistema = $res[0]['nome'];
$telefone_igreja_sistema = $res[0]['telefone'];
$endereco_igreja_sistema = $res[0]['endereco'];
$quantidade_tarefas = $res[0]['qtd_tarefas'];
$limitar_tesoureiro = $res[0]['limitar_tesoureiro'];
$relatorio_pdf = $res[0]['relatorio_pdf'];
$cabecalho_rel_img = $res[0]['cabecalho_rel_img'];
$itens_por_pagina = $res[0]['itens_por_pagina'];
$logs = $res[0]['logs'];
$multa_atraso = $res[0]['multa_atraso'];
$juros_atraso = $res[0]['juros_atraso'];
$dias_excluir_logs = $res[0]['dias_excluir_logs'];
$ocultar_mobile = $res[0]['ocultar_mobile'];
$mostrar_preloader = $res[0]['mostrar_preloader'];
$marca_dagua = $res[0]['marca_dagua'];
$assinatura_recibo = $res[0]['assinatura_recibo'];
$impressao_automatica = $res[0]['impressao_automatica'];
$cnpj_sistema = $res[0]['cnpj'];
$entrar_automatico = $res[0]['entrar_automatico'];
$api_whatsapp = $res[0]['api_whatsapp'];
$token_whatsapp = $res[0]['token_whatsapp'];
$instancia_whatsapp = $res[0]['instancia_whatsapp'];
$alterar_acessos = $res[0]['alterar_acessos'];
$dados_pagamento = $res[0]['dados_pagamento'];
$mostrar_acessos = $res[0]['mostrar_acessos'];
$logo_sistema = $res[0]['logo'];
$logo_rel = $res[0]['logo_rel'];
$icone_sistema = $res[0]['icone'];
$ativo_sistema = $res[0]['ativo'];

$tel_whats = '55' . preg_replace('/[ ()-]+/', '', $telefone_igreja_sistema);

if($ativo_sistema != 'Sim' and $ativo_sistema != ''){ ?>
	<style type="text/css">
		@media only screen and (max-width: 700px) {
  .imgsistema_mobile{
    width:300px;
  }
    
}
	</style>
	<div style="text-align: center; margin-top: 100px">
	<img src="<?php echo $url_sistema ?>img/bloqueio.png" class="imgsistema_mobile">	
	</div>
<?php 
exit();
} 

	
 ?>