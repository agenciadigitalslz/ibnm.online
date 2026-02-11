---
Tipo: planejamento
Data: 2026-02-09
Autor: André Lopes + Nexus
Status: ✅ COMPLETO (4/4 Sprints — 10/02/2026)
Criticidade: CRÍTICA
---

# PLANEJAMENTO DE SEGURANÇA EM CAMADAS — SaaS IBNM.online

**Objetivo**: Transformar o sistema de gestão eclesiástica em uma plataforma Enterprise com conformidade LGPD, proteção contra ataques automatizados e auditoria em tempo real.

**Duração Estimada**: 4 Sprints (3-4 semanas)  
**Prioridade**: Produção BLOQUEADA até Sprint 1 completa

---

## 🎯 VISÃO GERAL

### Modelo de Defesa em Profundidade (Defense in Depth)

```
┌─────────────────────────────────────────────────┐
│  CAMADA 4: AUDITORIA E MONITORAMENTO            │ Sprint 4
├─────────────────────────────────────────────────┤
│  CAMADA 3: AUTENTICAÇÃO ENTERPRISE (JWT)        │ Sprint 3
├─────────────────────────────────────────────────┤
│  CAMADA 2: PERÍMETRO (Anti-Bot + Headers)       │ Sprint 2
├─────────────────────────────────────────────────┤
│  CAMADA 1: BLINDAGEM CRÍTICA (SQL + .env)       │ Sprint 1 (URGENTE)
└─────────────────────────────────────────────────┘
```

---

## 🔴 **SPRINT 1: BLINDAGEM CRÍTICA (URGENTE)**

**Objetivo**: Eliminar vetores de ataque primários (SQL Injection + credenciais expostas)  
**Duração**: 3-5 dias  
**Status**: 🟡 Aguardando aprovação

### 📋 Tarefas Detalhadas

#### **1.1 SQL Injection no Login API** 🔴 CRÍTICA

**Arquivo**: `apiIgreja/login/login.php`

**Problema Atual**:
```php
// LINHA 7 - VULNERÁVEL
$query_buscar = $pdo->query("SELECT * from usuarios where (email = '$postjson[email]' or cpf = '$postjson[email]') and senha = '$postjson[senha]' ");
```

**Vulnerabilidades**:
- Concatenação direta de variáveis JSON
- Senha comparada em texto plano
- Ausência de sanitização de inputs
- Bypass trivial: `email: "' OR '1'='1'--"`

**Solução**:
```php
<?php 
include_once('../conexao.php');

$postjson = json_decode(file_get_contents("php://input"), true);

// Sanitização de inputs
$email = filter_var($postjson['email'] ?? '', FILTER_SANITIZE_EMAIL);
$senha = $postjson['senha'] ?? '';

// Prepared Statement
$query_buscar = $pdo->prepare("
    SELECT * FROM usuarios 
    WHERE (email = ? OR cpf = ?) 
    AND ativo = 'Sim'
");
$query_buscar->execute([$email, $email]);
$dados_buscar = $query_buscar->fetchAll(PDO::FETCH_ASSOC);

// Verificação de senha com password_verify
if (count($dados_buscar) > 0) {
    $usuario = $dados_buscar[0];
    
    if (password_verify($senha, $usuario['senha_crip'])) {
        // Buscar dados da igreja
        $query_igreja = $pdo->prepare("SELECT * FROM igrejas WHERE id = ?");
        $query_igreja->execute([$usuario['igreja']]);
        $res_igreja = $query_igreja->fetchAll(PDO::FETCH_ASSOC);
        
        $dados[] = array(
            'id' => intVal($usuario['id']),
            'nome' => $usuario['nome'],  
            'email' => $usuario['email'],
            'nivel' => $usuario['nivel'],
            'igreja' => intVal($usuario['igreja']),  
            'nome_igreja' => $res_igreja[0]['nome'] ?? '',
            'foto_igreja' => $res_igreja[0]['imagem'] ?? '',
        );
        
        echo json_encode(array('result' => $dados));
    } else {
        echo json_encode(array('success' => 'Dados Incorretos!'));
    }
} else {
    echo json_encode(array('success' => 'Dados Incorretos!'));
}
?>
```

