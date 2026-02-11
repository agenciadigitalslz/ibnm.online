<?php
/**
 * Configuração JWT (JSON Web Tokens)
 * 
 * Sprint 3 - Autenticação Enterprise
 * 
 * @author Nexus (Security Team)
 */

return [
    // Chave secreta para assinatura HMAC-SHA256
    // IMPORTANTE: Alterar em produção com chave forte (256+ bits)
    'secret_key' => getenv('JWT_SECRET_KEY') ?: 'IBNM_2026_JWT_SECRET_KEY_CHANGE_IN_PRODUCTION_MIN_256_BITS_' . hash('sha256', 'ibnm.online'),
    
    // Algoritmo de assinatura
    'algorithm' => 'HS256',
    
    // Tempo de vida do Access Token (em segundos)
    'access_token_lifetime' => 15 * 60, // 15 minutos
    
    // Tempo de vida do Refresh Token (em segundos)
    'refresh_token_lifetime' => 30 * 24 * 60 * 60, // 30 dias
    
    // Emissor do token (issuer)
    'issuer' => 'ibnm.online',
    
    // Audiência do token (opcional)
    'audience' => 'ibnm.online',
    
    // Permitir renovação automática de Access Token quando expirado
    'auto_refresh' => true,
    
    // Permitir múltiplos refresh tokens por usuário
    'allow_multiple_sessions' => true,
    
    // Limite de refresh tokens por usuário (0 = ilimitado)
    'max_refresh_tokens_per_user' => 5,
    
    // Revogar refresh tokens antigos ao criar novo (rotação)
    'rotate_refresh_tokens' => true,
];
?>
