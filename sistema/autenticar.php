<?php 
/**
 * Autenticação de Usuários
 * 
 * Versão: 2.0 (Sprint 2 - Security Hardening)
 * - Sprint 1: password_verify
 * - Sprint 2: Honeypot + Rate Limiting + Fingerprint
 * 
 * @author Nexus (Security Team)
 */

@session_start();
require_once("conexao.php");
require_once("helpers/RateLimiter.php");
require_once("helpers/SessionFingerprint.php");
require_once("helpers/SecurityLogger.php");

$secLogger = new SecurityLogger($pdo);

// =============================================================================
// INICIALIZAR VARIÁVEIS (FIX: variável $usuario para honeypot)
// =============================================================================

$usuario = $_POST['usuario'] ?? 'N/A';

// =============================================================================
// HONEYPOT ANTI-BOT (Task 2.1)
// =============================================================================

$honeypot = $_POST['website'] ?? '';

if (!empty($honeypot)) {
    // Bot detectado! (humanos não veem este campo)
    $rateLimiter = new RateLimiter($pdo);
    $rateLimiter->registrarFalha($usuario, 'BOT_DETECTED');
    $secLogger->logBotDetected($usuario);
    
    $_SESSION['msg'] = 'Erro no sistema. Tente novamente mais tarde.';
    echo '<script>window.location="index.php"</script>';
    exit();
}

// =============================================================================
// RATE LIMITING - VERIFICAR BLOQUEIO DE IP (Task 2.2)
// =============================================================================

$rateLimiter = new RateLimiter($pdo);
$status_bloqueio = $rateLimiter->verificarBloqueio();

if ($status_bloqueio['bloqueado']) {
    $minutos = $status_bloqueio['minutos_restantes'];
    $secLogger->logLoginBloqueado($usuario, $status_bloqueio['tentativas'] ?? 0, $minutos);
    
    if ($minutos > 60) {
        $horas = ceil($minutos / 60);
        $mensagem = "IP bloqueado por múltiplas tentativas. Tente novamente em {$horas} hora(s).";
    } else {
        $mensagem = "IP bloqueado. Tente novamente em {$minutos} minuto(s).";
    }
    
    $_SESSION['msg'] = $mensagem;
    echo '<script>window.location="index.php"</script>';
    exit();
}

// =============================================================================
// AUTENTICAÇÃO POR ID (SALVAR ACESSO - FUNCIONALIDADE EXISTENTE)
// =============================================================================

$id_usu = @$_POST['id'];
if($id_usu != ""){

	$query = $pdo->prepare("SELECT * from usuarios where id = :id");
	$query->bindValue(":id", "$id_usu");
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	
	if($linhas > 0){

	$_SESSION['nome'] = $res[0]['nome'];
	$_SESSION['id'] = $res[0]['id'];
	$_SESSION['nivel'] = $res[0]['nivel'];
	$_SESSION['id_igreja'] = $res[0]['igreja'];

	// Gerar fingerprint da sessão (Sprint 2 - Task 2.3)
	SessionFingerprint::renovar();

	// Gerar tokens JWT (Sprint 3)
	require_once("helpers/JWTManager.php");
	$jwtManager = new JWTManager($pdo);

	$accessToken = $jwtManager->gerarAccessToken([
		'id' => $res[0]['id'],
		'nome' => $res[0]['nome'],
		'nivel' => $res[0]['nivel'],
		'id_igreja' => $res[0]['igreja']
	]);

	$refreshToken = $jwtManager->gerarRefreshToken(
		$res[0]['id'],
		JWTManager::getClientIP(),
		$_SERVER['HTTP_USER_AGENT'] ?? null
	);

	echo "<script>
		localStorage.setItem('access_token', '$accessToken');
		localStorage.setItem('refresh_token', '$refreshToken');
	</script>";

	// EXECUTAR NO LOG
	$tabela = 'usuarios';
	$acao = 'Login';
	$id_reg = 0;
	$descricao = 'Login';
	if($res[0]['nivel'] == 'Bispo'){
		$painel = 'Painel Administrativo';
		$igreja = 0;
	}else{
		$painel = 'Painel Igreja';
		$igreja = $res[0]['igreja'];
	}
	require_once("logs.php");

	// Limpar bloqueio após login bem-sucedido
	$rateLimiter->limparBloqueio();

	echo '<script>window.location="painel"</script>';  
	}else{
		echo "<script>localStorage.setItem('id_usu', '')</script>";
		echo '<script>window.location="index.php"</script>';
	}
}