**Entregáveis**:
- [x] Refatorar `apiIgreja/login/login.php`
- [x] Testar login via Postman/Insomnia
- [x] Validar resposta JSON
- [x] Documentar em `docs/updates.md`

---

#### **1.2 Migração de Senhas para `password_hash`** 🔴 CRÍTICA

**Problema**: Coluna `usuarios.senha` armazena texto plano (usada apenas na API)

**Estratégia de Migração**:

**1. Script de Migração**:
```php
<?php
// Script: SQL/migrar_senhas.php
include_once('../sistema/conexao.php');

echo "Iniciando migração de senhas...\n";

// Selecionar todos os usuários com senha em texto plano
$query = $pdo->query("SELECT id, senha FROM usuarios WHERE senha_crip IS NULL OR senha_crip = ''");
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

$total = count($usuarios);
echo "Total de usuários a migrar: {$total}\n";

foreach ($usuarios as $usuario) {
    $id = $usuario['id'];
    $senha_plana = $usuario['senha'];
    
    // Gerar hash bcrypt (custo 12)
    $senha_hash = password_hash($senha_plana, PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Atualizar senha_crip
    $update = $pdo->prepare("UPDATE usuarios SET senha_crip = ? WHERE id = ?");
    $update->execute([$senha_hash, $id]);
    
    echo "✓ Usuário ID {$id} migrado\n";
}

echo "\n✅ Migração concluída! {$total} usuários atualizados.\n";
?>
```

**2. Validação Dual (Compatibilidade)**:
```php
// Durante período de transição: aceitar senha_crip OU senha
if (!empty($usuario['senha_crip'])) {
    $senha_valida = password_verify($senha, $usuario['senha_crip']);
} else {
    $senha_valida = ($senha === $usuario['senha']); // Fallback temporário
}
```

**Entregáveis**:
- [x] Script `SQL/migrar_senhas.php`
- [x] Backup completo da tabela `usuarios` antes da migração
- [x] Executar migração em localhost
- [x] Validar login web E mobile após migração
- [x] Remover coluna `senha` após 100% de confiança (Sprint 3)

---

#### **1.3 Proteção de Credenciais com `.env`** 🔴 CRÍTICA

**Problema**: Credenciais hardcoded em `sistema/conexao.php`

**Solução**:

**1. Criar `.env`**:
```env
# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=ibnm.online
DB_USER=root
DB_PASSWORD=

# URLs (geradas automaticamente, mas podem ser sobrescritas)
# URL_SISTEMA_SITE=http://localhost/ibnm.online/
# URL_SISTEMA=http://localhost/ibnm.online/sistema/

# Configurações de Email
EMAIL_SUPER_ADM=contato@agenciadigitalslz.com.br

# Sistema
NOME_SISTEMA=Igreja Batista Nacional Maracanã
TELEFONE_IGREJA=(00) 00000-0000
ENDERECO_IGREJA=Rua A, Número 150, Bairro XX

# Ambiente (development | production)
APP_ENV=development
```

**2. Criar `.env.example` (template versionado)**:
```env
# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=nome_do_banco
DB_USER=usuario
DB_PASSWORD=senha_segura

# URLs
# URL_SISTEMA_SITE=
# URL_SISTEMA=

# Email
EMAIL_SUPER_ADM=seu_email@exemplo.com

# Sistema
NOME_SISTEMA=Nome da Sua Igreja
TELEFONE_IGREJA=(00) 00000-0000
ENDERECO_IGREJA=Seu Endereço Completo

# Ambiente
APP_ENV=development
```

**3. Atualizar `.gitignore`**:
```gitignore
# Credenciais
.env

# Backups e dumps
*.sql
backup/

# Logs
error_log
sistema/img/
```

