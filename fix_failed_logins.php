<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('sistema/conexao.php');

echo "<h2>🔧 Fix tabela failed_logins</h2>";

// Dropar e recriar com estrutura correta
try {
    $pdo->exec("DROP TABLE IF EXISTS `failed_logins`");
    echo "✅ Tabela antiga removida<br>";
} catch(Exception $e) {
    echo "⚠️ " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("
        CREATE TABLE `failed_logins` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `ip` VARCHAR(45) NOT NULL,
            `email_tentativa` VARCHAR(255) DEFAULT NULL,
            `tentativas` INT(11) NOT NULL DEFAULT 1,
            `bloqueado_ate` DATETIME DEFAULT NULL,
            `ultimo_erro` VARCHAR(50) DEFAULT 'LOGIN_FAILED',
            `user_agent` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_ip` (`ip`),
            KEY `idx_bloqueado_ate` (`bloqueado_ate`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabela `failed_logins` recriada com estrutura correta<br>";
} catch(Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "<br>";
}

echo "<br><h3 style='color:green'>Pronto! Tente fazer login agora.</h3>";
echo "<br><a href='sistema/'>← Ir para Login</a>";
echo "<br><br><small style='color:red'>⚠️ Delete este arquivo após usar!</small>";
