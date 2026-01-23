<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->prepare("SELECT * from fornecedores where id = '$id' order by nome ASC");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }

    
    $dados = array(
    'id' => $res[$i]['id'],
    'nome' => $res[$i]['nome'],
    'telefone' => $res[$i]['telefone'],
    'email' => $res[$i]['email'],
    'endereco' => $res[$i]['endereco'],    
    'produto' => $res[$i]['produto'],    
    
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'dados'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>