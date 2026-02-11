<?php
/**
 * JWT (JSON Web Token) - Implementação Nativa PHP
 * 
 * Versão: 1.0 (Sprint 3 - Task 3.1)
 * Data: 2026-02-10
 * 
 * Implementação simplificada de JWT usando apenas funções nativas do PHP.
 * Compatível com PHP 7.4+ sem dependências externas.
 * 
 * @author Nexus (Security Team)
 */

class JWT {
    /**
     * Codificar payload em JWT
     * 
     * @param array $payload Dados a serem codificados
     * @param string $secret Chave secreta
     * @param string $algorithm Algoritmo (HS256, HS384, HS512)
     * @return string Token JWT
     */
    public static function encode($payload, $secret, $algorithm = 'HS256') {
        // Header
        $header = [
            'typ' => 'JWT',
            'alg' => $algorithm
        ];
        
        // Codificar header e payload em base64url
        $headerEncoded = self::base64urlEncode(json_encode($header));
        $payloadEncoded = self::base64urlEncode(json_encode($payload));
        
        // Criar assinatura
        $signature = self::sign("$headerEncoded.$payloadEncoded", $secret, $algorithm);
        $signatureEncoded = self::base64urlEncode($signature);
        
        // Retornar JWT completo
        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }
    
    /**
     * Decodificar e validar JWT
     * 
     * @param string $jwt Token JWT
     * @param string $secret Chave secreta
     * @return object Payload decodificado
     * @throws Exception Se token inválido
     */
    public static function decode($jwt, $secret) {
        // Separar as partes do JWT
        $parts = explode('.', $jwt);
        
        if (count($parts) !== 3) {
            throw new Exception('Token JWT inválido');
        }
        
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;
        
        // Decodificar header
        $header = json_decode(self::base64urlDecode($headerEncoded), true);
        
        if (!isset($header['alg'])) {
            throw new Exception('Algoritmo não especificado no header');
        }
        
        // Verificar assinatura
        $signature = self::base64urlDecode($signatureEncoded);
        $expectedSignature = self::sign("$headerEncoded.$payloadEncoded", $secret, $header['alg']);
        
        if (!hash_equals($expectedSignature, $signature)) {
            throw new Exception('Assinatura JWT inválida');
        }
        
        // Decodificar payload
        $payload = json_decode(self::base64urlDecode($payloadEncoded));
        
        if (!$payload) {
            throw new Exception('Payload JWT inválido');
        }
        
        // Validar expiração
        if (isset($payload->exp) && time() > $payload->exp) {
            throw new Exception('Token JWT expirado');
        }
        
        return $payload;
    }
    
    /**
     * Gerar assinatura HMAC
     * 
     * @param string $data Dados a assinar
     * @param string $secret Chave secreta
     * @param string $algorithm Algoritmo
     * @return string Assinatura binária
     */
    private static function sign($data, $secret, $algorithm) {
        $alg = [
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512'
        ];
        
        if (!isset($alg[$algorithm])) {
            throw new Exception("Algoritmo '$algorithm' não suportado");
        }
        
        return hash_hmac($alg[$algorithm], $data, $secret, true);
    }
    
    /**
     * Codificar em base64url (RFC 4648)
     * 
     * @param string $data Dados a codificar
     * @return string String codificada
     */
    private static function base64urlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Decodificar de base64url (RFC 4648)
     * 
     * @param string $data String codificada
     * @return string Dados decodificados
     */
    private static function base64urlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
?>