**4. Refatorar `sistema/conexao.php`**:
```php
<?php 
date_default_timezone_set('America/Sao_Paulo');

// Carregar variáveis de ambiente
function loadEnv($path = __DIR__ . '/../.env') {
    if (!file_exists($path)) {
        throw new Exception('.env não encontrado! Copie .env.example para .env e configure.');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorar comentários
        
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv(trim($name) . '=' . trim($value));
    }
}

loadEnv();

// Configuração do Banco
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
        echo 'Erro ao conectar com o Banco de Dados! <br><br>' . $e;
    } else {
        echo 'Erro de conexão. Contate o administrador.';
        error_log('DB Connection Error: ' . $e->getMessage());
    }
    exit();
}

// URLs automáticas (mantém lógica existente)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$scheme = $isHttps ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$scriptDir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$projectBase = preg_replace('#/sistema(?:/.*)?$#', '/', $scriptDir);
if (!$projectBase) { $projectBase = '/'; }
if (substr($projectBase, -1) !== '/') { $projectBase .= '/'; }

$url_sistema_site = getenv('URL_SISTEMA_SITE') ?: ($scheme . '://' . $host . $projectBase);
$url_sistema = getenv('URL_SISTEMA') ?: ($url_sistema_site . 'sistema/');

// Configurações do sistema
$email_super_adm = getenv('EMAIL_SUPER_ADM');
$nome_sistema = getenv('NOME_SISTEMA');
$telefone_igreja_sistema = getenv('TELEFONE_IGREJA');
$endereco_igreja_sistema = getenv('ENDERECO_IGREJA');

// [Resto do código permanece igual]
?>
```

**Entregáveis**:
- [x] Arquivo `.env` criado e testado
- [x] `.env.example` versionado no Git
- [x] `.gitignore` atualizado
- [x] `conexao.php` refatorado
- [x] Documentação de setup atualizada no README.md

---

#### **1.4 Checklist de Segurança Pré-Deploy**

- [ ] `.env` configurado em **localhost**
- [ ] `.env` configurado em **produção cPanel** (via File Manager)
- [ ] `.gitignore` impede commit de `.env`
- [ ] Senhas migradas para `senha_crip`
- [ ] Login API testado via Postman
- [ ] Login Web testado em 3 níveis (Admin, Pastor, Tesoureiro)
- [ ] Backup completo do banco antes do deploy
- [ ] Documentação atualizada em `docs/updates.md`

---

## 🛡️ **SPRINT 2: PERÍMETRO DE DEFESA**

**Objetivo**: Anti-Bot e Headers de Segurança  
**Duração**: 2-3 dias  
**Status**: 🔵 Aguardando Sprint 1

### **2.1 Honeypot no Login**

**Conceito**: Campo invisível que bots preenchem automaticamente (humanos não veem).

**Implementação**:

**HTML** (`sistema/index.php`):
```html
<!-- Campo honeypot (oculto via CSS) -->
<input type="text" name="website" id="website" style="display:none;" tabindex="-1" autocomplete="off">
```

**PHP** (`sistema/autenticar.php`):
```php
// No topo do arquivo
$honeypot = $_POST['website'] ?? '';

if (!empty($honeypot)) {
    // Bot detectado
    error_log('Bot detectado - Honeypot preenchido: IP ' . $_SERVER['REMOTE_ADDR']);
    echo "<script>window.location='index.php?erro=sistema'</script>";
    exit();
}

// Continua com autenticação normal...
```

---

### **2.2 Rate Limiting Progressivo**

**Tabela de Controle**:
```sql
CREATE TABLE failed_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    email_tentativa VARCHAR(255) NULL,
    tentativas INT DEFAULT 1,
    bloqueado_ate DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ip (ip),
    INDEX idx_bloqueado (bloqueado_ate)
);
```

**Lógica** (`sistema/autenticar.php`):
```php
$ip = $_SERVER['REMOTE_ADDR'];

// Verificar se IP está bloqueado
$check_block = $pdo->prepare("SELECT * FROM failed_logins WHERE ip = ? AND bloqueado_ate > NOW()");
$check_block->execute([$ip]);
$bloqueio = $check_block->fetch(PDO::FETCH_ASSOC);

if ($bloqueio) {
    $minutos_restantes = ceil((strtotime($bloqueio['bloqueado_ate']) - time()) / 60);
    echo "<script>alert('IP bloqueado. Tente novamente em {$minutos_restantes} minutos.'); window.location='index.php'</script>";
    exit();
}

// Se login falhou, registrar tentativa
if ($login_falhou) {
    $check_fail = $pdo->prepare("SELECT * FROM failed_logins WHERE ip = ?");
    $check_fail->execute([$ip]);
    $registro = $check_fail->fetch(PDO::FETCH_ASSOC);
    
    if ($registro) {
        $tentativas = $registro['tentativas'] + 1;
        
        // Escalação progressiva
        if ($tentativas >= 9) {
            $bloqueio_minutos = 60; // 1 hora
        } elseif ($tentativas >= 6) {
            $bloqueio_minutos = 10;
        } else {
            $bloqueio_minutos = 1;
        }
        
        $bloqueado_ate = date('Y-m-d H:i:s', strtotime("+{$bloqueio_minutos} minutes"));
        
        $update = $pdo->prepare("UPDATE failed_logins SET tentativas = ?, bloqueado_ate = ?, email_tentativa = ? WHERE ip = ?");
        $update->execute([$tentativas, $bloqueado_ate, $email, $ip]);
    } else {
        $insert = $pdo->prepare("INSERT INTO failed_logins (ip, email_tentativa) VALUES (?, ?)");
        $insert->execute([$ip, $email]);
    }
}

// Se login bem-sucedido, limpar registro
if ($login_sucesso) {
    $pdo->prepare("DELETE FROM failed_logins WHERE ip = ?")->execute([$ip]);
}
```

