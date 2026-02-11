<?php
/**
 * API Membros - Listar (Protegido por JWT)
 * 
 * Versão: 1.0 (Sprint 3 - Exemplo de uso JWT)
 * Endpoint: GET /apiIgreja/membros/listar_jwt.php
 * Header: Authorization: Bearer <token>
 * 
 * @author Nexus (Security Team)
 */

header('Content-Type: application/json; charset=utf-8');

require_once('../conexao.php');
require_once('../middleware/auth.php');

// Validar JWT
$usuario = validarJWT();

try {
    // Listar membros da igreja do usuário autenticado
    $igreja_id = $usuario->igreja;
    
    $query = $pdo->prepare("
        SELECT id, nome, cpf, telefone, email, ativo 
        FROM membros 
        WHERE igreja = ? 
        ORDER BY nome ASC 
        LIMIT 50
    ");
    $query->execute([$igreja_id]);
    $membros = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Resposta
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'igreja_id' => $igreja_id,
        'usuario_autenticado' => $usuario->nome,
        'total' => count($membros),
        'membros' => $membros
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar membros']);
    error_log('API Membros Error: ' . $e->getMessage());
}
?>
