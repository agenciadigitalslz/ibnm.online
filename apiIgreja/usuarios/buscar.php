<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$buscar = '%' .@$_GET['buscar']. '%';
$id_igreja = @$_GET['igreja']; 

$query = $pdo->prepare("SELECT * from usuarios where (nome LIKE '$buscar' or email LIKE '$buscar' or cpf LIKE '$buscar') and igreja = '$id_igreja' order by nome ASC");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
     

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
        'senha' => $res[$i]['senha'],
        'email' => $res[$i]['email'],        
        'foto' => $res[$i]['foto'],
        'cargo' => $res[$i]['nivel'],
        'cpf' => $res[$i]['cpf'],        
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'clientes'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>