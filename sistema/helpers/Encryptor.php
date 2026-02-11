<?php
/**
 * Encryptor - Criptografia AES-256-CBC para Dados Sensíveis
 * 
 * Sprint 4 - Task 4.4
 * 
 * Usa AES-256-CBC com IV aleatório por operação.
 * Chave carregada do .env (ENCRYPTION_KEY).
 * 
 * @author Nexus (Security Team)
 */

class Encryptor {
    private string $key;
    private string $cipher = 'aes-256-cbc';

    public function __construct(?string $key = null) {
        $this->key = $key ?? getenv('ENCRYPTION_KEY') ?: $_ENV['ENCRYPTION_KEY'] ?? '';
        
        if (empty($this->key)) {
            throw new Exception('ENCRYPTION_KEY não configurada no .env');
        }

        // Derivar chave de 32 bytes via SHA-256
        $this->key = hash('sha256', $this->key, true);
    }

    /**
     * Criptografa dado sensível
     * 
     * @return string Base64(iv + ciphertext)
     */
    public function encrypt(string $data): string {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new Exception('Falha na criptografia');
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Descriptografa dado
     */
    public function decrypt(string $encryptedData): string|false {
        $raw = base64_decode($encryptedData, true);
        if ($raw === false) return false;

        $ivLen = openssl_cipher_iv_length($this->cipher);
        if (strlen($raw) < $ivLen) return false;

        $iv = substr($raw, 0, $ivLen);
        $ciphertext = substr($raw, $ivLen);

        $decrypted = openssl_decrypt($ciphertext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : false;
    }

    /**
     * Hash SHA-256 para buscas (não reversível)
     */
    public static function hashForSearch(string $data): string {
        // Normalizar: remover pontuação e espaços
        $normalized = preg_replace('/[^0-9]/', '', $data);
        return hash('sha256', $normalized);
    }

    /**
     * Criptografa CPF e gera hash de busca
     * 
     * @return array ['encrypted' => string, 'hash' => string]
     */
    public function encryptCPF(string $cpf): array {
        return [
            'encrypted' => $this->encrypt($cpf),
            'hash' => self::hashForSearch($cpf)
        ];
    }
}
?>
