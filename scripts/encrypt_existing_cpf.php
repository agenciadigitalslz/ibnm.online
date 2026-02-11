<?php
/**
 * Script de Migração - Criptografia de CPFs Existentes
 * 
 * Sprint 4 - Task 4.4
 * 
 * EXECUTAR UMA ÚNICA VEZ:
 * php scripts/encrypt_existing_cpf.php
 * 
 * @author Nexus (Security Team)
 */

// Carregar .env
require_once(__DIR__ . '/../apiIgreja/conexao.php');
require_once(__DIR__ . '/../sistema/helpers/Encryptor.php');

echo "=== Migração de CPFs para Criptografia AES-256 ===\n\n";

try {
    $encryptor = new Encryptor();

    // Buscar todos os usuários com CPF não-criptografado
    $stmt = $pdo->query("SELECT id, cpf FROM usuarios WHERE cpf IS NOT NULL AND cpf != '' AND (cpf_encrypted IS NULL OR cpf_encrypted = '')");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Encontrados: " . count($usuarios) . " CPFs para migrar\n\n";

    $migrados = 0;
    $erros = 0;

    foreach ($usuarios as $user) {
        try {
            $resultado = $encryptor->encryptCPF($user['cpf']);

            $update = $pdo->prepare("UPDATE usuarios SET cpf_encrypted = ?, cpf_hash = ? WHERE id = ?");
            $update->execute([$resultado['encrypted'], $resultado['hash'], $user['id']]);

            $migrados++;
            echo "  ✅ ID {$user['id']} - CPF criptografado\n";
        } catch (Exception $e) {
            $erros++;
            echo "  ❌ ID {$user['id']} - Erro: {$e->getMessage()}\n";
        }
    }

    echo "\n=== RESULTADO ===\n";
    echo "Migrados: {$migrados}\n";
    echo "Erros: {$erros}\n";
    echo "Total: " . count($usuarios) . "\n\n";

    if ($migrados > 0) {
        echo "⚠️  IMPORTANTE: Os CPFs originais (coluna 'cpf') foram MANTIDOS.\n";
        echo "    Após validar que a criptografia está correta, você pode limpar\n";
        echo "    a coluna original com:\n";
        echo "    UPDATE usuarios SET cpf = NULL WHERE cpf_encrypted IS NOT NULL;\n";
    }

} catch (Exception $e) {
    echo "❌ ERRO FATAL: " . $e->getMessage() . "\n";
    exit(1);
}
?>
