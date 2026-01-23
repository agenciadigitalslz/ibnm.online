<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->prepare("SELECT * from tarefas where id = '$id'");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }

    
    $dataTar = implode('/', array_reverse(explode('-', $res[$i]['data'])));
    $horaF = (new DateTime($res[$i]['hora']))->format('H:i');
    $horaDate = date('y-M-d H:i:s', strtotime($res[$i]['hora']));
   

    $dados = array(
    'id' => $res[$i]['id'],
    'titulo' => $res[$i]['titulo'],
    'descricao' => $res[$i]['descricao'],
    'dataTar' => $res[$i]['data'],
    'dataTarF' => $dataTar,
    'hora' => $res[$i]['hora'],
    'horaF' => $horaF,
    'horaDate' => $horaDate,
    'status' => $res[$i]['status'],
    
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'dados'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>