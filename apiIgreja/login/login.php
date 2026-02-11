<?php 
/**
 * API de Autenticação - Login
 * 
 * Versão: 2.0 (Refatorada - 2026-02-09)
 * Segurança: SQL Injection ELIMINADA + password_verify
 * 
 * @author Nexus (Security Hardening)
 */

include_once('../conexao.php');

// Ler e decodificar JSON do corpo da requisição
$postjson = json_decode(file_get_contents("php://input"), true);

// Sanitização rigorosa de inputs
$email = filter_var($postjson['email'] ?? '', FILTER_SANITIZE_EMAIL);
$senha = $postjson['senha'] ?? '';

// Validação básica
if (empty($email) || empty($senha)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email e senha são obrigatórios']);
    exit();
}

try {
    // Prepared Statement - ANTI SQL INJECTION
    $query_buscar = $pdo->prepare("
        SELECT * FROM usuarios 
        WHERE (email = ? OR cpf = ?) 
        AND ativo = 'Sim'
    ");
    $query_buscar->execute([$email, $email]);
    $dados_buscar = $query_buscar->fetchAll(PDO::FETCH_ASSOC);
    
    // Verificar se usuário existe
    if (count($dados_buscar) === 0) {
        http_response_code(401);
        echo json_encode(['success' => 'Dados Incorretos!']);
        exit();
    }
    
    $usuario = $dados_buscar[0];
    
    // Verificar senha usando password_verify (bcrypt/argon2id)
    if (!password_verify($senha, $usuario['senha_crip'])) {
        http_response_code(401);
        echo json_encode(['success' => 'Dados Incorretos!']);
        exit();
    }
    
    // Buscar dados da igreja (Prepared Statement)
    $igreja_id = $usuario['igreja'];
    $query_igreja = $pdo->prepare("SELECT nome, imagem FROM igrejas WHERE id = ?");
    $query_igreja->execute([$igreja_id]);
    $res_igreja = $query_igreja->fetchAll(PDO::FETCH_ASSOC);
    
    $nome_igreja = $res_igreja[0]['nome'] ?? '';
    $foto_igreja = $res_igreja[0]['imagem'] ?? '';
    
    // Montar resposta
    $dados[] = array(
        'id' => intVal($usuario['id']),
        'nome' => $usuario['nome'],  
        'email' => $usuario['email'],
        'nivel' => $usuario['nivel'],
        'igreja' => intVal($usuario['igreja']),  
        'nome_igreja' => $nome_igreja,
        'foto_igreja' => $foto_igreja,
    );
    
    // Resposta bem-sucedida
    http_response_code(200);
    echo json_encode(array('result' => $dados));
    
} catch (PDOException $e) {
    // Erro no banco de dados
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor. Tente novamente mais tarde.']);
    error_log('API Login Error: ' . $e->getMessage());
}
?>
