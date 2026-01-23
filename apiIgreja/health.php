<?php
declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

$response = [
  'service' => 'SaaS Igreja',
  'scope' => 'api',
  'status' => 'ok',
  'db_connected' => false,
  'server_time' => date('c'),
];

$configPath = __DIR__ . '/conexao.php';
$content = @file_get_contents($configPath);
if ($content === false) {
  $response['status'] = 'error';
  $response['error'] = 'config_unreadable';
  echo json_encode($response);
  exit;
}

$patterns = [
  'banco' => '/^\s*\$banco\s*=\s*["\']([^"\']*)["\']\s*;/mi',
  'servidor' => '/^\s*\$servidor\s*=\s*["\']([^"\']*)["\']\s*;/mi',
  'usuario' => '/^\s*\$usuario\s*=\s*["\']([^"\']*)["\']\s*;/mi',
  'senha' => '/^\s*\$senha\s*=\s*["\']([^"\']*)["\']\s*;/mi',
];

$creds = [];
foreach ($patterns as $key => $regex) {
  if (preg_match_all($regex, $content, $m)) {
    $creds[$key] = end($m[1]);
  }
}

if (count($creds) !== 4) {
  $response['status'] = 'error';
  $response['error'] = 'config_parse_failed';
  echo json_encode($response);
  exit;
}

if (isset($creds['servidor']) && strtolower($creds['servidor']) === 'localhost') {
  $creds['servidor'] = '127.0.0.1';
}

try {
  $dsn = "mysql:dbname={$creds['banco']};host={$creds['servidor']}";
  $pdo = new PDO($dsn, $creds['usuario'], $creds['senha'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_TIMEOUT => 5,
  ]);

  $check = $pdo->query('SELECT 1 AS ok')->fetch();
  $dbTime = $pdo->query('SELECT NOW() AS db_time')->fetch();

  $response['db_connected'] = isset($check['ok']) && (int)$check['ok'] === 1;
  $response['db_time'] = $dbTime['db_time'] ?? null;
} catch (Throwable $e) {
  $response['db_connected'] = false;
  $response['error'] = 'db_connection_failed';
}

echo json_encode($response);