---

### **2.3 Headers de Segurança via `.htaccess`**

**Arquivo**: `.htaccess` (raiz do projeto)

```apache
# Segurança HTTP Headers
<IfModule mod_headers.c>
    # Content Security Policy
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';"
    
    # Prevenir Clickjacking
    Header always append X-Frame-Options SAMEORIGIN
    
    # Forçar HTTPS (em produção)
    # Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # Prevenir MIME sniffing
    Header set X-Content-Type-Options "nosniff"
    
    # XSS Protection (navegadores antigos)
    Header set X-XSS-Protection "1; mode=block"
    
    # Referrer Policy
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    
    # Permissions Policy (desabilitar recursos não usados)
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Bloquear acesso a arquivos sensíveis
<FilesMatch "^\.env$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Desabilitar listagem de diretórios
Options -Indexes

# Proteção contra injeção de scripts em uploads
<FilesMatch "\.(jpg|jpeg|png|gif|pdf|doc|docx)$">
    Header set Content-Disposition "inline"
    Header set X-Content-Type-Options "nosniff"
</FilesMatch>
```

---

## 🔐 **SPRINT 3: AUTENTICAÇÃO ENTERPRISE (JWT)**

**Objetivo**: Migrar API para tokens JWT  
**Duração**: 4-5 dias  
**Status**: 🟣 Aguardando Sprint 2

### **3.1 Biblioteca Firebase JWT**

**Instalação via Composer**:
```bash
cd C:\xampp\htdocs\ibnm.online
composer require firebase/php-jwt
```

**Autoload** (criar `vendor/autoload.php` se não existir):
```bash
composer dump-autoload
```

---

### **3.2 Geração de Token JWT**

**Novo Endpoint**: `apiIgreja/auth/token.php`

```php
<?php
require_once('../../vendor/autoload.php');
require_once('../../sistema/conexao.php');

use \Firebase\JWT\JWT;

$postjson = json_decode(file_get_contents("php://input"), true);

$email = filter_var($postjson['email'] ?? '', FILTER_SANITIZE_EMAIL);
$senha = $postjson['senha'] ?? '';

// Buscar usuário
$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = ? OR cpf = ?) AND ativo = 'Sim'");
$query->execute([$email, $email]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($senha, $usuario['senha_crip'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciais inválidas']);
    exit();
}

// Buscar dados da igreja
$query_igreja = $pdo->prepare("SELECT nome, imagem FROM igrejas WHERE id = ?");
$query_igreja->execute([$usuario['igreja']]);
$igreja = $query_igreja->fetch(PDO::FETCH_ASSOC);

// Chave secreta (mover para .env)
$secret_key = getenv('JWT_SECRET') ?: 'chave-super-secreta-mudar-em-producao';

// Payload do token
$payload = [
    'iss' => $url_sistema_site, // Issuer
    'aud' => $url_sistema_site, // Audience
    'iat' => time(), // Issued at
    'exp' => time() + (60 * 60 * 24), // Expira em 24h
    'data' => [
        'id' => intVal($usuario['id']),
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'nivel' => $usuario['nivel'],
        'igreja' => intVal($usuario['igreja']),
        'nome_igreja' => $igreja['nome'] ?? '',
        'foto_igreja' => $igreja['imagem'] ?? ''
    ]
];

// Gerar token
$jwt = JWT::encode($payload, $secret_key, 'HS256');

// Salvar refresh token no banco
$refresh_token = bin2hex(random_bytes(32));
$expira_refresh = date('Y-m-d H:i:s', strtotime('+30 days'));

$insert_token = $pdo->prepare("INSERT INTO tokens (usuario, token, expiracao) VALUES (?, ?, ?)");
$insert_token->execute([$usuario['id'], $refresh_token, $expira_refresh]);

echo json_encode([
    'access_token' => $jwt,
    'refresh_token' => $refresh_token,
    'token_type' => 'Bearer',
    'expires_in' => 86400, // 24h em segundos
    'user' => $payload['data']
]);
?>
```

