<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents("php://input"), true);

$query_buscar = $pdo->query("SELECT * from usuarios where (email = '$postjson[email]' or cpf = '$postjson[email]') and senha = '$postjson[senha]' ");

$dados_buscar = $query_buscar->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($dados_buscar); $i++) { 
    foreach ($dados_buscar[$i] as $key => $value) {
    }

    $igreja = $dados_buscar[$i]['igreja'];
    $query = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    if(@count($res) > 0){
        $nome_igreja = $res[0]['nome'];
        $foto_igreja = $res[0]['imagem'];
    }else{
        $nome_igreja = "";
        $foto_igreja = "";
    }
    

    $dados[] = array(
        'id' => intVal($dados_buscar[$i]['id']),
        'nome' => $dados_buscar[$i]['nome'],  
        'email' => $dados_buscar[$i]['email'],
        'nivel' => $dados_buscar[$i]['nivel'],
        'igreja' => intVal($dados_buscar[$i]['igreja']),  
        'nome_igreja' => $nome_igreja,
        'foto_igreja' => $foto_igreja,
    );
}

if(@count($dados_buscar) > 0){
    $result = json_encode(array('result'=>$dados));
    echo $result;
}else{
    $result = json_encode(array('success'=>'Dados Incorretos!'));
 	echo $result;
}

?>