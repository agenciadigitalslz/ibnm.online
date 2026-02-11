<?php
/**
 * API Igreja - Conexão com Banco de Dados
 * 
 * Versão: 2.0 (Refatorada - 2026-02-09)
 * Segurança: Credenciais isoladas em .env
 * 
 * @author Nexus (Security Hardening)
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 
header('Content-Type: application/json; charset=utf-8');  

date_default_timezone_set('America/Sao_Paulo');

// Carregar variáveis de ambiente do .env
function loadEnv($path = __DIR__ . '/../.env') {
    if (!file_exists($path)) {
        throw new Exception('.env não encontrado! Copie .env.example para .env e configure.');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentários
        if (strpos(trim($line), '#') === 0) continue;
        
        // Separar chave=valor
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            putenv(trim($name) . '=' . trim($value));
        }
    }
}

// Carregar .env
loadEnv();

// Configuração do Banco (via .env)
$banco = getenv('DB_NAME');
$servidor = getenv('DB_HOST');
$usuario = getenv('DB_USER');
$senha = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8mb4", "$usuario", "$senha", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
} catch (Exception $e) {
    if (getenv('APP_ENV') === 'development') {
        echo 'Erro ao conectar com o banco!! ' . $e->getMessage();
    } else {
        echo json_encode(['error' => 'Erro de conexão. Contate o administrador.']);
        error_log('DB Connection Error: ' . $e->getMessage());
    }
    exit();
}
?>