---

### **3.3 Middleware de Validação JWT**

**Arquivo**: `apiIgreja/middleware/auth.php`

```php
<?php
require_once('../../vendor/autoload.php');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function validarJWT() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
        http_response_code(401);
        echo json_encode(['error' => 'Token não fornecido']);
        exit();
    }
    
    $jwt = str_replace('Bearer ', '', $authHeader);
    $secret_key = getenv('JWT_SECRET') ?: 'chave-super-secreta-mudar-em-producao';
    
    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        return $decoded->data; // Retorna dados do usuário
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido ou expirado']);
        exit();
    }
}
?>
```

**Uso em Endpoints**:
```php
<?php
require_once('../middleware/auth.php');

$usuario = validarJWT(); // Valida e retorna dados do usuário

// Continua com lógica do endpoint...
echo json_encode(['mensagem' => "Bem-vindo, {$usuario->nome}!"]);
?>
```

---

### **3.4 Refresh Token**

**Tabela** (já existe `tokens`, validar estrutura):
```sql
CREATE TABLE IF NOT EXISTS tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiracao DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expiracao (expiracao)
);
```

**Endpoint**: `apiIgreja/auth/refresh.php`
```php
<?php
require_once('../../vendor/autoload.php');
require_once('../../sistema/conexao.php');

use \Firebase\JWT\JWT;

$postjson = json_decode(file_get_contents("php://input"), true);
$refresh_token = $postjson['refresh_token'] ?? '';

// Validar refresh token
$query = $pdo->prepare("SELECT * FROM tokens WHERE token = ? AND expiracao > NOW()");
$query->execute([$refresh_token]);
$token_db = $query->fetch(PDO::FETCH_ASSOC);

if (!$token_db) {
    http_response_code(401);
    echo json_encode(['error' => 'Refresh token inválido ou expirado']);
    exit();
}

// Buscar usuário
$query_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$query_user->execute([$token_db['usuario']]);
$usuario = $query_user->fetch(PDO::FETCH_ASSOC);

// Gerar novo access token (mesmo código do endpoint /token)
// [...]

echo json_encode([
    'access_token' => $novo_jwt,
    'token_type' => 'Bearer',
    'expires_in' => 86400
]);
?>
```

---

## 📊 **SPRINT 4: AUDITORIA E MONITORAMENTO**

**Objetivo**: Visibilidade total de segurança  
**Duração**: 3-4 dias  
**Status**: 🟤 Aguardando Sprint 3

### **4.1 SecurityLogger Centralizado**

**Arquivo**: `sistema/helpers/SecurityLogger.php`

```php
<?php
class SecurityLogger {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function log($tipo, $descricao, $usuario_id = null, $ip = null, $severidade = 'INFO') {
        $ip = $ip ?: $_SERVER['REMOTE_ADDR'];
        
        $query = $this->pdo->prepare("
            INSERT INTO security_logs (tipo, descricao, usuario_id, ip, severidade) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $query->execute([$tipo, $descricao, $usuario_id, $ip, $severidade]);
    }
    
    public function loginFalho($email, $motivo = 'Senha incorreta') {
        $this->log('LOGIN_FAILED', "Falha no login: {$email} - {$motivo}", null, null, 'WARNING');
    }
    
    public function loginSucesso($usuario_id, $nome) {
        $this->log('LOGIN_SUCCESS', "Login bem-sucedido: {$nome}", $usuario_id, null, 'INFO');
    }
    
    public function acessoNegado($usuario_id, $modulo) {
        $this->log('ACCESS_DENIED', "Acesso negado ao módulo: {$modulo}", $usuario_id, null, 'WARNING');
    }
    
    public function sqlInjectionDetectado($query) {
        $this->log('SQL_INJECTION', "Tentativa de SQL Injection detectada: {$query}", null, null, 'CRITICAL');
    }
    
    public function botDetectado($motivo) {
        $this->log('BOT_DETECTED', "Bot detectado: {$motivo}", null, null, 'HIGH');
    }
}
?>
```

