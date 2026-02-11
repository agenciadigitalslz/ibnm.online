<?php
/**
 * Gerador de Chave Secreta JWT
 * 
 * Gera uma chave aleatória segura (256+ bits) para uso em produção
 * 
 * Uso: php scripts/generate_jwt_secret.php
 */

echo "═══════════════════════════════════════════════════════════════\n";
echo "         GERADOR DE CHAVE SECRETA JWT (256 bits)              \n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Gerar 64 bytes aleatórios (512 bits)
$randomBytes = random_bytes(64);

// Codificar em Base64
$base64Key = base64_encode($randomBytes);

// Codificar em Hexadecimal
$hexKey = bin2hex($randomBytes);

echo "Chave Base64 (64 bytes = 512 bits):\n";
echo "───────────────────────────────────────────────────────────────\n";
echo $base64Key . "\n\n";

echo "Chave Hexadecimal (128 caracteres = 512 bits):\n";
echo "───────────────────────────────────────────────────────────────\n";
echo $hexKey . "\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "  INSTRUÇÕES:                                                  \n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "1. Copie a chave Base64 acima\n";
echo "2. Edite o arquivo .env na raiz do projeto\n";
echo "3. Adicione a linha:\n\n";
echo "   JWT_SECRET_KEY=\"{$base64Key}\"\n\n";
echo "4. Salve o arquivo\n\n";

echo "⚠️  IMPORTANTE:\n";
echo "   - Nunca compartilhe esta chave\n";
echo "   - Nunca commite o .env no Git\n";
echo "   - Use chaves diferentes em dev/test/prod\n\n";

echo "✅ Chave gerada com sucesso!\n";
echo "═══════════════════════════════════════════════════════════════\n";

?>