// =============================================================================
// AUTENTICAÇÃO NORMAL (EMAIL/CPF + SENHA)
// =============================================================================

$usuario = @$_POST['usuario'];
$senha = @$_POST['senha'];
$salvar = @$_POST['salvar'];

// Buscar usuário por email OU CPF
$query = $pdo->prepare("SELECT * from usuarios where (email = :usuario OR cpf = :usuario) AND ativo = 'Sim' order by id asc limit 1");
$query->bindValue(":usuario", "$usuario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){

	// Verificar senha
	if(!password_verify($senha, $res[0]['senha_crip'])){
		// Senha incorreta - registrar falha
		$rateLimiter->registrarFalha($usuario, 'LOGIN_FAILED');
		$secLogger->logLogin($res[0]['id'], false, $usuario, $res[0]['igreja'] ?? null);
		
		$_SESSION['msg'] = 'Dados Incorretos!';
		echo '<script>window.location="index.php"</script>';  
		exit();
	}

	// Verificar se usuário está ativo
	if($res[0]['ativo'] != 'Sim'){
		$_SESSION['msg'] = 'Seu Acesso foi desativado!'; 
		echo '<script>window.location="index.php"</script>';  
		exit();
	}

	// =========================================================================
	// LOGIN BEM-SUCEDIDO
	// =========================================================================

	$_SESSION['nome'] = $res[0]['nome'];
	$_SESSION['id'] = $res[0]['id'];
	$_SESSION['nivel'] = $res[0]['nivel'];
	$_SESSION['id_igreja'] = $res[0]['igreja'];

	// Gerar fingerprint da sessão (Sprint 2 - Task 2.3)
	SessionFingerprint::renovar();

	// Log de segurança (Sprint 4)
	$secLogger->logLogin($res[0]['id'], true, $usuario, $res[0]['igreja'] ?? null);
	$secLogger->logTokenGerado($res[0]['id']);

	$id = $res[0]['id'];

	// =========================================================================
	// GERAR TOKENS JWT (Sprint 3)
	// =========================================================================
	require_once("helpers/JWTManager.php");
	$jwtManager = new JWTManager($pdo);

	$accessToken = $jwtManager->gerarAccessToken([
		'id' => $res[0]['id'],
		'nome' => $res[0]['nome'],
		'nivel' => $res[0]['nivel'],
		'id_igreja' => $res[0]['igreja']
	]);

	$refreshToken = $jwtManager->gerarRefreshToken(
		$res[0]['id'],
		JWTManager::getClientIP(),
		$_SERVER['HTTP_USER_AGENT'] ?? null
	);

	// Armazenar tokens no localStorage (JavaScript)
	echo "<script>
		localStorage.setItem('access_token', '$accessToken');
		localStorage.setItem('refresh_token', '$refreshToken');
	</script>";

	// Salvar acesso no localStorage (se solicitado)
	if($salvar == 'Sim'){
		echo "<script>localStorage.setItem('email_usu', '$usuario')</script>";
		echo "<script>localStorage.setItem('senha_usu', '$senha')</script>";
		echo "<script>localStorage.setItem('id_usu', '$id')</script>";
	}else{
		echo "<script>localStorage.setItem('email_usu', '')</script>";
		echo "<script>localStorage.setItem('senha_usu', '')</script>";
		echo "<script>localStorage.setItem('id_usu', '')</script>";
	}
	

	// EXECUTAR NO LOG
	$tabela = 'usuarios';
	$acao = 'Login';
	$id_reg = 0;
	$descricao = 'Login';
	if($res[0]['nivel'] == 'Bispo'){
		$painel = 'Painel Administrativo';
		$igreja = 0;
	}else{
		$painel = 'Painel Igreja';
		$igreja = $res[0]['igreja'];
	}
	require_once("logs.php");
	
	// Limpar bloqueio após login bem-sucedido (Rate Limiting)
	$rateLimiter->limparBloqueio();

	// Redirecionar para painel apropriado
	if($res[0]['igreja'] == 0){
		echo "<script>window.location='painel-admin'</script>";
	}else{
		echo "<script>window.location='painel-igreja'</script>";
	}		

}else{
	// Usuário não encontrado - registrar falha
	$rateLimiter->registrarFalha($usuario, 'LOGIN_FAILED');
	$secLogger->logLogin(null, false, $usuario);
	
	$_SESSION['msg'] = 'Dados Incorretos!'; 
	echo '<script>window.location="index.php"</script>';  
}

?>