**Tabela**:
```sql
CREATE TABLE security_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    usuario_id INT NULL,
    ip VARCHAR(45) NOT NULL,
    severidade ENUM('INFO', 'WARNING', 'HIGH', 'CRITICAL') DEFAULT 'INFO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_severidade (severidade),
    INDEX idx_created (created_at),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
```

---

### **4.2 Dashboard de Segurança**

**Arquivo**: `sistema/painel-admin/paginas/security_dashboard.php`

**Métricas**:
- Tentativas de login falhadas (últimas 24h, 7 dias, 30 dias)
- IPs bloqueados atualmente
- Top 10 IPs com mais tentativas
- Logs de severidade CRITICAL/HIGH
- Gráfico de eventos por hora/dia

**Interface** (exemplo com ApexCharts):
```php
<?php
require_once("../../conexao.php");
require_once("verificar.php");

$pag = 'security_dashboard';

// Estatísticas gerais
$query_stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 ELSE 0 END) as ultimas_24h,
        SUM(CASE WHEN severidade = 'CRITICAL' THEN 1 ELSE 0 END) as criticos
    FROM security_logs
");
$stats = $query_stats->fetch(PDO::FETCH_ASSOC);

// IPs bloqueados
$query_blocked = $pdo->query("
    SELECT ip, tentativas, bloqueado_ate 
    FROM failed_logins 
    WHERE bloqueado_ate > NOW() 
    ORDER BY tentativas DESC
");
$bloqueados = $query_blocked->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard de Segurança</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <div class="container-fluid">
        <h3>🛡️ Dashboard de Segurança</h3>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5>Total de Eventos</h5>
                        <h2><?= number_format($stats['total']) ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5>Últimas 24h</h5>
                        <h2><?= number_format($stats['ultimas_24h']) ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5>Eventos Críticos</h5>
                        <h2><?= number_format($stats['criticos']) ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h5>IPs Bloqueados</h5>
                        <h2><?= count($bloqueados) ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráficos e tabelas... -->
    </div>
</body>
</html>
```

---

### **4.3 Alertas Automáticos**

**Integração WhatsApp** (já existe em `sistema/painel-admin/apis/`):
```php
<?php
// sistema/helpers/SecurityAlerts.php
class SecurityAlerts {
    private $pdo;
    private $logger;
    
    public function __construct($pdo, $logger) {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }
    
    public function enviarAlertaCritico($tipo, $descricao) {
        // Buscar configurações do WhatsApp
        $query = $pdo->query("SELECT * FROM config WHERE nome = 'api_whatsapp'");
        $config = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($config['valor'] != 'Sim') return;
        
        // Montar mensagem
        $mensagem = "*⚠️ ALERTA DE SEGURANÇA*\n\n";
        $mensagem .= "*Tipo:* {$tipo}\n";
        $mensagem .= "*Descrição:* {$descricao}\n";
        $mensagem .= "*Data/Hora:* " . date('d/m/Y H:i:s') . "\n";
        $mensagem .= "*IP:* " . $_SERVER['REMOTE_ADDR'];
        
        // Enviar para Super Admin
        $telefone_admin = $config['telefone_super_admin']; // Definir no .env
        
        // Usar API existente (adaptar caminho)
        include('../../sistema/painel-admin/apis/texto.php');
        // [Código de envio via WhatsApp existente]
    }
}
?>
```

**Uso**:
```php
// Após detectar SQL Injection
$alerts = new SecurityAlerts($pdo, $logger);
$alerts->enviarAlertaCritico('SQL_INJECTION', 'Tentativa de SQL Injection detectada na API de login');
```

---

### **4.4 Compliance LGPD**

