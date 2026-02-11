<?php 
/**
 * Verificar Autenticação + Session Fingerprint + JWT
 * 
 * Versão: 3.0 (Sprint 3)
 * - Valida JWT (access token) ou sessão PHP (backward compatibility)
 * - Valida fingerprint da sessão (anti-hijacking)
 * - Valida nível de acesso (Pastor/Igreja, não Bispo)
 * 
 * @author Nexus (Security Team)
 */

// Verificar JWT ou sessão PHP (Sprint 3)
require_once("../helpers/authMiddleware.php");
$user = verificarIgreja(); // Retorna usuário autenticado ou redireciona

// Validar fingerprint da sessão (Sprint 2 - Task 2.3)
// Apenas se for autenticação por sessão PHP
@session_start();
if (isset($_SESSION['id'])) {
	require_once("../helpers/SessionFingerprint.php");
	
	if (!SessionFingerprint::validar()) {
		// Fingerprint alterado - possível session hijacking!
		require_once("../helpers/SecurityLogger.php");
		$secLog = new SecurityLogger($GLOBALS['pdo'] ?? null);
		if ($GLOBALS['pdo'] ?? null) $secLog->logSessionHijack($_SESSION['id'] ?? null);
		
		SessionFingerprint::destruirSessao("../index.php");
		// Não retorna (exit no método destruirSessao)
	}
}

// Disponibilizar dados do usuário globalmente
$id_usuario = $user->id;
$nome_usuario = $user->nome;
$nivel_usuario = $user->nivel;
$id_igreja_usuario = $user->id_igreja;

?>

