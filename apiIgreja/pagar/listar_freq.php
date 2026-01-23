<?php 

include_once('../conexao.php');

$query = $pdo->prepare("SELECT * from frequencias");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }

    $dados[] = array(
        'id' => $res[$i]['dias'],
        'nome' => $res[$i]['frequencia'],
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>