<?php
/**
 * Implementação JWT Simples (HS256)
 * 
 * Classe leve para geração e validação de JSON Web Tokens
 * Algoritmo: HMAC-SHA256 (HS256)
 * 
 * Versão: 1.0 (Sprint 3)
 * @author Nexus (Security Team)
 */

class JWT {
    
    /**
     * Codifica dados em Base64 URL-safe
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Decodifica Base64 URL-safe
     * 
     * @param string $data
     * @return string|false
     */
    private static function base64UrlDecode(string $data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
    
    /**
     * Gera assinatura HMAC-SHA256
     * 
     * @param string $data
     * @param string $secret
     * @return string
     */
    private static function sign(string $data, string $secret): string {
        return hash_hmac('sha256', $data, $secret, true);
    }
    
    /**
     * Codifica (cria) um JWT
     * 
     * @param array $payload Dados a serem incluídos no token
     * @param string $secret Chave secreta (mínimo 256 bits recomendado)
     * @param string $algorithm Algoritmo (apenas HS256 suportado)
     * @return string Token JWT
     */
    public static function encode(array $payload, string $secret, string $algorithm = 'HS256'): string {
        if ($algorithm !== 'HS256') {
            throw new Exception('Apenas algoritmo HS256 é suportado');
        }
        
        // Header
        $header = [
            'typ' => 'JWT',
            'alg' => $algorithm
        ];
        
        // Codificar header e payload
        $encodedHeader = self::base64UrlEncode(json_encode($header));
        $encodedPayload = self::base64UrlEncode(json_encode($payload));
        
        // Criar assinatura
        $signature = self::sign($encodedHeader . '.' . $encodedPayload, $secret);
        $encodedSignature = self::base64UrlEncode($signature);
        
        // Retornar JWT completo
        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }
    
    /**
     * Decodifica (valida) um JWT
     * 
     * @param string $token Token JWT
     * @param string $secret Chave secreta
     * @return object|false Payload decodificado ou false se inválido
     */
    public static function decode(string $token, string $secret) {
        // Separar partes do token
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            error_log('JWT: Token inválido (formato incorreto)');
            return false;
        }
        
        list($encodedHeader, $encodedPayload, $encodedSignature) = $parts;
        
        // Decodificar header
        $header = json_decode(self::base64UrlDecode($encodedHeader), true);
        
        if (!$header || !isset($header['alg']) || $header['alg'] !== 'HS256') {
            error_log('JWT: Algoritmo inválido ou não suportado');
            return false;
        }
        
        // Verificar assinatura
        $expectedSignature = self::sign($encodedHeader . '.' . $encodedPayload, $secret);
        $actualSignature = self::base64UrlDecode($encodedSignature);
        
        if (!hash_equals($expectedSignature, $actualSignature)) {
            error_log('JWT: Assinatura inválida');
            return false;
        }
        
        // Decodificar payload
        $payload = json_decode(self::base64UrlDecode($encodedPayload));
        
        if (!$payload) {
            error_log('JWT: Payload inválido');
            return false;
        }
        
        // Verificar expiração (exp)
        if (isset($payload->exp) && $payload->exp < time()) {
            error_log('JWT: Token expirado');
            return false;
        }
        
        // Verificar não-antes (nbf)
        if (isset($payload->nbf) && $payload->nbf > time()) {
            error_log('JWT: Token ainda não válido (nbf)');
            return false;
        }
        
        return $payload;
    }
    
    /**
     * Valida um token sem decodificar (verificação rápida)
     * 
     * @param string $token
     * @param string $secret
     * @return bool
     */
    public static function verify(string $token, string $secret): bool {
        return self::decode($token, $secret) !== false;
    }
}
?>