**Política de Retenção**:
```sql
-- Criar evento no MySQL para limpeza automática
CREATE EVENT IF NOT EXISTS limpar_logs_antigos
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM security_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

**Auditoria de Acesso a Dados Pessoais**:
```php
// Registrar sempre que dados sensíveis forem acessados
$logger->log('DATA_ACCESS', "Acesso aos dados do membro ID {$membro_id}", $usuario_id);
```

---

## 📊 CRONOGRAMA GERAL

| Sprint | Dias | Início | Término | Status |
|--------|------|--------|---------|--------|
| Sprint 1 | 3-5 | 2026-02-10 | 2026-02-14 | 🟡 Aguardando |
| Sprint 2 | 2-3 | 2026-02-15 | 2026-02-17 | 🔵 Bloqueado |
| Sprint 3 | 4-5 | 2026-02-18 | 2026-02-22 | 🟣 Bloqueado |
| Sprint 4 | 3-4 | 2026-02-23 | 2026-02-26 | 🟤 Bloqueado |

**Total Estimado**: 12-17 dias úteis (3-4 semanas)

---

## ✅ CRITÉRIOS DE ACEITE FINAL

### Sprint 1
- [x] Login API usando `prepared statements`
- [x] Senhas migradas para `password_hash`
- [x] Credenciais protegidas em `.env`
- [x] `.gitignore` impede vazamento
- [x] Testes bem-sucedidos em localhost

### Sprint 2
- [ ] Honeypot funcional no login
- [ ] Rate limiting bloqueando IPs automaticamente
- [ ] Headers de segurança configurados
- [ ] Testes de penetração básicos realizados

### Sprint 3
- [ ] API autenticada via JWT
- [ ] Refresh token funcional
- [ ] Sessões web intactas (zero quebra)
- [ ] Documentação de API atualizada

### Sprint 4
- [ ] Dashboard de segurança acessível
- [ ] Logs sendo registrados automaticamente
- [ ] Alertas via WhatsApp funcionais
- [ ] Compliance LGPD implementado

---

## 🚨 RISCOS E MITIGAÇÕES

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Quebra de autenticação em produção | BAIXA | CRÍTICO | Testar exaustivamente em localhost antes do deploy |
| Perda de dados na migração de senhas | BAIXA | CRÍTICO | Backup completo antes de executar migração |
| Conflito JWT com sessões web | MÉDIA | ALTO | API isolada, sessões web permanecem sem alteração |
| Headers bloquearem recursos CDN | BAIXA | MÉDIO | CSP permite CDNs conhecidos (jsdelivr, cdnjs) |

---

## 📚 DOCUMENTAÇÃO ADICIONAL

- **Backup Obrigatório**: Antes de CADA sprint, executar backup completo:
  ```bash
  mysqldump -u root ibnm.online > backup_pre_sprint_X.sql
  ```

- **Testes de Penetração**: Após Sprint 4, executar:
  - OWASP ZAP (scan automatizado)
  - Postman Collection (testes de API)
  - Manual: tentativas de SQL Injection, XSS, CSRF

- **Documentação de API**: Criar `docs/api.md` com:
  - Endpoints disponíveis
  - Autenticação (Bearer Token)
  - Exemplos de requisições/respostas
  - Códigos de erro

---

## 🎯 PRÓXIMOS PASSOS IMEDIATOS

1. ✅ Aprovação do André para iniciar Sprint 1
2. ⏳ Backup completo do banco de dados
3. ⏳ Criar branch Git: `feature/seguranca-sprint-1`
4. ⏳ Implementar tasks 1.1, 1.2, 1.3, 1.4
5. ⏳ Testes extensivos em localhost
6. ⏳ Code review + aprovação do André
7. ⏳ Deploy em produção (cPanel)
8. ⏳ Monitoramento pós-deploy (24h)
9. ⏳ Atualizar `docs/updates.md`
10. ⏳ Iniciar Sprint 2

---

**Responsáveis**:
- **Planejamento**: André Lopes + Nexus
- **Implementação**: Nexus (coordenado por André)
- **Revisão**: André Lopes
- **Testes**: André Lopes + Equipe
- **Deploy**: André Lopes

---

*Documento vivo. Última atualização: 2026-02-09 22:50 GMT-3*
*Status: ✅ APROVADO PARA EXECUÇÃO*
